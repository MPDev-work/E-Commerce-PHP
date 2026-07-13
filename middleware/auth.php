<?php

session_start();

function is_logged_in(){
    return isset($_SESSION["user_id"]);
}

function require_logged_in(){
    if(!is_logged_in()){
        header('location: ../auth/login.php');
    }
}

function is_admin(){
    return is_logged_in() && (($_SESSION['role'] ?? null) == 'admin' );
}

function is_admin_logged_in(){
    if(!is_admin()){
        header('location: ../auth/login.php');
    }
}


function is_client()
{
    return is_logged_in() && (($_SESSION['role'] ?? null) == 'client' || ($_SESSION['role'] ?? null) == 'admin');
}

function is_client_logged_in()
{
    if (!is_client()) {
        header('location: ../auth/login.php');
    }
}