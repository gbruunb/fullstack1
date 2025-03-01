<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../index.php");
    exit;
}

// Check if the user role is correct
if($_SESSION["role"] !== "manager") {
    header("location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard - User Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <nav class="navbar">
            <div class="navbar-container">
                <a href="dashboard.php" class="navbar-brand">
                    <i class="fas fa-user-shield"></i> User Management
                </a>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-chart-line"></i> Reports
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-cog"></i> Settings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">
                            <i class="fas fa-sign-out-alt"></i> Sign Out
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        
        <div class="dashboard-header">
            <div>
                <h1 class="dashboard-title">Manager Dashboard</h1>
                <p class="user-welcome">Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</p>
            </div>
        </div>
        
        <div class="card-body">
            <div class="row" style="display: flex; flex-wrap: wrap; margin: -10px;">
                <div style="width: 33.333%; padding: 10px; box-sizing: border-box;">
                    <div class="card" style="height: 100%; background: linear-gradient(to right, #36b9cc, #1cc88a); color: white;">
                        <div class="card-body">
                            <h4><i class="fas fa-users"></i> Users</h4>
                            <h2>15</h2>
                            <p>Active users in the system</p>
                        </div>
                    </div>
                </div>
                <div style="width: 33.333%; padding: 10px; box-sizing: border-box;">
                    <div class="card" style="height: 100%; background: linear-gradient(to right, #4e73df, #224abe); color: white;">
                        <div class="card-body">
                            <h4><i class="fas fa-project-diagram"></i> Projects</h4>
                            <h2>8</h2>
                            <p>Ongoing projects</p>
                        </div>
                    </div>
                </div>
                <div style="width: 33.333%; padding: 10px; box-sizing: border-box;">
                    <div class="card" style="height: 100%; background: linear-gradient(to right, #f6c23e, #f4b619); color: white;">
                        <div class="card-body">
                            <h4><i class="fas fa-tasks"></i> Tasks</h4>
                            <h2>24</h2>
                            <p>Tasks pending review</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card" style="margin-top: 20px;">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-clipboard-list"></i> Manager Information</h3>
                </div>
                <div class="card-body">
                    <p>This is the manager dashboard page. You are logged in as a manager.</p>
                    <p>From here, you can manage projects, assign tasks, and view progress reports.</p>
                </div>
            </div>
            
            <div class="card" style="margin-top: 20px;">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-calendar"></i> Recent Activities</h3>
                </div>
                <div class="card-body">
                    <ul style="list-style-type: none; padding: 0;">
                        <li style="padding: 10px 0; border-bottom: 1px solid #e3e6f0;">
                            <i class="fas fa-check-circle" style="color: #1cc88a;"></i> Project X completed
                            <small style="color: #858796; float: right;">Today 10:30 AM</small>
                        </li>
                        <li style="padding: 10px 0; border-bottom: 1px solid #e3e6f0;">
                            <i class="fas fa-user-plus" style="color: #4e73df;"></i> New user joined Team A
                            <small style="color: #858796; float: right;">Yesterday 3:15 PM</small>
                        </li>
                        <li style="padding:
