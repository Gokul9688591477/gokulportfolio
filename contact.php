<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"));
    $name = isset($data->name) ? trim($data->name) : '';
    $email = isset($data->email) ? trim($data->email) : '';
    $phone = isset($data->phone) ? trim($data->phone) : '';
    $service = isset($data->service) ? trim($data->service) : '';
    $message = isset($data->message) ? trim($data->message) : '';

    if (empty($name) || empty($email) || empty($phone) || empty($message)) {
        echo json_encode(['success' => false, 'message' => 'Please fill all required fields.']);
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'gokul.graphicarts@gmail.com';
        $mail->Password = 'zjof hhgd cqox iaxi';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        // Recipients
        $mail->setFrom('gokul.graphicarts@gmail.com', "Portfolio Contact");
        $mail->addAddress('gokul.graphicarts@gmail.com');
        $mail->addReplyTo($email, $name);

        // Content
        $mail->isHTML(true);
        $mail->Subject = "New Project Inquiry: " . ($service ? $service : 'General');
        
        // HTML Email Template
        $mail->Body = "
        <html>
        <head>
            <style>
                body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f4f4f4; padding: 20px; margin: 0; }
                .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
                .header { background: #1a1a1a; padding: 30px 20px; text-align: center; border-bottom: 3px solid #c7af93; }
                .header h1 { color: #ffffff; margin: 0; font-size: 22px; font-weight: 500; letter-spacing: 1px; text-transform: uppercase; }
                .content { padding: 35px 30px; color: #333333; line-height: 1.6; }
                .content h2 { color: #1a1a1a; font-size: 18px; margin-top: 0; border-bottom: 1px solid #eeeeee; padding-bottom: 12px; margin-bottom: 25px; }
                .field { margin-bottom: 22px; }
                .field strong { display: block; font-size: 12px; color: #888888; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
                .field div { background: #fcfcfc; padding: 14px 16px; border-radius: 6px; border-left: 3px solid #c7af93; font-size: 15px; color: #222; }
                .message-box { background: #fcfcfc; padding: 16px; border-radius: 6px; border-left: 3px solid #c7af93; white-space: pre-wrap; font-size: 15px; color: #222; }
                .footer { background: #fafafa; padding: 20px; text-align: center; color: #aaaaaa; font-size: 12px; border-top: 1px solid #eeeeee; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>New Portfolio Inquiry</h1>
                </div>
                <div class='content'>
                    <h2>Contact Details</h2>
                    
                    <div class='field'>
                        <strong>Name</strong>
                        <div>" . htmlspecialchars($name) . "</div>
                    </div>
                    
                    <div class='field'>
                        <strong>Email Address</strong>
                        <div><a href='mailto:" . htmlspecialchars($email) . "' style='color: #c7af93; text-decoration: none; font-weight: bold;'>" . htmlspecialchars($email) . "</a></div>
                    </div>
                    
                    <div class='field'>
                        <strong>Phone Number</strong>
                        <div><a href='tel:" . htmlspecialchars($phone) . "' style='color: #c7af93; text-decoration: none; font-weight: bold;'>" . ($phone ? htmlspecialchars($phone) : 'Not provided') . "</a></div>
                    </div>
                    
                    <div class='field'>
                        <strong>Service Needed</strong>
                        <div>" . ($service ? htmlspecialchars($service) : 'Not specified') . "</div>
                    </div>
                    
                    <div class='field'>
                        <strong>Project Brief</strong>
                        <div class='message-box'>" . nl2br(htmlspecialchars($message)) . "</div>
                    </div>
                </div>
                <div class='footer'>
                    This email was sent securely from your portfolio contact form.
                </div>
            </div>
        </body>
        </html>
        ";
        
        $mail->AltBody = "You have received a new message from your portfolio contact form.\n\n" .
            "Name: $name\n" .
            "Email: $email\n" .
            "Phone: " . ($phone ? $phone : 'Not provided') . "\n" .
            "Service Needed: " . ($service ? $service : 'Not specified') . "\n\n" .
            "Message:\n$message\n";

        $mail->send();
        echo json_encode(['success' => true, 'message' => 'Message has been sent']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>