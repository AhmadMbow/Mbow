<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validation améliorée
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING) ?? '';
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '';
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING) ?? '';

    if (empty($name) || empty($email) || empty($message)) {
        http_response_code(400);
        exit(json_encode(['error' => 'Tous les champs sont obligatoires']));
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        exit(json_encode(['error' => 'Adresse email invalide']));
    }

    $mail = new PHPMailer(true);

    try {
        // Configuration SMTP sécurisée
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'ahmadoulebeau@gmail.com';
        $mail->Password = 'vlgplbjwedxhzwaq'; // Mot de passe d'application sans espaces
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // SSL
        $mail->Port = 465;
        $mail->CharSet = 'UTF-8';

        // Configuration de l'email
        $mail->setFrom('noreply@i2sn.com', 'Site I2SN'); // Email générique
        $mail->addAddress('ahmadoulebeau@gmail.com', 'Administrateur');
        $mail->addReplyTo($email, $name);

        // Contenu structuré
        $mail->Subject = "[I2SN] Nouveau message de $name";
        $mail->Body = "
            Nom: $name
            Email: $email
            Message: 
            $message
        ";

        $mail->send();
        http_response_code(200);
        exit(json_encode(['success' => 'Message envoyé avec succès!']));

    } catch (Exception $e) {
        error_log("Erreur SMTP: " . $e->getMessage());
        http_response_code(500);
        exit(json_encode(['error' => 'Erreur lors de l\'envoi du message']));
    }
} else {
    http_response_code(405);
    header('Allow: POST');
    exit(json_encode(['error' => 'Méthode non autorisée']));
}
