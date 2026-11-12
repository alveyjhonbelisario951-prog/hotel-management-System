<?php
require_once "database.php";

const ADMIN_USER = "admin";
const ADMIN_PASS = "admin123";

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = trim($_POST['username'] ?? '');
    $p = $_POST['password'] ?? '';
    if ($u === '' || $p === '') {
        $error = "Please enter your username and password.";
    } elseif (hash_equals(ADMIN_USER, $u) && hash_equals(ADMIN_PASS, $p)) {
        session_regenerate_id(true);
        $_SESSION['admin'] = true;
        header("Location: index.php"); exit;
    } else {
        $error = "Incorrect username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign in | Horizon Hotel</title>
    <link rel="stylesheet" href="css/style.css?v=<?= time() ?>">
</head>
<body class="login-page">
<div class="login">
    <section class="login-art">
        <div class="brand"><div class="brand-logo">H</div><div><b>HORIZON</b><small>Hotel Management</small></div></div>
        <div class="login-art-copy">
            <span class="login-eyebrow">HORIZON HOTEL</span>
            <h2>Manage rooms.<br>Welcome guests.</h2>
            <p>Everything you need to keep your hotel running smoothly.</p>
        </div>
        <svg class="login-illustration hotel-scene" viewBox="0 0 520 300" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <circle class="scene-halo" cx="175" cy="155" r="138" fill="#35C7F4" opacity=".4"/>
            <circle cx="175" cy="155" r="103" fill="#13A9E3" opacity=".32"/>
            <g class="scene-cloud">
                <path d="M365 75c0-10 8-18 18-18 4-13 22-16 30-4 12-3 23 6 23 18 0 9-7 16-16 16h-38c-10 0-17-5-17-12Z" fill="white" opacity=".8"/>
            </g>
            <circle class="scene-sparkle sparkle-one" cx="424" cy="119" r="5" fill="#FFF2B8"/>
            <circle class="scene-sparkle sparkle-two" cx="344" cy="97" r="4" fill="white"/>
            <path d="M62 263h396" stroke="#075985" stroke-width="3" stroke-linecap="round"/>
            <rect x="104" y="91" width="205" height="172" rx="5" fill="#F8FDFF"/>
            <path d="M95 96 206 57l112 39H95Z" fill="#0877AC"/>
            <rect x="122" y="111" width="169" height="28" rx="3" fill="#0B4E78"/>
            <text x="206" y="130" fill="white" font-family="Arial, sans-serif" font-size="12" font-weight="700" letter-spacing="2" text-anchor="middle">HORIZON</text>
            <rect class="scene-window window-one" x="125" y="154" width="42" height="39" rx="3" fill="#BDEEFF"/>
            <rect class="scene-window window-two" x="185" y="154" width="42" height="39" rx="3" fill="#BDEEFF"/>
            <rect class="scene-window window-three" x="245" y="154" width="42" height="39" rx="3" fill="#BDEEFF"/>
            <path d="M125 210h42v53h-42zM245 210h42v53h-42z" fill="#D9F5FF"/>
            <path d="M185 263v-42c0-12 9-21 21-21s21 9 21 21v42h-42Z" fill="#0B4E78"/>
            <circle cx="219" cy="244" r="2.5" fill="#F7C960"/>
            <rect x="323" y="147" width="91" height="116" rx="4" fill="#0B4E78"/>
            <path d="M340 164h25v31h-25zM374 164h25v31h-25z" fill="#5AD3F2"/>
            <path d="M339 208h59v55h-59z" fill="#0877AC"/>
            <path d="M347 217h43v5h-43zM347 229h31v5h-31z" fill="#BDEEFF" opacity=".8"/>
            <g class="scene-luggage">
                <rect x="295" y="223" width="34" height="40" rx="5" fill="#F7C960"/>
                <path d="M305 223v-7c0-4 3-7 7-7s7 3 7 7v7" stroke="#E7A936" stroke-width="3"/>
                <path d="M295 234h34" stroke="#E7A936" stroke-width="2"/>
                <circle cx="312" cy="244" r="3" fill="#0B4E78"/>
            </g>
            <path d="M448 263v-49" stroke="#075985" stroke-width="3" stroke-linecap="round"/>
            <path class="scene-leaf leaf-one" d="M447 237c-17-8-20-20-17-30 13 4 21 13 17 30Z" fill="#168EAA"/>
            <path class="scene-leaf leaf-two" d="M448 247c15-10 25-9 34-3-6 11-18 15-34 3Z" fill="#27A77D"/>
            <path d="M467 263v-36" stroke="#075985" stroke-width="3" stroke-linecap="round"/>
            <path class="scene-leaf leaf-three" d="M466 242c-12-5-14-14-12-22 9 3 14 9 12 22Z" fill="#168EAA"/>
            <path class="scene-leaf leaf-four" d="M467 251c11-7 18-6 25-2-5 8-13 10-25 2Z" fill="#27A77D"/>
        </svg>
        <small class="login-copyright">&copy; <?= date('Y') ?> Horizon Hotel</small>
    </section>
    <section class="login-form-wrap">
        <form method="post" class="login-form">
            <div class="login-heading-mark"><?= icon('door', 20) ?></div>
            <h1>Welcome to <span>Horizon</span></h1>
            <p class="muted">Sign in to your administrator account.</p>
            <?php if ($error): ?><div class="alert alert-danger" role="alert"><span><?= e($error) ?></span></div><?php endif; ?>
            <div class="field">
                <label for="username">Username</label>
                <input class="input" id="username" type="text" name="username" autocomplete="username" required autofocus>
            </div>
            <div class="field">
                <label for="password">Password</label>
                <input class="input" id="password" type="password" name="password" autocomplete="current-password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Sign in to Dashboard</button>
            <p class="login-secure-note"><?= icon('check', 15) ?> Secure administrator access</p>
        </form>
    </section>
</div>
</body>
</html>