<?php
session_start();
if (!isset($_SESSION['matricule']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}
include '../includes/config.php';

// Récupération des messages
$result = $conn->query("SELECT * FROM messages ORDER BY date_creation DESC");
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Messages reçus - Admin</title>
    <link rel="icon" href="../assets\images\logo.png">
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

        .message-card {
            border-left: 4px solid #0d6efd;
        }
    </style>
</head>

<body>
    <?php include "navbar.php"; ?>

    <div class="dashboard">
        <h3>Messages des utilisateurs</h3>

        <div class="mt-4">
            <?php while ($message = $result->fetch_assoc()): ?>
                <div class="card mb-3 message-card">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($message['nom']) ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted">
                            <?= htmlspecialchars($message['email']) ?> -
                            <?= date('d/m/Y H:i', strtotime($message['date_creation'])) ?>
                        </h6>
                        <p class="card-text"><?= nl2br(htmlspecialchars($message['message'])) ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>

</html>