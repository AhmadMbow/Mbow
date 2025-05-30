<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    echo json_encode(['error' => 'Méthode non autorisée']);
    exit;
}

// Récupération et nettoyage des données du formulaire
$name    = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING) ?? '';
$email   = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '';
$message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING) ?? '';

// Vérification des champs
if (empty($name) || empty($email) || empty($message)) {
    http_response_code(400);
    echo json_encode(['error' => 'Tous les champs sont obligatoires']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Adresse email invalide']);
    exit;
}

// Envoi de l'e-mail via PHPMailer
$mail = new PHPMailer(true);

try {
    // Configuration du serveur SMTP sécurisé
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'ahmadoulebeau@gmail.com';
    $mail->Password   = 'vlgplbjwedxhzwaq'; // mot de passe d'application Gmail
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;
    $mail->CharSet    = 'UTF-8';

    // Configuration de l'email
    $mail->setFrom('noreply@i2sn.com', 'Site I2SN');
    $mail->addAddress('ahmadoulebeau@gmail.com', 'Administrateur');
    $mail->addReplyTo($email, $name);

    $mail->Subject = "[I2SN] Nouveau message de $name";
    $mail->Body    = "Nom: $name\nEmail: $email\nMessage:\n$message";

    $mail->send();

    http_response_code(200);
    echo json_encode(['success' => 'Message envoyé avec succès!']);
} catch (Exception $e) {
    error_log('Erreur PHPMailer: ' . $mail->ErrorInfo);
    http_response_code(500);
    echo json_encode(['error' => 'Erreur lors de l\'envoi du message. Veuillez réessayer plus tard.']);
}
