<?php

session_start();
require_once "../config/connect.php";

// ==================== Login ====================
if (isset($_POST['register'])) {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pwd = password_hash($_POST['pwd'], PASSWORD_DEFAULT);

    // Check if email already exists
    $check = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $check);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>
            alert('Email already registered.');
            window.location.href = '../auth/login.php';
          </script>";
        exit();
    } else {

        $sql = "INSERT INTO users(username, email, pwd)
                VALUES('$username', '$email', '$pwd')";

        if (mysqli_query($conn, $sql)) {
            header("Location: ../auth/login.php");
            exit();
        } else {
            echo "Register Failed";
        }
    }
}
if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $pwd = $_POST['pwd'];

    $select = $conn->query("SELECT * FROM users WHERE email = '$email'");

    if ($select->num_rows > 0) {

        $user = $select->fetch_assoc();

        if (password_verify($pwd, $user['pwd'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['roles'];

            if ($user['roles'] == 'admin') {
                header("location: ../admin/dashboard.php");
            } else {
                header("location: ../client/indexAfter.php");
            }
        } else {

            $_SESSION["pwd_error"] = "Password not match";
            header("location: ../auth/login.php");
        }
    } else {

        $_SESSION["email_error"] = "Email Not Found";
        header("location: ../auth/login.php");
    }
}
