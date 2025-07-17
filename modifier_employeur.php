<?php
// Include database connection
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

// Check if a student number (superviseur ID) is passed as a query parameter
if (!isset($_GET['num'])) {
    echo "Aucun employeur sélectionné pour modification.";
    exit;
}

// Get the superviseur number from the query parameter
$noemployeur = $_GET['num'];

// Fetch the superviseur data to pre-fill the form
$sql = "SELECT `Nom de l'employeur`, `Nom de l'entreprise`, mdpemployeur FROM acces_employeurs WHERE noemployeur = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $noemployeur);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Employeur introuvable.";
    exit;
}

$row = $result->fetch_assoc();
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form inputs
    $nomemployeur = isset($_POST['nomsup']) ? trim($_POST['nomsup']) : '';
    $nomdelentreprise = isset($_POST['nomdelentreprise']) ? trim($_POST['nomdelentreprise']) : '';
    $motdepasse = isset($_POST['motpasse']) ? trim($_POST['motpasse']) : '';
    $motdepasse2 = isset($_POST['motpasse2']) ? trim($_POST['motpasse2']) : '';

    // Validate inputs
    if (empty($nomemployeur) || empty($nomdelentreprise)) {
        echo "Tous les champs obligatoires doivent être remplis.";
    } elseif ($motdepasse !== $motdepasse2) {
        echo "Les mots de passe ne correspondent pas.";
    } else {
        // If the password is not provided, keep the existing one
        if (empty($motdepasse)) {
            $motdepasse = $row['mdpemployeur'];
        }

        // Update the employeur data
        $update_sql = "UPDATE acces_employeurs 
                       SET `Nom de l'employeur` = ?, `Nom de l'entreprise` = ?, mdpemployeur = ?
                       WHERE noemployeur = ?";
        $stmt = $con->prepare($update_sql);
        $stmt->bind_param('ssss', $nomemployeur, $nomdelentreprise, $motdepasse, $noemployeur);

        if ($stmt->execute()) {
            header("Location: admin_employeur.php?message=success");
            exit;
        } else {
            echo "Erreur lors de la mise à jour : " . $stmt->error;
        }
        $stmt->close();
        
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un employeur</title>
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
<?php
// Set welcome message if it's not set
$welcome_message = isset($_SESSION['welcome_message']) ? $_SESSION['welcome_message'] : '';

?>

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
        <div class="tableau">
            <h2>Modifier un employeur</h2>
            <h4>Modifier l'employeur <?php echo htmlspecialchars($row['Nom de l\'employeur']); ?></h4>
            <form method="post" action="">
                <label for="nomsup">Nom de l'employeur*</label><br />
                <input type="text" id="nomsup" name="nomsup" value="<?php echo htmlspecialchars($row['Nom de l\'employeur']); ?>" required><br />

                <label for="nomdelentreprise">Nom de l'entreprise*</label><br />
                <input type="text" id="nomdelentreprise" name="nomdelentreprise" value="<?php echo htmlspecialchars($row['Nom de l\'entreprise']); ?>" required><br />

                <label for="motpasse">Mot de passe</label><br />
                <input type="password" id="motpasse" name="motpasse" maxlength="25"><br />
                <label for="motpasse2">Confirmer le mot de passe</label><br />
                <input type="password" id="motpasse2" name="motpasse2" maxlength="25"><br />

                <button type="submit">Modifier</button>
            </form>
        </div>
</div>    
</body>
</html>
