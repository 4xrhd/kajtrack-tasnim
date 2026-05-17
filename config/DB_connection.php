<?php  

$sName = "localhost";
$uName = "root";
$pass  = "toor";
$db_name = "task_management_db";

$conn = null;

try {
    // Attempt standard MySQL connection
    $conn = new PDO("mysql:host=$sName;dbname=$db_name", $uName, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    // Fallback to SQLite if MySQL fails
    $sqlite_path = __DIR__ . '/../database/task_management_db.sqlite';
    $db_exists = file_exists($sqlite_path);
    
    try {
        $conn = new PDO("sqlite:" . $sqlite_path);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Enable foreign keys for SQLite
        $conn->exec("PRAGMA foreign_keys = ON;");
        
        // If the database file is newly created, build the schema and seed data
        if (!$db_exists || filesize($sqlite_path) === 0) {
            // Create Users Table
            $conn->exec("CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                full_name TEXT NOT NULL,
                username TEXT NOT NULL,
                password TEXT NOT NULL,
                role TEXT CHECK(role IN ('admin', 'employee')) NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );");
            
            // Create Tasks Table
            $conn->exec("CREATE TABLE IF NOT EXISTS tasks (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT NOT NULL,
                description TEXT,
                assigned_to INTEGER,
                due_date TEXT,
                status TEXT CHECK(status IN ('pending', 'in_progress', 'completed')) DEFAULT 'pending',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL
            );");
            
            // Create Notifications Table
            $conn->exec("CREATE TABLE IF NOT EXISTS notifications (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                message TEXT NOT NULL,
                recipient INTEGER NOT NULL,
                type TEXT NOT NULL,
                date TEXT DEFAULT CURRENT_TIMESTAMP,
                is_read INTEGER DEFAULT 0
            );");
            
            // Seed Default Users
            // passwords match the SQL dump: admin->admin, elias->elias, john->john, oliver->oliver
            $conn->exec("INSERT INTO users (id, full_name, username, password, role) VALUES 
                (1, 'Oliver Admin', 'admin', '$2y$10\$TnyR1Y43m1EIWpb0MiwE8Ocm6rj0F2KojE3PobVfQDo9HYlAHY/7O', 'admin'),
                (2, 'Elias A.', 'elias', '$2y$10\$8xpI.hVCVd/GKUzcYTxLUO7ICSqlxX5GstSv7WoOYfXuYOO/SZAZ2', 'employee'),
                (7, 'John', 'john', '$2y$10\$CiV/f.jO5vIsSi0Fp1Xe7ubWG9v8uKfC.VfzQr/sjb5/gypWNdlBW', 'employee'),
                (8, 'Oliver', 'oliver', '$2y$10\$E9Xx8UCsFcw44lfXxiq/5OJtloW381YJnu5lkn6q6uzIPdL5yH3PO', 'employee');");
                
            // Seed Default Tasks
            $conn->exec("INSERT INTO tasks (id, title, description, assigned_to, due_date, status) VALUES 
                (1, 'Initial Orientation', 'Complete onboarding and welcome tasks.', 7, NULL, 'completed'),
                (4, 'Monthly Financial Report Preparation', 'Prepare and review the monthly financial report, including profit and loss statements, balance sheets, and cash flow analysis.', 7, '2026-06-01', 'completed'),
                (5, 'Customer Feedback Survey Analysis', 'Collect and analyze data from the latest customer feedback survey to identify areas for improvement in customer service.', 7, '2026-06-03', 'in_progress'),
                (6, 'Website Maintenance and Update', 'Perform regular maintenance on the company website, update content, and ensure all security patches are applied.', 7, '2026-06-15', 'pending'),
                (7, 'Quarterly Inventory Audit', 'Conduct a thorough audit of inventory levels across all warehouses and update the inventory management system accordingly.', 2, '2026-06-10', 'completed'),
                (8, 'Employee Training Program Development', 'Develop and implement a new training program focused on enhancing employee skills in project management and teamwork.', 2, '2026-06-05', 'pending');");

            // Seed Default Notifications
            $conn->exec("INSERT INTO notifications (id, message, recipient, type, is_read) VALUES 
                (1, '\"Customer Feedback Survey Analysis\" has been assigned to you. Please review and start working on it.', 7, 'New Task Assigned', 1),
                (2, '\"Website Maintenance and Update\" has been assigned to you. Please review and start working on it.', 7, 'New Task Assigned', 0);");
        }
    } catch (Exception $sqlite_error) {
        // If SQLite also fails, display error and stop execution
        echo "Database connection failed! MySQL Error: " . $e->getMessage() . " | SQLite Error: " . $sqlite_error->getMessage();
        exit;
    }
}