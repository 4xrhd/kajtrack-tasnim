# 🛣️ Route & Headless Handler Directory Mapping

This document provides a comprehensive mapping of all GET views (Pages), POST/GET action handlers, security levels, and redirection parameters.

---

## 🔑 Active Session Metadata
Upon a successful login, the session is populated with the following keys:
- `$_SESSION['id']` (integer): Primary key matching `users.id`
- `$_SESSION['username']` (string): Unique login handle
- `$_SESSION['role']` (string): Access level (`'admin'` or `'employee'`)
- `$_SESSION['full_name']` (string): Display name of the user

---

## 📄 1. Page Routes (GET Views)
All user-facing HTML templates are housed inside `/views/` and globally protected by session filters.

### 🔓 Public Routes
- **`GET /login.php`**
  - **Purpose**: Authenticated landing view. If the user is already logged in, they are auto-redirected to `index.php`.

---

### 🛡️ Shared Authenticated Routes
- **`GET /index.php`**
  - **Security**: Authenticated (Admin/Employee).
  - **Purpose**: Interactive metrics dashboard. Shows role-aware stats.
- **`GET /my_task.php`**
  - **Security**: Authenticated (Admin/Employee).
  - **Purpose**: Displays a grid of tasks currently assigned to the logged-in user.
- **`GET /notifications.php`**
  - **Security**: Authenticated (Admin/Employee).
  - **Purpose**: Unified listing of unread and read employee alerts.
- **`GET /logout.php`**
  - **Security**: Authenticated.
  - **Purpose**: Headless endpoint that destroys the active PHP session and redirects back to `/login.php`.

---

### 👑 Admin-Only Routes
- **`GET /user.php`**
  - **Purpose**: Manage employees. Contains action buttons for modification and creation.
- **`GET /add-user.php`**
  - **Purpose**: Employee onboarding form.
- **`GET /edit-user.php?id=<id>`**
  - **Purpose**: Form to modify user details or overwrite passwords.
- **`GET /delete-user.php?id=<id>`**
  - **Purpose**: Headless endpoint that deletes the employee and redirects to `/user.php`.
- **`GET /tasks.php`**
  - **Purpose**: Global task backlog. Supports filter queries:
    - `?due_date=Due Today`
    - `?due_date=Overdue`
    - `?due_date=No Deadline`
- **`GET /create_task.php`**
  - **Purpose**: Create and assign a task to an employee.
- **`GET /edit-task.php?id=<id>`**
  - **Purpose**: Administrative task editor.
- **`GET /delete-task.php?id=<id>`**
  - **Purpose**: Headless deletion endpoint.

---

### 👤 Employee-Only Routes
- **`GET /profile.php`**
  - **Purpose**: Displays the logged-in employee's account details.
- **`GET /edit_profile.php`**
  - **Purpose**: Self-managed profile and password update form.
- **`GET /edit-task-employee.php?id=<id>`**
  - **Purpose**: Streamlined screen allowing employees to update task status.

---

## ⚡ 2. Action Handlers (`/handlers/`)
All mutations, form submissions, and AJAX callbacks target file endpoints located within the `/handlers/` directory.

### 🔐 Authentication Handler
- **`POST /handlers/login.php`**
  - **Payload**: `user_name` (string), `password` (string)
  - **Operation**: Authenticates credentials using `password_verify()` against the standard BCrypt hash.
  - **Success**: Redirects to `/index.php`
  - **Failure**: Redirects to `/login.php?error=Incorrect username or password`

---

### 👑 Admin Management Handlers
- **`POST /handlers/add-user.php`**
  - **Payload**: `full_name` (string), `user_name` (string), `password` (string)
  - **Operation**: Registers a new employee. Automatically generates secure BCrypt hashes.
- **`POST /handlers/update-user.php`**
  - **Payload**: `id` (int), `full_name` (string), `user_name` (string), `password` (optional password overwrite)
  - **Operation**: Updates employee details.
- **`POST /handlers/add-task.php`**
  - **Payload**: `title` (string), `description` (string), `assigned_to` (int), `due_date` (YYYY-MM-DD)
  - **Operation**: Creates a task and dispatches an automatic alert notification to the assigned employee.
- **`POST /handlers/update-task.php`**
  - **Payload**: `id` (int), `title` (string), `description` (string), `assigned_to` (int), `due_date` (YYYY-MM-DD)
  - **Operation**: Updates task metadata.

---

### 👤 Employee Interaction Handlers
- **`POST /handlers/update-task-employee.php`**
  - **Payload**: `id` (int), `status` (pending | in_progress | completed)
  - **Operation**: Updates the task execution stage.
- **`POST /handlers/update-profile.php`**
  - **Payload**: `full_name` (string), `password` (current password), `new_password` (new password), `confirm_password` (confirmation)
  - **Operation**: Validates current password and securely updates the user record.

---

### 🔔 Asynchronous API Handlers (GET)
- **`GET /handlers/notification-count.php`**
  - **Returns**: Unread notification count for the sidebar bell indicator.
- **`GET /handlers/notification.php`**
  - **Returns**: Unread notification item dropdown links.
- **`GET /handlers/notification-read.php?notification_id=<id>`**
  - **Operation**: Marks a notification as read and redirects the employee to their dashboard.

---

## 📢 Feedback & Response Patterns
All handlers provide feedback by redirecting to their respective view pages with standard query parameters:
- **Success Alert**: `?success=Action completed successfully`
- **Error Banner**: `?error=An error occurred during submission`

These values are caught at the page level and rendered in elegant alert blocks.
