<!-- Aspect sécurité 0... J'accueille les injections SQL à bras ouverts lmao -->
<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matricule = $_POST['matricule'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM utilisateurs WHERE matricule='$matricule' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Connexion réussie
        $_SESSION['matricule'] = $matricule;
        $_SESSION['role'] = $user['role'];
        $_SESSION['user_id'] = $user['id']; // Stocke l'identifiant de l'utilisateur dans la session
        
        // Redirection selon le rôle de l'utilisateur
        if ($user['role'] == 'admin') {
            header("Location: ../admin/admin_dashboard.php");
        } else {
            header("Location: ../student/student_dashboard.php");
        }
    } else {
        // Connexion échouée
        echo "Matricule ou mot de passe incorrect.";
    }
}
?>
