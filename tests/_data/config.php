<?php
/**
 * Disable autoloading while running tests, as the test
 * suite already bootstraps the autoloader and creates
 * fatal errors when the autoloader is loaded twice
 */
define( 'GRAPHQL_DEBUG', true );

// Use reCAPTCHA test keys: https://developers.google.com/recaptcha/docs/faq
define( 'GF_RECAPTCHA_PUBLIC_KEY', '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI' );
define( 'GF_RECAPTCHA_PRIVATE_KEY', '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe' );
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'GRAPHQL_DEBUG', true );

