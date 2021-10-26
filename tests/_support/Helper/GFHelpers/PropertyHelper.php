<?php

namespace Helper\GFHelpers;

class PropertyHelper extends GFHelpers {

	public function addressType( $value = null ) {
		return isset( $value ) ? $value : 'international';
	}

	public function adminLabel( $value = null ) {
		return isset( $value ) ? $value : ( $this->dummy->text( 0, 8, true ) ?? null );
	}

	public function adminOnly( $value = null ) {
		return isset( $value ) ? $value : null;
	}

	public function nextButton( $value = null ) {
		return isset( $value ) ? $value : [
			'type'     => 'text',
			'text'     => $this->dummy->words( 2 ),
			'imageUrl' => null,
		];
	}
	public function previousButton( $value = null ) {
		return isset( $value ) ? $value : [
			'type'     => 'text',
			'text'     => null,
			'imageUrl' => '/path/to/image.jpg',
		];
	}

	public function allowsPrepopulate( $value = null ) {
		return isset( $value ) ? $value : $this->dummy->yesno();
	}

	public function autocompleteAttribute( $value = null ) {
		return isset( $value ) ? $value : ( $this->dummy->text( 0, 8, true ) ?? null );
	}

	public function checkboxLabel( $value = null ) {
		return isset( $value ) ? $value : $this->dummy->text( 1, 8, true );
	}

	public function calculationFormula( $value = null ) {
		return isset( $value ) ? $value : '';
	}

	public function calculationRounding( $value = null ) {
		return isset( $value ) ? $value : '';
	}

	public function calendarIconType( $value = null ) {
		return isset( $value ) ? $value : 'none';
	}

	public function calendarIconUrl( $value = null ) {
		return isset( $value ) ? $value : null;
	}

	public function captchaLanguage( $value = null ) {
		return isset( $value ) ? $value : 'iw';
	}

	public function captchaTheme( $value = null ) {
		return isset( $value ) ? $value : 'dark';
	}

	public function captchaType( $value = null ) {
		return isset( $value ) ? $value : 'recaptcha';
	}

	public function chainedSelectsAlignment( $value = null ) {
		return isset( $value ) ? $value : null;
	}

	public function chainedSelectsHideInactive( $value = null ) {
		return isset( $value ) ? $value : null;
	}

	public function choices( $value = null ) {
		return isset( $value ) ? $value : null;
	}

	public function copyValuesOptionDefault( $value = null ) {
		return isset( $value ) ? $value : '';
	}
	public function copyValuesOptionField( $value = null ) {
		return isset( $value ) ? $value : null;
	}

	public function cssClass( $value = null ) {
		return isset( $value ) ? $value : ( $this->dummy->text( 0, 8, true ) ?? null );
	}

	public function dateFormat( $value = null ) {
		return isset( $value ) ? $value : 'dmy';
	}

	public function dateType( $value = null ) {
		return isset( $value ) ? $value : 'datepicker';
	}

	public function defaultCountry( $value = null ) {
		return isset( $value ) ? $value : 'United States';
	}

	public function defaultProvince( $value = null ) {
		return isset( $value ) ? $value : '';
	}

	public function defaultState( $value = null ) {
		return isset( $value ) ? $value : '';
	}

	public function defaultValue( $value = null ) {
		return isset( $value ) ? $value : ( $this->dummy->text( 0, 8, true ) ?? null );
	}

	public function description( $value = null ) {
		return isset( $value ) ? $value : $this->dummy->text();
	}

	public function descriptionPlacement( $value = null ) {
		return isset( $value ) ? $value : '';
	}

	public function disableQuantity( $value = null ) {
		return isset( $value ) ? $value : false;
	}

	public function disableMargins( $value = null ) {
		return isset( $value ) ? $value : false;
	}

	public function displayAllCategories( $value = null ) {
		return isset( $value ) ? $value : false;
	}

	public function displayOnly( $value = null ) :bool {
		return isset( $value ) ? $value : true;
	}

	public function emailConfirmEnabled( $value = null) :bool {
		return isset( $value ) ? $value : true;
	}

	public function enableAutocomplete( $value = null ) {
		return isset( $value ) ? $value : $this->dummy->yesno();
	}

	public function enableCalculation( $value = null ) {
		return isset( $value ) ? $value : '';
	}

	public function enableChoiceValue( $value = null ) {
		return isset( $value ) ? $value : $this->dummy->yesno();
	}

	public function enableCopyValuesOption( $value = null ) {
		return isset( $value ) ? $value : '';
	}

	public function enableEnhancedUI( $value = null ) {
		return isset( $value ) ? $value : '';
	}

	public function enablePrice( $value = null ) {
		return isset( $value ) ? $value : null;
	}

	public function enableOtherChoice( $value = null ) {
		return isset( $value ) ? $value : $this->dummy->yesno();
	}

	public function enablePasswordInput( $value = null ) {
		return isset( $value ) ? $value : '';
	}

	public function enableSelectAll( $value = null ) {
		return isset( $value ) ? $value : $this->dummy->yesno();
	}

	public function errorMessage( $value = null ) {
		return isset( $value ) ? $value : $this->dummy->text();
	}

	public function id( $value = null ) {
		return (string) isset( $value ) ? $value : 1;
	}

	public function inputMask( $value = null ) {
		return isset( $value ) ? $value : false;
	}

	public function inputMaskIsCustom( $value = null ) {
		return isset( $value ) ? $value : false;
	}

	public function inputMaskValue( $value = null ) {
		return isset( $value ) ? $value : '';
	}

	public function inputName( $value = null ) {
		return isset( $value ) ? $value : ( $this->dummy->text( 0, 4, true ) ?? null );
	}

	public function inputs( $value = null ) {
		return isset( $value ) ? $value : '';
	}

	public function inputType( $value = null ) {
		return isset( $value ) ? $value : '';
	}

	public function isRequired( $value = null ) {
		return isset( $value ) ? $value : $this->dummy->yesno();
	}
	public function isSelected( $value = null ) {
		return isset( $value ) ? $value : $this->dummy->yesno();
	}

	public function label( $value = null ) {
		return isset( $value ) ? $value : ( $this->dummy->words( 1, 3 ) );
	}

	public function labelPlacement( $value = null ) {
		return isset( $value ) ? $value : '';
	}

	public function layoutGridColumnSpan( $value = null ) {
		return isset( $value ) ? $value : ( rand( 0, 12 ) ?: null );
	}

	public function layoutSpacerGridColumnSpan( $value = null ) {
		return isset( $value ) ? $value : ( rand( 0, 12 ) ?: null );
	}

	public function maxFiles( $value = null ) {
		return isset( $value ) ? $value : '';
	}

	public function maxLength( $value = null ) {
		return isset( $value ) ? $value : ( rand( 0, 150 ) ?: null );
	}

	public function multipleFiles( $value = null ) {
		return isset( $value ) ? $value : false;
	}

	public function noDuplicates( $value = null ) {
		return isset( $value ) ? $value : false;
	}
	public function numberFormat( $value = null ) {
		return isset( $value ) ? $value : 'decimal_comma';
	}

	public function pageNumber( $value = null ) {
		return isset( $value ) ? $value : 1;
	}

	public function phoneFormat( $value = null ) {
		return isset( $value ) ? $value : 'standard';
	}

	public function placeholder( $value = null ) {
		return isset( $value ) ? $value : ( $this->dummy->words( 2 ) );
	}

	public function productField( $value = null ) {
		return isset( $value ) ? $value : '';
	}

	public function rangeMin( $value = null ) {
		return isset( $value ) ? $value : null;
	}

	public function rangeMax( $value = null ) {
		return isset( $value ) ? $value : null;
	}

	public function simpleCaptchaBackgroundColor( $value = null ) {
		return isset( $value ) ? $value : null;
	}

	public function simpleCaptchaFontColor( $value = null ) {
		return isset( $value ) ? $value : null;
	}

	public function simpleCaptchaSize( $value = null ) {
		return isset( $value ) ? $value : null;
	}

	public function size( $value = null ) {
		return isset( $value ) ? $value : 'medium';
	}

	public function subLabelPlacement( $value = null ) {
		return isset( $value ) ? $value : '';
	}

	public function text( $value = null ) {
		return isset( $value ) ? $value : $this->dummy->words( 3, 0, 0 );
	}

	public function type( $value = null ) {
		return $value;
	}

	public function useRichTextEditor( $value = null ) {
		return isset( $value ) ? $value : $this->dummy->yesno();
	}

	public function value( $value = null ) {
		return isset( $value ) ? $value : $this->dummy->text( 1, 4, true );
	}

	public function visibility( $value = null ) {
		return isset( $value ) ? $value : 'visible';
	}

	public function conditionalLogic( $value = null ) {
		return isset( $value ) ? $value : null;
	}

	public function content( $value = null ) {
		return isset( $value ) ? $value : '<div>' . $this->text( 140, 600 ) . '</div>';
	}

	public function streetInput( array $keys ) {
		return $this->getAll( $keys );
	}

	public function customLabel( $value = null ) {
		return isset( $value ) ? $value : ( $this->dummy->words( 1, 3 ) ?? null );
	}

	public function isHidden( $value = null ) {
		return isset( $value ) ? $value : $this->dummy->yesno();
	}
	public function name( $value = null ) {
		return isset( $value ) ? $value : ( $this->dummy->text( 1, 8, true ) ?? '' );
	}

}
