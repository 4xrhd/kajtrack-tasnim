# 📊 KajTrack System Execution Flow Diagrams

This document contains Mermaid sequence diagrams that map the primary interaction loops, authorization guards, database drivers, and redirect parameters of the platform.

---

## 🔑 1. User Authentication & Login Flow

```mermaid
sequenceDiagram
    actor User
    participant LoginPage as views/login.php
    participant LoginHandler as handlers/login.php
    participant DBConn as config/DB_connection.php
    participant UserModel as models/User.php
    participant DB as DB Engine (MySQL/SQLite)
    participant Dashboard as views/index.php

    User->>LoginPage: Browse to login screen
    User->>LoginHandler: POST username + password
    LoginHandler->>DBConn: Request connection ($conn)
    DBConn-->>LoginHandler: Return Active PDO instance
    LoginHandler->>UserModel: Query username record
    UserModel->>DB: SELECT * FROM users WHERE username = ?
    DB-->>UserModel: User account row with BCrypt Hash
    UserModel-->>LoginHandler: Return data array
    LoginHandler->>LoginHandler: Cryptographic password_verify() matching
    alt Valid Credentials Match
        LoginHandler->>LoginHandler: Populate secure $_SESSION keys
        LoginHandler-->>Dashboard: Redirect to /index.php (Login Successful)
    else Invalid Credentials / No Match
        LoginHandler-->>LoginPage: Redirect with /login.php?error=Incorrect username...
    end
```

---

## 👑 2. Administrator Creates Task & Alerts Employee

```mermaid
sequenceDiagram
    actor Admin
    participant CreateTaskPage as views/create_task.php
    participant AddTaskHandler as handlers/add-task.php
    participant DBConn as config/DB_connection.php
    participant TaskModel as models/Task.php
    participant NotifModel as models/Notification.php
    participant DB as DB Engine (MySQL/SQLite)

    Admin->>CreateTaskPage: Load assignment form
    Admin->>AddTaskHandler: POST title, description, assigned_to, due_date
    AddTaskHandler->>AddTaskHandler: Validate admin role guard
    AddTaskHandler->>DBConn: Request connection ($conn)
    DBConn-->>AddTaskHandler: Return Active PDO instance
    AddTaskHandler->>TaskModel: insert_task(title, description, assignee, due_date)
    TaskModel->>DB: INSERT INTO tasks (...)
    DB-->>TaskModel: Confirm write success
    AddTaskHandler->>NotifModel: insert_notification(recipient_id, alert_text)
    NotifModel->>DB: INSERT INTO notifications (...)
    DB-->>NotifModel: Confirm alert write success
    AddTaskHandler-->>CreateTaskPage: Redirect with ?success=Task added successfully
```

---

## 👤 3. Employee Updates Task Progress Stage

```mermaid
sequenceDiagram
    actor Employee
    participant MyTaskPage as views/my_task.php
    participant EditTaskPage as views/edit-task-employee.php
    participant UpdateHandler as handlers/update-task-employee.php
    participant DBConn as config/DB_connection.php
    participant TaskModel as models/Task.php
    participant DB as DB Engine (MySQL/SQLite)

    Employee->>MyTaskPage: Access personal work backlog
    Employee->>EditTaskPage: Click "Update Status" on task row
    Employee->>UpdateHandler: POST task_id + selected_status (completed / in_progress)
    UpdateHandler->>UpdateHandler: Validate employee role guard
    UpdateHandler->>DBConn: Request connection ($conn)
    DBConn-->>UpdateHandler: Return Active PDO instance
    UpdateHandler->>TaskModel: update_task_status(status, id)
    TaskModel->>DB: UPDATE tasks SET status = ? WHERE id = ?
    DB-->>TaskModel: Confirm save success
    UpdateHandler-->>MyTaskPage: Redirect with ?success=Status updated successfully
```

---

## 🔔 4. Asynchronous Header Notifications Fetch

```mermaid
sequenceDiagram
    actor User
    participant Header as views/layout/header.php (JS)
    participant CountEndpoint as handlers/notification-count.php
    participant ListEndpoint as handlers/notification.php
    participant ReadEndpoint as handlers/notification-read.php
    participant DBConn as config/DB_connection.php
    participant DB as DB Engine (MySQL/SQLite)
    participant NotificationsPage as views/notifications.php

    User->>Header: Open any authenticated page
    Note over Header,CountEndpoint: Runs automatically every 5 seconds
    Header->>CountEndpoint: AJAX GET unread count
    CountEndpoint->>DBConn: Request connection ($conn)
    CountEndpoint->>DB: SELECT COUNT(id) FROM notifications WHERE recipient = ? AND is_read = 0
    DB-->>CountEndpoint: Returns integer unread total
    CountEndpoint-->>Header: Updates unread badge indicator in DOM

    User->>Header: Click bell notification icon
    Header->>ListEndpoint: AJAX GET dropdown list items
    ListEndpoint->>DBConn: Request connection ($conn)
    ListEndpoint->>DB: SELECT * FROM notifications WHERE recipient = ? ORDER BY id DESC LIMIT 5
    DB-->>ListEndpoint: Returns recent notification rows
    ListEndpoint-->>Header: Renders styled HTML list-group items

    User->>Header: Click an item in notification dropdown
    Header->>ReadEndpoint: GET /handlers/notification-read.php?notification_id=<id>
    ReadEndpoint->>DBConn: Request connection ($conn)
    ReadEndpoint->>DB: UPDATE notifications SET is_read = 1 WHERE id = ?
    DB-->>ReadEndpoint: Confirm updated status
    ReadEndpoint-->>NotificationsPage: Redirect client to main alerts inbox view
```
