<?php
session_start();
// Contact form handler
// Usage: POST name,email,subject,message

// SMTP / mail configuration - edit these for your environment
$MAIL_FROM_ADDRESS = 'no-reply@example.com';
$MAIL_FROM_NAME = 'TATA Website';
$SMTP_HOST = 'smtp.example.com';
$SMTP_PORT = 587;
$SMTP_USERNAME = 'smtp-user@example.com';
$SMTP_PASSWORD = 'smtp-password';
$SMTP_SECURE = 'tls'; // 'tls' or 'ssl' or '' for none

require_once __DIR__ . '/db.php';

// Helper: respond and redirect
function redirect_with_message($msg, $success = true) {
    $_SESSION['message'] = $msg;
    header('Location: ../8.html');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_with_message('Invalid request method.', false);
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$subject = trim($_POST['subject'] ?? 'Contact form submission');
$message = trim($_POST['message'] ?? '');

// Basic validation
if ($name === '' || $email === '' || $message === '') {
    redirect_with_message('Please fill in required fields (name, email, message).', false);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirect_with_message('Please provide a valid email address.', false);
}

// Save to database (table name: users)
try {
    if (!isset($conn)) {
        // db.php creates $conn
        throw new Exception('Database connection not available.');
    }
    $sql = "INSERT INTO users (name, email, subject, message) VALUES (:name, :email, :subject, :message)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':subject' => $subject,
        ':message' => $message
    ]);
} catch (Exception $e) {
    // Log error optionally. For now, return to form with message.
    redirect_with_message('Database error: ' . $e->getMessage(), false);
}

// Try to send email to site owner
$ownerEmail = 'tanzaniathinkandactfor@gmail.com';
$mailSent = false;

// Use PHPMailer if available
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    try {
        require_once __DIR__ . '/../vendor/autoload.php';
        $phpMailerClass = 'PHPMailer\\PHPMailer\\PHPMailer';
        if (!class_exists($phpMailerClass)) {
            throw new Exception('PHPMailer class not found; install phpmailer/phpmailer via Composer.');
        }
        $mail = new $phpMailerClass(true);
        // Server settings
        if (!empty($SMTP_HOST)) {
            $mail->isSMTP();
            $mail->Host = $SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = $SMTP_USERNAME;
            $mail->Password = $SMTP_PASSWORD;
            $mail->SMTPSecure = $SMTP_SECURE;
            $mail->Port = $SMTP_PORT;
        }

        // Recipients
        $mail->setFrom($MAIL_FROM_ADDRESS, $MAIL_FROM_NAME);
        $mail->addAddress($ownerEmail);
        $mail->addReplyTo($email, $name);

        // Content
        $mail->isHTML(true);
        $mail->Subject = '[Website Contact] ' . $subject;
        $body  = "<p><strong>Name:</strong> " . htmlspecialchars($name) . "</p>";
        $body .= "<p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>";
        $body .= "<p><strong>Subject:</strong> " . htmlspecialchars($subject) . "</p>";
        $body .= "<p><strong>Message:</strong><br>" . nl2br(htmlspecialchars($message)) . "</p>";
        $mail->Body = $body;

        $mail->send();
        $mailSent = true;
    } catch (Exception $e) {
        // PHPMailer failed, we'll try mail() below as fallback
        $mailSent = false;
    }
} else {
    // PHPMailer not installed - fallback to PHP mail()
    $emailSubject = '[Website Contact] ' . $subject;
    $emailBody  = "Name: $name\n";
    $emailBody .= "Email: $email\n";
    $emailBody .= "Subject: $subject\n\n";
    $emailBody .= $message . "\n";
    $headers = 'From: ' . $MAIL_FROM_NAME . ' <' . $MAIL_FROM_ADDRESS . '>' . "\r\n";
    $headers .= 'Reply-To: ' . $email . "\r\n";
    if (@mail($ownerEmail, $emailSubject, $emailBody, $headers)) {
        $mailSent = true;
    }
}

if ($mailSent) {
    redirect_with_message('Thank you! Your message has been sent.');
} else {
    redirect_with_message('Message saved but failed to send email. Check SMTP settings or install PHPMailer.', false);
}

