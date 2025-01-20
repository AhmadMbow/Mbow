<?php
// Vérifier si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupérer les données du formulaire
    $name = htmlspecialchars(trim($_POST["name"]));
    $email = htmlspecialchars(trim($_POST["email"]));
    $message = htmlspecialchars(trim($_POST["message"]));

    // Adresse email de destination
    $to = "ahmadoulebeau@gmail.com";  // Remplacez par l'email de destination

    // Sujet de l'email
    $subject = "Nouveau message de $name via le formulaire de contact";

    // Contenu de l'email
    $emailBody = "
    Vous avez reçu un nouveau message depuis le formulaire de contact :
    
    Nom : $name
    Email : $email

    Message :
    $message
    ";

    // Entêtes de l'email
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Envoyer l'email
    if (mail($to, $subject, $emailBody, $headers)) {
        echo 'L\'email a été envoyé avec succès.';
    } else {
        echo "Une erreur s'est produite lors de l'envoi du message.";
    }
}
?>
