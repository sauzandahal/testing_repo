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
    $user_id = $_SESSION['user_id'];
    $platform = trim($_POST['platform']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $pin = $_POST['pin'];
    
    // Basic validation
    if (empty($platform) || empty($email) || empty($password)) {
        $_SESSION['error'] = "Platform, username/email, and password are required";
        header("Location: ../dashboard.php");
        exit();
    }
    
    // Prepare SQL statement to prevent SQL injection
    $sql = "INSERT INTO passwords (user_id, platform, email, password, pin, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $user_id, $platform, $email, $password, $pin);
    
    if ($stmt->execute()) {
        // Password saved successfully
        $_SESSION['success'] = "Password saved successfully!";
        header("Location: ../dashboard.php");
        exit();
    } else {
        // Failed to save password
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