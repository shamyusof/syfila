

## Installation

Follow the steps mentioned below to install and run the project. 

1. Clone or download the repository
2. Go to the project directory and run `composer install`
3. Run `npm install`
4. Create `.env` file by copying the `.env.example`. You may use the command to do that `cp .env.example .env`
5. Update the database name and credentials in `.env` file
6. Run the command `php artisan migrate --seed`
7. Link storage directory: `php artisan storage:link`
8. Run the command : `npm run dev` or `npm run build`
9. You may create a virtualhost entry to access the application or run `php artisan serve` from the project root and visit `http://127.0.0.1:8000`

# Appplication Demo
Check the following demo project. It is just a straight installation of the project without any modification.

Demo URL: http://127.0.0.1:8000/admin/login

You may use the following account credentials to access the application backend.

```
User: super@admin.com
Pass: password

User: admin@admin.com
Pass: password

User: moderator@admin.com
Pass: password

User: developer@admin.com
Pass: password

```
