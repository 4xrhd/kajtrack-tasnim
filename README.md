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

## 💾 2. How to Run in XAMPP (Windows & Linux)

Deploying KajTrack inside XAMPP is straightforward:

1. **Locate your XAMPP Web Root**:
   - **Windows**: `C:\xampp\htdocs\`
   - **Linux**: `/opt/lampp/htdocs/`
2. **Move Project**: Copy the `kajtrack` folder directly into the web root:
   - **Windows**: Move to `C:\xampp\htdocs\kajtrack`
   - **Linux**: Run `sudo cp -r /path/to/project/tasnim-54/kajtrack /opt/lampp/htdocs/kajtrack`
3. **Start Servers**: Open the XAMPP Control Panel and start **Apache** and **MySQL**.
4. **Create Database**:
   - Navigate to `http://localhost/phpmyadmin/` in your browser.
   - Click **New** in the sidebar, name the database `task_management_db`, and select UTF-8 coding.
5. **Import Schema**:
   - Click on the newly created `task_management_db` database.
   - Go to the **Import** tab at the top.
   - Click **Choose File** and select `/database/task_management_db.sql` located inside the project files.
   - Scroll to the bottom and click **Go** (or **Import**).
6. **Open Dashboard**: Go to your browser and enter:
   ```
   http://localhost/kajtrack/login.php
   ```

---

## 🔑 Default Login Credentials

Both SQLite and MySQL databases are pre-seeded with the following credentials. All default passwords are the string **`123`**:

### 👑 Administrator
- **Username**: `admin`
- **Password**: `123`

### 👤 Employees
- **John**: Username: `john` | Password: `123`
- **Elias**: Username: `elias` | Password: `123`
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
