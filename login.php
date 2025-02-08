<?php
session_start();
include 'includes/config.php';
if (isset($_SESSION['matricule'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISI Évaluation - Connexion</title>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="assets\images\logo.png">
    <link rel="stylesheet" href="assets/css/styleslogin.css">

</head>
<body class="body-home">
    <div class="black-fill">
        <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">
                        <img src="assets\images\logo.png" alt="Logo" width="40">
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link" aria-current="page" href="index.php">Accueil</a>
                            </li>
                        </ul>
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link" href="login.php">Aller connecte toi !</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav><br><br><br><br>

            <div data-aos="fade-up"data-aos-anchor-placement="center-bottom">
            <section class="login-form">
                <h3>Connexion</h3>
                <form action="includes/login.php" method="post">
                    <div class="mb-3">
                        <label for="matricule" class="form-label">Numéro de matricule</label>
                        <input type="text" class="form-control" id="matricule" name="matricule" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Se connecter</button>
                </form>
            </section>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>
</html>
