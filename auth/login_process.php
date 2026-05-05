<?php
session_start();
include "../includes/db.php";  // Include DB File
include "../includes/functions.php";  // Include functions File
/** @var mysqli $conn */


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = c($_POST['email']);
    $pass = c($_POST['password']);

    // Input Validation
    if (!required($email) || !required($pass)) {
        setFlash('error', 'Both feilds are required.');
        redirect('login.php');
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        setFlash('error', 'Invalid Email Format.');
        redirect('login.php');
    }

    // Get Data
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ? AND password = ?");
    mysqli_stmt_bind_param($stmt, "ss", $email, $pass);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    if ($user) {
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        setFlash('success', 'Login Successful');
        redirect(BASE_URL . "index.php");
    } else {
        setFlash('error', 'Invalid email or password');
        redirect('login.php');
    }
}
