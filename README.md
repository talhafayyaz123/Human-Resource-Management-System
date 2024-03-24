# Setup and configuring the project

-   Run `composer install`
-   Run `php artisan key:generate`
-   Run `php artisan migrate` (Run `php artisan migrate:fresh` for fresh migrations)
-   Run `php artisan db:seed`
-   Run `php artisan serve`

-   Run `npm install`
-   SET `VITE_DOCUMENT_SERVICE_URL` enviornment variable (acts as BASE_URL for all the document service APIs)
-   SET `VITE_AUTH_SERVICE_URL` enviornment variable (acts as BASE_URL for all users, role and permissions APIs)
-   SET `VITE_TEMPLATE_PARSER_URL` enviornment variable (acts as BASE_URL for parsing templates)
-   SET `VITE_LICENSE_SERVICE_URL` enviornment variable (acts as BASE_URL for licensing module)
-   SET `VITE_MAIL_SERVICE_URL` enviornment variable (acts as BASE_URL for mail templates module)
-   SET `VITE_WEB_SOCKET_BASE_URL` enviorment variable (used as base url for pusher service)
-   SET `VITE_DH2C_SERVICE_BASE_URL` enviorment variable (used as base url for dhc2 service)
-   SET `LOGGING_IP` enviornment variable (for setting up redis for error handling)
-   SET `LOGGING_PORT` enviornment variable (for setting up redis for error handling)
-   SET `LOGGING_SERVICE_NAME` enviornment variable (for setting up redis for error handling)
-   Run `npm run dev`
-   Run `php artisan serve`
