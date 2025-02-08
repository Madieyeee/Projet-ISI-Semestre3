<?php
session_start();
include 'includes/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $nom = $conn->real_escape_string($_POST['nom']);
    $message = $conn->real_escape_string($_POST['message']);

    $stmt = $conn->prepare("INSERT INTO messages (email, nom, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $email, $nom, $message);
    
    if ($stmt->execute()) {
        $_SESSION['message_success'] = "Votre message a bien été envoyé !";
    } else {
        $_SESSION['message_error'] = "Erreur lors de l'envoi du message.";
    }
    
    header("Location: index.php#contact");
    exit();
}