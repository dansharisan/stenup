# Introduction
Boilerplate for API-based web app project, with basic admin panel and api documentation.

# Tech stack
- Laravel 6.x for the backend
- VueJS 2.x for the frontend (admin site and user site)
- Bootstrap Vue 4
- Swagger with OpenAPI 3.0 for API documentation
- Codeception for automation test

# Used PHP Packages
- bensampo/laravel-enum: more features on enum data type
- tymondesigns/jwt-auth: JWT Authentication from frontend (ui) to backend (api)
- spatie/laravel-permission: one user can have multiple roles, and one role can have multiple permissions
- darkaonline/l5-swagger: enable swagger, we can create API documentation as well as API execution page

# Used Node Packages
- bootstrap-vue: great bootstrap for vue
- jquery: manipulate DOM easily
- vue-snotify: Show toast notifications
- vue-loading-overlay: Show loading icon on full page or its container
- simple-line-icons: Some icons for the admin panel
- vue-chartjs: Build charts for dashboard
- vuelidate: One of the most flexible validation packages for vue
- vue-sweetalert2: Alert box for vue
And of course unmissable common packages like vuex, vue-router...

# Notes
- Default roles and permissions will be added when running RolesAndPermissionsSeeder.
- There are 3 default roles: ADMINISTRATOR, MODERATOR, and MEMBER
- You can add new roles and adjust permissions in admin panel.
- Use PermissionType when you want to create/edit permissions. Don't forget to build seeder to add then into database as well.
- AccountSeeder can be used to create admin account. Password is hashed using bcrypt with 10 rounds. You can hash it here: https://bcrypt-generator.com/

# Installation
## Normal installation
- Run "composer install"
- Run "npm install"
- Create a new database
- Clone the .env.example file and rename it to .env
- Config database information and mail server information in .env
- Run "sudo chmod -R 777 storage" (on mac or linux) or "chmod -R 777 storage" (on windows) to grant permission for the app to access/modify storage folder
- Adjust app timezone in app.php (currenly Asia/Tokyo)
- Run "php artisan key:generate" to generate the app key
- Run "php artisan jwt:secret" to generate jwt secret key
- In AccountSeeder.php you might want to add your own admin user to fully have control of the system: just duplicate the factory block and change the email with your own email, with the hash of password can be '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' (is 'password')
- Run "php artisan migrate:fresh --seed" in the command line to generate tables and seeding data
- Run "php artisan storage:link" to create symbolic link to storage
- Run "php artisan l5-swagger:generate" to generate API docs
- Run "php artisan serve" to start the server then go to localhost:8000 and enjoy
## With Docker
- Clone the .env.example file and rename it to .env
- Open .env file, adjust Mail information
- Adjust app timezone in app.php (currenly Asia/Tokyo)
- Install and run Docker
- Go to the project folder, and run "docker-compose build --no-cache && docker-compose up -d" to build and run start containers
- Run "docker exec -it stenup-app bash" to go inside the stenup-app container
- Run "composer install"
- Run "npm install"
- Run "php artisan key:generate" to generate the app key
- Run "php artisan jwt:secret" to generate jwt secret key
- In AccountSeeder.php you might want to add your own admin user to fully have control of the system: just duplicate the factory block and change the email with your own email, with the hash of password can be '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' (is 'password')
- Run "php artisan migrate:fresh --seed" in the command line to generate tables and seeding data
- Run "php artisan storage:link" to create symbolic link to storage
- Run "php artisan l5-swagger:generate" to generate API docs
- Go to localhost:8000 and enjoy

# Testing (Only ready on Docker environment)
- Clone the .env.test.example file and rename it to .env.test
- Open .env.test file, adjust Mail information
- Start containers if not started yet (see steps in Installation with Docker section)
- Go inside the stenup-app container if not there yet (see steps in Installation with Docker section)
- Run "php artisan key:generate --env=test"
- Run "php artisan jwt:secret --env=test"
- Run "php vendor/bin/codecept run" to run the tests

# URLs
- User site: /
- Admin site: /admin
- API documentation: /api
