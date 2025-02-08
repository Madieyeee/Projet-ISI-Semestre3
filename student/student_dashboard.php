<?php
session_start();
if (!isset($_SESSION['matricule']) || $_SESSION['role'] != 'etudiant') {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Élève - ISI Évaluation</title>
    <link rel="icon" href="../assets/images/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="includes/styles.css">
</head>
<body class="body-home">
    <?php 
        include "navbar.php";
    ?><br><br><br>
    
    <div class="container dashboard">
        <h2>Bienvenue sur votre tableau de bord Etudiant <?php echo $_SESSION['matricule']; ?> 🫡</h2>
        <p>Utilisez le menu de navigation pour accéder aux différentes sections du tableau de bord.</p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
