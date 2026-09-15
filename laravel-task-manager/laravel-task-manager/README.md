# Task Manager (Laravel 11)

A clean task management app: create tasks, organize them into projects, filter
by project, and reorder tasks with drag-and-drop.

This package contains only the **application-layer files** (models,
controllers, migrations, routes, views) — you drop them into a fresh Laravel
11 install. That keeps the download small and means you always start from a
current, correctly-installed Laravel core rather than a hand-copied one.

## What's included

```
app/Http/Controllers/DashboardController.php
app/Http/Controllers/ProjectController.php
app/Http/Controllers/TaskController.php
app/Models/Project.php
app/Models/Task.php
database/migrations/2024_01_01_000001_create_projects_table.php
database/migrations/2024_01_01_000002_create_tasks_table.php
database/seeders/DatabaseSeeder.php
resources/views/layouts/app.blade.php
resources/views/dashboard/index.blade.php
routes/web.php
.env.example
```

## Setup

**1. Create a fresh Laravel 11 project** (requires PHP 8.2+ and Composer):

```bash
composer create-project laravel/laravel task-manager "11.*"
cd task-manager
```

**2. Copy these files into it**, overwriting `routes/web.php` and the default
`.env.example`:

```bash
cp -r /path/to/this-package/app/* app/
cp -r /path/to/this-package/database/* database/
cp -r /path/to/this-package/resources/* resources/
cp /path/to/this-package/routes/web.php routes/web.php
cp /path/to/this-package/.env.example .env.example
```

**3. Configure the environment.** SQLite is the fastest way to get running
with zero server setup:

```bash
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
```

(If you'd rather use MySQL, edit `.env` to use the commented-out `DB_*` MySQL
values instead, and create the database first, e.g. `mysql -u root -e "CREATE DATABASE task_manager"`.)

**4. Run migrations** (and optionally seed sample data):

```bash
php artisan migrate
php artisan db:seed   # optional: adds 2 sample projects and 6 sample tasks
```

**5. Serve the app:**

```bash
php artisan serve
```

Visit **http://localhost:8000**.

## How it works

- **Projects** (`projects` table) — name, description, a color used for
  badges in the UI.
- **Tasks** (`tasks` table) — belong optionally to a project
  (`project_id` is nullable), have a `status` enum
  (`pending` / `in_progress` / `completed`), and a `position` integer that
  drives drag-and-drop ordering.
- **Filtering** — the sidebar links to `/?project={id}`, `/?project=none`
  (unassigned tasks), or `/` (all tasks). `DashboardController::index()`
  reads that query param and scopes the Eloquent query accordingly.
- **Drag-and-drop reordering** — the task `<ul>` uses
  [SortableJS](https://sortablejs.github.io/Sortable/) (loaded from a CDN,
  no npm build step needed). On drop, it POSTs the new array of task IDs to
  `POST /tasks/reorder`, and `TaskController::reorder()` updates each task's
  `position` column inside a DB transaction.
- **Status toggle** — clicking the circle next to a task cycles
  pending → in progress → completed via `PATCH /tasks/{task}/toggle`.
- **Editing** — each task/project has a small "⋮" menu (built with a plain
  `<details>` element, no JS needed) containing an edit form and a delete
  button.
- **Moving a task to a different project** — done via the project dropdown
  in the task's edit form (simpler and more reliable than dragging across
  two different drop targets); the task is placed at the end of its new
  project's list.

## Notes / things you could extend

- Add authentication (`php artisan make:auth`-style scaffolding or
  Laravel Breeze) if you want per-user task lists instead of a single shared
  board.
- Add due-date sorting/overdue highlighting.
- Add Form Request classes (`php artisan make:request`) if the inline
  validation in the controllers grows.
- Add feature tests under `tests/Feature` for the CRUD and reorder endpoints
  (e.g. assert `POST /tasks/reorder` updates `position` correctly).
