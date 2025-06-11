# XKCD Email Subscription Project

## Overview

This project is a PHP-based email subscription system that allows users to register their email addresses, verify ownership via a secure verification code sent by email, and receive a random XKCD comic every day. It is designed to work seamlessly in local development environments with MailHog for email testing and supports automated daily comic emails through a CRON job.

## Features

- **User Registration with Email Verification:**  
  Users submit their email address and receive a 6-digit verification code via email. Only verified emails are saved and subscribed.

- **Daily XKCD Comic Delivery:**  
  A scheduled CRON job fetches a random XKCD comic every 24 hours and sends it to all verified subscribers.

- **Unsubscription Process:**  
  Each email includes an unsubscribe link leading users to a secure flow to unsubscribe with email verification.

- **Local Development Friendly:**  
  Fully compatible with MailHog, a local SMTP testing server, allowing safe email testing without sending real emails.

- **Simple File-Based Storage:**  
  Subscribed emails are stored in a text file (`registered_emails.txt`) for easy setup without requiring a database.

- **Responsive & Professional UX:**  
  Modern, clean, and interactive user interface powered by Bootstrap for excellent usability and accessibility.

## Requirements

- PHP 7.4 or newer
- Composer (for dependency management)
- MailHog installed and running locally for email testing
- Access to set up scheduled tasks / CRON jobs for daily comic delivery
- A web server or PHP built-in server for hosting the application

## Installation & Setup

1. **Clone the repository:**

   ```bash
   git clone https://github.com/rushimore11/xkcd-email-subscription.git
   cd xkcd-email-subscription

   Install dependencies (PHPMailer):


composer install
Configure MailHog:

Download and run MailHog locally (https://github.com/mailhog/MailHog/releases).
Configure your PHP php.ini to send emails via MailHog SMTP (port 1025).
Add or update the line:
sendmail_path = "C:\Path\To\MailHog\MailHog.exe" sendmail -S smtp://127.0.0.1:1025
Adjust the path as appropriate.
Start your web server:

Use PHP built-in server for quick testing:

php -S localhost:8000 -t src
Access the application:

Open your browser at http://localhost:8000

Set up CRON job for daily XKCD comics:

Use your OS scheduling system (CRON on Linux/macOS, Task Scheduler on Windows) to run:


php /full/path/to/src/cron.php
Schedule it to run once every 24 hours.

Usage
Visit the site to subscribe by entering your email.
Check your inbox (or MailHog interface at http://localhost:8025 during development) for a verification code.
Enter the code to verify your subscription.
Receive daily XKCD comics automatically.
Use the unsubscribe link included in each email to stop receiving comics.
File Structure
/src
Contains all PHP code files including index.php, functions.php, cron.php, and unsubscribe.php.
/src/registered_emails.txt
Text file storing verified subscriber emails.
/vendor
Composer dependencies including PHPMailer.
Security Considerations
Verification codes expire after 5 minutes to enhance security.
Emails are verified before subscription.
Unsubscribe process also uses verification to prevent unauthorized removals.
PHPMailer is used for secure and reliable SMTP email sending.
Contributing
Contributions are welcome! Please fork the repository and submit pull requests or open issues for bug reports and feature requests.

License
This project is licensed under the MIT License. See LICENSE for details.

Acknowledgments
XKCD (https://xkcd.com) for the comic content.
PHPMailer for email handling.
MailHog for local SMTP testing.
Bootstrap for UI design.
Contact
For questions or support, please open an issue on GitHub.

Thank you for using the XKCD Email Subscription Project!!!!!
