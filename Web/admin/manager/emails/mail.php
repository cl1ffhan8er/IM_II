<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'email/src/Exception.php';
require 'email/src/PHPMailer.php';
require 'email/src/SMTP.php';

if (isset($_POST["send"])) {

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'alissamay071006@gmail.com';
        $mail->Password   = 'hfcr hemq fqwl vknx';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('alissamay071006@gmail.com', 'SRVan Travels'); 
        $mail->addAddress($_POST["email"], $_POST["name"]);

        // --- TEMPLATE LOGIC ---
        $template_choice = $_POST['template'];
        $user_name = htmlspecialchars($_POST["name"]);
        $user_email = htmlspecialchars($_POST["email"]);
        $user_subject = htmlspecialchars($_POST["subject"]);
        $user_message = nl2br(htmlspecialchars($_POST["message"]));

        $email_body = "";
        $email_subject = "";

        switch ($template_choice) {
            case 'itinerary_change':
                $email_subject = "Itinerary Change";

                // Load the template file
                $email_body = file_get_contents('email_templates/itinerary_change.html');

                // Replace placeholders
                $email_body = str_replace(
                    ['{{user_name}}', '{{itinerary_list}}'],
                    [
                        $user_name,
                        '<li>Stop 1: Quezon City</li><li>Stop 2: Antipolo</li><li>Stop 3: Laguna</li>'
                    ],
                    $email_body
                );

                break;
            
            // You can add other cases here for different templates
            case 'itinerary_approval':
                $email_subject = "Itinerary Approval";

                // Load template file
                $email_body = file_get_contents('email_templates/itinerary_approval.html');

                // Replace placeholders with actual values
                $email_body = str_replace(
                    ['{{user_name}}', '{{date}}', '{{itinerary_list}}', '{{price}}'],
                    [$user_name, 'July 20, 2025', '<li>Stop 1: Manila</li><li>Stop 2: Tagaytay</li>', '₱4,500'],
                    $email_body
                );

                break;

            default: // Default to a simple text-based inquiry
                $email_subject = "General Inquiry from {$user_name}: {$user_subject}";
                $email_body = "
                    <h2>New General Inquiry</h2>
                    <p><b>Name:</b> {$user_name}</p>
                    <p><b>Email:</b> {$user_email}</p>
                    <p><b>Message:</b></p>
                    <p>{$user_message}</p>
                ";
                break;
        }

        // Content
        $mail->isHTML(true);
        $mail->Subject = $email_subject;
        $mail->Body    = $email_body;

        $mail->send();
        echo
        " 
        <script> 
          alert('Message was sent successfully!');
          document.location.href = 'emailtest.php';
        </script>
        ";
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
