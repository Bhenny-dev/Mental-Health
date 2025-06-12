<?php
// support.php – form handler script
require __DIR__ . '/vendor/autoload.php';  // PHPMailer via Composer
$config = require 'config.php';
require 'EmailForm.php';

// Set JSON response header
header('Content-Type: application/json; charset=utf-8');

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Collect and sanitize form inputs
$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
    exit;
}

// Send the email
$emailForm = new EmailForm($config);
$sent = $emailForm->send([
    'name'    => $name,
    'email'   => $email,
    'subject' => $subject,
    'message' => $message
]);

// Log the submission (append to submissions.json)
// Ensure the file exists or create it
$logFile = 'submissions.json';
$entry = [
    'name'      => $name,
    'email'     => $email,
    'subject'   => $subject,
    'message'   => $message,
    'timestamp' => date('c')  // ISO8601 timestamp
];
$entries = [];
if (file_exists($logFile) && filesize($logFile) > 0) {
    $existing = json_decode(file_get_contents($logFile), true);
    if (is_array($existing)) {
        $entries = $existing;
    }
}
$entries[] = $entry;
file_put_contents($logFile, json_encode($entries, JSON_PRETTY_PRINT));  // Log to JSON:contentReference[oaicite:6]{index=6}

// Return JSON response
if ($sent) {
    echo json_encode(['success' => true, 'message' => 'Email sent successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to send email.']);
}
