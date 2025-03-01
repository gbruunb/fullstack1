<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../index.php");
    exit;
}

// Check if the user role is correct
if($_SESSION["role"] !== "user") {
    header("location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - User Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/tailwind.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-background text-foreground">
    <div class="flex flex-col min-h-screen">
        <header class="bg-primary text-primary-foreground shadow-sm">
            <div class="container flex items-center justify-between h-16">
                <div class="flex items-center space-x-4">
                    <a href="dashboard.php" class="text-lg font-semibold">
                        <i class="fa-solid fa-user-shield mr-2"></i>User Management
                    </a>
                </div>
                <nav>
                    <ul class="flex items-center space-x-1">
                        <li>
                            <a href="dashboard.php" class="nav-item nav-item-active flex items-center">
                                <i class="fa-solid fa-gauge mr-2"></i> Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-item flex items-center">
                                <i class="fa-solid fa-user mr-2"></i> Profile
                            </a>
                        </li>
                        <li>
                            <a href="../logout.php" class="nav-item flex items-center">
                                <i class="fa-solid fa-right-from-bracket mr-2"></i> Sign Out
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </header>
        
        <main class="container flex-1 py-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold">User Dashboard</h1>
                    <p class="text-muted-foreground">Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</p>
                </div>
            </div>
            
            <div class="grid gap-6 md:grid-cols-2">
                <div class="card shadow">
                    <div class="card-header">
                        <h3 class="card-title flex items-center">
                            <i class="fa-solid fa-info-circle mr-2"></i> User Information
                        </h3>
                    </div>
                    <div class="card-content">
                        <p class="mb-3">This is the user dashboard page. You are logged in as a regular user.</p>
                        <p>From here, you can view and manage your account settings.</p>
                    </div>
                </div>
                
                <div class="card shadow">
                    <div class="card-header">
                        <h3 class="card-title flex items-center">
                            <i class="fa-solid fa-tasks mr-2"></i> Available Actions
                        </h3>
                    </div>
                    <div class="card-content">
                        <ul class="list-disc pl-5">
                            <li>View your profile information</li>
                            <li>Update your personal details</li>
                            <li>Change your password</li>
                        </ul>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
