<?php
// Start session
session_start();

// Check if user is logged in and has appropriate role
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] != 'manager' && $_SESSION['user_role'] != 'admin')) {
    header("Location: ../loginAdmin.php");
    exit;
}

// Check if id and status are provided
if (isset($_GET['id']) && isset($_GET['status'])) {
    require_once 'connection.php';
    
    $id = $_GET['id'];
    $status = $_GET['status'];
    
    // Validate status
    $valid_statuses = ['pendiente', 'en_proceso', 'completado'];
    if (!in_array($status, $valid_statuses)) {
        $_SESSION['error_message'] = "Estado no válido";
        header("Location: pagIni.php");
        exit;
    }
    
    try {
        // Update reservation status
        $stmt = $conn->prepare("UPDATE reservations SET status = :status WHERE id = :id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $_SESSION['success_message'] = "Estado actualizado correctamente";
    } catch(PDOException $e) {
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
    }
}

// Redirect back to pagIni.php
header("Location: pagIni.php");
exit;
?>