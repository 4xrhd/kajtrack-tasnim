# 🏛️ KajTrack Architecture & Design

KajTrack uses a modern, modular PHP architecture designed for scalability, database portability, and a clear separation of business logic from UI templates.

---

## 🏗️ High-Level Design Pattern

The application implements a clean **Model-View-Handler** structure to decouple database interactions, presentation templates, and backend form endpoints:

```
┌──────────────────────────────────────────────────────────────┐
│                        bootstrap.php                         │
│   (Initializes sessions, loads settings, imports models)    │
└──────────────┬──────────────────────────────┬────────────────┘
               │                              │
               ▼                              ▼
 ┌───────────────────────────┐  ┌───────────────────────────┐
 │       views/layout/       │  │          models/          │
 │  (header.php / footer.php)│  │ (User / Task / Notif SQL) │
 └─────────────┬─────────────┘  └─────────────┬─────────────┘
               │                              │
               ▼                              ▼
 ┌───────────────────────────┐  ┌───────────────────────────┐
 │          views/           │  │         handlers/         │
 │  (index.php / tasks.php)  │  │ (Process POST & Redirect) │
 └───────────────────────────┘  └───────────────────────────┘
```

1. **Bootstrapper (`bootstrap.php`)**: Acts as the central system entrypoint. It starts sessions securely (CLI-safe), sets up timezone settings, and auto-imports all models.
2. **Views (`views/`)**: Clean HTML/PHP presentation templates. They do not write queries or alter state directly; they only display data by calling functions in `models/` and render layouts housed in `views/layout/`.
3. **Models (`models/`)**: Reusable data access libraries that use parameterized PDO statements. They handle all operations for `User`, `Task`, and `Notification` tables.
4. **Handlers (`handlers/`)**: Dedicated back-end POST endpoints. They process form submissions, validate roles, call models to persist state, and redirect back to the front-end view layer.

---

## 🔑 Runtime Lifecycle & Session Model

1. **Accessing a Page**:
   - The user requests a view (e.g. `/index.php` which maps directly to `/views/index.php`).
   - The view includes the core bootstrapper `bootstrap.php`, which establishes the session and database connection `$conn` via `config/DB_connection.php`.
   - The view checks the user's role: `$_SESSION['role']` must be set.
2. **Authentication Flow**:
   - The login page submissions post to `handlers/login.php`.
   - The handler loads the connection, queries the `users` table, and verifies the password using modern `password_verify` matching.
   - Successful auth populates `$_SESSION['id']`, `$_SESSION['username']`, `$_SESSION['role']`, and `$_SESSION['full_name']`, then redirects to `index.php`.
3. **State Mutation**:
   - Creating/updating entities (Users, Tasks, Profiles) posts data to files in the `/handlers/` directory.
   - Handlers validate authorization, apply model changes, and redirect to the frontend views using standardized response parameters (`?success=...` or `?error=...`).

---

## 🛡️ Route Access Model

Route access is restricted and validated at the top of each view file:

### 👑 Administrator Role (`admin`)
- **User Management**: `add-user.php`, `edit-user.php`, `delete-user.php`
- **Task Assignment & Admin View**: `create_task.php`, `tasks.php`, `edit-task.php`, `delete-task.php`

### 👤 Employee Role (`employee`)
- **Profile Management**: `profile.php`, `edit_profile.php`
- **Task Updating**: `edit-task-employee.php`

### 🤝 Shared/Authenticated Routes
- **Workload Dashboard**: `index.php`
- **Personal Work**: `my_task.php`
- **Notifications & Logout**: `notifications.php`, `logout.php`

---

## 🔔 Asynchronous Notification Subsystem

Notifications are loaded asynchronously via custom AJAX hooks inside `views/layout/header.php`:
- **Badge Counter**: Standard notifications badge queries `handlers/notification-count.php` every 5 seconds to load unread counts.
- **Dropdown List**: Clicking the bell icon executes an AJAX fetch to `handlers/notification.php`, returning the unread list fragment.
- **Read Action**: Clicking a notification triggers `handlers/notification-read.php?notification_id=<id>`, marking the item read and redirecting the employee.

---

## 📂 Architecture Map
- **`bootstrap.php`**: Global bootstrapper and model autoloading.
- **`config/DB_connection.php`**: Self-healing connection manager supporting zero-config SQLite and production MySQL.
- **`views/layout/header.php`**: Custom navigation sidebars, bell notification drawers, and responsiveness structures.
- **`models/User.php`**: Reusable user querying, user addition, counts, and profile updates.
- **`models/Task.php`**: Metric queries, dynamic parameter-bound task list sorting, and task state updates.
- **`models/Notification.php`**: Count utilities and event alerts.
