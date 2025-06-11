<?php
//session_start();

function generateVerificationCode() {
    return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
}

function registerEmail($email) {
    $file = __DIR__ . '/registered_emails.txt';
    $emails = file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];
    $email = strtolower(trim($email));

    if (!in_array($email, $emails)) {
        $emails[] = $email;
        file_put_contents($file, implode(PHP_EOL, $emails) . PHP_EOL, LOCK_EX);
        return true;
    }
    return false;
}

function unsubscribeEmail($email) {
    $file = __DIR__ . '/registered_emails.txt';
    if (!file_exists($file)) {
        return false;
    }
    $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $email = strtolower(trim($email));
    $filtered = array_filter($emails, fn($e) => strtolower($e) !== $email);

    if (count($emails) !== count($filtered)) {
        file_put_contents($file, implode(PHP_EOL, $filtered) . PHP_EOL, LOCK_EX);
        return true;
    }
    return false;
}

function sendVerificationEmail($email, $code, $type = 'register') {
    $to = $email;
    $subject = $type === 'register' ? 'Your Verification Code' : 'Confirm Un-subscription';

    if ($type === 'register') {
        $body = "<p>Your verification code is: <strong>" . htmlspecialchars($code) . "</strong></p>";
    } else {
        $body = "<p>To confirm un-subscription, use this code: <strong>" . htmlspecialchars($code) . "</strong></p>";
    }

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: no-reply@example.com" . "\r\n";

    return mail($to, $subject, $body, $headers);
}

function verifyCode($email, $code) {
    if (!isset($_SESSION['verification_codes'][$email])) {
        return false;
    }
    return $_SESSION['verification_codes'][$email] === $code;
}

function fetchAndFormatXKCDData() {
    $maxComicUrl = "https://xkcd.com/info.0.json";
    $maxData = file_get_contents($maxComicUrl);
    if ($maxData === false) {
        return false;
    }
    $maxJson = json_decode($maxData, true);
    if (!isset($maxJson['num'])) {
        return false;
    }
    $maxNum = intval($maxJson['num']);

    $randomNum = random_int(1, $maxNum);
    $url = "https://xkcd.com/$randomNum/info.0.json";
    $data = file_get_contents($url);
    if ($data === false) {
        return false;
    }
    $comic = json_decode($data, true);
    if (!$comic || !isset($comic['img'], $comic['alt'])) {
        return false;
    }

    $img = htmlspecialchars($comic['img']);
    $alt = htmlspecialchars($comic['alt']);
    $num = intval($comic['num']);
    $title = htmlspecialchars($comic['title']);
    $comicUrl = "https://xkcd.com/$num/";

    $html = <<<HTML
<h2>XKCD Comic: {$title}</h2>
<a href="{$comicUrl}" target="_blank" rel="noopener noreferrer"><img src="{$img}" alt="{$alt}" style="max-width:100%;height:auto;"></a>
<p><a href="unsubscribe.php" id="unsubscribe-button" style="color:#1d4ed8;text-decoration:none;">Unsubscribe</a></p>
HTML;

    return $html;
}

function sendXKCDUpdatesToSubscribers() {
    $file = __DIR__ . '/registered_emails.txt';
    if (!file_exists($file)) {
        return false;
    }
    $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (empty($emails)) {
        return false;
    }
    $htmlContent = fetchAndFormatXKCDData();
    if ($htmlContent === false) {
        return false;
    }

    $subject = "Your XKCD Comic";
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: no-reply@example.com" . "\r\n";

    foreach ($emails as $email) {
        @mail(trim($email), $subject, $htmlContent, $headers);
    }
    return true;
}

