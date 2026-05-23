# Task Management System

A simple To-Do App built with Laravel 13, MySQL, and pure CSS.

## Requirements
- PHP 8.3
- Composer
- Laravel 13
- MySQL (via Laragon)

## Setup Instructions

1. **Clone the repository**
   git clone https://github.com/akyrraaa/TaskManagementSystemTodoList.git
   cd TaskManagementSystemTodoList

2. **Install dependencies**
   composer install

3. **Configure environment**
   cp .env.example .env
   php artisan key:generate

4. **Set up database**
   - Create a MySQL database named `taskmanagement`
   - Update `.env` file:
   DB_DATABASE=taskmanagement
   DB_USERNAME=root
   DB_PASSWORD=

5. **Run migrations and seed**
   php artisan migrate --seed

6. **Run the application**
   php artisan serve

7. **Open browser**
   http://127.0.0.1:8000/tasks

## Database Summary

The `tasks` table connects to the `categories` table via a **foreign key** (`category_id`).

- `categories` table — stores task categories (Work, Personal, Study)
- `tasks` table — each task **belongs to** one category (`belongsTo`)
- `Category` model — has many tasks (`hasMany`)

This relationship ensures every task is always linked to a valid category.

## Group Members
- John Paul A. Laforteza
- Arjel Joseph D. Laguardia
- Kreshille G. Tagum
- Mailene DG. Terrado