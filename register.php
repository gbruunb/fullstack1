<?php
session_start();

// Redirect if already logged in
if(isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Define variables and initialize with empty values
$username = $password = $confirm_password = $email = "";
$username_err = $password_err = $confirm_password_err = $email_err = "";

// Process form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "db_connection.php";
    
    // Validate username
    if(empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $param_username);
            
            $param_username = trim($_POST["username"]);
            
            if($stmt->execute()) {
                $stmt->store_result();
                
                if($stmt->num_rows == 1) {
                    $username_err = "This username is already taken.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            
            $stmt->close();
        }
    }
    
    // Validate email
    if(empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email.";
    } else {
        $sql = "SELECT id FROM users WHERE email = ?";
        
        if($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $param_email);
            
            $param_email = trim($_POST["email"]);
            
            if($stmt->execute()) {
                $stmt->store_result();
                
                if($stmt->num_rows == 1) {
                    $email_err = "This email is already registered.";
                } else {
                    $email = trim($_POST["email"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            
            $stmt->close();
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";     
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Check input errors before inserting into database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err)) {
        
        // Default role is 'user'
        $role = "user";
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)";
         
        if($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssss", $param_username, $param_password, $param_email, $param_role);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_email = $email;
            $param_role = $role;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()) {
                // Redirect to login page
                header("location: index.php");
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }
    
    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - User Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/tailwind.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-background text-foreground">
    <div class="flex items-center justify-center min-h-screen py-8">
        <div class="card animate-in w-full max-w-md">
            <div class="card-header bg-primary text-primary-foreground">
                <h2 class="card-title"><i class="fa-solid fa-user-plus mr-2"></i>Create Account</h2>
            </div>
            <div class="card-content">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="space-y-4">
                    <div>
                        <label for="username" class="label">
                            <span class="flex items-center">
                                <i class="fa-solid fa-user mr-2"></i>Username
                            </span>
                        </label>
                        <input type="text" id="username" name="username" value="<?php echo $username; ?>"
                               class="input rounded border-input focus:border-ring focus:ring-2 focus:ring-ring" 
                               placeholder="Choose a username" required>
                        <?php if(!empty($username_err)): ?>
                            <div class="text-destructive text-sm mt-2 flex items-center">
                                <i class="fa-solid fa-circle-exclamation mr-1"></i> <?php echo $username_err; ?>
                            </div>
                        <?php endif; ?>
                    </div>    
                    <div>
                        <label for="email" class="label">
                            <span class="flex items-center">
                                <i class="fa-solid fa-envelope mr-2"></i>Email
                            </span>
                        </label>
                        <input type="email" id="email" name="email" value="<?php echo $email; ?>"
                               class="input rounded border-input focus:border-ring focus:ring-2 focus:ring-ring" 
                               placeholder="Enter your email" required>
                        <?php if(!empty($email_err)): ?>
                            <div class="text-destructive text-sm mt-2 flex items-center">
                                <i class="fa-solid fa-circle-exclamation mr-1"></i> <?php echo $email_err; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label for="password" class="label">
                            <span class="flex items-center">
                                <i class="fa-solid fa-lock mr-2"></i>Password
                            </span>
                        </label>
                        <input type="password" id="password" name="password" value="<?php echo $password; ?>"
                               class="input rounded border-input focus:border-ring focus:ring-2 focus:ring-ring" 
                               placeholder="Choose a password" required>
                        <?php if(!empty($password_err)): ?>
                            <div class="text-destructive text-sm mt-2 flex items-center">
                                <i class="fa-solid fa-circle-exclamation mr-1"></i> <?php echo $password_err; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label for="confirm_password" class="label">
                            <span class="flex items-center">
                                <i class="fa-solid fa-check-circle mr-2"></i>Confirm Password
                            </span>
                        </label>
                        <input type="password" id="confirm_password" name="confirm_password" value="<?php echo $confirm_password; ?>"
                               class="input rounded border-input focus:border-ring focus:ring-2 focus:ring-ring" 
                               placeholder="Confirm your password" required>
                        <?php if(!empty($confirm_password_err)): ?>
                            <div class="text-destructive text-sm mt-2 flex items-center">
                                <i class="fa-solid fa-circle-exclamation mr-1"></i> <?php echo $confirm_password_err; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div>
                        <button type="submit" class="button button-primary w-full mb-4">
                            <i class="fa-solid fa-user-plus mr-2"></i> Create Account
                        </button>
                    </div>
                </form>
                
                <div class="my-6 border-t border-border"></div>
                
                <div class="text-center text-sm text-muted-foreground">
                    <p>Already have an account? <a href="index.php" class="text-primary hover:underline">
                        <i class="fa-solid fa-right-to-bracket mr-1"></i> Login here
                    </a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
