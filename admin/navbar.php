<?php
    $current_page = basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="admin_dashboard.php">
            <img src="../assets/images/logo.png" width="50">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse"  id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'admin_dashboard.php') ? 'active' : '' ?>" href="admin_dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'professeurs.php') ? 'active' : '' ?>" href="professeurs.php">Professeurs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'eleves.php') ? 'active' : '' ?>" href="eleves.php">Élèves</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'evaluations.php') ? 'active' : '' ?>" href="evaluations.php">Evaluations</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'message.php') ? 'active' : '' ?>" href="message.php">Messages</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="../includes/logout.php">Déconnexion</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
