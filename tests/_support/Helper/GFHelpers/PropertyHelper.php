<?php

namespace Helper\GFHelpers;

class PropertyHelper extends GFHelpers {

	public function addIconUrl( $value = null ) {
		return $value ?? 'someurl.test';
	}

	public function addressType( $value = null ) {
		return $value ?? 'international';
	}

	public function adminLabel( $value = null ) {
		return $value ?? ( $this->dummy->text( 0, 8, true ) ?? null );
	}

	public function allowsPrepopulate( $value = null ) {
		return $value ?? $this->dummy->yesno();
	}

	public function allowedExtensions( $value = null ) {
		return $value ?: 'jpg,png';
	}


	public function autocompleteAttribute( $value = null ) {
		return $value ?? ( $this->dummy->text( 0, 8, true ) ?? null );
	}

	public function backgroundColor( $value = null ) {
		return $value ?? '#7ca6d8';
	}

	public function borderColor( $value = null ) {
		return $value ?? '#7ca6d8';
	}

	public function borderStyle( $value = null ) {
		return $value ?? 'groove';
	}

	public function borderWidth( $value = null ) {
		return $value ?? $this->dummy->number(0,3);
	}

	public function boxWidth( $value = null ) {
		return $value ?? $this->dummy->number(300,1000);
	}

	public function checkboxLabel( $value = null ) {
		return $value ?? $this->dummy->text( 1, 8, true );
	}

	public function calculationFormula( $value = null ) {
		return $value ?? '';
	}

	public function calculationRounding( $value = null ) {
		return $value ?? null;
	}

	public function calendarIconType( $value = null ) {
		return $value ?? 'none';
	}

	public function calendarIconUrl( $value = null ) {
		return $value ?? null;
	}

	public function captchaBadgePosition( $value = null ) {
		return $value ?? 'bottomright';
	}

	public function captchaLanguage( $value = null ) {
		return $value ?? 'iw';
	}

	public function captchaTheme( $value = null ) {
		return $value ?? 'dark';
	}

	public function captchaType( $value = null ) {
		return $value ?? 'recaptcha';
	}

	public function chainedSelectsAlignment( $value = null ) {
		return $value ?? null;
	}

	public function chainedSelectsHideInactive( $value = null ) : bool {
		return ! empty( $value );
	}

	public function choices( $value = null ) {
		return $value ?? null;
	}

	public function copyValuesOptionDefault( $value = null ) : bool {
		return ! empty( $value );
	}

	public function copyValuesOptionField( $value = null ) {
		return $value ?? null;
	}

	public function cssClass( $value = null ) {
		return $value ?? ( $this->dummy->text( 0, 8, true ) ?? null );
	}

	public function dateFormat( $value = null ) {
		return $value ?? 'ymd_dash';
	}

	public function dateType( $value = null ) {
		return $value ?? 'datepicker';
	}

	public function defaultCountry( $value = null ) {
		return $value ?? 'United States';
	}

	public function defaultProvince( $value = null ) {
		return $value ?? '';
	}

	public function defaultState( $value = null ) {
		return $value ?? '';
	}

	public function defaultValue( $value = null ) {
		return $value ?? ( $this->dummy->text( 0, 8, true ) ?? null );
	}

	public function deleteIconUrl( $value = null ) {
		return $value ?? 'someurl.test';
	}

	public function description( $value = null ) {
		return $value ?? $this->dummy->text();
	}

	public function descriptionPlacement( $value = null ) : string {
		return ! empty( $value ) ?: 'inherit';
	}

	public function disableQuantity( $value = null ) : bool {
		return ! empty( $value );
	}

	public function disableMargins( $value = null ) : bool {
		return ! empty( $value );
	}

	public function displayAlt( $value = null ) : bool {
		return $value ?: true;
	}


	public function displayAllCategories( $value = null ) : bool {
		return ! empty( $value );
	}

	public function displayCaption( $value = null ) : bool {
		return $value ?: true;
	}

	public function displayDescription( $value = null ) : bool {
		return $value ?: true;
	}

	public function displayOnly( $value = null ) : bool {
		return ! empty( $value );
	}

	public function displayTitle( $value = null ) : bool {
		return $value ?: true;
	}

	public function emailConfirmEnabled( $value = null ) : bool {
		return ! empty( $value );
	}

	public function enableAutocomplete( $value = null ) : bool {
		return ! empty( $value ) ?: $this->dummy->yesno();
	}

	public function enableCalculation( $value = null ) : bool {
		return ! empty( $value );
	}

	public function enableChoiceValue( $value = null ) : bool {
		return ! empty( $value ) ?: $this->dummy->yesno();
	}

	public function enableCopyValuesOption( $value = null ) : bool {
		return ! empty( $value );
	}

	public function enableEnhancedUI( $value = null ) : bool {
		return ! empty( $value );
	}

	public function enableOtherChoice( $value = null ) : bool {
		return ! empty( $value );
	}

	public function enablePasswordInput( $value = null ) : bool {
		return ! empty( $value );
	}

	public function gquizEnableRandomizeQuizChoices( bool $value = null ) : bool {
		return ! empty( $value );
	}

	public function enableSelectAll( $value = null ) : bool {
		return ! empty( $value );
	}
	public function enableColumns( $value = null ) : bool {
		return ! empty( $value );
	}

	public function gquizWeightedScoreEnabled( bool $value = null ) : ?bool {
		return $value ?? true;
	}

	public function errorMessage( $value = null ) {
		return $value ?? null;
	}

	public function id( $value = null ) {
		return $value ?? 1;
	}

	public function inputMaskValue( $value = null ) {
		return $value ?? '?****************************************************';
	}

	public function inputName( $value = null ) {
		return $value ?? ( $this->dummy->text( 0, 4, true ) ?? null );
	}

	public function inputs( $value = null ) {
		return $value ?? null;
	}

	public function inputType( $value = null ) {
		return $value ?? null;
	}

	public function gquizAnswerExplanation( string $value = null ) : ?string {
		return $value ?? $this->dummy->sentence( 1, 3 );
	}

	public function gquizIsCorrect( bool $value = null ) : ?bool {
		return $value ?? $this->dummy - yesno();
	}

	public function hasInputMask( bool $value = null ) : ?bool {
		return $value ?? true;
	}

	public function isRequired( $value = null ) {
		return $value ?? false;
	}

	public function isSelected( $value = null ) {
		return $value ?? $this->dummy->yesno();
	}

	public function label( $value = null ) {
		return $value ?? ( $this->dummy->words( 1, 3 ) );
	}

	public function labelPlacement( $value = null ) {
		return $value ?? 'inherit';
	}

	public function layoutGridColumnSpan( $value = null ) {
		return $value ?? ( rand( 0, 12 ) ?: null );
	}

	public function layoutSpacerGridColumnSpan( $value = null ) {
		return $value ?? ( rand( 0, 12 ) ?: null );
	}

	public function listValues( $value = null ) {
		return $value ?? null;
	}

	public function maxFiles( $value = null ) {
		return $value ?: 5;
	}

	public function maxFileSize( $value = null ) {
		return $value ?: rand( 1, 4 );
	}

	public function maxLength( $value = null ) {
		return $value ?? ( rand( 100, 550 ) ?: null );
	}
	public function maxRows( $value = null ) {
		return $value ?? ( rand( 3, 10 ) ?: null );
	}

	public function multipleFiles( $value = null ) : bool {
		return ! empty( $value );
	}

	public function nextButton( $value = null ) {
		return $value ?? [
			'type'     => 'text',
			'text'     => $this->dummy->words( 2 ),
			'imageUrl' => null,
		];
	}

	public function nameFormat( $value = null ) {
		return $value ?? 'advanced';
	}

	public function noDuplicates( $value = null ) : bool {
		return ! empty( $value );
	}
	public function numberFormat( $value = null ) {
		return $value ?? 'decimal_comma';
	}

	public function pageNumber( $value = null ) {
		return $value ?? 1;
	}

	public function penColor( $value = null ) {
		return $value ?? '#855fa8';
	}

	public function penSize( $value = null ) {
		return $value ?? $this->dummy->number(1,3);
	}

	public function phoneFormat( $value = null ) {
		return $value ?? 'standard';
	}

	public function postFeaturedImage( $value = null ) {
		return $value ?: true;
	}

	public function placeholder( $value = null ) {
		return $value ?? ( $this->dummy->words( 2 ) );
	}

	public function previousButton( $value = null ) {
		return $value ?? [
			'type'     => 'text',
			'text'     => null,
			'imageUrl' => '/path/to/image.jpg',
		];
	}

	public function productField( $value = null ) {
		return $value ?? '';
	}

	public function gquizFieldType( string $value = null ) : string {
		return $value ?? 'CHECKBOX';
	}

	public function rangeMin( $value = null ) {
		return $value ?? null;
	}

	public function rangeMax( $value = null ) {
		return $value ?? null;
	}

	public function gquizShowAnswerExplanation( bool $value = null ): ?bool {
		return $value ?? $this->dummy->yesno();
	}

	public function simpleCaptchaBackgroundColor( $value = null ) {
		return $value ?? null;
	}

	public function simpleCaptchaFontColor( $value = null ) {
		return $value ?? null;
	}

	public function simpleCaptchaSize( $value = null ) {
		return $this->size( $value );
	}

	public function size( $value = null ) : string {
		return ! empty( $value ) ?: 'medium';
	}

	public function storageType( $value = null ) {
		return $value ?? 'json';
	}

	public function subLabelPlacement( $value = null ) {
		return $this->labelPlacement( $value );
	}

	public function text( $value = null ) {
		return $value ?? $this->dummy->words( 3, 0, 0 );
	}

	public function type( $value = null ) {
		return $value;
	}

	public function useRichTextEditor( $value = null ) {
		return $value ?? $this->dummy->yesno();
	}

	public function value( $value = null ) {
		return $value ?? $this->dummy->text( 1, 4, true );
	}

	public function visibility( $value = null ) {
		return ! empty( $value ) ?: 'visible';
	}

	public function conditionalLogic( $value = null ) {
		return $value ?? null;
	}

	public function content( $value = null ) {
		return $value ?? '<div>' . $this->text( 140, 600 ) . '</div>';
	}

	public function streetInput( array $keys ) {
		return $this->getAll( $keys );
	}

	public function customLabel( $value = null ) {
		return $value ?? ( $this->dummy->words( 1, 3 ) ?? null );
	}

	public function isHidden( $value = null ) {
		return $value ?? $this->dummy->yesno();
	}

	public function name( $value = null ) {
		return $value ?? ( $this->dummy->text( 1, 8, true ) ?? '' );
	}

	public function gquizWeight( $value = null ) {
		return $value ?? $this->dummy->number( 0, 5 );
	}

}
