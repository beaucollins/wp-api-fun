<?php
define( 'ABSPATH', __DIR__ . '/../wordpress' );
define( 'WPINC', '/wp-includes' );

require_once __DIR__ . '/../wordpress/wp-includes/functions.php';
require_once __DIR__ . '/../wordpress/wp-includes/class-wp-http-response.php';
require_once __DIR__ . '/../wordpress/wp-includes/rest-api/class-wp-rest-request.php';
require_once __DIR__ . '/../wordpress/wp-includes/rest-api/class-wp-rest-response.php';