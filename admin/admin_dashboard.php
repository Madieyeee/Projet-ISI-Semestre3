<?php
session_start();
if (!isset($_SESSION['matricule']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin dashboard - ISI Évaluation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="includes/styles.css">
    <link rel="icon" href="../assets\images\logo.png">
</head>
<body>

<?php 
    include "navbar.php";
?><br>
<div class="container">
    <div class="container dashboard">
        <h2>Bienvenue <?php echo $_SESSION['matricule']; ?> !</h2>
        <p>Ici, vous pouvez :</p>
            <ul>
                <li>Gérer les Professeurs: Ajouter/Supprimer</li>
                <li>Gérer les Eleves: Ajouter/Supprimer</li>
                <li>Voir l'historique des évaluations.</li>
                <li>Voir les messages qu'on laissé les visiteurs</li>
            </ul>
    </div><br><br><br><br><br>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
