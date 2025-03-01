<?php
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS fullstack";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully or already exists<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

// Select the database
$conn->select_db("fullstack");


if ($conn->query($sql) === TRUE) {
    echo "Users table created successfully or already exists<br>";
    
    // // Check if admin user exists
    // $result = $conn->query("SELECT * FROM users WHERE role='admin' LIMIT 1");
    
    // // Create default admin if none exists
    // if ($result->num_rows == 0) {
    //     $admin_password = password_hash("admin123", PASSWORD_DEFAULT);
    //     $sql = "INSERT INTO users (username, password, email, role) VALUES ('admin', '$admin_password', 'admin@example.com', 'admin')";
    //     if ($conn->query($sql) === TRUE) {
    //         echo "Default admin user created successfully<br>";
    //         echo "Username: admin, Password: admin123<br>";
    //     } else {
    //         echo "Error creating admin user: " . $conn->error . "<br>";
    //     }
    // }
} else {
    echo "Error users table: " . $conn->error . "<br>";
}

$conn->close();
echo "Database setup complete! <a href='index.php'>Go to Login</a>";
?>
