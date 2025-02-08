<?php
session_start();
if (!isset($_SESSION['matricule']) || $_SESSION['role'] != 'etudiant') {
    header("Location: ../index.php");
    exit();
}
include '../includes/config.php';

// Récupérer la liste des professeurs
$sql = "SELECT * FROM enseignants";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Professeurs - ISI Évaluation</title>
    <link rel="icon" href="../assets/images/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="includes/styles.css">
</head>
<body>
    <?php 
        include "navbar.php";
    ?><br>
    
    <div class="container dashboard">
        <h2>Liste des Professeurs</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Matière</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['nom']; ?></td>
                    <td><?php echo $row['matiere']; ?></td>
                    <td>
                        <a href="evaluations.php?enseignant_id=<?php echo $row['id']; ?>" class="btn btn-primary">Évaluer</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
