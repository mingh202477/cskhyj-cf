<?php
// auth_check.php
session_start();
function isLoggedIn() {
    return isset($_SESSION['user_logged']) && $_SESSION['user_logged'] === true;
}

function getCurrentUser() {
    return isLoggedIn() ? $_SESSION['username'] : null;
}