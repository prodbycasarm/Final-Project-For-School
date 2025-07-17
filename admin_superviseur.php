<?php
ob_start();
session_start();

include "bd.php";
// Check if the user is not logged in
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
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
    <title>Rapports de Stage - 2023 Superviseur1</title>
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

        .titre h1 {
            color: white;
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
            width: 10%;
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
        .questions{
                margin-bottom:20px;
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
        .sub-menu {
            color: black !important;
        }
        .tableau {
                background-color: #dadada;
                padding: 50px;
                width: 70%;
                margin: 0 auto; /* Centers the container horizontally */
            }
    </style>
    
   
</head>

<?php

include "bd.php"; // Make sure to include your database connection

// Declare a variable to store the message (success or error)
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data from the POST request
    $nomuser = isset($_POST['nomuser']) ? trim($_POST['nomuser']) : '';
    $numuser = isset($_POST['numuser']) ? trim($_POST['numuser']) : '';
    $courriel = isset($_POST['courriel']) ? trim($_POST['courriel']) : '';
    $motpasse = isset($_POST['motpasse']) ? trim($_POST['motpasse']) : '';
    $motpasse2 = isset($_POST['motpasse2']) ? trim($_POST['motpasse2']) : '';
    
   

    // Validate the form data
    if (empty($nomuser) || empty($numuser) || empty($courriel) || empty($motpasse) || empty($motpasse2)) {
        $message = 'Tous les champs sont requis.';
    } elseif ($motpasse !== $motpasse2) {
        $message = 'Les mots de passe ne correspondent pas.';
    } else {
        // Check if student number already exists
        $query = "SELECT * FROM acces_adm WHERE noemploye = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 's', $numuser);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $message = 'Le numéro du superviseur existe déjà.';
        } else {
            // Insert the new student into the database
            $query = "INSERT INTO acces_adm (nomsup, noemploye, courriel, motdepasse) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt, 'ssss', $nomuser, $numuser, $courriel, $motpasse);

            if (mysqli_stmt_execute($stmt)) {
                $message = 'Le superviseur à été créé avec succès !';
            } else {
                $message = 'Erreur lors de la création du superviseur : ' . mysqli_error($con);
            }

            mysqli_stmt_close($stmt);
        }

        mysqli_close($con);
    }
}
?>

<body>
    <div class="main">
    <nav>
            <div class="menu">
                <div class="titre"><h1 href="#">Stage technique d'intégration multimédia</h1></div>
                <ul class="nav-links-menu">
                    <li style="color:white; font-size:18px; padding-left:30px;">
                    <?php echo $welcome_message; ?> <!-- Nom du superviseur -->
                    </li>
                    <li><a href="page_admin.php">Accueil</a></li>
                    <li><a href="admin.php">Administration</a></li>
                    <li><a href="Googlemap/googlemaps.php">Google maps</a></li>
                    <li id="disconnect"><a href="index.php">Déconnecter</a></li>
                </ul>
            </div>
        </nav>

        <div class="header">
            <div class="wrapper">
                <ul class="nav-links">
                    <li><a class="sub-menu" href="page_admin.php">Rapports d'étape</a></li>
                    <li><a class="sub-menu" href="liste_des_stagiaires.php">Évaluations des Stagiaires</a></li>
                </ul> 
            </div>
        </div>

        <!-- Hidden form for logout -->
        <form id="logoutForm" method="post" action="">
            <input type="hidden" name="disconnect">
        </form>
        
        <div class="tableau">
            <h2>Créer un superviseur</h2>
            
            <form class="tous" id="createStudentForm" action="admin_superviseur.php" method="post" onsubmit="return validateForm()">
            <?php if (!empty($message)): ?>
                <p style="color: <?php echo (strpos($message, 'succès') !== false) ? 'green' : 'red'; ?>;"><?php echo $message; ?></p>
            <?php endif; ?> 
                <div style="float:left;">
                    <label for="nomuser">Nom du superviseur</label><br />
                    <input class="questions" type="text" id="nomuser" name="nomuser" value="" placeholder="Prénom et nom" maxlength="35" /><br />
                    <label for="numuser">Numéro du superviseur</label><br />
                    <input class="questions" type="text" id="numuser" name="numuser" value="" placeholder="#####" maxlength="5" /><br />
                    <label for="courriel">Courriel</label><br />
                    <input class="questions" type="email" id="courriel" name="courriel" value="" placeholder="abc@exemple.com"  /><br />
                    
                    <label for="motpasse">Mot de passe</label><br />
                    <input class="questions" placeholder="ex:Chaise123" type="password" id="motpasse" name="motpasse" value="" maxlength="25" /><br />
                    <label for="motpasse2">Confirmer le mot de passe</label><br />
                    <input class="questions" placeholder="ex:Chaise123" type="password" id="motpasse2" name="motpasse2" value="" maxlength="25" /><br />
                </div>
                
               
                <div style="clear:both;"></div>
                
                <input type="submit" id="envoyer" name="envoyer" value="Créer" /><br />
            </form>
        </div>
        
        
        



        <script>
            // Client-side validation
            function validateForm() {
                var nomuser = document.getElementById("nomuser").value;
                var numuser = document.getElementById("numuser").value;
                var courriel = document.getElementById("courriel").value;
                var motpasse = document.getElementById("motpasse").value;
                var motpasse2 = document.getElementById("motpasse2").value;
                
                

                // Validate name (should not be empty)
                if (nomuser == "") {
                    alert("Le nom de l'étudiant est requis.");
                    return false;
                }

                // Validate student number (should be 5 digits)
                var numuserPattern = /^[0-9]{5}$/;
                if (!numuser.match(numuserPattern)) {
                    alert("Le numéro de l'étudiant doit être constitué de 7 chiffres.");
                    return false;
                }

                // Validate password (should not be empty and match)
                if (motpasse == "" || motpasse2 == "") {
                    alert("Les mots de passe ne doivent pas être vides.");
                    return false;
                }

                if (motpasse !== motpasse2) {
                    alert("Les mots de passe ne correspondent pas.");
                    return false;
                }

                

                return true; // If all validations pass
            }
        </script>

       
        
            <br>
        <?php
            // Connexion à la base de données
            include "bd.php";

            // Requête sécurisée pour récupérer les informations des rapports
            $sql = "SELECT nomsup, noemploye, courriel, motdepasse FROM acces_adm";
            $result = mysqli_query($con, $sql);

            // Vérifier s'il y a des lignes récupérées pour les rapports
            if ($result && mysqli_num_rows($result) > 0) {
        ?>
            <div class="tableau">
                
            <h2>Modifier/Supprimer un superviseur</h2>
                <table style="border-collapse: collapse; width: 70%; margin: 0 auto;">
                    <thead>
                        <tr>
                            <th style="border: 1px solid black; padding: 8px; text-align: center;">Numéro du superviseur</th>
                            <th style="border: 1px solid black; padding: 8px;">Nom du superviseur</th>
                            <th style="border: 1px solid black; padding: 8px;">Courriel</th>
                            <th style="border: 1px solid black; padding: 8px;">Mot de passe</th>
                            <th style="border: 1px solid black; padding: 8px; text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Itérer à travers le jeu de résultats
                        while ($row = mysqli_fetch_assoc($result)) {
                            // Dynamically generate URLs for 'view' and 'delete' actions
                            $modifier_link = "modifier_superviseur.php?num=" . urlencode($row['noemploye']);
                            $delete_link = "supprimer_superviseur.php?num=" . urlencode($row['noemploye']);
                        ?>
                            <tr>
                                <td style="border: 1px solid black; padding: 8px; text-align: center;">
                                    <?php echo htmlspecialchars($row['noemploye']); ?>
                                </td>
                                <td style="border: 1px solid black; padding: 8px;">
                                    <?php echo htmlspecialchars($row['nomsup']); ?>
                                </td>
                                <td style="border: 1px solid black; padding: 8px;">
                                    <?php echo htmlspecialchars($row['courriel']); ?>
                                </td>
                                <td style="border: 1px solid black; padding: 8px;">
                                    <?php echo htmlspecialchars($row['motdepasse']); ?>
                                </td>
                                <td style="border: 1px solid black; padding: 8px; text-align: center;">
                                    <!-- Lien pour voir le rapport -->
                                    <a href="<?php echo $modifier_link; ?>" title="Modifier le superviseur " >
                                        Modifier
                                    </a>

                                    <!-- Lien pour supprimer le rapport -->
                                    <a href="<?php echo $delete_link; ?>" title="Supprimer le superviseur" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet superviseur ?');">
                                        Supprimer
                                    </a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        <?php
            } else {
                echo "<p>Aucun rapport trouvé.</p>";
            }
        ?>
        </div>
    </div>

</html>