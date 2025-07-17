<?php
ob_start();
session_start();

include "bd.php";
// Check if the user is not logged in
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'superviseur') {
    // Redirect to login page or any other page as needed
    header("Location: index.php");
    exit();
}

// Check if the disconnect button is clicked
if (isset($_POST['disconnect'])) {
    // Destroy all session data
    session_unset();
    session_destroy();
    // Redirect to login page or any other page as needed
    header("Location: index.php");
    exit();
}

// Set welcome message if it's not set
$welcome_message = isset($_SESSION['welcome_message']) ? $_SESSION['welcome_message'] : '';

?>


<!-- Rest of your page content goes here -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapports de Stage - 2023 <?php echo $welcome_message; ?></title>
    <style>
         * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
            color: #333;
        }
        /* Pour le menu */
        .menu {
            background-color: #000;
            padding: 10px 20px;
            display: flex;
            align-items: center; /* Center items vertically */
        }
        .menu p {
            margin: 0; /* Remove default margin */
            margin-right: auto; /* Push the paragraph to the left */
            color: #fff;
            font-weight: bold;
        }
        .menu ul {
            list-style-type: none; /* Remove bullet points */
            margin: 0; /* Remove default margin */
            padding: 0; /* Remove default padding */
            display: flex;
            align-items: center; /* Center items vertically */
        }

        .menu a {
            text-decoration: none;
            color: #fff;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .menu a:hover {
            background-color: #555;
        }
        .menu .nav-links-menu { 
            padding-left:10%;
        }

        /* Modifier le contenu lorsque l'écran est inférieur à 1128px */
        @media (max-width: 1292px) {
                .menu .nav-links-menu { 
                    padding-left:5%;
                }
        }

        /* Modify layout for screens smaller than 1128px */
        @media (max-width: 1228px) {
            .menu {
                display: block; /* Make the menu stack vertically */
                padding: 10px;
                text-align: center; /* Center align the menu items */
            }

            .titre {
                margin-bottom: 15px; /* Add space between the title and menu */
            }

            .menu ul {
                display: flex;
                flex-direction: column; /* Stack menu items vertically */
                padding: 0;
            }

            .menu ul li {
                margin: 5px 0; /* Add spacing between menu items */
            }

            .menu .nav-links-menu {
                padding-left: 0; /* Remove left padding */
            }
        }







        .header {
            background-color: #333;
            color: #fff;
            padding: 20px 0;
            text-align: center;
        }
        
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
            background-color: #fff;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            color: #333;
        }
        img {
            vertical-align: middle;
            max-width: 100%;
            height: auto;
        }

        .container {
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.6);
        }
        h2 {
            margin-top: 0;
            color: #333;
            text-align: center;
        }
        .text {
            margin-bottom: 20px;
            color: #333;
            text-align: center;
            font-weight: bold; /* Make the text bold */
            text-decoration: underline; /* Add underline */
            padding: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        textarea {
            width: calc(100% - 24px);
            padding: 10px;
            border: 3px solid #C7C7C7;
            border-radius: 5px;
            box-sizing: border-box;
        }
        textarea {
            height: 100px;
        }
        input[type="submit"], input[type="reset"] {
            background-color: #333;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            width: 100%;
            margin-top: 10px;
        }

        input[type="submit"]:hover, input[type="reset"]:hover {
            background-color: #0056b3;
        }
    </style>
    <style>
        /* Navbar styles */
        nav {
            width: 100%;
            background: #242526;
            border-bottom: 2px white solid;
        }

       

        nav .wrapper {
            max-width: 1300px;
            padding:30px;
            height: 70px;
            line-height: 70px;
            margin: auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .wrapper .logo a {
            color: #f2f2f2;
            font-size: 30px;
            font-weight: 600;
            text-decoration: none;
        }

        .nav-links {
            display: inline-flex;
        }

        .nav-links li {
            list-style: none;
        }

        .nav-links li a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            font-weight: 500;
            padding: 9px 15px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        .sub-menu {
            color: black !important;
        }
  
        .nav-links li a:hover {
            background: #3A3B3C;
            color: white !important;
        }

        /* Header and Title Styles */
        .header {
            background-color: white;
            color: black;
        }

        .titre h1 {
            color: white;
        }
        .header .wrapper {
            display: flex;
            flex-direction: column;
            padding:10px;
            align-items: center; /* Center the menu horizontally */
            text-align: center;
        }

        .header .nav-links {
            display: flex;
            justify-content: center; /* Center menu items within the flex container */
            gap: 15px; /* Optional: space between menu items */
            padding: 20px;
            
        }

        .tous {
            margin-top: 40px;
            text-align: left;
            width: 70%;
            margin: 20px auto;
        }

        /* Responsive adjustments */
        @media screen and (max-width: 970px) {
            .wrapper .nav-links {
                position: fixed;
                height: 100vh;
                width: 100%;
                max-width: 350px;
                top: 0;
                left: -100%;
                background: #242526;
                padding: 50px 10px;
                line-height: 50px;
                box-shadow: 0px 15px 15px rgba(0, 0, 0, 0.18);
                transition: all 0.3s ease;
            }

            .nav-links li {
                margin: 15px 10px;
            }

            .nav-links li a {
                font-size: 20px;
                display: block;
                padding: 0 20px;
            }
        }
        .rapport-link {
            color:black;
        }
        .tableau {
            background-color: #dadada;
            padding: 50px;
            width: 70%;
            margin: 0 auto; /* Centers the container horizontally */
        }
    </style>
    
   
</head>
<body>
    <div class="main">
        <nav>
            <div class="menu">
                <div class="titre"><h1 href="#">Stage technique d'intégration multimédia</h1></div>
                <ul class="nav-links-menu">
                    <li style="color:white; font-size:18px; padding-left:30px;">
                    <?php echo $welcome_message; ?> <!-- Nom du superviseur -->
                    </li>
                    <li><a href="#">Accueil</a></li>
                    <li><a href="Googlemap/googlemaps.php">Google maps</a></li>
                    <li id="disconnect"><a href="index.php">Déconnecter</a></li>
                </ul>
            </div>
        </nav>

        <div class="header">
            <div class="wrapper">
                <ul class="nav-links">
                    <li><a class="sub-menu" href="#">Rapports d'étape</a></li>
                    <li><a class="sub-menu" href="liste_des_stagiaires.php">Évaluations des Stagiaires</a></li>
                </ul> 
            </div>
        </div>
        <!-- Hidden form for logout -->
        <form id="logoutForm" method="post" action="">
            <input type="hidden" name="disconnect">
        </form>

        <div>
        <?php
        // Connexion à la base de données
        include "bd.php";

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_num'])) {
            // Retrieve the numero to delete from the form
            $deleteNum = $_POST['delete_num'];

            // Prepare and execute the delete query
            $stmt = $con->prepare("DELETE FROM journal WHERE numero = ?");
            $stmt->bind_param("s", $deleteNum); // Bind the 'numero' to the query

            if ($stmt->execute()) {
                // Redirect to refresh the page after deletion
                header("Location: page_admin_sans_admin.php?message=Rapport supprimé avec succès.");
                exit;
            } else {
                echo "Erreur lors de la suppression du rapport : " . $stmt->error;
            }

            $stmt->close();
        }

        // Default sorting column and order
        $classer = isset($_POST['classer']) ? $_POST['classer'] : 'DateJournal';
        $ordre = isset($_POST['ordre']) ? $_POST['ordre'] : 'DESC';

        // Sanitize the input to prevent SQL injection
        $classer = mysqli_real_escape_string($con, $classer);
        $ordre = mysqli_real_escape_string($con, $ordre);

        // Requête pour récupérer les informations des rapports avec une jointure entre acces_etu et journal
        $sql = "SELECT 
                acces_etu.nomsup,
                journal.numero,
                journal.commentaire,
                journal.NomEtu, 
                journal.DateJournal, 
                journal.numero, 
                acces_employeurs.`Nom de l'employeur` AS NomEmployeur,
                acces_employeurs.`Nom de l'entreprise` AS NomEntreprise
            FROM acces_etu
            INNER JOIN journal ON journal.numetu = acces_etu.numetu
            INNER JOIN acces_employeurs ON acces_employeurs.noemployeur = acces_etu.noemployeur
            ORDER BY $classer $ordre"; // Dynamically order based on user selection

        $result = mysqli_query($con, $sql);

        // Vérifier s'il y a des lignes récupérées pour les rapports
        if ($result && mysqli_num_rows($result) > 0) {
        ?>
        <div class="tableau">
            <h2 class="tous">Rapport d'étape</h2>
            <h5 class="tous">Liste des rapports complétés</h5>
            <form class="tous" method="POST" action="">
                Classer par 
                <select name="classer">
                    <option value="nometu" <?php echo ($classer == 'nometu') ? 'selected' : ''; ?>>Nom d'Étudiant</option>
                    <option value="nomsup" <?php echo ($classer == 'nomsup') ? 'selected' : ''; ?>>Superviseur</option>
                    <option value="DateJournal" <?php echo ($classer == 'DateJournal') ? 'selected' : ''; ?>>Date</option>
                </select> 
                en ordre 
                <select name="ordre">
                    <option value="ASC" <?php echo ($ordre == 'ASC') ? 'selected' : ''; ?>>Ascendant</option>
                    <option value="DESC" <?php echo ($ordre == 'DESC') ? 'selected' : ''; ?>>Descendant</option> 
                </select> 
                <input type="submit" class="submit mini" value="Classer">
            </form>

            <table class="tous" style="border-collapse: collapse; width: 70%;">
                <tr>
                    <th style="border: 1px solid black; padding: 8px; text-align: center;">Date</th>
                    <th style="border: 1px solid black; padding: 8px;">Stagiaire</th>
                    <th style="border: 1px solid black; padding: 8px;">Superviseur</th>
                    <th style="border: 1px solid black; padding: 8px;">Employeur</th>
                    <th style="border: 1px solid black; padding: 8px; text-align: center;">Actions</th>
                </tr>
                <?php
                // Itérer à travers le jeu de résultats
                while ($row = mysqli_fetch_assoc($result)) {
                    $rapport_etape_link = "rapport_etape_modifier.php?num=" . htmlspecialchars($row['numero']);
                    $commentaire = $row['commentaire'];
                    ?>
                    <tr>
                        <td style="border: 1px solid black; padding: 8px; text-align: center;"><?php echo htmlspecialchars($row['DateJournal']); ?></td>
                        <td style="border: 1px solid black; padding: 8px;"><?php echo htmlspecialchars($row['NomEtu']); ?></td>
                        <td style="border: 1px solid black; padding: 8px;"><?php echo htmlspecialchars($row['nomsup']); ?></td>
                        <td style="border: 1px solid black; padding: 8px;">
                            <?php echo htmlspecialchars($row['NomEmployeur']) . " - " . htmlspecialchars($row['NomEntreprise']); ?>
                        </td>
                        <td style="border: 1px solid black; padding: 8px; text-align: center;">
                            <a href="<?php echo $rapport_etape_link; ?>" title="Voir le rapport d'étape">
                                <img src="img/voir.png" width="20" height="20" alt="Voir le rapport" style="vertical-align: middle; margin-right: 8px;">
                            </a>

                            <a href="<?php echo $rapport_etape_link; ?>" title="Modifier un commentaire">
                                <?php
                                if (empty($commentaire)) {
                                    // If the commentaire is empty, show 'no comment' icon
                                    echo '<img src="img/icon_nocomment.png" width="20" height="20" alt="Aucun commentaire" style="vertical-align: middle; margin-right: 8px;">';
                                } else {
                                    // If there is a commentaire, show 'comment' icon
                                    echo '<img src="img/icon_comment.png" width="20" height="20" alt="Modifier le rapport" style="vertical-align: middle; margin-right: 8px;">';
                                }
                                ?>
                            </a>

                            <!-- Formulaire de suppression -->
                            <form action="page_admin_sans_admin.php" method="POST" style="display:inline;">
                                <input type="hidden" name="delete_num" value="<?php echo $row['numero']; ?>" />
                                <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce rapport ?');">
                                    <img src="img/supprimer.png" width="20" height="20" alt="Supprimer le rapport" style="vertical-align: middle;">
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>
        <?php
        } else {
            echo "<p>Aucun rapport trouvé.</p>";
        }
        ?>

        </div>
    </div>

    <script>
        document.getElementById('disconnect').addEventListener('click', function() {
            // Submit the form when the "Déconnecter" li element is clicked
            document.getElementById('logoutForm').submit();
        });
    </script>

</body>
</html>
<!-- Add the disconnect button -->
