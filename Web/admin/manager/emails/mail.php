<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'email/src/Exception.php';
require 'email/src/PHPMailer.php';
require 'email/src/SMTP.php';
require_once '../../../include/connect.php'; // ⬅️ Make sure this points correctly to DB connection

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

        // Input values
        $template_choice = $_POST['template'];
        $user_name = htmlspecialchars($_POST["name"]);
        $user_email = htmlspecialchars($_POST["email"]);
        $user_subject = htmlspecialchars($_POST["subject"]);
        $user_message = nl2br(htmlspecialchars($_POST["message"]));
        $orderID = $_POST["order_ID"] ?? null;
        error_log("Received order_ID: " . $orderID);


        // Recipients
        $mail->setFrom('alissamay071006@gmail.com', 'SRVan Travels');
        $mail->addAddress($user_email, $user_name);

        // Email subject and body
        $email_subject = "";
        $email_body = "";

        if ($template_choice === 'itinerary_change') {
            $email_subject = "Itinerary Change";

            $email_body = file_get_contents('email_templates/itinerary_change.html');
            $email_body = str_replace(
                ['{{user_name}}', '{{itinerary_list}}'],
                [$user_name, '<li>Stop 1: Quezon City</li><li>Stop 2: Antipolo</li><li>Stop 3: Laguna</li>'],
                $email_body
            );

            // ✅ Update status in DB
            if ($orderID) {
                $stmt = $conn->prepare("UPDATE order_details SET status = 'IN MODIFICATION' WHERE order_ID = ?");
                $stmt->bind_param("i", $orderID);
                $stmt->execute();
            }

        } elseif ($template_choice === 'itinerary_approval') {
            $email_subject = "Itinerary Approval";

            $email_body = file_get_contents('email_templates/itinerary_approval.html');
            $email_body = str_replace(
                ['{{user_name}}', '{{date}}', '{{itinerary_list}}', '{{price}}'],
                [$user_name, 'July 20, 2025', '<li>Stop 1: Manila</li><li>Stop 2: Tagaytay</li>', '₱4,500'],
                $email_body
            );

            // ✅ Update status in DB
            if ($orderID) {
                $stmt = $conn->prepare("UPDATE order_details SET status = 'ACCEPTED' WHERE order_ID = ?");
                if (!$stmt) {
                    die("Prepare failed: " . $conn->error);
                }
                $stmt->bind_param("i", $orderID);
                if (!$stmt->execute()) {
                    die("Execute failed: " . $stmt->error);
                }
                $stmt->execute();
            }

        } elseif ($template_choice === 'reject') {
            $email_subject = "Booking Rejected";
            $email_body = "
                <h2>Booking Rejected</h2>
                <p>Dear {$user_name},</p>
                <p>We regret to inform you that your booking request has been rejected.</p>
                <p>If you have any questions, feel free to contact us.</p>
            ";


            // ✅ Delete order from DB
            if ($orderID) {
                $stmt = $conn->prepare("DELETE FROM order_details WHERE order_ID = ?");
                $stmt->bind_param("i", $orderID);
                $stmt->execute();
            }

        } else {
            // General inquiry fallback
            $email_subject = "General Inquiry from {$user_name}: {$user_subject}";
            $email_body = "
                <h2>New General Inquiry</h2>
                <p><b>Name:</b> {$user_name}</p>
                <p><b>Email:</b> {$user_email}</p>
                <p><b>Message:</b></p>
                <p>{$user_message}</p>
            ";
        }

        // Send the email
        $mail->isHTML(true);
        $mail->Subject = $email_subject;
        $mail->Body    = $email_body;
        $mail->send();

        // ✅ Redirect to manager dashboard
        echo "
        <script> 
            alert('Email sent and order updated.');
            window.location.href = '../home.php';
        </script>
        ";

    } catch (Exception $e) {
        echo "Mailer Error: {$mail->ErrorInfo}";
    }
}
