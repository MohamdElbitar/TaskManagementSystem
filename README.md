## Task Management System API
This is a RESTful API for a Task Management System built with Laravel. It includes features like authentication, role-based access control, task creation, task dependencies, and filtering tasks by status, due date, and assigned user.

## Features
Authentication: Uses Laravel Sanctum for API token-based authentication.

Role-Based Access Control: Uses Spatie Laravel-Permission to manage roles (Manager and User).

## Task Management:

1-Create, update, and delete tasks.

2-Add task dependencies.

3-Filter tasks by status, due date range, and assigned user.

4-Database: MySQL database with migrations and seeders.

## Requirements
PHP >= 8.0

Composer

MySQL

Laravel Sanctum

Spatie Laravel-Permission


## Installation
# 1-Clone the Repository:

git clone https://github.com/MohamdElbitar/TaskManagementSystem.git

cd TaskManagementSystem

## Install Dependencies:

composer install

## Set Up Environment:

Copy the .env.example file to .env 

# Update the .env file with your database credentials:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_management
DB_USERNAME=root
DB_PASSWORD=

## Generate Application Key:

php artisan key:generate

# Run Migrations and Seeders:

php artisan migrate --seed

# Run the Application:

php artisan serve




## Contributing
If you'd like to contribute, please fork the repository and create a pull request.

## License
This project is open-source and available under the MIT License.

## Contact
For any questions or issues, please contact MohamedElbitar.


