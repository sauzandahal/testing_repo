<?php
session_start();
require_once '../config/db_connect.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please login to access the dashboard";
    header("Location: ../index.php");
    exit();
}

// Check if ID is provided
if(isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    $user_id = $_SESSION['user_id'];
    
    // Verify that the password entry belongs to the logged-in user
    $check_sql = "SELECT user_id FROM passwords WHERE id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows == 1) {
        $row = $check_result->fetch_assoc();
        if ($row['user_id'] != $user_id) {
            $_SESSION['error'] = "You do not have permission to delete this password";
            header("Location: ../dashboard.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Password not found";
        header("Location: ../dashboard.php");
        exit();
    }
    
    // Prepare SQL statement to delete the password
    $sql = "DELETE FROM passwords WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id, $user_id);
    
    if ($stmt->execute()) {
        // Password deleted successfully
        $_SESSION['success'] = "Password deleted successfully!";
        header("Location: ../dashboard.php");
        exit();
    } else {
        // Failed to delete password
        $_SESSION['error'] = "Error: " . $stmt->error;
        header("Location: ../dashboard.php");
        exit();
    }
    if ($stmt) {
        $stmt->close();
    }
} else {
    // If ID is not provided
    $_SESSION['error'] = "Invalid request";
    header("Location: ../dashboard.php");
    exit();
}

if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}