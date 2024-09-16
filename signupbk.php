<?php
require_once('./config.php');

$username = $password = $email = $confirmpassword = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $email = trim($_POST['email']);
    $confirmpassword = trim($_POST['confirmpassword']);

    if (empty($username) || empty($password) || empty($email) || empty($confirmpassword)) {
        echo "All fields are required";
    } elseif (strlen($password) < 3) {
        echo "Password must be at least 3 characters long";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
    } elseif ($password !== $confirmpassword) {
        echo "Passwords do not match";
    } else {
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            // $param_password = password_hash($password, PASSWORD_DEFAULT);
            mysqli_stmt_bind_param($stmt, "sss", $username, $email, $password);
            if (mysqli_stmt_execute($stmt)) {
                header("location: login.php");
                exit();
            } else {
                echo "Something went wrong... cannot redirect!";
            }
        }
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
}
?>
