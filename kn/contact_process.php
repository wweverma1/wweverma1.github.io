<?php    
    include('phpMailer/mailer_config.php');
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require $_SERVER['DOCUMENT_ROOT'] . '/phpMailer/Exception.php';
    require $_SERVER['DOCUMENT_ROOT'] . '/phpMailer/PHPMailer.php';
    require $_SERVER['DOCUMENT_ROOT'] . '/phpMailer/SMTP.php';


    if (isset($_POST['form-submit'])) 
    {

        function problem($error)
        {
            echo "We are very sorry, but there were error(s) found with the form you submitted. ";
            echo "These errors appear below.<br><br>";
            echo $error . "<br><br>";
            echo "Please go back and fix these errors.<br><br>";
            die();
        }

        // validation expected data exists
        if ((!isset($_POST['email']) &&
            !isset($_POST['cnumber']) ) ||
            !isset($_POST['name']) ||
            !isset($_POST['message'])
        ) {
            problem('We are sorry, but there appears to be a problem with the form you submitted.');
        }

        $name = $_POST['name']; // required
        $email = $_POST['email']; // required
        $message = $_POST['message']; // required
        $number = $_POST['cnumber']; //required

        $error_message = "";
        $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';

        if (!preg_match($email_exp, $email)) {
            $error_message .= 'The Email address you have entered does not appear to be valid.<br>';
        }

        $string_exp = "/^[A-Za-z .'-]+$/";

        if (!preg_match($string_exp, $name)) {
            $error_message .= 'The Name you have entered does not appear to be valid.<br>';
        }

        if ((strlen($number) < 10 || strlen($number) > 13)) {
            $error_message .= 'The Number you have entered does not appear to be valid.<br>';
        }

        if (strlen($message) < 2) {
            $error_message .= 'The Message you have entered do not appear to be valid.<br>';
        }

        if (strlen($error_message) > 0) {
            problem($error_message);
        }

        $email_message = "New Enquiry Form details:<br>";

        function clean_string($string)
        {
            $bad = array("content-type", "bcc:", "to:", "cc:", "href");
            return str_replace($bad, "", $string);
        }

        $email_message .= "Name: <b>" . clean_string($name) . "</b><br>";
        $email_message .= "Contact Number: <b>" . clean_string($number) . "</b><br>";
        $email_message .= "Email: <b>" . clean_string($email) . "</b><br>";
        $email_message .= "Message: " . clean_string($message) . ".<br><br>";
        $email_message .= "Thanks & Regards<br><br><b>Krishi Network</b>";

                $mail = new PHPMailer;
                $mail->isSMTP(); 
                $mail->SMTPDebug = 0;
                $mail->Host = "smtp.gmail.com"; 
                $mail->Port = 587; 
                $mail->SMTPSecure = 'tls'; 
                $mail->SMTPAuth = true;
                $mail->Username = $mailer_username; 
                $mail->Password = $mailer_password; 
                $mail->setFrom('enquiry@kn.com', 'Krishi Network'); 
                $mail->addAddress('wweverma1@gmail.com', 'Aditya');
                $mail->Subject = 'New Enquiry';
                $mail->msgHTML($email_message);
                $mail->AltBody = 'HTML Texts Not Supported'; 
                $mail->SMTPOptions = array(
                                    'ssl' => array(
                                        'verify_peer' => false,
                                        'verify_peer_name' => false,
                                        'allow_self_signed' => true
                                    )
                                );
                $mail->send();


        header('location: index.html');
    }
?>
