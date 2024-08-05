# Todo List API

## Project Overview

The Todo List API is a RESTful API designed to manage tasks within a to-do list. It allows users to create, update, filter, and delete their tasks. This API includes support for nested tasks with unlimited depth and provides comprehensive filtering and sorting capabilities.

## Features

- **Task Management**
    - Create, update, and delete tasks
    - Change task status
    - Nested tasks with unlimited depth

- **Filtering**
    - Filter tasks by `status`, `priority`
    - Full-text search for `title` and `description`

- **Sorting**
    - Sort tasks by `created_at`, `completed_at`, and `priority`
    - Support for multi-field sorting (e.g., `priority desc, created_ asc`)

- **Restrictions**
    - Users cannot modify or delete tasks owned by others
    - Completed tasks cannot be deleted
    - Tasks with incomplete subtasks cannot be marked as completed

## Requirements

- PHP 8.1+
- Laravel
- Docker Compose for environment setup

## Setup

### Installation

1. **Clone the Repository**<br>
   `git clone git@github.com:JiggaRin/ToDoListAPI.git`<br>
   `cd ToDoListAPI`
2. **Install Dependencies** <br>
   `composer install` <br> `npm install`
3. **Set Up Environment**<br>
Copy the `.env.example` file to `.env` and configure your environment settings.<br>
   `cp .env.example .env`
4. **Start the Development Server**<br>
      `docker-compose up -d`
5. **Run Migrations and Seeders**<br>
`php artisan migrate --seed`
6. **Generate Application Key**<br>
`php artisan key:generate`
7. **Run local server**<br>
`php artisan serve`


## Usage

### Authentication

- **Login**

`POST /api/login`

**Request body**:<br>
`{
"email": "user@example.com",
"password": "yourpassword"
}`<br>
**Response:**<br>
`{
"access_token": "your_access_token",
"token_type": "Bearer"
}`
- **Logout**

`POST /api/logout`

Response:<br>
`{
"message": "Successfully logged out"
}`
### Documentation Endpoint

`/api/documentation`
