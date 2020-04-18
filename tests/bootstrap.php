<?php

define( 'ABSPATH', __DIR__ . '/../wordpress' );
define( 'WPINC', '/wp-includes' );

require_once __DIR__ . '/../wordpress/wp-includes/functions.php';
require_once __DIR__ . '/../wordpress/wp-includes/plugin.php';

require_once __DIR__ . '/../wordpress/wp-includes/class-wp-error.php';
require_once __DIR__ . '/../wordpress/wp-includes/pomo/translations.php';
require_once __DIR__ . '/../wordpress/wp-includes/l10n.php';
require_once __DIR__ . '/../wordpress/wp-includes/class-wp-http-response.php';
require_once __DIR__ . '/../wordpress/wp-includes/rest-api/class-wp-rest-request.php';
require_once __DIR__ . '/../wordpress/wp-includes/rest-api/class-wp-rest-response.php';
require_once __DIR__ . '/../wordpress/wp-includes/rest-api/class-wp-rest-server.php';
require_once __DIR__ . '/../wordpress/wp-includes/rest-api.php';
require_once __DIR__ . '/../wordpress/wp-includes/load.php';

add_action( 'rest_api_init', 'totes_register_endpoints' );

/** @psalm-suppress InvalidGlobal */
global $wp_rest_server;

$wp_rest_server = new WP_REST_Server();

do_action( 'rest_api_init' );
