<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

function requireAuth(): void {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit;
    }
}

function loginUser(int $id, string $name, string $email): void {
    session_regenerate_id(true);

    $_SESSION['user_id'] = $id;
    $_SESSION['user_name'] = $name;
    $_SESSION['user_email'] = $email;
}

function logoutUser(): void {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p["path"], $p["domain"], $p["secure"], $p["httponly"]);
    }
    session_destroy();
}
