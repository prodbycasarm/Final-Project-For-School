<?php
ob_start();
session_start();

include "bd.php"; // Assuming this file contains your database connection
// Check if the user is not an admin or not logged in
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'employeur') {

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
$welcome_message = isset($_SESSION['welcome_message']) ? $_SESSION['welcome_message'] : '';

$userid2 = isset($_SESSION['user_id2']) ? $_SESSION['user_id2'] : '';






?>
<!-- Rest of your page content goes here -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashbord Employeur</title>
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
        .header {
            background-color: #333;
            color: #fff;
            padding: 20px 0;
            text-align: center;
        }
        .menu {
            background-color: #444;
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
        .menu li {
            margin-right: 10px; /* Add spacing between menu items */
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
        
        p{
            padding-top: 10px;
            padding-bottom: 10px;
        }
        h3{
            padding-top: 10px;
            padding-bottom: 10px;
        }
        .form-group{
            padding-top: 10px;
            padding-bottom: 10px;
        }
        .text {
            display: flex;
            justify-content: space-between;
            align-items: center; /* Aligns items vertically in the center */
        }

        .text h2 {
                margin: 0; /* Remove any default margin if necessary */
                font-size:30px;
            }

        .text .text1 {
                margin: 0; /* Remove any default margin if necessary */
                text-align: right;
                font-weight: bold;
            }
            .special-hr{
                margin-top: 10px;
                margin-bottom: 40px;
            }
            .bullet_point ul {
                padding-left: 20px; /* Adds indentation to the entire list */
            }

            .bullet_point li {
                text-indent: -10px; /* Optional: further control bullet position */
                padding-left: 10px; /* Aligns the text with the bullet */
            }

            /* Container for rows */
            .row {
                display: flex;
                flex-wrap: wrap; /* Allows columns to wrap into new rows */
                margin: 0 -15px; /* Optional: adjusts for column padding */
            }

            /* Column styling */
            [class*="col-"] {
                padding: 0 15px; /* Optional: space between columns */
                box-sizing: border-box;
            }

            /* General reset and styling for form elements */


            fieldset {
                border: 1px solid #000;
                padding: 50px;
                margin-bottom: 20px;
            }

            textarea {
                width: 70%;
                padding: 10px;
                margin-top: 20px;
                border-radius: 4px;
                border: 1px solid #ddd;
                resize: vertical; /* Allows resizing only vertically */
            }

            /* Column widths in 5% increments */
            .col-5  { flex: 0 0 5%;  max-width: 5%; }
            .col-10 { flex: 0 0 10%; max-width: 10%; }
            .col-15 { flex: 0 0 15%; max-width: 15%; }
            .col-20 { flex: 0 0 20%; max-width: 20%; }
            .col-25 { flex: 0 0 25%; max-width: 25%; }
            .col-30 { flex: 0 0 30%; max-width: 30%; }
            .col-35 { flex: 0 0 35%; max-width: 35%; }
            .col-40 { flex: 0 0 40%; max-width: 40%; }
            .col-45 { flex: 0 0 45%; max-width: 45%; }
            .col-50 { flex: 0 0 50%; max-width: 50%; }
            .col-55 { flex: 0 0 55%; max-width: 55%; }
            .col-60 { flex: 0 0 60%; max-width: 60%; }
            .col-65 { flex: 0 0 65%; max-width: 65%; }
            .col-70 { flex: 0 0 70%; max-width: 70%; }
            .col-75 { flex: 0 0 75%; max-width: 75%; }
            .col-80 { flex: 0 0 80%; max-width: 80%; }
            .col-85 { flex: 0 0 85%; max-width: 85%; }
            .col-90 { flex: 0 0 90%; max-width: 90%; }
            .col-95 { flex: 0 0 95%; max-width: 95%; }
            .col-100 { flex: 0 0 100%; max-width: 100%; }

            /* Responsive stacking on small screens */
            @media (max-width: 768px) {
                [class*="col-"] {
                    flex: 0 0 100%; /* Full width on small screens */
                    max-width: 100%;
                }
            }
    </style>
    
   
</head>
<body>
<div class="main">
<nav>
    <div class="wrapper">
        <div class="titre"><h1 href="#">Stage technique d'intégration multimédia</h1></div>
        <ul class="nav-links">
        <label for="close-btn" class="btn close-btn"><i class="fas fa-times"></i></label>
        <li style="color:white; font-size:18px;"><?php echo $welcome_message; ?> | </li>
        <li><a href="page_employeur.php">Accueil</a></li>
        <li id="disconnect"><a href="index.php">Déconnecter</a></li>
        </ul>
        <label for="menu-btn" class="btn menu-btn"><i class="fas fa-bars"></i></label>
    </div>
    </nav>


    <div class="header">
        <div class="wrapper">
            <ul class="nav-links">
                <li><a class="sub-menu" href="page_employeur">Liste des stagiaires</a></li>

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

        // Vérifier si la session contient un ID utilisateur valide
        if (isset($_SESSION['user_id2'])) {
            $user_id = $_SESSION['user_id2'];

            // Requête sécurisée avec jointure et filtre par noemployeur
            $stmt = $con->prepare("
                SELECT 
                    acces_etu.numetu,
                    acces_etu.nometu,
                    acces_etu.nomsup,
                    acces_etu.noemployeur,
                    acces_employeurs.noemployeur
                FROM acces_etu
                INNER JOIN acces_employeurs ON acces_employeurs.noemployeur = acces_etu.noemployeur
                WHERE acces_employeurs.noemployeur = ?
            ");

            // Lier l'ID utilisateur à la requête
            $stmt->bind_param("s", $user_id);

            // Exécuter la requête
            $stmt->execute();

            // Récupérer les résultats
            $result = $stmt->get_result();

            // Vérifier s'il y a des lignes récupérées pour les rapports
            if ($result && $result->num_rows > 0) {

        ?>
            <div class="tableau">
                <h2 class="tous">Liste des stagiaires</h2>
                <h5 class="tous">Voici la liste de tous les stagiaires à évaluer.</h5>

                <table class="tous" style="border-collapse: collapse; width: 70%;">
                    <tr>
                        <th style="border: 1px solid black; padding: 8px; text-align: center;">Stagiaire</th>
                        <th style="border: 1px solid black; padding: 8px;">Superviseur</th>
                        <th style="border: 1px solid black; padding: 8px; text-align: center;">Actions</th>
                    </tr>
                    <?php
                    // Itérer à travers le jeu de résultats
                    while ($row = $result->fetch_assoc()) {
                        $evaluer_stagiaire = "evaluer_stagiaire.php?num=" . htmlspecialchars($row['numetu']);
                        $afficher_evaluation = "evaluations/stagiaires/" . htmlspecialchars($row['numetu']) . ".html";

                        // Check if the evaluation file exists
                        $evaluation_exists = file_exists($afficher_evaluation);
                    ?>
                    <tr>
                        <td style="border: 1px solid black; padding: 8px;"><?php echo htmlspecialchars($row['nometu']); ?></td>
                        <td style="border: 1px solid black; padding: 8px;"><?php echo htmlspecialchars($row['nomsup']); ?></td>
                        
                        
                        <td style="border: 1px solid black; padding: 8px; text-align: center;">
                        <?php
                        if ($evaluation_exists) {
                            echo '<a href="' . $afficher_evaluation . '" title="Afficher les détails">Afficher</a>';
                        } else {
                            echo '<a href="' . $evaluer_stagiaire . '" title="Évaluer le stagiaire">Évaluer</a>';
                        }
                        ?>
                            
                            
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
                </table>
            </div>
        <?php
            } else {
                echo "<p>Aucun stagiaire trouvé pour cet employeur.</p>";
            }

            // Fermer la déclaration préparée
            $stmt->close();
        } else {
            echo "<p>Session invalide. Veuillez vous reconnecter.</p>";
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
