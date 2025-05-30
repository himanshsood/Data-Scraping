<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        // Validate credentials (in real app, check against database)
        if ($username === 'admin' && $password === 'admin123') {
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $username;
            header('Location: index.php');
            exit();
        } else {
            $error = "Invalid username or password";
        }
    }
?>