<?php
ob_start();
session_start();

include "bd.php"; // Database connection

// Check if the user is not logged in
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Handle disconnection
if (isset($_POST['disconnect'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}

// Set welcome message
$welcome_message = isset($_SESSION['welcome_message']) ? $_SESSION['welcome_message'] : '';

// Message variable for form feedback
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data and sanitize
    $milieu = isset($_POST['milieu']) ? $_POST['milieu'] : '';
    $latitude = isset($_POST['latitude']) ? trim($_POST['latitude']) : '';
    $longitude = isset($_POST['longitude']) ? trim($_POST['longitude']) : '';
    $adresse = isset($_POST['adresse']) ? trim($_POST['adresse']) : '';
    $tel = isset($_POST['tel']) ? trim($_POST['tel']) : '';

    // Validate input
    if (empty($latitude) || empty($longitude) || empty($adresse) || empty($tel)) {
        $message = 'Tous les champs sont requis.';
    } else {
        // Generate the Google Maps link using the coordinates
        $googlemap_link = "https://www.google.com/maps?q=" . $latitude . "," . $longitude;

        // Assuming you have a database connection
        include "bd.php"; // Database connection

        // Insert the Google Maps link into the database for the specified employer
        $sql = "UPDATE acces_employeurs SET googlemap = ? WHERE noemployeur = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ss", $googlemap_link, $milieu); // "s" for string
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "";
        } else {
            echo "";
        }

        // Parse the address using a regular expression
        $pattern = '/^(\d+)\s+([A-Za-zÀ-ÿ0-9\s\-]+),\s+([A-Za-zÀ-ÿ\s\-]+),\s+([A-Za-z]{2})\s+([A-Za-z]\d[A-Za-z]\s?\d[A-Za-z]\d)$/';

        if (preg_match($pattern, $adresse, $matches)) {
            $nocivique = $matches[1];
            $rue = $matches[2];
            $ville = $matches[3];
            $province = $matches[4];
            $code_postal = $matches[5];
        } else {
            $message = 'Adresse invalide.';
            exit;
        }

        // Check if the employer exists in the database
        $query = "SELECT * FROM acces_employeurs WHERE noemployeur = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 's', $milieu);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // If the employer exists, update the record
        if (mysqli_num_rows($result) > 0) {
            $query = "UPDATE acces_employeurs 
                      SET latitude = ?, longitude = ?, nocivique = ?, rue = ?, ville = ?, province = ?, codepostal = ?, numtelephone = ? 
                      WHERE noemployeur = ?";
            $stmt = mysqli_prepare($con, $query);

            // Ensure the right number of variables are passed here
            mysqli_stmt_bind_param($stmt, 'sssssssss', 
                $latitude, $longitude, $nocivique, $rue, $ville, $province, $code_postal, $tel, $milieu);

            if (mysqli_stmt_execute($stmt)) {
                $message = 'Employeur mis à jour avec succès !';
            } else {
                $message = 'Erreur lors de la mise à jour de l\'employeur : ' . mysqli_error($con);
            }
        } else {
            // If the employer doesn't exist, create a new record
            $query = "INSERT INTO acces_employeurs (noemployeur, latitude, longitude, nocivique, rue, ville, province, codepostal, numtelephone) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt, 'sssssssss', $milieu, $latitude, $longitude, $nocivique, $rue, $ville, $province, $code_postal, $tel);

            if (mysqli_stmt_execute($stmt)) {
                $message = 'Employeur créé avec succès !';
            } else {
                $message = 'Erreur lors de la création de l\'employeur : ' . mysqli_error($con);
            }
        }

        // Close the statement and database connection
        mysqli_stmt_close($stmt);
        mysqli_close($con);
    }
}


?>



<!-- Rest of your page content goes here -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer le Google Map</title>
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
        .sub-menu {
            color: black !important;
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
        .questions{
            margin-bottom:20px;
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
        <h2>Gérer le Google Map</h2>
        <h4><strong>Associer un milieu de stage au Google Map</strong></h4>
        <?php if (!empty($message)): ?>
            <p style="color: <?php echo (strpos($message, 'succès') !== false) ? 'green' : 'red'; ?>;"><?php echo $message; ?></p>
        <?php endif; ?>
        <form action="admin_googlemap.php" method="post">
            <label>Choisir un milieu de stage</label><br />
            <select id="milieu" name="milieu">
                <?php
                    // Connexion à la base de données
                    include "bd.php";

                    // Requête pour récupérer les employeurs et leurs entreprises
                    $sql = "SELECT noemployeur, `Nom de l'employeur`, `Nom de l'entreprise` FROM acces_employeurs";
                    $result = mysqli_query($con, $sql);

                    // Vérifier s'il y a des employeurs dans la base de données
                    if ($result && mysqli_num_rows($result) > 0) {
                        // Parcourir chaque employeur et afficher dans le select
                        while ($row = mysqli_fetch_assoc($result)) {
                            // Afficher chaque option avec le 'noemployeur' comme valeur et le nom de l'entreprise comme texte
                            echo '<option value="' . htmlspecialchars($row['noemployeur']) . '">' . htmlspecialchars($row['Nom de l\'employeur']) . ' - ' . htmlspecialchars($row['Nom de l\'entreprise']) . '</option>';
                        }
                    } else {
                        // Option si aucune donnée n'est trouvée
                        echo '<option value="">Aucun employeur disponible</option>';
                    }
                ?>
            </select><br />


            <label for="latitude">Latitude Google Map</label><br />
            <input type="text" id="latitude" name="latitude" placeholder="Latitude" /><br />

            <label for="longitude">Longitude Google Map</label><br />
            <input type="text" id="longitude" name="longitude" placeholder="Longitude" /><br />

            <label for="adresse">Adresse</label><br />
            <textarea id="adresse" name="adresse" placeholder="Adresse complète"></textarea><br />

            <label for="tel">Numéro de téléphone</label><br />
            <input 
                type="text" 
                id="tel" 
                name="tel" 
                placeholder="819-111-5555" 
                maxlength="12" 
                oninput="formatPhoneNumber(this)" 
            /><br />
            <script>
                function formatPhoneNumber(input) {
                    // Remove all non-numeric characters
                    let value = input.value.replace(/\D/g, '');

                    // Add formatting as the user types
                    if (value.length > 3 && value.length <= 6) {
                        // Format as 819-111
                        value = value.replace(/^(\d{3})(\d{0,3})/, '$1-$2');
                    } else if (value.length > 6) {
                        // Format as 819-111-5555
                        value = value.replace(/^(\d{3})(\d{3})(\d{0,4})/, '$1-$2-$3');
                    }

                    // Update the input value
                    input.value = value;
                }
            </script>
       
            <input type="submit" value="Envoyer" />
        </form>
    </div>
        
        
        



        <script>
            // Client-side validation
            function validateForm() {
                var latitude = document.getElementById("latitude").value;
                var longitude = document.getElementById("longitude").value;
                var adresse = document.getElementById("adresse").value;
                var tel = document.getElementById("tel").value;

                // Validate name (should not be empty)
                if (latitude == "") {
                    alert("Le nom de l'employeur est requis.");
                    return false;
                }
                
                // Validate info number (should be 7 digits)
                // Validate name (should not be empty)
                if (longitude == "") {
                    alert("Le nom de l'employeur est requis.");
                    return false;
                }

                if (adresse == "") {
                    alert("Le nom de l'employeur est requis.");
                    return false;
                }

                if (tel == "") {
                    alert("Le nom de l'employeur est requis.");
                    return false;
                }
                
                // Validate select fields
                if (milieu == "nepasassocier" || superviseur == "nepasassocier") {
                    alert("Veuillez associer un milieu et un superviseur.");
                    return false;
                }

                return true; // If all validations pass
            }
        </script>

        <div>
            <br>

            <?php
                // Connexion à la base de données
                include "bd.php";

                // Requête sécurisée pour récupérer les informations des rapports
                $sql = "SELECT 
                    acces_etu.numetu, 
                    acces_etu.nometu, 
                    acces_etu.noemployeur,
                    acces_employeurs.`Nom de l'entreprise`,
                    acces_employeurs.numtelephone,
                    acces_employeurs.latitude,
                    acces_employeurs.longitude
                FROM acces_etu
                INNER JOIN acces_employeurs 
                ON acces_etu.noemployeur = acces_employeurs.noemployeur";  // Proper quoting of column name

                $result = mysqli_query($con, $sql);

                // Vérifier s'il y a des lignes récupérées pour les rapports
                if ($result && mysqli_num_rows($result) > 0) {
            ?>
                <div class="tableau">
                    <h2>Liste des stagiaires avec leurs milieux de stage</h2>

                    <table style="border-collapse: collapse; width: 70%; margin: 0 auto;">
                        <thead>
                            <tr>
                                <th style="border: 1px solid black; padding: 8px; text-align: center;">Stagiaire</th>
                                <th style="border: 1px solid black; padding: 8px;">Milieu de stage</th>
                                <th style="border: 1px solid black; padding: 8px;">Téléphone</th>
                                <th style="border: 1px solid black; padding: 8px;">Coordonnées sur Googlemap</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch rows one by one
                            while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                <tr>
                                    <td style="border: 1px solid black; padding: 8px; text-align: center;">
                                        <?php echo htmlspecialchars($row['nometu']); ?>
                                    </td>
                                    <td style="border: 1px solid black; padding: 8px;">
                                        <?php echo htmlspecialchars($row['Nom de l\'entreprise']); ?>
                                    </td>
                                    <td style="border: 1px solid black; padding: 8px;">
                                        <?php echo htmlspecialchars($row['numtelephone']); ?>
                                    </td>
                                    <td style="border: 1px solid black; padding: 8px;">
                                        <?php echo htmlspecialchars($row['latitude']); ?>, <?php echo htmlspecialchars($row['longitude']); ?>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php
                }
                else {
                    echo "<p>Aucun stagiaire trouvé.</p>";
                }
            ?>
        </div>

</html>