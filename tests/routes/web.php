<?php
return [
	
	'not-found' => [
		'auth_check_requirements' => 'none',
		'page_filename' => 'not-found.php',
		'redirect_to' => '',
		'route_type' => 'web-app-common',
		'allowed_request_method' => 'ANY',
                'controller_type' => 'procedural',
                'with_middleware' => '',
                'without_middleware' => ''
	],
	'forbidden' => [
		'auth_check_requirements' => 'none',
		'page_filename' => 'forbidden.php',
		'redirect_to' => '',
		'route_type' => 'web-app-common',
		'allowed_request_method' => 'ANY',
                'controller_type' => 'procedural',
                'with_middleware' => '',
                'without_middleware' => ''
	],
	'bad-request' => [
		'auth_check_requirements' => 'none',
		'page_filename' => 'bad-request.php',
		'redirect_to' => '',
		'route_type' => 'web-app-common',
		'allowed_request_method' => 'ANY',
                'controller_type' => 'procedural',
                'with_middleware' => '',
                'without_middleware' => ''
	],
	'unauthorized' => [
		'auth_check_requirements' => 'none',
		'page_filename' => 'unauthorized.php',
		'redirect_to' => '',
		'route_type' => 'web-app-common',
		'allowed_request_method' => 'ANY',
                'controller_type' => 'procedural',
                'with_middleware' => '',
                'without_middleware' => ''
	],
	'method-not-allowed' => [
		'auth_check_requirements' => 'none',
		'page_filename' => 'method-not-allowed.php',
		'redirect_to' => '',
		'route_type' => 'web-app-common',
		'allowed_request_method' => 'ANY',
                'controller_type' => 'procedural',
                'with_middleware' => '',
                'without_middleware' => ''
	],
	'not-acceptable' => [
		'auth_check_requirements' => 'none',
		'page_filename' => 'not-acceptable.php',
		'redirect_to' => '',
		'route_type' => 'web-app-common',
		'allowed_request_method' => 'ANY',
                'controller_type' => 'procedural',
                'with_middleware' => '',
                'without_middleware' => ''
	],
	'default-home' => [
		'route_value' => '/',
		'auth_check_requirements' => 'none',
		'page_filename' => 'default-home.php',
		'redirect_to' => '',
		'route_type' => 'frontend-web-app',
		'allowed_request_method' => 'ANY',
                'controller_type' => 'procedural',
                'with_middleware' => '',
                'without_middleware' => ''
	],
	'default-home1' => [
		'route_value' => '/index.php',
		'auth_check_requirements' => 'none',
		'page_filename' => 'default-home.php',
		'redirect_to' => '',
		'route_type' => 'frontend-web-app',
		'allowed_request_method' => 'ANY',
                'controller_type' => 'procedural',
                'with_middleware' => '',
                'without_middleware' => ''
	],
	'header-response-only-400-bad-request' => [
		'auth_check_requirements' => 'none',
		'page_filename' => 'header-response-only-400-bad-request.php',
		'redirect_to' => '',
		'route_type' => 'ajax-web-service-common',
		'allowed_request_method' => 'ANY',
                'controller_type' => 'procedural',
                'with_middleware' => '',
                'without_middleware' => ''
	],
	'header-response-only-401-unauthorized' => [
		'auth_check_requirements' => 'none',
		'page_filename' => 'header-response-only-401-unauthorized.php',
		'redirect_to' => '',
		'route_type' => 'ajax-web-service-common',
		'allowed_request_method' => 'ANY',
                'controller_type' => 'oop-mapped',
                'with_middleware' => '',
                'without_middleware' => ''
	],
	'header-response-only-403-forbidden' => [
		'auth_check_requirements' => 'none',
		'page_filename' => 'header-response-only-403-forbidden.php',
		'redirect_to' => '',
		'route_type' => 'ajax-web-service-common',
		'allowed_request_method' => 'ANY',
                'controller_type' => 'procedural',
                'with_middleware' => '',
                'without_middleware' => ''
	],
	'header-response-only-404-not-found' => [
		'auth_check_requirements' => 'none',
		'page_filename' => 'header-response-only-404-not-found.php',
		'redirect_to' => '',
		'route_type' => 'ajax-web-service-common',
		'allowed_request_method' => 'ANY',
                'controller_type' => 'procedural',
                'with_middleware' => 'auth,hostnamecheck',
                'without_middleware' => 'startsession'
	],
	'header-response-only-405-method-not-allowed' => [
		'auth_check_requirements' => 'none',
		'page_filename' => 'header-response-only-405-method-not-allowed.php',
		'redirect_to' => '',
		'route_type' => 'ajax-web-service-common',
		'allowed_request_method' => 'ANY',
                'controller_type' => 'procedural',
                'with_middleware' => '',
                'without_middleware' => ''
	],
	'header-response-only-406-not-acceptable' => [
		'auth_check_requirements' => 'none',
		'page_filename' => 'header-response-only-406-not-acceptable.php',
		'redirect_to' => '',
		'route_type' => 'ajax-web-service-common',
		'allowed_request_method' => 'ANY',
                'controller_type' => 'procedural',
                'with_middleware' => '',
                'without_middleware' => ''
	],
	'rest-login' => [
		'route_value' => '/rest/login',
		'auth_check_requirements' => 'pre-login',
		'page_filename' => 'rest-login.php',
		'redirect_to' => '',
		'route_type' => 'rest-web-service',
		'allowed_request_method' => 'POST',
                'controller_type' => 'procedural',
                'with_middleware' => '',
                'without_middleware' => ''
	],
	'rest-logout' => [
		'route_value' => '/rest/logout',
		'auth_check_requirements' => 'post-login',
		'page_filename' => 'rest-logout.php',
		'redirect_to' => '',
		'route_type' => 'rest-web-service',
		'allowed_request_method' => 'POST',
                'controller_type' => 'procedural',
                'with_middleware' => '',
                'without_middleware' => ''
	],
	'rest-register-password' => [
		'route_value' => '/rest/register-password',
		'auth_check_requirements' => 'pre-login',
		'page_filename' => 'rest-register-password.php',
		'redirect_to' => '',
		'route_type' => 'rest-web-service',
		'allowed_request_method' => 'POST',
                'controller_type' => 'procedural',
                'with_middleware' => '',
                'without_middleware' => ''
	],
	'rest-password-setup-request-ref-code-status-check' => [
		'route_value' => '/rest/password-setup-request-ref-code/status-check',
		'auth_check_requirements' => 'pre-login',
		'page_filename' => 'rest-password-setup-request-ref-code-status-check.php',
		'redirect_to' => '',
		'route_type' => 'rest-web-service',
		'allowed_request_method' => 'POST',
                'controller_type' => 'procedural',
                'with_middleware' => '',
                'without_middleware' => ''
	],
	'rest-change-password' => [
		'route_value' => '/rest/change-password',
		'auth_check_requirements' => 'post-login',
		'page_filename' => 'rest-change-password.php',
		'redirect_to' => '',
		'route_type' => 'rest-web-service',
		'allowed_request_method' => 'POST',
                'controller_type' => 'procedural',
                'with_middleware' => '',
                'without_middleware' => ''
	],
	'rest-forgot-password' => [
		'route_value' => '/rest/forgot-password',
		'auth_check_requirements' => 'pre-login',
		'page_filename' => 'rest-forgot-password.php',
		'redirect_to' => '',
		'route_type' => 'rest-web-service',
		'allowed_request_method' => 'POST',
                'controller_type' => 'procedural',
                'with_middleware' => '',
                'without_middleware' => ''
	],
	'rest-reset-password' => [
		'route_value' => '/rest/reset-password',
		'auth_check_requirements' => 'post-login',
		'page_filename' => 'rest-reset-password.php',
		'redirect_to' => '',
		'route_type' => 'rest-web-service',
		'allowed_request_method' => 'POST',
                'controller_type' => 'procedural',
                'with_middleware' => '',
                'without_middleware' => ''
	],
	'rest-user-delete' => [
		'route_value' => '/rest/user/delete',
		'auth_check_requirements' => 'post-login',
		'page_filename' => 'rest-user-delete.php',
		'redirect_to' => '',
		'route_type' => 'rest-web-service',
		'allowed_request_method' => 'POST',
                'controller_type' => 'procedural',
                'with_middleware' => '',
                'without_middleware' => ''
	],
	'rest-user-change-status' => [
		'route_value' => '/rest/user/change-status',
		'auth_check_requirements' => 'post-login',
		'page_filename' => 'rest-user-change-status.php',
		'redirect_to' => '',
		'route_type' => 'rest-web-service',
		'allowed_request_method' => 'POST',
                'controller_type' => 'procedural',
                'with_middleware' => '',
                'without_middleware' => ''
	],
	'rest-re-send-activation-email' => [
		'route_value' => '/rest/re-send/activation-email',
		'auth_check_requirements' => 'post-login',
		'page_filename' => 'rest-re-send-activation-email.php',
		'redirect_to' => '',
		'route_type' => 'rest-web-service',
		'allowed_request_method' => 'POST',
                'controller_type' => 'procedural',
                'with_middleware' => '',
                'without_middleware' => ''
	],
	'rest-get-user-details' => [
		'route_value' => '/rest/user-details/get',
		'auth_check_requirements' => 'post-login',
		'page_filename' => 'rest-user-details-get.php',
		'redirect_to' => '',
		'route_type' => 'rest-web-service',
		'allowed_request_method' => 'POST',
                'controller_type' => 'procedural',
                'with_middleware' => '',
                'without_middleware' => ''
	],
	'rest-user-groups-list' => [
		'route_value' => '/rest/user-groups/list',
		'auth_check_requirements' => 'post-login',
		'page_filename' => 'rest-user-groups-list.php',
		'redirect_to' => '',
		'route_type' => 'rest-web-service',
		'allowed_request_method' => 'POST',
                'controller_type' => 'procedural',
                'with_middleware' => '',
                'without_middleware' => ''
	],
	'rest-user-account-close' => [
		'route_value' => '/rest/user-account/close',
		'auth_check_requirements' => 'post-login',
		'page_filename' => 'rest-user-account-close.php',
		'redirect_to' => '',
		'route_type' => 'rest-web-service',
		'allowed_request_method' => 'POST',
                'controller_type' => 'procedural',
                'with_middleware' => '',
                'without_middleware' => ''
	],						

];	
?>