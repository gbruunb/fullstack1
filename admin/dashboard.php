<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../index.php");
    exit;
}

// Check if the user role is correct
if($_SESSION["role"] !== "admin") {
    header("location: ../index.php");
    exit;
}

// Include database connection
require_once "../db_connection.php";

// Get all users from database
$sql = "SELECT id, username, email, role, created_at FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="dashboard-container">
        <div class="nav">
            <a class="active" href="dashboard.php">Dashboard</a>
            <div class="right">
                <a href="../logout.php">Sign Out</a>
            </div>
        </div>
        <h2>Admin Dashboard</h2>
        <div class="header">
            <h3>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h3>
            <a href="add_user.php" class="btn">Add New User</a>
        </div>
        <h4>User Management</h4>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["role"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["created_at"]) . "</td>";
                        echo "<td>
                                <a href='edit_user.php?id=" . $row["id"] . "' class='btn'>Edit</a>
                                <a href='delete_user.php?id=" . $row["id"] . "' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No users found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
