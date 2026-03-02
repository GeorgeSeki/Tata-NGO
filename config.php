<?php
session_start();
include_once '../databases/db.php';

if (!isset($conn)) {
    die('Database connection not established.');
}

if($_SERVER['REQUEST_METHOD'] =='POST'){
    $username = isset($_POST['name']) ? $_POST['name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $subject = isset($_POST['subject']) ? $_POST['subject'] : '';
    $message = isset($_POST['message']) ? $_POST['message'] : '';

    try {
        $sql = "INSERT INTO users(name,email,subject,message) VALUES (:name,:email,:subject,:message)";
        $stmt = $conn->prepare($sql); 
        $stmt->execute([
            'name' => $username,
            'email' => $email,
            'subject' => $subject,
            'message' => $message
        ]);
        $_SESSION['message'] = "Your message has been sent successfully!";
        header('location: ../8.html');
        exit();
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error: " . $e->getMessage();
        header('location: ../8.html');
        exit();
    }
}
