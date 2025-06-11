     <?php
     $to = 'recipient@example.com'; // Replace with your email address
     $subject = 'Test MailHog Email';
     $message = 'This is a test email sent through MailHog.';
     $headers = 'From: noreply@example.com'; // Replace with your email address

     if (mail($to, $subject, $message, $headers)) {
         echo 'Email sent successfully!';
     } else {
         echo 'Email sending failed.';
     }
     ?>
     