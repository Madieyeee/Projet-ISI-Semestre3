<?php
session_start();
if (!isset($_SESSION['matricule']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}
include '../includes/config.php';

//La fonction real_escape_string en PHP est utilisée pour sécuriser les chaînes de caractères avant de les intégrer dans une requête SQL. Elle effectue deux actions principales :

// 1) Échappement des caractères spéciaux :
// Elle ajoute un antislashe (\) devant les caractères potentiellement dangereux comme ', ", \, etc.
// Exemple : "O'Connor" devient "O\'Connor", ce qui permet d’éviter les erreurs de syntaxe SQL.

// 2) Protection minimale contre les injections SQL :
// C’est une mesure de sécurité basique pour neutraliser certaines tentatives d’injection SQL.

// Ajouter un étudiant
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'ajouter') {
    $matricule = $conn->real_escape_string($_POST['matricule']);
    $nom = $conn->real_escape_string($_POST['nom']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO utilisateurs (matricule, nom, email, password, role) VALUES (?, ?, ?, ?, 'etudiant')");
    $stmt->bind_param("ssss", $matricule, $nom, $email, $password);

    if ($stmt->execute()) {
        header("Location: eleves.php");
    } else {
        echo "Erreur: " . $stmt->error;
    }
}

// Modifier un étudiant
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'modifier') {
    $id = intval($_POST['id']);
    $matricule = $conn->real_escape_string($_POST['matricule']);
    $nom = $conn->real_escape_string($_POST['nom']);
    $email = $conn->real_escape_string($_POST['email']);

    // Gestion du mot de passe
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE utilisateurs SET matricule=?, nom=?, email=?, password=? WHERE id=?");
        $stmt->bind_param("ssssi", $matricule, $nom, $email, $password, $id);
    } else {
        $stmt = $conn->prepare("UPDATE utilisateurs SET matricule=?, nom=?, email=? WHERE id=?");
        $stmt->bind_param("sssi", $matricule, $nom, $email, $id);
    }

    if ($stmt->execute()) {
        header("Location: eleves.php");
    } else {
        echo "Erreur: " . $stmt->error;
    }
}

// Supprimer un étudiant
if (isset($_GET['action']) && $_GET['action'] == 'supprimer' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM utilisateurs WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: eleves.php");
    } else {
        echo "Erreur: " . $stmt->error;
    }
}

// Récupérer la liste des étudiants
$result = $conn->query("SELECT * FROM utilisateurs WHERE role='etudiant'");
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des élèves - ISI Évaluation</title>
    <link rel="icon" href="../assets/images/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html,body {
            height: 100%;
            margin: 0;
        }

        .dashboard {
            min-height: 100%;
            width: 100%;
            padding: 2em;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }

        .modal-content {
            margin: 15% auto;
            width: 90%;
            max-width: 500px;
        }

        .password-note {
            font-size: 0.8em;
            color: #666;
        }
    </style>
</head>
<body>
<?php include "navbar.php"; ?>
<div class="dashboard">
    <div class="actions">
        <h2>Liste des élèves</h2>
        <button class="btn btn-primary" onclick="ouvrirModal(event, 'ajouter')">Ajouter un élève</button>
    </div>

    <table class="table table-hover">
        <thead class="table-dark">
            <tr>
                <th>Matricule</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['matricule']) ?></td>
                    <td><?= htmlspecialchars($row['nom']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td>
                        <button class="btn btn-warning btn-sm"
                            onclick="ouvrirModal(event, 'modifier', <?= $row['id'] ?>)"
                            data-matricule="<?= htmlspecialchars($row['matricule']) ?>"
                            data-nom="<?= htmlspecialchars($row['nom']) ?>"
                            data-email="<?= htmlspecialchars($row['email']) ?>">
                            Modifier
                        </button>
                        <a href="eleves.php?action=supprimer&id=<?= $row['id'] ?>"
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet élève ?');">
                            Supprimer
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div id="modal" class="modal">
    <div class="modal-content p-4">
        <form method="post">
            <h4 id="modalTitle" class="mb-4"></h4>
            <input type="hidden" name="action" id="action">
            <input type="hidden" name="id" id="id">

            <div class="mb-3">
                <label>Matricule</label>
                <input type="text" name="matricule" id="modalMatricule" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Nom complet</label>
                <input type="text" name="nom" id="modalNom" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" id="modalEmail" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Mot de passe</label>
                <input type="password" name="password" id="modalPassword" class="form-control">
                <div class="password-note mt-1">Laisser vide pour garder l'actuel</div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn btn-secondary" onclick="fermerModal()">Annuler</button>
                <button type="submit" class="btn btn-primary">Valider</button>
            </div>
        </form>
    </div>
</div>

<script>
    function ouvrirModal(event, action, id = null) {
        const modal = document.getElementById('modal');
        modal.style.display = 'block';
        document.getElementById('action').value = action;
        document.getElementById('modalTitle').textContent = action === 'ajouter' ? 'Ajouter un élève' : 'Modifier un élève';

        if (action === 'modifier') {
            const btn = event.currentTarget;
            document.getElementById('id').value = id;
            document.getElementById('modalMatricule').value = btn.dataset.matricule;
            document.getElementById('modalNom').value = btn.dataset.nom;
            document.getElementById('modalEmail').value = btn.dataset.email;
        } else {
            document.getElementById('id').value = '';
            document.getElementById('modalMatricule').value = '';
            document.getElementById('modalNom').value = '';
            document.getElementById('modalEmail').value = '';
            document.getElementById('modalPassword').value = '';
        }
    }

    function fermerModal() {
        document.getElementById('modal').style.display = 'none';
    }

    window.onclick = function(e) {
        if (e.target === document.getElementById('modal')) fermerModal();
    }
</script>

<!-- <script>src="includes/script.js"</script> -->
</body>

</html>