<?php
// EmailForm.php – reusable class for sending emails via PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailForm {
    protected $config;

    public function __construct(array $config) {
        $this->config = $config;
    }

    /**
     * Send the email. $data should contain name, email, subject, message.
     * Returns true on success, false on failure.
     */
    public function send(array $data): bool {
        $mail = new PHPMailer(true);
        try {
            // Configure PHPMailer for SMTP if enabled
            if (!empty($this->config['use_smtp'])) {
                $mail->isSMTP();
                $mail->Host       = $this->config['smtp']['host'];
                $mail->SMTPAuth   = true;
                $mail->Username   = $this->config['smtp']['username'];
                $mail->Password   = $this->config['smtp']['password'];
                $mail->SMTPSecure = $this->config['smtp']['encryption'];
                $mail->Port       = $this->config['smtp']['port'];
            }
            // Set sender and recipient
            $mail->setFrom($this->config['from']['email'], $this->config['from']['name']);
            $mail->addAddress($this->config['to']['email'], $this->config['to']['name']);
            // Reply to the user's email
            $mail->addReplyTo($data['email'], $data['name']);

            // Email content
            $mail->isHTML(false);
            $mail->Subject = $data['subject'];
            $mail->Body    = $data['message'];

            $mail->send();
            return true;
        } catch (Exception $e) {
            // You may log $mail->ErrorInfo or $e->getMessage() if needed
            return false;
        }
    }
}
