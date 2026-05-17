<?php
require_once __DIR__ . '/config/DB_connection.php';

echo "Starting database seeding with highly realistic 2026 synthetic data...\n";

try {
    // 1. Clear existing tasks and notifications
    $conn->exec("DELETE FROM notifications");
    $conn->exec("DELETE FROM tasks");
    
    // Clear custom users keeping core ones
    $conn->exec("DELETE FROM users WHERE id NOT IN (1, 2, 7, 8, 9, 10)");
    
    // Add additional realistic users including the project owners
    $new_users = [
        [3, 'Sarah Connor', 'sarah', password_hash('123', PASSWORD_DEFAULT), 'employee'],
        [4, 'David Miller', 'david', password_hash('123', PASSWORD_DEFAULT), 'employee'],
        [5, 'Sophia Martinez', 'sophia', password_hash('123', PASSWORD_DEFAULT), 'employee'],
        [6, 'Marcus Vance', 'marcus', password_hash('123', PASSWORD_DEFAULT), 'employee'],
        [9, 'Tasnim Rahman', 'tasnim', password_hash('123', PASSWORD_DEFAULT), 'admin'],
        [10, 'Umme Hany Mitu', 'mitu', password_hash('123', PASSWORD_DEFAULT), 'employee']
    ];
    
    $is_sqlite = (strpos($conn->getAttribute(PDO::ATTR_DRIVER_NAME), 'sqlite') !== false);
    
    if ($is_sqlite) {
        $user_stmt = $conn->prepare("INSERT OR REPLACE INTO users (id, full_name, username, password, role) VALUES (?, ?, ?, ?, ?)");
    } else {
        $user_stmt = $conn->prepare("INSERT INTO users (id, full_name, username, password, role) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE full_name=VALUES(full_name), role=VALUES(role)");
    }
    
    foreach ($new_users as $u) {
        $user_stmt->execute($u);
    }
    
    echo "Users table populated successfully.\n";

    // 2. Insert realistic tasks with relative dates
    $today = date('Y-m-d');
    $yesterday = date('Y-m-d', strtotime('-1 day'));
    $three_days_ago = date('Y-m-d', strtotime('-3 days'));
    $five_days_ago = date('Y-m-d', strtotime('-5 days'));
    $tomorrow = date('Y-m-d', strtotime('+1 day'));
    $two_days_later = date('Y-m-d', strtotime('+2 days'));
    $five_days_later = date('Y-m-d', strtotime('+5 days'));
    $next_week = date('Y-m-d', strtotime('+7 days'));
    
    $tasks = [
        // Completed Tasks
        ['Q1 System Audit', 'Perform code quality, security posture assessment and dependency audit on Core Repository.', 1, $three_days_ago, 'completed'],
        ['Client Database Syncing', 'Synchronize production customer list with the internal marketing CRM.', 2, $five_days_ago, 'completed'],
        ['Glassmorphism UI Overhaul', 'Implement sleek, modern glassmorphic styles with responsive theme selector across the system dashboard.', 7, $yesterday, 'completed'],
        ['Initial Project Orientation', 'Complete onboarding requirements, team meetups, and workspace config.', 8, null, 'completed'],
        ['SSL Certificates Renewal', 'Renew expired staging and production server SSL certificates.', 3, $yesterday, 'completed'],
        
        // In Progress Tasks
        ['Dynamic Dashboard Integration', 'Integrate responsive dynamic charts to display task completeness and urgencies.', 7, $tomorrow, 'in_progress'],
        ['Bug Fix: Session Timeout', 'Investigate and resolve random user logouts during intense DB transactions.', 2, $two_days_later, 'in_progress'],
        ['API Endpoint Performance Tuning', 'Optimize execution speed for large fetch tasks in models/Task.php.', 4, $today, 'in_progress'],
        ['Prepare Q2 Budget Proposals', 'Compile and review department expenditure worksheets for the upcoming quarter.', 5, $tomorrow, 'in_progress'],
        
        // Pending Tasks
        ['Emergency Database Migration', 'Migrate core transactional tables to higher capacity SSD volume to prevent write locks.', 2, $three_days_ago, 'pending'],
        ['Security Patch Deployment', 'Apply critical security updates and package upgrades to remote servers.', 3, $five_days_ago, 'pending'],
        ['Weekly Team Sync Meeting', 'Organize and facilitate the weekly update sync for active sprints.', 4, $today, 'pending'],
        ['Customer Support Survey Analysis', 'Download feedback datasets, perform sentiment analysis and present findings.', 5, $five_days_later, 'pending'],
        ['Update Deployment Documentation', 'Refactor DEPLOYMENT.md and ARCHITECTURE.md references for the new directory structure.', 8, $next_week, 'pending'],
        ['New Hire Onboarding', 'Walk Marcus through company coding practices, Git flow, and standard tooling.', 6, $next_week, 'pending'],
        ['Software License Review', 'Review active software subscriptions and propose cost optimizations.', 6, null, 'pending'],
    ];
    
    $task_stmt = $conn->prepare("INSERT INTO tasks (title, description, assigned_to, due_date, status) VALUES (?, ?, ?, ?, ?)");
    foreach ($tasks as $t) {
        $task_stmt->execute($t);
    }
    
    echo "Tasks table populated successfully.\n";

    // 3. Insert realistic notifications
    $notifications = [
        ['"Glassmorphism UI Overhaul" has been marked as COMPLETED by John.', 1, 'Task Completed'],
        ['"Dynamic Dashboard Integration" has been assigned to you. Please review and start working on it.', 7, 'New Task Assigned'],
        ['"Emergency Database Migration" is currently OVERDUE. Please update its status.', 2, 'Task Overdue'],
        ['"Security Patch Deployment" is currently OVERDUE. Please prioritize immediately.', 3, 'Task Overdue'],
        ['"Weekly Team Sync Meeting" is due today.', 4, 'Task Reminder'],
    ];
    
    $notif_stmt = $conn->prepare("INSERT INTO notifications (message, recipient, type) VALUES (?, ?, ?)");
    foreach ($notifications as $n) {
        $notif_stmt->execute($n);
    }
    
    echo "Notifications table populated successfully.\n";
    echo "Database seeding finished successfully!\n";
    
} catch (Exception $e) {
    echo "Seeding failed: " . $e->getMessage() . "\n";
}
