<?php
session_start(); // 1. Access the current active session container

// 2. Clear all internal session values out of server memory
$_SESSION = array();

// 3. If your server is using session cookies, completely expire the cookie record
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Permanently destroy the session file completely on the web server
session_destroy();

// 5. Send the cleared user safely back to the login terminal
header("Location: LoginPage.php");
exit();
?>