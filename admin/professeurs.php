<?php
session_start();
if (!isset($_SESSION['matricule']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}
include '../includes/config.php';

// Ajouter un professeur
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'ajouter') {
    $nom = $_POST['nom'];
    $matiere = $_POST['matiere'];
    $biographie = $_POST['biographie'];

    // Gestion de l'upload de photo
    $photo = 'default.jpg';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $target_dir = "../assets/prof_photos/";
        $target_file = $target_dir . basename($_FILES["photo"]["name"]);
        move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file);
        $photo = basename($_FILES["photo"]["name"]);
    }

    $stmt = $conn->prepare("INSERT INTO enseignants (nom, matiere, biographie, photo) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nom, $matiere, $biographie, $photo);

    if ($stmt->execute()) {
        header("Location: professeurs.php");
    } else {
        echo "Erreur: " . $stmt->error;
    }
}

// Modifier un professeur
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'modifier') {
    $id = intval($_POST['id']);
    $nom = $_POST['nom'];
    $matiere = $_POST['matiere'];
    $biographie = $_POST['biographie'];

    // Gestion de la photo
    $photo = $_POST['photo_actuelle'];
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $target_dir = "../assets/prof_photos/";
        $target_file = $target_dir . basename($_FILES["photo"]["name"]);
        move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file);
        $photo = basename($_FILES["photo"]["name"]);
    }

    $stmt = $conn->prepare("UPDATE enseignants SET nom=?, matiere=?, biographie=?, photo=? WHERE id=?");
    $stmt->bind_param("ssssi", $nom, $matiere, $biographie, $photo, $id);

    if ($stmt->execute()) {
        header("Location: professeurs.php");
    } else {
        echo "Erreur: " . $stmt->error;
    }
}

// Supprimer un professeur
if (isset($_GET['action']) && $_GET['action'] == 'supprimer' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Démarrer une transaction
    $conn->begin_transaction();

    try {
        // 1. Supprimer les évaluations associées
        $stmt_eval = $conn->prepare("DELETE FROM evaluations WHERE enseignant_id = ?");
        $stmt_eval->bind_param("i", $id);
        $stmt_eval->execute();

        // 2. Supprimer le professeur
        $stmt_prof = $conn->prepare("DELETE FROM enseignants WHERE id = ?");
        $stmt_prof->bind_param("i", $id);
        $stmt_prof->execute();

        // Valider la transaction
        $conn->commit();
        header("Location: professeurs.php");
        
    } catch (Exception $e) {
        // Annuler en cas d'erreur
        $conn->rollback();
        echo "Erreur : " . $e->getMessage();
    }
}

// Récupérer la liste des professeurs
$result = $conn->query("SELECT * FROM enseignants");
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des professeurs - ISI Évaluation</title>
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
        .prof-photo {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
        }
    </style>
</head>

<body>
    <?php include "navbar.php"; ?>
    <div class="dashboard">
        <div class="actions mb-4">
            <h2>Gestion des enseignants</h2>
            <button class="btn btn-primary" onclick="ouvrirModal('ajouter')">Ajouter un professeur</button>
        </div>

        <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <!-- <th>Photo</th> -->
                    <th>Nom</th>
                    <th>Matière</th>
                    <th>Biographie</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <!-- <td><img src="../assets/prof_photos/<?= htmlspecialchars($row['photo']) ?>" class="prof-photo" alt="Photo"></td> -->
                        <td><?= htmlspecialchars($row['nom']) ?></td>
                        <td><?= htmlspecialchars($row['matiere']) ?></td>
                        <td><?= htmlspecialchars(substr($row['biographie'], 0, 50)) ?>...</td>
                        <td>
                            <button class="btn btn-warning btn-sm"
                                onclick="ouvrirModal('modifier', <?= $row['id'] ?>)"
                                data-nom="<?= htmlspecialchars($row['nom']) ?>"
                                data-matiere="<?= htmlspecialchars($row['matiere']) ?>"
                                data-biographie="<?= htmlspecialchars($row['biographie']) ?>"
                                data-photo="<?= htmlspecialchars($row['photo']) ?>">
                                Modifier
                            </button>
                            <a href="professeurs.php?action=supprimer&id=<?= $row['id'] ?>"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce professeur ?');">
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
            <form method="post" enctype="multipart/form-data">
                <h4 class="mb-4" id="modalTitle"></h4>
                <input type="hidden" name="action" id="action">
                <input type="hidden" name="id" id="id">
                <input type="hidden" name="photo_actuelle" id="photo_actuelle">

                <div class="mb-3">
                    <label>Nom complet</label>
                    <input type="text" name="nom" id="modalNom" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Matière enseignée</label>
                    <input type="text" name="matiere" id="modalMatiere" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Biographie</label>
                    <textarea name="biographie" id="modalBiographie" class="form-control" rows="3"></textarea>
                </div>

                <div class="mb-3">
                    <label>Photo de profil</label>
                    <input type="file" name="photo" class="form-control">
                    <div id="photoPreview" class="mt-2"></div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-secondary" onclick="fermerModal()">Annuler</button>
                    <button type="submit" class="btn btn-primary">Valider</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function ouvrirModal(action, id = null) {
            const modal = document.getElementById('modal');
            modal.style.display = 'block';
            document.getElementById('action').value = action;
            document.getElementById('modalTitle').textContent = action === 'ajouter' ?
                'Ajouter un professeur' :
                'Modifier un professeur';

            if (action === 'modifier') {
                const btn = event.target.closest('button');
                document.getElementById('id').value = id;
                document.getElementById('modalNom').value = btn.dataset.nom;
                document.getElementById('modalMatiere').value = btn.dataset.matiere;
                document.getElementById('modalBiographie').value = btn.dataset.biographie;
                document.getElementById('photo_actuelle').value = btn.dataset.photo;
                document.getElementById('photoPreview').innerHTML = `<img src="../assets/images/${btn.dataset.photo}" style="max-width: 100px;">`;
            } else {
                document.getElementById('id').value = '';
                document.getElementById('modalNom').value = '';
                document.getElementById('modalMatiere').value = '';
                document.getElementById('modalBiographie').value = '';
                document.getElementById('photoPreview').innerHTML = '';
            }
        }

        function fermerModal() {
            document.getElementById('modal').style.display = 'none';
        }

        window.onclick = function(e) {
            if (e.target === document.getElementById('modal')) fermerModal();
        }
    </script>
</body>

</html>