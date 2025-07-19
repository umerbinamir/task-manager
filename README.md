# Task Manager

A simple task management application which is built with Laravel. The main features includes task creation, editing, deletion, drag & drop reordering, and project-based organization.

## Features

- Create, edit, and delete tasks
- Automatic priority management
- Drag-and-drop task reordering
- Project-based task organization
- Responsive design with Tailwind CSS
- Automatic timestamps tracking
- Filter tasks by project

## Requirements

- PHP (Xampp)
- Composer
- MySQL

## Installation & Setup

### 1. Clone the Repository

```bash
git clone https://github.com/umerbinamir/task-manager.git
cd task-manager
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Configuration

Edit your `.env` file with your MySQL database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Create Database

Create a MySQL database named `task_manager` (or whatever you specified in DB_DATABASE):

```sql
CREATE DATABASE task_manager;
```

### 6. Run Migrations and Seeders

```bash
php artisan migrate --seed
```

This will create the necessary tables and populate them with sample data.

### 7. Start the Development Server

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## Usage

### Managing Tasks

1. **Create Task**: Use the form at the top of the tasks page to add new tasks
2. **Edit Task**: Click the "Edit" link next to any task
3. **Delete Task**: Click the "Delete" link (with confirmation)
4. **Reorder Tasks**: Drag and drop tasks to change their priority order

### Managing Projects

1. **Create Project**: Use the form on the projects page
2. **View Project Tasks**: Click "View Tasks" to see only tasks for that project
3. **Edit Project**: Click "Edit" to modify project details
4. **Delete Project**: Click "Delete" (this will also delete all associated tasks)

### Project Filtering

- Use the dropdown filter on the tasks page to view tasks for a specific project
- Select "All Projects" to view all tasks regardless of project

## Database Schema

### Projects Table
- `id` - Primary key
- `name` - Project name
- `description` - Optional project description
- `created_at` / `updated_at` - Timestamps

### Tasks Table
- `id` - Primary key
- `name` - Task name
- `priority` - Integer priority (1 = highest priority)
- `project_id` - Foreign key to projects table (nullable)
- `created_at` / `updated_at` - Timestamps

## Technical Implementation

### Drag-and-Drop Functionality

The application uses [SortableJS](https://sortablejs.github.io/Sortable/) for drag-and-drop functionality. When tasks are reordered:

1. JavaScript captures the new order
2. when an AJAX request is sent to `/tasks/reorder`
3. The server updates priority values in the database
4. The UI is updated to reflect new priorities.

### Priority Management

- The new tasks automatically get the next available priority
- When user reorder the tasks, all priorities are recalculated like 1, 2, 3, etc.
- Priority 1 is the highest priority which appears at the top.
