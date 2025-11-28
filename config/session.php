<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getUser() {
    return $_SESSION ?? null;
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /vegout-cafe/pages/login.php');
        exit;
    }
}
