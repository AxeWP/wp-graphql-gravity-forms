<?php

namespace WPGraphQLGravityForms\Mutations;

use GFFormsModel;
use GFCommon;
use GraphQL\Error\UserError;
use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Mutation;

/**
 * Create a Gravity Forms draft entry.
 */
class CreateDraftEntry implements Hookable, Mutation {
    /**
     * Mutation name.
     */
    const NAME = 'createGravityFormsDraftEntry';

    public function register_hooks() {
        add_action( 'graphql_register_types', [ $this, 'register_mutation' ] );
	}

	public function register_mutation() {
		register_graphql_mutation( self::NAME, [
            'inputFields'         => $this->get_input_fields(),
			'outputFields'        => $this->get_output_fields(),
			'mutateAndGetPayload' => $this->mutate_and_get_payload(),
        ] );
	}

	/**
	 * Defines the input field configuration.
	 *
	 * @return array
	 */
	public static function get_input_fields() : array {
		return [
			'formId' => [
				'type'        => 'Integer',
				'description' => __( 'The form ID.', 'wp-graphql-gravity-forms' ),
			],
			'pageNumber' => [
				'type'        => 'Integer',
				'description' => __( 'Optional. The page number where the user left off. Default is 1.', 'wp-graphql-gravity-forms' ),
			],
			'ip' => [
				'type'        => 'String',
				'description' => __( 'Optional. The IP address of the user who submitted the draft entry. Default is an empty string.', 'wp-graphql-gravity-forms' ),
			],
			// 'files' => [
			// 	'type'        => '',
			// 	'description' => __( '', 'wp-graphql-gravity-forms' ),
			// ],
		];
    }

	/**
	 * Defines the output field configuration.
	 *
	 * @return array
	 */
	public function get_output_fields() : array {
		return [
			'resumeToken' => [
				'type'        => 'String',
				'description' => __( 'Draft resume token.', 'wp-graphql-gravity-forms' ),
			],
			'resumeUrl' => [
				'type'        => 'String',
				'description' => __( 'Draft resume URL. If the "Referer" header is not included in the request, this will be an empty string.', 'wp-graphql-gravity-forms' ),
			],
		];
    }

	/**
	 * Defines the data modification closure.
	 *
	 * @return callable
	 */
	public function mutate_and_get_payload() : callable {
		return function( $input, AppContext $context, ResolveInfo $info ) : array {
			if ( empty( $input ) || ! is_array( $input ) || ! isset( $input['formId'] ) ) {
				throw new UserError( __( 'Mutation not processed. The input data was missing or invalid.', 'wp-graphql-gravity-forms' ) );
            }

			$form_id   = absint( $input['formId'] );
			$form_info = GFFormsModel::get_form( $form_id, true );

            if ( ! $form_info || ! $form_info->is_active || $form_info->is_trash ) {
                throw new UserError( __( 'The ID for a valid, active form must be provided.', 'wp-graphql-gravity-forms' ) );
			}

			$form         = GFFormsModel::get_form_meta( $form_id );
			$source_url   = esc_url_raw( $this->truncate( $_SERVER['HTTP_REFERER'] ?? '', 250 ) );
			$resume_token = $this->save_draft_submission( $input, $form, $source_url );

            if ( ! $resume_token ) {
                throw new UserError( __( 'An error occurred while trying to create the draft entry.', 'wp-graphql-gravity-forms' ) );
			}

			return [
				'resumeToken' => $resume_token,
				'resumeUrl'   => $this->get_resume_url( $source_url, $resume_token, $form ),
			];
		};
	}

	/**
	 * Mimics Gravity Forms' GFFormsModel::save_draft_submission() method.
	 *
	 * @param array  $input      Request input.
	 * @param array  $form       Form object.
	 * @param string $source_url Source URL.
	 *
	 * @return string The resume token, or empty string on failure.
	 */
	private function save_draft_submission( array $input, array $form, string $source_url ) : string {
		$ip             = isset( $input['ip'] ) && ! empty( $form['personalData']['preventIP'] ) ? sanitize_text_field( $input['ip'] ) : '';
		$entry          = $this->get_draft_entry_data( $form, $ip, $source_url );
		$field_values   = '';
		$page_number    = isset( $input['pageNumber'] ) ? absint( $input['pageNumber'] ) : 1;
		$files          = []; // TODO: Get from Request.
		$form_unique_id = $this->get_form_unique_id( $form['id'] );

		$resume_token = GFFormsModel::save_draft_submission(
			$form,
			$entry,
			$field_values,
			$page_number,
			$files,
			$form_unique_id,
			$ip,
			$source_url,
			''
		);

		return $resume_token ?: '';
	}

	/**
	 * Mimics Gravity Forms' GFFormsModel::create_lead() method.
	 *
	 * @param array  $form       Form object.
	 * @param string $ip         IP address.
	 * @param string $source_url Source URL.
	 *
	 * @return array Draft entry data.
	 */
	private function get_draft_entry_data( array $form, string $ip, string $source_url ) : array {
		return [
			'id'           => null,
			'post_id'      => null,
			'date_created' => null,
			'date_updated' => null,
			'form_id'      => $form['id'],
			'ip'           => $ip,
			'source_url'   => $source_url ,
			'user_agent'   => sanitize_text_field( $this->truncate( $_SERVER['HTTP_USER_AGENT'] ?? '', 250 ) ),
			'created_by'   => get_current_user_id() ?: 'NULL',
			'currency'     => gf_apply_filters( [ 'gform_currency_pre_save_entry', $form['id'] ], GFCommon::get_currency(), $form ),
		];
	}

	/**
	 * Mimics Gravity Forms' GFFormsModel::truncate() method.
	 *
	 * @param string $str Original string.
	 * @param int    $length The maximum length of the string.
	 *
	 * @return string The string, possibly truncated.
	 */
	private function truncate( string $str, int $length ) : string {
		if ( strlen( $str ) > $length ) {
			$str = substr( $str, 0, $length );
		}
	
		return $str;
	}

	/**
	 * Mimics Gravity Forms' GFFormsModel::get_form_unique_id() method.
	 *
	 * @param int $form_id Form ID.
	 *
	 * @return string Unique ID.
	 */
	private function get_form_unique_id( int $form_id ) : string {		
		if ( ! isset( GFFormsModel::$unique_ids[ $form_id ] ) ) {
			GFFormsModel::$unique_ids[ $form_id ] = uniqid();
		}

		return GFFormsModel::$unique_ids[ $form_id ];
	}

	/**
	 * Get the draft resume URL.
	 *
	 * @param string $source_url   Source URL.
	 * @param string $resume_token Resume token.
	 * @param array  $form         Form object.
	 *
	 * @return string Resume URL, or empty string if no source URL was provided.
	 */
	private function get_resume_url( string $source_url, string $resume_token, array $form ) : string {
		if ( ! $source_url ) {
			return '';
		}

		/**
		 * Filters the 'Save and Continue' URL to be used with a partial entry submission.
		 *
		 * @param string $resume_url   The URL to be used to resume the partial entry.
		 * @param array  $form         The Form Object.
		 * @param string $resume_token The token that is used within the URL.
		 * @param string $unused       Unused parameter. Included for consistency with the native
		 *                             Gravity Forms gform_save_and_continue_resume_url hook.
		 */
		return esc_url( apply_filters(
			'gform_save_and_continue_resume_url',
			add_query_arg( [ 'gf_token' => $resume_token ], $source_url ),
			$form,
			$resume_token,
			''
		) );
	}
}
