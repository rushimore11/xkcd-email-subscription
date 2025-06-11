<?php
session_start();
require 'functions.php';

$message = null;
$messageType = 'success';

// Check if email is already verified
$isVerified = isset($_SESSION['verified']) && $_SESSION['verified'] === true;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email']) && !$isVerified) {
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        if ($email) {
            $code = generateVerificationCode();
            $_SESSION['verification_codes'][$email] = [
                'code' => $code,
                'expiry' => time() + 300 // Code valid for 5 minutes
            ];
            $_SESSION['current_email'] = $email;
            if (sendVerificationEmail($email, $code)) {
                $message = "Verification code sent to " . htmlspecialchars($email) . ". Please check your email.  Code expires in 5 minutes.";
                $messageType = 'success';
            } else {
                $message = "Failed to send verification email. Please try again.";
                $messageType = 'danger';
            }
        } else {
            $message = "Invalid email address.";
            $messageType = 'danger';
        }
    } elseif (isset($_POST['verification_code']) && !$isVerified) {
        $verification_code = $_POST['verification_code'];
        $email = $_SESSION['current_email'] ?? null;

        if ($email && isset($_SESSION['verification_codes'][$email]) &&
            $_SESSION['verification_codes'][$email]['code'] == $verification_code &&
            $_SESSION['verification_codes'][$email]['expiry'] > time()) {
            unset($_SESSION['verification_codes'][$email]);
            $_SESSION['verified'] = true; // Mark email as verified
            $message = "Email successfully verified!";
            $messageType = 'success';
        } else {
            $message = "Invalid or expired verification code.";
            $messageType = 'danger';
        }
    } elseif (isset($_POST['resend_code']) && !$isVerified) {
        // Resend verification code logic
        $email = $_SESSION['current_email'] ?? null;
        if ($email) {
            $code = generateVerificationCode();
            $_SESSION['verification_codes'][$email] = [
                'code' => $code,
                'expiry' => time() + 300 // Code valid for 5 minutes
            ];
            if (sendVerificationEmail($email, $code)) {
                $message = "New verification code sent to " . htmlspecialchars($email) . ". Please check your email. Code expires in 5 minutes.";
                $messageType = 'success';
            } else {
                $message = "Failed to resend verification email. Please try again.";
                $messageType = 'danger';
            }
        } else {
            $message = "No email address found. Please enter your email.";
            $messageType = 'danger';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        .form-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0069d9;
            border-color: #0062cc;
        }
        .alert {
            margin-top: 20px;
        }
        .verified-icon {
            color: green;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="form-container">
                    <h2 class="text-center mb-4">Email Verification
                        <?php if ($isVerified): ?>
                            <i class="fas fa-check-circle verified-icon" title="Email Verified"></i>
                        <?php endif; ?>
                    </h2>
                    <?php if ($message): ?>
                        <div class="alert alert-<?= htmlspecialchars($messageType) ?>"><?= htmlspecialchars($message) ?></div>
                    <?php endif; ?>

                    <?php if ($isVerified): ?>
                        <div class="alert alert-success text-center">
                            <i class="fas fa-check-circle"></i> Your email is already verified!
                        </div>
                    <?php elseif (empty($_SESSION['current_email'])): ?>
                        <form method="post" action="index.php">
                            <div class="form-group">
                                <label for="email">Email Address:</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Send Verification Code</button>
                        </form>
                    <?php else: ?>
                        <form method="post" action="index.php">
                            <div class="form-group">
                                <label for="verification_code">Verification Code:</label>
                                <input type="text" class="form-control" id="verification_code" name="verification_code" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Verify Code</button>
                            <button type="submit" class="btn btn-secondary btn-block" name="resend_code">Resend Code</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
