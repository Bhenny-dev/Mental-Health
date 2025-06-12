<?php
// config.php – Configuration settings
return [
    // Toggle between SMTP (true) or PHP mail() (false)
    'use_smtp' => true,

    // SMTP settings for PHPMailer (when use_smtp = true)
    'smtp' => [
        'host'       => 'smtp.gmail.com',      // e.g. smtp.gmail.com
        'username'   => 'riverabenlor461@gmail.com',// Your Gmail address
        'password'   => '',   // Your Gmail App Password (16-char):contentReference[oaicite:2]{index=2}
        'port'       => 587,
        'encryption' => 'tls'                  // or 'ssl' for port 465
    ],

    // Sender and recipient info
    'from' => [
        'email' => 'your-email@gmail.com',     // Same as SMTP username
        'name'  => 'Your Name'
    ],
    'to' => [
        'email' => 'recipient@example.com',    // Where contact form messages are sent
        'name'  => 'Recipient Name'
    ]
];
