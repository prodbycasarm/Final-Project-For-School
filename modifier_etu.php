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
$nostagiaire = $_GET['num'];

// Fetch the superviseur data to pre-fill the form
$sql = "SELECT 
                acces_etu.numetu, 
                acces_etu.nometu, 
                acces_etu.nomsup, 
                acces_etu.noemployeur, 
                acces_etu.motdepasse,
                acces_employeurs.`Nom de l'employeur`,
                acces_employeurs.`Nom de l'entreprise`
            FROM acces_etu
            INNER JOIN acces_employeurs 
            ON acces_etu.noemployeur = acces_employeurs.noemployeur
            WHERE acces_etu.numetu = ?";  // Add a WHERE clause to filter by numetu

$stmt = $con->prepare($sql);
$stmt->bind_param("s", $nostagiaire);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Étudiant introuvable.";
    exit;
}

$row = $result->fetch_assoc();
$stmt->close();

// Fetch employeurs for the "milieu de stage" dropdown
$noemployeur_sql = "SELECT noemployeur, `Nom de l'employeur`, `Nom de l'entreprise` FROM acces_employeurs";
$noemployeur_result = $con->query($noemployeur_sql);

// Fetch superviseurs for the "superviseur" dropdown
$nomsup_sql = "SELECT noemploye, nomsup FROM acces_adm"; // Adjust table name if needed
$nomsup_result = $con->query($nomsup_sql);
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form inputs
    $nometu = isset($_POST['nometu']) ? trim($_POST['nometu']) : '';
    $nomsup = isset($_POST['nomsup']) ? trim($_POST['nomsup']) : '';  // Correct field name
    $noemployeur = isset($_POST['noemployeur']) ? trim($_POST['noemployeur']) : '';
    $motdepasse = isset($_POST['motdepasse']) ? trim($_POST['motdepasse']) : '';
    $motdepasse2 = isset($_POST['motdepasse2']) ? trim($_POST['motdepasse2']) : '';

    // Validate inputs
    if (empty($nometu)) {
        echo "Tous les champs obligatoires doivent être remplis.";
    } elseif ($motdepasse !== $motdepasse2) {
        echo "Les mots de passe ne correspondent pas.";
    } else {
        // If the password is not provided, keep the existing one
        if (empty($motdepasse)) {
            $motdepasse = $row['motdepasse']; // Use the current password if it's not provided
        }
        
        // Handle the case where no employer is selected
        if ($noemployeur === 'nepasassocier') {
            $noemployeur = null;  // If no employer is selected, set it as NULL or handle accordingly
        }
        
        // Handle the case where no supervisor is selected
        if ($nomsup === 'nepasassocier') {
            $nomsup = null;  // If no supervisor is selected, set it as NULL or handle accordingly
        }

        // Update the database
        $update_sql = "UPDATE acces_etu
               SET nometu = ?, nomsup = ?, noemployeur = ?, motdepasse = ? 
               WHERE numetu = ?";

        $stmt = $con->prepare($update_sql);
        $stmt->bind_param('sssss', $nometu, $nomsup, $noemployeur, $motdepasse, $nostagiaire);

        if ($stmt->execute()) {
            // Update the "NomEtu" field in the journal table
            $update_journal_sql = "UPDATE journal 
                                   SET NomEtu = ?, nomsup = ?
                                   WHERE numetu = ?";
            
            $journal_stmt = $con->prepare($update_journal_sql);
            $journal_stmt->bind_param('sss', $nometu, $nomsup, $nostagiaire);
        
            if ($journal_stmt->execute()) {
                header("Location: admin_stagiaire.php?message=success");
                exit;
            } else {
                echo "Erreur lors de la mise à jour dans la table journal : " . $journal_stmt->error;
            }
            $journal_stmt->close();
        } else {
            echo "Erreur lors de la mise à jour : " . $stmt->error;
        }
        $stmt->close();
    }
}

?>
<?php
// Set welcome message if it's not set
$welcome_message = isset($_SESSION['welcome_message']) ? $_SESSION['welcome_message'] : '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un stagiaire</title>
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


        .main {
            display: block;
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
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

        <div class="tableau">
            <h2>Modifier un stagiaire</h2>
            <h4>Modifier le stagiaire <?php echo htmlspecialchars($row['nometu']); ?></h4>
            <form method="post" action="">
                <label for="nometu">Nom du stagiaire*</label><br />
                <input type="text" id="nometu" name="nometu" value="<?php echo htmlspecialchars($row['nometu']); ?>" required><br />
                
                <label>Associer un milieu de stage</label><br />
                <select id="noemployeur" name="noemployeur" style="float:left; margin-bottom:19px;">
                    <option value="nepasassocier">Ne pas associer tout de suite</option>
                    <option value="" disabled="disabled">---Milieux de stage---</option>
                    <?php
                    // Populate the "milieu" dropdown with employeurs from the database
                    while ($noemployeur = $noemployeur_result->fetch_assoc()) {
                        $selected = ($row['noemployeur'] == $noemployeur['noemployeur']) ? 'selected' : '';
                        // Corrected the concatenation of both "Nom de l'employeur" and "Nom de l'entreprise"
                        echo "<option value=\"{$noemployeur['noemployeur']}\" $selected>{$noemployeur['Nom de l\'employeur']} - {$noemployeur['Nom de l\'entreprise']}</option>";
                    }
                    ?>
                </select><br /><br><br>

                <label>Associer un superviseur</label><br />
                <select id="nomsup" name="nomsup">
                    <option value="nepasassocier">Ne pas associer tout de suite</option>
                    <option value="" disabled="disabled">---Superviseurs---</option>
                    <?php
                    // Populate the "superviseur" dropdown with superviseurs from the database
                    while ($nomsup = $nomsup_result->fetch_assoc()) {
                        $selected = ($row['nomsup'] == $nomsup['nomsup']) ? 'selected' : '';
                        echo "<option value=\"{$nomsup['nomsup']}\" $selected>{$nomsup['nomsup']}</option>";
                    }
                    ?>
                </select><br />
                
                <label for="motdepasse">Mot de passe</label><br />
                <input type="password" id="motdepasse" name="motdepasse" maxlength="25"><br />
                <label for="motdepasse2">Confirmer le mot de passe</label><br />
                <input type="password" id="motdepasse2" name="motdepasse2" maxlength="25"><br />

                <button type="submit">Modifier</button>
            </form>
        </div>
        
</div>
</body>
</html>
