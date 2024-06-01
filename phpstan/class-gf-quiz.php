<?php

class GFAddon{}
class GF_Field{}
class GFQuiz extends GFAddon{

	/**
	 * If the field is a Poll type radio, select or checkbox then replace the choice value with the choice text.
	 *
	 * @param string|array<mixed> $value The field value.
	 * @param GF_Field|null $field The field object being processed or null.
	 *
	 * @return string|array<mixed>
	 */
	public function maybe_format_field_values( $value, $field ) {}
}
