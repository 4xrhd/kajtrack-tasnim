# 📊 KajTrack — Role-Based Task Management System

KajTrack is a modern, modular role-based task management web application built on a clean PHP MVC architecture. It features a fully database-agnostic design with zero-config SQLite/MySQL support, structured headless event routing, and a modern, liquid-glass visual aesthetic powered by the professional **Outfit** font.

It enables organizations to assign, track, and monitor employee tasks through role-based admin and employee dashboards, real-time counters, and asynchronous event notifications.

---

## 🛠️ Technology Stack

- **Server-Side**: PHP (8.x compatible)
- **Database Engine**: Fully portable MySQL / MariaDB (production) & SQLite (zero-config local sandbox)
- **Data Access Layer**: 100% database-agnostic PDO prepared statements
- **Front-End Styling**: Vanilla CSS Modern variables, Outfit typography, Font Awesome, and Bootstrap 5.3 CDN
- **Scripting**: Asynchronous jQuery-based AJAX notifications

---

## 🚀 1. How to Run on Linux (LAMP / PHP Server)

You can run KajTrack on Linux either instantly using PHP's built-in development server (Fast Track) or using a standard Apache/LAMP environment.

### Option A: Instant Development Server (No Setup Required)
KajTrack features a self-healing database fallback. If a MySQL connection is not configured or unavailable, it will automatically provision and seed an SQLite database inside `database/task_management_db.sqlite`.

1. Open your terminal and navigate to the project directory:
   ```bash
   cd /path/to/project/tasnim-54/kajtrack
   ```
2. Start the built-in PHP server:
   ```bash
   php -S 127.0.0.1:8000 -t public/
   ```
3. Open your browser and navigate to:
   ```
   http://127.0.0.1:8000/login.php
   ```
   > [!NOTE]
   > The application will automatically detect that no MySQL database exists, construct a local SQLite database in the `/database/` directory, set up the tables, and seed them with the default users and tasks.

---

### Option B: Apache / LAMP Production Setup
1. Move the project folder into your web root directory (usually `/var/www/html/`):
   ```bash
   sudo cp -r /path/to/project/tasnim-54/kajtrack /var/www/html/kajtrack
   ```
2. Set ownership and write permissions so that Apache (`www-data`) can manage sessions and SQLite fallbacks if needed:
   ```bash
   sudo chown -R www-data:www-data /var/www/html/kajtrack
   sudo chmod -R 775 /var/www/html/kajtrack/database
   ```
3. Create a MySQL database and import the official schema dump:
   ```bash
   mysql -u root -p -e "CREATE DATABASE task_management_db;"
   mysql -u root -p task_management_db < /var/www/html/kajtrack/database/task_management_db.sql
   ```
4. Verify/configure MySQL credentials in `/config/DB_connection.php`.
5. Access the application in your browser:
   ```
   http://localhost/kajtrack/login.php
   ```

---

## 💾 2. Windows & XAMPP Complete Setup Guide

Deploying KajTrack on Windows using XAMPP is straightforward. Follow these steps to set up your environment:

### 📥 Step 1: Install and Start XAMPP
1. Download and install [XAMPP for Windows](https://www.apachefriends.org/download.html) (PHP 8.x or higher recommended).
2. Open the **XAMPP Control Panel** from your start menu.
3. Click the **Start** button next to both **Apache** and **MySQL** to run the services.

### 📂 Step 2: Deploy the Codebase
1. Locate your XAMPP installation directory (usually `C:\xampp`).
2. Navigate to the web server root directory: `C:\xampp\htdocs\`.
3. Copy the entire `kajtrack` project directory into `htdocs`, resulting in the path:
   ```text
   C:\xampp\htdocs\kajtrack\
   ```

### 🗄️ Step 3: Create the Database in phpMyAdmin
1. Open your web browser and navigate to the XAMPP database management dashboard:
   ```text
   http://localhost/phpmyadmin/
   ```
2. Click on **New** in the left-hand sidebar.
3. Under **Database name**, enter:
   ```text
   task_management_db
   ```
4. Choose `utf8mb4_general_ci` as the collation, and click **Create**.

### 📥 Step 4: Import the Schema
1. Select your newly created `task_management_db` database in the left sidebar.
2. Click on the **Import** tab in the top navigation bar.
3. Click **Choose File** (or **Browse**) and select the SQL schema dump file inside the project:
   ```text
   C:\xampp\htdocs\kajtrack\database\task_management_db.sql
   ```
4. Scroll to the bottom of the page and click **Import** (or **Go**). The tables will instantly populate!

### ⚙️ Step 5: Verify Connection Configuration
1. Open `C:\xampp\htdocs\kajtrack\config\DB_connection.php` in a text editor.
2. Ensure the MySQL connection parameters align with XAMPP's default values:
   ```php
   $host = "localhost";
   $user = "root";       // Default XAMPP user
   $password = "";       // Default XAMPP password is empty
   $db_name = "task_management_db";
   ```

### 🚀 Step 6: Open the Application
1. In your browser, navigate to the login portal:
   ```text
   http://localhost/kajtrack/login.php
   ```
2. Log in using the pre-seeded credentials:
   * **Admin (Tasnim)**: Username: `tasnim` | Password: `123`
   * **Employee (Mitu)**: Username: `mitu` | Password: `123`

> [!TIP]
> **Zero-Config SQLite Alternative for Windows:**
> If you prefer a sandbox environment without setting up MySQL tables or phpMyAdmin, you can just start XAMPP Apache, copy the folder, and go directly to the URL. If the MySQL connection fails, KajTrack will automatically create and seed a secure SQLite database (`C:\xampp\htdocs\kajtrack\database\task_management_db.sqlite`) with no manual intervention!

---

## 🔑 Default Login Credentials

Both SQLite and MySQL databases are pre-seeded with the following credentials. All default passwords are the string **`123`**:

### 👑 Administrators
- **Tasnim Rahman** (Project Owner): Username: `tasnim` | Password: `123`
- **Oliver Admin**: Username: `admin` | Password: `123`

### 👤 Employees
- **Umme Hany Mitu** (Project Owner): Username: `mitu` | Password: `123`
- **Elias**: Username: `elias` | Password: `123`
- **John**: Username: `john` | Password: `123`
- **Oliver**: Username: `oliver` | Password: `123`

---

## 📁 Reorganized Directory Structure

KajTrack uses a modern, modular MVC architecture to maintain a clean separation of concerns and eliminate legacy script coupling:

- **`bootstrap.php`**: Global bootstrapper. Safely handles session starts across CLI/web systems and imports all models.
- **`config/`**: System configuration scripts.
  - `config/DB_connection.php`: Portable MySQL connector with automatic SQLite failover, table auto-creation, and data-seeding.
- **`models/`**: Reusable database access libraries.
  - `models/User.php`: Employee database logic.
  - `models/Task.php`: Task lifecycle and dashboard metrics.
  - `models/Notification.php`: Real-time dispatching and unread queries.
- **`handlers/`**: Back-end form mutation and AJAX endpoints.
- **`views/`**: Protected front-end HTML/PHP visual templates.
  - `views/layout/header.php` / `views/layout/footer.php`: Global, premium styled header and responsive sidebar components.
- **`assets/`**: Static CSS, javascripts, and visual assets.

---

## 📖 Complete Documentation Index

For deep architectural and implementation details, refer to the following resources:
- **System Architecture**: [docs/ARCHITECTURE.md](file:///home/tr/Desktop/project/tasnim-54/kajtrack/docs/ARCHITECTURE.md)
- **Headless Routing**: [docs/ROUTES_AND_HANDLERS.md](file:///home/tr/Desktop/project/tasnim-54/kajtrack/docs/ROUTES_AND_HANDLERS.md)
- **Database Schema**: [docs/DATABASE.md](file:///home/tr/Desktop/project/tasnim-54/kajtrack/docs/DATABASE.md)
- **Deployment Guide**: [docs/DEPLOYMENT.md](file:///home/tr/Desktop/project/tasnim-54/kajtrack/docs/DEPLOYMENT.md)
- **Security Strategy**: [docs/SECURITY.md](file:///home/tr/Desktop/project/tasnim-54/kajtrack/docs/SECURITY.md)
- **Quality & Testing**: [docs/TESTING.md](file:///home/tr/Desktop/project/tasnim-54/kajtrack/docs/TESTING.md)
- **Sequence Flows**: [docs/SEQUENCE_DIAGRAMS.md](file:///home/tr/Desktop/project/tasnim-54/kajtrack/docs/SEQUENCE_DIAGRAMS.md)

---

## 👥 Developed By (Contributors)

This project is developed and maintained by:

- **Umme Hany Mitu**
  - **ID**: `0432320005101077`
- **Tasnim Rahman**
  - **ID**: `0432320005101092`

# kajtrack-tasnim
