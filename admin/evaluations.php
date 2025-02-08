<?php
session_start();
if (!isset($_SESSION['matricule']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}
include '../includes/config.php';

// GESTION DES CRITÈRES D'ÉVALUATION

// Ajouter un critère
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'ajouter_critere') {
    $nom = htmlspecialchars($_POST['nom']);

    $stmt = $conn->prepare("INSERT INTO criteres (nom) VALUES (?)");
    $stmt->bind_param("s", $nom);

    if ($stmt->execute()) {
        header("Location: evaluations.php");
    } else {
        echo "Erreur: " . $stmt->error;
    }
}

// Modifier un critère
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'modifier_critere') {
    $id = intval($_POST['id']);
    $nom = htmlspecialchars($_POST['nom']);

    $stmt = $conn->prepare("UPDATE criteres SET nom = ? WHERE id = ?");
    $stmt->bind_param("si", $nom, $id);

    if ($stmt->execute()) {
        header("Location: evaluations.php");
    } else {
        echo "Erreur: " . $stmt->error;
    }
}

// Supprimer un critère
if (isset($_GET['action']) && $_GET['action'] == 'supprimer_critere' && isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM criteres WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: evaluations.php");
    } else {
        echo "Erreur: " . $stmt->error;
    }
}

// RÉCUPÉRATION DES DONNÉES

// Liste des critères
$criteres = $conn->query("SELECT * FROM criteres");

// Évaluations complètes avec jointures
$evaluations = $conn->query("
    SELECT e.*, u.nom as eleve_nom, ens.nom as prof_nom 
    FROM evaluations e
    JOIN utilisateurs u ON e.user_id = u.id
    JOIN enseignants ens ON e.enseignant_id = ens.id
    ORDER BY e.date_evaluation DESC
");
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Gestion des Évaluations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="../assets\images\logo.png">
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
        
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }

        .badge {
            font-size: 0.9em;
        }
    </style>
</head>

<body>
    <?php include "navbar.php"; ?>

    <div class="dashboard">
        <!-- Section Gestion des Critères -->
        <div class="mb-5">
            <h3 class="mb-4">Gestion des Critères d'Évaluation
                <button class="btn btn-sm btn-primary float-end" onclick="ouvrirModalCritere('ajouter')">
                    + Nouveau Critère
                </button>
            </h3>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom du Critère</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($critere = $criteres->fetch_assoc()): ?>
                        <tr>
                            <td><?= $critere['id'] ?></td>
                            <td><?= htmlspecialchars($critere['nom']) ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning"
                                    onclick="ouvrirModalCritere('modifier', <?= $critere['id'] ?>,'<?= htmlspecialchars($critere['nom']) ?>')">
                                    Modifier
                                </button>
                                <a href="evaluations.php?action=supprimer_critere&id=<?= $critere['id'] ?>"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Supprimer ce critère ?')">
                                    Supprimer
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Section des Évaluations -->
        <div>
            <h3 class="mb-4">Historique des Évaluations</h3>

            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Élève</th>
                        <th>Professeur</th>
                        <th>Critères</th>
                        <th>Commentaire</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($eval = $evaluations->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($eval['eleve_nom']) ?></td>
                            <td><?= htmlspecialchars($eval['prof_nom']) ?></td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    <span class="badge bg-primary">Pédagogie: <?= $eval['pedagogie'] ?>/5</span>
                                    <span class="badge bg-success">Ponctualité: <?= $eval['ponctualité'] ?>/5</span>
                                    <span class="badge bg-info">Interaction: <?= $eval['interaction'] ?>/5</span>
                                    <span class="badge bg-warning">Organisation: <?= $eval['organisation'] ?>/5</span>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($eval['commentaire']) ?></td>
                            <td><?= date('d/m/Y', strtotime($eval['date_evaluation'])) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal pour les Critères -->
    <div id="critereModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content p-3">
                <form method="post">
                    <h4 id="modalTitle" class="mb-3"></h4>
                    <input type="hidden" name="action" id="modalAction">
                    <input type="hidden" name="id" id="critereId">

                    <div class="mb-3">
                        <label>Nom du critère</label>
                        <input type="text" name="nom" id="critereNom" class="form-control" required>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" onclick="fermerModal()">Annuler</button>
                        <button type="submit" class="btn btn-primary">Valider</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Gestion des Modals
        function ouvrirModalCritere(action, id = null, nom = '') {
            const modal = document.getElementById('critereModal');
            modal.style.display = 'block';

            document.getElementById('modalTitle').textContent =
                action === 'ajouter' ? 'Ajouter un Critère' : 'Modifier un Critère';

            document.getElementById('modalAction').value = action + '_critere';

            if (action === 'modifier') {
                document.getElementById('critereId').value = id;
                document.getElementById('critereNom').value = nom;
            } else {
                document.getElementById('critereId').value = '';
                document.getElementById('critereNom').value = '';
            }
        }

        function fermerModal() {
            document.getElementById('critereModal').style.display = 'none';
        }

        // Fermer la modal en cliquant à l'extérieur
        window.onclick = function(event) {
            if (event.target === document.getElementById('critereModal')) {
                fermerModal();
            }
        }
    </script>
</body>

</html>