<?php
session_start();

// Redirect if already logged in
if(isset($_SESSION['user_id'])) {
    switch($_SESSION['role']) {
        case 'admin':
            header("Location: admin/dashboard.php");
            break;
        case 'manager':
            header("Location: manager/dashboard.php");
            break;
        case 'user':
            header("Location: user/dashboard.php");
            break;
    }
    exit;
}

// Check if form submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once 'db_connection.php';
    
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $sql = "SELECT id, username, password, role FROM users WHERE username = ?";
    
    if($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);
        
        if($stmt->execute()) {
            $stmt->store_result();
            
            if($stmt->num_rows == 1) {
                $stmt->bind_result($id, $username, $hashed_password, $role);
                if($stmt->fetch()) {
                    if(password_verify($password, $hashed_password)) {
                        // Password is correct, start a session
                        session_start();
                        
                        // Store data in session variables
                        $_SESSION["loggedin"] = true;
                        $_SESSION["user_id"] = $id;
                        $_SESSION["username"] = $username;
                        $_SESSION["role"] = $role;
                        
                        // Redirect user to appropriate page
                        switch($role) {
                            case 'admin':
                                header("Location: admin/dashboard.php");
                                break;
                            case 'manager':
                                header("Location: manager/dashboard.php");
                                break;
                            default:
                                header("Location: user/dashboard.php");
                        }
                    } else {
                        $login_err = "Invalid username or password.";
                    }
                }
            } else {
                $login_err = "Invalid username or password.";
            }
        } else {
            $login_err = "Something went wrong. Please try again later.";
        }
        
        $stmt->close();
    }
    
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - User Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/tailwind.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-background text-foreground">
    <div class="flex items-center justify-center h-screen">
        <div class="card animate-in w-full max-w-md">
            <div class="card-header bg-primary text-primary-foreground">
                <h2 class="card-title"><i class="fa-solid fa-lock mr-2"></i>Login</h2>
            </div>
            <div class="card-content">
                <?php 
                if(!empty($login_err)){
                    echo '<div class="p-3 mb-4 rounded text-destructive bg-destructive/10 text-sm flex items-center">
                            <i class="fa-solid fa-circle-exclamation mr-2"></i> ' . $login_err . '
                          </div>';
                }        
                ?>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="space-y-4">
                    <div>
                        <label for="username" class="label">
                            <span class="flex items-center">
                                <i class="fa-solid fa-user mr-2"></i>Username
                            </span>
                        </label>
                        <input type="text" id="username" name="username" 
                               class="input rounded border-input focus:border-ring focus:ring-2 focus:ring-ring" 
                               placeholder="Enter your username" required>
                    </div>    
                    <div>
                        <label for="password" class="label">
                            <span class="flex items-center">
                                <i class="fa-solid fa-key mr-2"></i>Password
                            </span>
                        </label>
                        <input type="password" id="password" name="password" 
                               class="input rounded border-input focus:border-ring focus:ring-2 focus:ring-ring" 
                               placeholder="Enter your password" required>
                    </div>
                    <div>
                        <button type="submit" class="button button-primary w-full mb-4">
                            <i class="fa-solid fa-right-to-bracket mr-2"></i> Login
                        </button>
                    </div>
                </form>
                
                <div class="my-6 border-t border-border"></div>
                
                <div class="text-center text-sm text-muted-foreground">
                    <p class="mb-2">Don't have an account? <a href="register.php" class="text-primary hover:underline">Sign up now</a></p>
                    <p><a href="db_setup.php" class="text-primary hover:underline">
                        <i class="fa-solid fa-database mr-1"></i> Setup Database
                    </a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
