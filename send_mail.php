<?php
// Inclure PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Charger l'autoloader de Composer
require 'vendor/autoload.php';

// Afficher les erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupérer les données du formulaire
    $name = htmlspecialchars(trim($_POST["name"]));
    $email = htmlspecialchars(trim($_POST["email"]));
    $message = htmlspecialchars(trim($_POST["message"]));

    // Créer une instance de PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configurer le serveur SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Serveur SMTP de Gmail
        $mail->SMTPAuth = true;
        $mail->Username = '';  // Remplacez par votre adresse Gmail
        $mail->Password = '';   // Remplacez par votre mot de passe spécifique à l'application Gmail
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;  // Port SMTP pour Gmail

        // Destinataire
        $mail->setFrom('papaahmadmbow@gmail.com', 'Nom'); // Utilisez votre adresse Gmail ici
        $mail->addAddress('ahmadoulebeau@gmail.com');  // Adresse de destination

        // Contenu de l'email
        $mail->isHTML(false);  // Utiliser le format texte brut
        $mail->Subject = 'Nouveau message de contact';
        $mail->Body    = "Nom : $name\nEmail : $email\nMessage : $message";

        // Envoyer l'email
        $mail->send();
        echo 'L\'email a été envoyé avec succès.';
    } catch (Exception $e) {
        echo "L'envoi de l'email a échoué. Erreur: {$mail->ErrorInfo}";
    }
}
?>
