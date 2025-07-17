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
            margin-top: 10px;
            margin-left: 30px;
        }

        input[type="submit"]:hover, input[type="reset"]:hover {
            background-color: #0056b3;
        }
        .tableau {
            background-color: #dadada;
            padding: 50px;
            width: 70%;
            margin: 0 auto; /* Centers the container horizontally */
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
    $nomDeLEntreprise = isset($_POST['Nomdelentreprise']) ? trim($_POST['Nomdelentreprise']) : '';
    $motpasse = isset($_POST['motpasse']) ? trim($_POST['motpasse']) : '';
    $motpasse2 = isset($_POST['motpasse2']) ? trim($_POST['motpasse2']) : '';
    
   

    // Validate the form data
    if (empty($nomuser) || empty($numuser) || empty($nomDeLEntreprise) || empty($motpasse) || empty($motpasse2)) {
        $message = 'Tous les champs sont requis.';
    } elseif ($motpasse !== $motpasse2) {
        $message = 'Les mots de passe ne correspondent pas.';
    } else {
        // Check if student number already exists
        $query = "SELECT * FROM acces_employeurs WHERE noemployeur = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 's', $numuser);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $message = 'Le numéro du superviseur existe déjà.';
        } else {
            // Insert the new student into the database
            $query = "INSERT INTO acces_employeurs (`Nom de l'employeur`, noemployeur, `Nom de l'entreprise`, mdpemployeur) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt, 'ssss', $nomuser, $numuser, $nomDeLEntreprise, $motpasse);

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

        
        
        <?php
include "bd.php"; // Include your database connection

// Fetch list of students (stagiaires)
$stagiaires_query = "SELECT numetu, nometu FROM acces_etu";
$stagiaires_result = mysqli_query($con, $stagiaires_query);

// Fetch list of supervisors (superviseurs)
$superviseurs_query = "SELECT noemploye, nomsup FROM acces_adm";
$superviseurs_result = mysqli_query($con, $superviseurs_query);

// Initialize variables for existing values
$current_nometu = '';
$current_nomsuperviseur = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $nometu = $_POST['nometu'] ?? '';
    $nomsuperviseur = $_POST['nomsuperviseur'] ?? '';

    // Validate the form data
    if (empty($nometu) || empty($nomsuperviseur)) {
        $message = 'Tous les champs sont requis.';
    } else {
        // Update the data into the database
        $query = "UPDATE acces_etu SET nomsup = ? WHERE numetu = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 'ss', $nomsuperviseur, $nometu);

        if (mysqli_stmt_execute($stmt)) {
            $message = 'Étudiant associé à un superviseur avec succès !';
        } else {
            $message = 'Erreur lors de l\'association de l\'étudiant : ' . mysqli_error($con);
        }

        mysqli_stmt_close($stmt);
    }
} else if (isset($_GET['numetu'])) {
    // Fetch current data when the student is selected
    $nometu = $_GET['numetu'];
    $query = "SELECT numetu, nomsup FROM acces_etu WHERE numetu = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 's', $nometu);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $current_nometu, $current_nomsuperviseur);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

mysqli_close($con);
?>

<div class="tableau">
    <h2>Associer un étudiant à un superviseur</h2>

    <?php if (!empty($message)): ?>
        <p style="color: <?php echo (strpos($message, 'succès') !== false) ? 'green' : 'red'; ?>;"><?php echo $message; ?></p>
    <?php endif; ?>

    <form action="admin_associer_superviseur.php" method="POST" id="createStudentForm">
        <div style="float:left; margin-left:30px;">
            <label for="nometu">Choisir un stagiaire :</label><br />
            <select id="nometu" name="nometu" style="float:left; margin-bottom:19px;">
                <option value="" disabled selected>---Choisir un stagiaire---</option>
                <?php while ($row = mysqli_fetch_assoc($stagiaires_result)): ?>
                    <option value="<?php echo htmlspecialchars($row['numetu']); ?>"
                        <?php echo ($row['numetu'] == $current_nometu) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($row['nometu']); ?>
                    </option>
                <?php endwhile; ?>
            </select><br />

            <label for="nomsuperviseur">Associer un superviseur :</label><br />
            <select id="nomsuperviseur" name="nomsuperviseur" style="float:left; margin-bottom:19px;">
                <option value="" disabled selected>---Choisir un superviseur---</option>
                <?php while ($row = mysqli_fetch_assoc($superviseurs_result)): ?>
                    <option value="<?php echo htmlspecialchars($row['nomsup']); ?>"
                        <?php echo ($row['nomsup'] == $current_nomsuperviseur) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($row['nomsup']); ?>
                    </option>
                <?php endwhile; ?>
            </select><br />
        </div>
        <div style="clear:both;"></div>
        <input type="submit" value="Associer" /><br />
    </form>
</div>

<div>
    <br>

    <?php
    include "bd.php";

    // Corrected SQL query
    $sql = "SELECT nometu, nomsup FROM acces_etu";
    $result = mysqli_query($con, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
    ?>
    <div class="tableau">
    <h2>Liste des stagiaires</h2>
        <table style="border-collapse: collapse; width: 70%; margin: 0 auto;">
            
            <thead>
                <tr>
                    <th style="border: 1px solid black; padding: 8px; text-align: center;">Stagiaire</th>
                    <th style="border: 1px solid black; padding: 8px; text-align: center;">Superviseur</th>
                </tr>
            </thead>
            <tbody>
            <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td style='border: 1px solid black; padding: 8px; text-align: center;'>" . htmlspecialchars($row['nometu']) . "</td>
                            <td style='border: 1px solid black; padding: 8px; text-align: center;'>" . htmlspecialchars($row['nomsup']) . "</td>
                        </tr>";
                }
            ?>

            </tbody>
        </table>
    </div>
    <?php
    } else {
        echo "<p style='text-align: center; color: red;'>Aucun stagiaire trouvé.</p>";
    }

    mysqli_close($con);
    ?>
</div>
</body>

</html>