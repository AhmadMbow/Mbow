<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validation des données
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

    // Vérification des champs obligatoires
    if (empty($name) || empty($email) || empty($message)) {
        http_response_code(400);
        die("Tous les champs sont obligatoires");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        die("Adresse email invalide");
    }

    $mail = new PHPMailer(true);

    try {
        // Configuration SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'ahmadoulebeau@gmail.com'; 
        $mail->Password = 'lamr ddyz tzlb joxe '; // À remplacer par un mot de passe d'application
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';

        // Configuration de l'email
        $mail->setFrom($email, $name); // Utiliser l'email et le nom de l'expéditeur
        $mail->addAddress('ahmadoulebeau@gmail.com', 'Destinataire');
        $mail->addReplyTo($email, $name);

        // Contenu
        $mail->Subject = 'Nouveau message de contact de ' . $name;
        $mail->Body = "Nom: $name\nEmail: $email\n\nMessage:\n$message";

        // Envoi
        $mail->send();
        http_response_code(200);
        echo "Message envoyé avec succès!";
    } catch (Exception $e) {
        http_response_code(500);
        error_log("Erreur d'envoi d'email: " . $mail->ErrorInfo); // Log des erreurs
        echo "Une erreur est survenue lors de l'envoi du message.";
    }
} else {
    http_response_code(405);
    echo "Méthode non autorisée";
}
?>
