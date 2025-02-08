<?php 
session_start();
include '../includes/config.php';

// Vérifier si l'étudiant est connecté
if (!isset($_SESSION['matricule']) || $_SESSION['role'] != 'etudiant') {
    header("Location: ../index.php");
    exit();
}

if (!isset($_SESSION['user_id'])) {
    die('Erreur: user_id non défini dans la session.');
}

$user_id = $_SESSION['user_id']; // Utilise l'identifiant de l'utilisateur stocké dans la session

// Récupérer la liste des enseignants
$sql_enseignants = "SELECT id, nom FROM enseignants";
$result_enseignants = $conn->query($sql_enseignants);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $enseignant_id = $_POST['enseignant_id'];
    $pedagogie = $_POST['pedagogie'];
    $ponctualite = $_POST['ponctualite'];
    $interaction = $_POST['interaction'];
    $organisation = $_POST['organisation'];
    $commentaire = $_POST['commentaire'];

    if (!empty($enseignant_id) && !empty($pedagogie) && !empty($ponctualite) && !empty($interaction) && !empty($organisation)) {
        $sql_insert = "INSERT INTO evaluations (user_id, enseignant_id, pedagogie, ponctualité, interaction, organisation, commentaire, date_evaluation) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql_insert);
        $stmt->bind_param("iiiiiss", $user_id, $enseignant_id, $pedagogie, $ponctualite, $interaction, $organisation, $commentaire);
        
        if ($stmt->execute()) {
            $message = "Évaluation soumise avec succès !";
        } else {
            $message = "Erreur lors de la soumission.";
        }
        $stmt->close();
    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}

// Récupérer les évaluations précédentes de l'élève
$sql_evaluations = "SELECT e.*, p.nom AS professeur_nom FROM evaluations e JOIN enseignants p ON e.enseignant_id = p.id WHERE e.user_id = '$user_id'";
$result_evaluations = $conn->query($sql_evaluations);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Évaluer un enseignant</title>
    <link rel="icon" href="../assets/images/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Helvetica Neue', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
        }
        h2 {
            color: #555;
            text-align: center;
            margin-bottom: 20px;
            font-weight: 300;
        }
        form {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }
        label {
            margin-top: 10px;
            font-weight: 500;
        }
        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            display: block;
            width: 100%;
            padding: 12px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }
        button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <?php include "navbar.php"; ?><br>
    <h2>Évaluation des enseignants</h2>
    <?php if (isset($message)) echo "<p>$message</p>"; ?>
    <form method="POST" action="">
        <label for="enseignant_id">Choisissez un enseignant :</label>
        <select name="enseignant_id" class="form-control" required>
            <option value="">Sélectionnez un enseignant</option>
            <?php while ($ens = $result_enseignants->fetch_assoc()) { ?>
                <option value="<?php echo $ens['id']; ?>"><?php echo htmlspecialchars($ens['nom']); ?></option>
            <?php } ?>
        </select>
        
        <label for="pedagogie">Pédagogie (1-5) :</label>
        <input type="number" name="pedagogie" class="form-control" min="1" max="5" required>
        
        <label for="ponctualite">Ponctualité (1-5) :</label>
        <input type="number" name="ponctualite" class="form-control" min="1" max="5" required>
        
        <label for="interaction">Interaction (1-5) :</label>
        <input type="number" name="interaction" class="form-control" min="1" max="5" required>
        
        <label for="organisation">Organisation (1-5) :</label>
        <input type="number" name="organisation" class="form-control" min="1" max="5" required>
        
        <label for="commentaire">Commentaire :</label>
        <textarea name="commentaire" class="form-control" rows="4"></textarea>
        
        <button type="submit">Soumettre</button>
    </form>

    <h2>Évaluations précédentes</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Professeur</th>
                <th>Pédagogie</th>
                <th>Ponctualité</th>
                <th>Interaction</th>
                <th>Organisation</th>
                <th>Commentaire</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result_evaluations->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['professeur_nom']); ?></td>
                <td><?php echo htmlspecialchars($row['pedagogie']); ?></td>
                <td><?php echo htmlspecialchars($row['ponctualité']); ?></td>
                <td><?php echo htmlspecialchars($row['interaction']); ?></td>
                <td><?php echo htmlspecialchars($row['organisation']); ?></td>
                <td><?php echo htmlspecialchars($row['commentaire']); ?></td>
                <td><?php echo htmlspecialchars($row['date_evaluation']); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
