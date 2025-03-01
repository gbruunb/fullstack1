<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== "admin") {
    header("location: ../index.php");
    exit;
}

// Include database connection
require_once "../db_connection.php";

// Define variables and initialize with empty values
$username = $password = $confirm_password = $email = $role = "";
$username_err = $password_err = $confirm_password_err = $email_err = $role_err = "";

// Process form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {
    
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

    // Validate role
    if(empty(trim($_POST["role"]))) {
        $role_err = "Please select a role.";
    } else {
        $role = trim($_POST["role"]);
        if(!in_array($role, ['user', 'manager', 'admin'])) {
            $role_err = "Invalid role selected.";
        }
    }
    
    // Check input errors before inserting into database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err) && empty($role_err)) {
        
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
                // Redirect to admin dashboard page
                header("location: dashboard.php");
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
    <title>Add User</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="form-container">
        <div class="nav">
            <a href="dashboard.php">Dashboard</a>
            <a class="active" href="add_user.php">Add User</a>
            <div class="right">
                <a href="../logout.php">Sign Out</a>
            </div>
        </div>
        <h2>Add User</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" value="<?php echo $username; ?>" required>
                <span class="error"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?php echo $email; ?>" required>
                <span class="error"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" value="<?php echo $password; ?>" required>
                <span class="error"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" value="<?php echo $confirm_password; ?>" required>
                <span class="error"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Role</label>
                <select name="role" required>
                    <option value="">Select Role</option>
                    <option value="user" <?php if($role == "user") echo "selected"; ?>>User</option>
                    <option value="manager" <?php if($role == "manager") echo "selected"; ?>>Manager</option>
                    <option value="admin" <?php if($role == "admin") echo "selected"; ?>>Admin</option>
                </select>
                <span class="error"><?php echo $role_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" value="Add User">
            </div>
            <p><a href="dashboard.php">Cancel</a></p>
        </form>
    </div>    
</body>
</html>
