<?php
session_start();
if (!isset($_SESSION['matricule']) || $_SESSION['role'] != 'etudiant') {
    header("Location: ../index.php");
    exit();
}
include '../includes/config.php';

// Je récupère les informations de l'élève
$matricule = $_SESSION['matricule'];
$sql = "SELECT * FROM utilisateurs WHERE matricule='$matricule'";
$result = $conn->query($sql);
$eleve = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil de l'Élève - ISI Évaluation</title>
    <link rel="icon" href="../assets/images/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="includes/styles.css">
</head>
<body>
    <?php 
        include "navbar.php";
    ?><br>
    
    <div class="container dashboard">
        <h2>Profil de l'Élève</h2>
        <div class="mb-3">
            <label for="matricule" class="form-label"><strong>Matricule :</strong></label>
            <p id="matricule"><?php echo $eleve['matricule']; ?></p>
        </div>
        <div class="mb-3">
            <label for="nom" class="form-label"><strong>Nom :</strong></label>
            <p id="nom"><?php echo $eleve['nom']; ?></p>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label"><strong>Email :</strong></label>
            <p id="email"><?php echo $eleve['email']; ?></p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
