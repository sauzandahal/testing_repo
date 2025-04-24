<?php
session_start();
require_once '../config/db_connect.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please login to access the dashboard";
    header("Location: ../index.php");
    exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $id = $_POST['id'];
    $platform = trim($_POST['platform']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $pin = $_POST['pin'];
    $user_id = $_SESSION['user_id'];
    
    // Basic validation
    if (empty($platform) || empty($email) || empty($password)) {
        $_SESSION['error'] = "Platform, username/email, and password are required";
        header("Location: ../dashboard.php");
        exit();
    }
    
    // Verify that the password entry belongs to the logged-in user
    $check_sql = "SELECT user_id FROM passwords WHERE id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows == 1) {
        $row = $check_result->fetch_assoc();
        if ($row['user_id'] != $user_id) {
            $_SESSION['error'] = "You do not have permission to edit this password";
            header("Location: ../dashboard.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Password not found";
        header("Location: ../dashboard.php");
        exit();
    }
    
    // Prepare SQL statement to update the password
    $sql = "UPDATE passwords SET platform = ?, email = ?, password = ?, pin = ?, updated_at = NOW() WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssii", $platform, $email, $password, $pin, $id, $user_id);
    
    if ($stmt->execute()) {
        // Password updated successfully
        $_SESSION['success'] = "Password updated successfully!";
        header("Location: ../dashboard.php");
        exit();
    } else {
        // Failed to update password
        $_SESSION['error'] = "Error: " . $stmt->error;
        header("Location: ../dashboard.php");
        exit();
    }
    if ($stmt) {
        $stmt->close();
    }
} else {
    // If not submitted through POST method
    header("Location: ../dashboard.php");
    exit();
}

if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}