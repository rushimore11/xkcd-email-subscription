<?php
// src/unsubscribe.php
session_start();
require 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['unsubscribe_email'])) {
    $email = $_POST['unsubscribe_email'];
    $code = generateVerificationCode();
    $_SESSION['verification_codes'][$email] = $code;
    sendVerificationEmail($email, $code, 'unsubscribe');
    $message = "Unsubscription code sent to $email.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unsubscribe</title>
</head>
<body>
    <h1>Unsubscribe from XKCD Comics</h1>
    <?php if (isset($message)): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="email" name="unsubscribe_email" placeholder="Enter your email to unsubscribe" required>
        <button id="submit-unsubscribe">Unsubscribe</button>
    </form>
</body>
</html>
