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
    <title>ISI Evaluation - Accueil</title>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="icon" href="assets\images\logo.png">
    <style>body {background: url('assets/images/bg.jpg') no-repeat  fixed;}</style>
</head>
<body id="accueil" class="body-home">
    <div class="black-fill">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light bg">
                <div class="container-fluid">
                    <a class="navbar-brand">
                        <img src="assets\images\logo.png" alt="Logo" width="40">
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="#accueil">Accueil</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#about">À propos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#contact">Contact</a>
                            </li>
                        </ul>
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link" href="login.php">Connexion</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav><br><br><br><br>

            <section class="welcome-text">
                <img src="assets\images\logo.png" alt="Logo ISI Evaluation">
                <h1>Bienvenue sur ISI Evaluation</h1>
                <p>Évaluez vos enseignants de manière simple et efficace</p>
            </section><br><br><br><br><br><br><br>

            <section id="about"><br><br>
                <div data-aos="zoom-in" class="card mb-3 card-1">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="assets\images\logo.png" class="img-fluid rounded-start" alt="À propos">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">À propos de nous</h5><br>
                                <p class="card-text">
                                    ISI Evaluation est une plateforme moderne permettant aux étudiants de noter et de commenter leurs enseignants de manière simple et efficace.
                                </p>
                                <p class="card-text">
                                    <small class="text-muted">Institut Supérieur d'Informatique - Dakar</small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section><br><br><br><br><br><br><br>

            <div data-aos="fade-up" data-aos-anchor-placement="center-bottom">

            <section id="contact">
                <form method="POST" action="traitement_message.php">
                    <h3>Contactez-nous</h3>
                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Nom complet</label>
                        <input type="text" class="form-control" id="name" name="nom" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Envoyer</button>
                </form>
                
                    <?php if(isset($_SESSION['message_success'])): ?>
                    <div class="alert alert-success mt-3">
                        <?= $_SESSION['message_success'] ?>
                        <?php unset($_SESSION['message_success']); ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if(isset($_SESSION['message_error'])): ?>
                    <div class="alert alert-danger mt-3">
                        <?= $_SESSION['message_error'] ?>
                        <?php unset($_SESSION['message_error']); ?>
                    </div>
                    <?php endif; ?>

            </section>
            </div><br><br><br>

            <footer>
                <p>&copy; 2025 ISI Evaluation. Tous droits réservés.</p>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>
</html>
