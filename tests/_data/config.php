<?php
// We use GRAPHQL_DEBUG responses in our tests.
if ( ! defined( 'GRAPHQL_DEBUG' ) ) {
	define( 'GRAPHQL_DEBUG', true );
}

// Use reCAPTCHA test keys: https://developers.google.com/recaptcha/docs/faq
define( 'GF_RECAPTCHA_PUBLIC_KEY', '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI' );
define( 'GF_RECAPTCHA_PRIVATE_KEY', '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe' );
