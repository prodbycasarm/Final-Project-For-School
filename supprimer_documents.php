<?php
ob_start();
session_start();

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    // Redirect to login page or any other page as needed
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Valider si le fichier est passé dans le formulaire
    if (!empty($_POST['fichier'])) {
        $fichier = $_POST['fichier'];

        // Vérifiez si le fichier existe
        if (file_exists($fichier)) {
            // Essayez de supprimer le fichier
            if (unlink($fichier)) {
                $_SESSION['supri'] = 'L\'opération a été effectuée avec succès.';
                header("location:liste_des_stagiaires.php");
            } else {
                echo "<p>Erreur : impossible de supprimer le fichier. Vérifiez les permissions.</p>";
            }
        } else {
            echo "<p>Erreur : le fichier n'existe pas.</p>";
        }
    } else {
        echo "<p>Erreur : aucun fichier spécifié.</p>";
    }
} else {
    echo "<p>Erreur : méthode de requête invalide.</p>";
}
?>
