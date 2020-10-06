<?php
//require_once "get-environment-variables.php";

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    'name' => env('APP_NAME', 'EaseApp-PHP'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),
	
	/*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => env('APP_DEBUG', false),
	
	/*
    |--------------------------------------------------------------------------
    | Application URL Hostname
    |--------------------------------------------------------------------------
    |
    | This is the Hostname, where the Application Runs.
    |
    */

    'hostname' => env('APP_HOSTNAME', 'localhost'),
    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL shows the default web root.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    'asset_url' => env('ASSET_URL', null),
	
	/*
    |--------------------------------------------------------------------------
    | Base Path
    |--------------------------------------------------------------------------
    |
    | This is the Base Path of the Application. This will be the Same for both Web & Commandline. This is the place .env is expected to be placed ingeneral.
    |
    */

    'base_path' => env('APP_BASE_PATH'),
	
	/*
    |--------------------------------------------------------------------------
    | Web Root Path
    |--------------------------------------------------------------------------
    |
    | This is the Web Root Path of the Application. This will be the Same for both Web & Commandline.
    |
    */

    'web_root_path' => env('APP_WEB_ROOT_PATH'),
	
    'siteroot_basedir' => env('APP_BASE_PATH') . 'public_html',
    'siteroot_basedir_command_line' => env('APP_BASE_PATH') . 'public_html',
    'siteroot_basedir_command_line_dev' => env('APP_BASE_PATH') . 'public_html',
    'site_home_path_full' => env('APP_BASE_PATH'),
    'site_home_path_full_dev' => env('APP_BASE_PATH'),
	
	
	/*
    |--------------------------------------------------------------------------
    | Encryption Key Storage
    |--------------------------------------------------------------------------
    |
    | This is the Path where, the Encryption Keys are stored
    |
    */

    'app_generated_enc_auth_keys_foldername' => env('APP_GENERATED_ENC_AUTH_KEYS_FOLDERNAME'),
	
	/*
    |--------------------------------------------------------------------------
    | Encryption Key Filenames
    |--------------------------------------------------------------------------
    |
    | The following are the list of Encryption Key Filenames w.r.t. different Encryption Algorithms
    |
    */

	'app_symmetric_encryption_key_filename' => env('APP_SYMMETRIC_ENCRYPTION_KEY_FILENAME'),
	'app_symmetric_authentication_key_filename' => env('APP_SYMMETRIC_AUTHENTICATION_KEY_FILENAME'),
	'app_file_rel_symmetric_encryption_key_filename' => env('APP_FILE_REL_SYMMETRIC_ENCRYPTION_KEY_FILENAME'),
	'app_file_rel_symmetric_authentication_key_filename' => env('APP_FILE_REL_SYMMETRIC_AUTHENTICATION_KEY_FILENAME'),
	'app_asymmetric_anonymous_encryption_keypair_filename' => env('APP_ASYMMETRIC_ANONYMOUS_ENCRYPTION_KEYPAIR_FILENAME'),
	'app_asymmetric_authentication_keypair_filename' => env('APP_ASYMMETRIC_AUTHENTICATION_KEYPAIR_FILENAME'),
	'app_file_rel_asymmetric_anonymous_encryption_keypair_filename' => env('APP_FILE_REL_ASYMMETRIC_ANONYMOUS_ENCRYPTION_KEYPAIR_FILENAME'),
	'app_file_rel_asymmetric_authentication_keypair_filename' => env('APP_FILE_REL_ASYMMETRIC_AUTHENTICATION_KEYPAIR_FILENAME'),
	'app_current_crypto_key_version' => env('APP_CURRENT_CRYPTO_KEY_VERSION'),
	
	/*
    |--------------------------------------------------------------------------
    | Sessions
    |--------------------------------------------------------------------------
    |
    | This is w.r.t. Sessions
    |
	| active_session_backend = file | redis (file: File system based sessions, redis: Single Redis Server, default value: file)
	|
	| files_based_session_storage_location_choice = default-location | custom-location (default-location: Default File System based Session Storage Location, as per PHP Settings, 
	| custom-location: User Chosen File System based Session Storage Location, default value: custom-location)
	|
	| session_based_authentication = 0 | 1 (0: Sessions are Disabled, 1: Sessions are Enabled)
	|
    */

    'session_based_authentication' => '0',
	'active_session_backend' => env('SESSION_DRIVER', 'file'),
	'files_based_session_storage_location_choice' => env('SESSION_STORAGE_LOCATION_SETTING', 'custom-location'),
	'files_based_session_storage_custom_path' => env('APP_BASE_PATH') . 'sessions',
	
	'single_redis_server_session_backend_host' => 'tcp://localhost:6379',
	'session_lifetime' => env('SESSION_LIFETIME', '86400'),
	
	/*
    |--------------------------------------------------------------------------
    | JSON Web Tokens
    |--------------------------------------------------------------------------
    |
    | This is w.r.t. JSON Web Tokens
    |
	| token_based_authentication = 0 | 1 (0: Tokens are Disabled, 1: Tokens are Enabled)
	|
	| token_based_authentication_type = JWT
	|
	| concurrent_token_based_logins_setting = 0 | 1 (0: Concurrent Token based Logins are Disabled, 1: Concurrent Token based Logins are Enabled)
	|
	| max_allowed_active_concurrent_tokens_count_setting = 200 (This designates the maximum number of active logins, at any given point of time.)
	|
    */

    'token_based_authentication' => '1',
	'token_based_authentication_type' => 'JWT',
	'concurrent_token_based_logins_setting' => '1',
	'max_allowed_active_concurrent_tokens_count_setting' => '200',
	
	/*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => 'UTC',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Faker Locale
    |--------------------------------------------------------------------------
    |
    | This locale will be used by the Faker PHP library when generating fake
    | data for your database seeds. For example, this will be used to get
    | localized telephone numbers, street address information and more.
    |
    */

    'faker_locale' => 'en_US',

	/*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by EaseApp is shown below to make development simple.
    |
    |
    | All databases work in EaseApp is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */
	
    'connections' => [

        'mysql' => [
            'driver' => 'mysql',
            'url' => env('MYSQL_DB_URL'),
            'host' => env('MYSQL_DB_HOST', '127.0.0.1'),
            'port' => env('MYSQL_DB_PORT', '3306'),
            'database' => env('MYSQL_DB_DATABASE', 'easeapp'),
            'username' => env('MYSQL_DB_USERNAME', 'easeapp'),
            'password' => env('MYSQL_DB_PASSWORD', ''),
            'unix_socket' => env('MYSQl_DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

    ],
    
	/*
    |--------------------------------------------------------------------------
    | Routing Rule Length
    |--------------------------------------------------------------------------
    |
    | This is the Maximum Length in no. of Characters of Routing Rule.
    |
    */

    'routing_rule_length' => '500',
	
	/*
    |--------------------------------------------------------------------------
    | Routing Engine Rules related Files Array
    |--------------------------------------------------------------------------
    |
    | This is an Array that will have a List of Files with Routing Engine Rules.
    |
    */

    /* 'routing_engine_rule_files' => [
		__DIR__.'/routing-engine-app-core-rules.php',
		__DIR__.'/routing-engine-app-specific-rules1.php',
		__DIR__.'/routing-engine-app-specific-rules2.php',
	], */
	'routing_engine_rule_files' => [
		__DIR__.'/../routes/web.php',
		__DIR__.'/../routes/ajax.php',
		__DIR__.'/../routes/ajax-api-common.php',
		__DIR__.'/../routes/rest-api.php',
		__DIR__.'/../routes/soap-api.php',
	],
	
	/*
    |--------------------------------------------------------------------------
    | Chosen Template (Backend)
    |--------------------------------------------------------------------------
    |
    | This is the Chosen Backend Template Name.
    |
    */

    'chosen_template' => 'default-admin',
	
	/*
    |--------------------------------------------------------------------------
    | Chosen Template (Frontend)
    |--------------------------------------------------------------------------
    |
    | This is the Chosen Frontend Template Name.
    |
    */

    'chosen_frontend_template' => 'default-frontend',
	
	/*
    |--------------------------------------------------------------------------
    | Route rel Template Context - Common Page Scenario in both Frontend / Backend
    |--------------------------------------------------------------------------
    |
    | This is Route related Template Context, that will have to be defined in Page Model, so routes/pages, that has route_type = web-app-common, can be loaded in Frontend /Backend | appropriately.
	| Values include: frontend | backend
	| 
	| This applies specifically to 404 Not Found, 403 Forbidden etc... HTTP Error Code Scenarios, in Frontend / backend Web Application Context.
    |
    */

    'route_rel_template_context' => '',
	
    /*
    |--------------------------------------------------------------------------
    | Other Config
    |--------------------------------------------------------------------------
    |
    | This will be re-organized under different sub headings
    |
    */

   
	'project_main_folder' => '',
	'live_url_main_domain_name' => 'easeapp.org',
	'cli_dev_account_ref' => 'apidev-easeapp',
	'cli_live_account_ref' => 'apilive-easeapp',
	'organisation_name' => 'EaseApp',
	'organisation_name_full' => 'EaseApp',
	
	'encr_filename_salt_length' => '20',
	'encr_filename_length' => '45',
	'hash_algorithm' => 'whirlpool',
	'password_min_length' => '8',
	'username_min_length' => '5',
	'username_max_length' => '20',
	'debug_mode' => 'ON',
	'app_site_status' => 'live',
	'contact_page_email' => 'securitywonks@gmail.com',
	'contact_phone_primary' => '+91-234-1234567',
	'currency_symbol' => 'Rs.',
	'log_lifetime_settings_seconds' => '32140800',
	'minify_javascript_setting' => '1',
	'minify_css_setting' => '1',
	'show_page_load_db_query_info' => '1',
	'check_email_activation_login' => 'yes',
	
    /*
    |--------------------------------------------------------------------------
    | Third Party Provider Keys Storage Settings
    |--------------------------------------------------------------------------
    |
    | The following are mappings to third party provider related Keys storage settings
    |
    */

    'third_party_provider_keys_storage_folder_name' => env('THIRD_PARTY_PROVIDER_KEYS_STORAGE_FOLDER_NAME'),
	
	/*
    |--------------------------------------------------------------------------
    | Vonage SMS, Voice Call API Credentials & Details
    |--------------------------------------------------------------------------
    |
    | The following are mappings to Vonage SMS & Voice Call API Credentials & Details
    |
    */

    'vonage_communications_api_key' => env('VONAGE_COMMUNICATIONS_API_KEY'),
	'vonage_communications_api_secret1' => env('VONAGE_COMMUNICATIONS_API_SECRET1'),
	'vonage_communications_api_secret2' => env('VONAGE_COMMUNICATIONS_API_SECRET2'),
	'vonage_communications_application_name' => env('VONAGE_COMMUNICATIONS_APPLICATION_NAME'),
	'vonage_communications_application_id' => env('VONAGE_COMMUNICATIONS_APPLICATION_ID'),
	'vonage_communications_application_rel_private_key_filename' => env('VONAGE_COMMUNICATIONS_APPLICATION_REL_PRIVATE_KEY_FILENAME'),
    
    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [

        /*
         * EaseApp PHP Framework Service Providers...
         */
        
        /*
         * Package Service Providers...
         */

        /*
         * Application Service Providers...
         */
        \EaseAppPHP\Providers\AppServiceProvider::class,
        \EaseAppPHP\Providers\RouteServiceProvider::class,

    ],
    
    /*
    |--------------------------------------------------------------------------
    | Route Type Middlware Group Mapping
    |--------------------------------------------------------------------------
    |
    | The Different Route_type and corresponding MiddlwareGroup name mapping.
    | route_type: ajax | soap-web-service | rest-web-service | ajax-web-service-common | frontend-web-app | backend-web-app | web-app-common
    */
	
    'route_type_middleware_group_mapping' => [
        
            'ajax' => 'ajax',
            'ajax-web-service-common' => 'ajax',
            'rest-web-service' => 'api',
            'soap-web-service' => 'api',
            'frontend-web-app' => 'web',
            'backend-web-app' => 'web',
            'web-app-common' => 'web',
    
    ],
    
	
];
?>