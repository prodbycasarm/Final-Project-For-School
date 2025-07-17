<?php
ob_start();
session_start();

include "bd.php"; // Assuming this file contains your database connection

// Check if the user is not an admin or not logged in
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'employeur') {
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
$welcome_message = isset($_SESSION['welcome_message']) ? $_SESSION['welcome_message'] : '';

$userid2 = isset($_SESSION['user_id2']) ? $_SESSION['user_id2'] : '';

// Initialize the session type variable
$_SESSION['user_id'] = "";

// Fetch data from the database
// Prepare SQL query
$sql_employeur = "SELECT noemployeur, `Nom de l'employeur` FROM acces_employeurs WHERE noemployeur = ?";

$stmt = $con->prepare($sql_employeur);
$stmt->bind_param('s', $_SESSION['user_id']); // 's' indicates the type (string)
$stmt->execute();
$result_employeur = $stmt->get_result();

// Fetch data safely
if ($row = $result_employeur->fetch_assoc()) {
    $userNumber = $row['noemployeur'];
    $userName = $row['Nom de l\'employeur'];
} else {
    $userNumber = "";
    $userName = "";
}

$stmt->close();



// Check if there is a row fetched
if ($row = mysqli_fetch_assoc($result_employeur)) {
    $userNumber = $row['noemployeur'];
    $userName = $row['Nom de l\'employeur']; // Replace with correct column name
} else {
    $userNumber = "";
    $userName = "";
}




?>
<!-- Rest of your page content goes here -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Évaluation des stagiaires</title>
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
    <div class="tableau">
        <section class="contenu">
            
            <div class="row">
                <?php
                    if (isset($_GET['num'])) {
                        $num = htmlspecialchars($_GET['num']); // Ensure proper sanitization
                    } else {
                        echo "No number provided in the URL.";
                        exit;
                    }

                    // Assuming you already have a database connection ($con)
                    $query = "SELECT acces_etu.nometu, 
                                    acces_etu.numetu, 
                                    acces_employeurs.`Nom de l'employeur` AS NomEmployeur
                                    FROM acces_etu 
                                    INNER JOIN acces_employeurs ON acces_employeurs.noemployeur = acces_etu.noemployeur
                                    WHERE numetu = ?";
                    $stmt = $con->prepare($query);
                    $stmt->bind_param("i", $num); // Ensure the parameter is properly bound
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $nometu = htmlspecialchars($row['nometu']);
                        $numetu = htmlspecialchars($row['numetu']);
                        $nomemployeur = htmlspecialchars($row['NomEmployeur']);
                        
                    } else {
                        echo "No student found with the provided number.";
                        exit;
                    }

                    $currentDate = date("d-m-Y");

                ?>
            
                <div class="emphase col-sm-12" id="information">
                        <p class="create">Évaluation du stagiaire <strong><?php echo htmlspecialchars($row['nometu']); ?></strong> en date du <strong><?php echo $currentDate; ?></strong></p>
                        <h3>Évaluation du stagiaire</h3>
                        <p>À l’usage de la personne responsable de l’évaluation au sein de l’entreprise. Sur les deux pages suivantes se trouve la grille d’évaluation devant être remplie par la personne qui, au sein de l’entreprise, est responsable de l’évaluation des stagiaires. Cette grille contient les dix points devant être évalués à l’aide d’une échelle d’appréciation à cinq échelons. Pour évaluer chacun des points, il suffit de déterminer l’échelon qui décrit le mieux ce qui est observé chez l’élève à évaluer.</p>
                        <h3>Exemple d’évaluation</h3>
                        <p> Avant d’évaluer la <strong>Motivation</strong> d’une ou d’un stagiaire, il faut lire la description de chacun des cinq échelons qui établissent des différences entre Excellent, Très bien, Bien, Faible et Très faible ; ensuite, il s’agit simplement de situer la ou le stagiaire à l’échelon qui correspond le mieux à ce qu’on a pu observer de son engagement, de sa participation et de sa persistance dans les activités. Ce faisant, on lui accorde ainsi une note selon le barème établi pour chacun des éléments d'évaluation, la somme des pointages donnera une note sur 100. Après avoir procédé de cette façon pour chacun des dix éléments de la grille, un calcul de la note s'effectuera lorsque vous appuierez sur le bouton Calculer. Finalement, cliquez sur le bouton Envoyer afin de sauvegarder l'évaluation.</p>
                        <p class="end"></p>
                </div>		
                
                
                
                <div class="col-sm-12">

            <!-- FORMS -->
            
            
                <div class="resultat_evaluation_hidden" id="conteneur_resultat_note"></div>
            
            <?php
                $directory = __DIR__ . '/evaluations/stagiaires';
                if (!is_dir($directory)) {
                    mkdir($directory, 0755, true);
                }
            $filenameex = $directory . '/' . $num . '.html';
            if (file_exists($filenameex)) {
                $_SESSION['messageerror'] = 'Le fichier existe déjà, l\'évaluation a déjà été faite..';
                header("Location: page_employeur.php"); // Redirige vers le tableau de bord
                exit;
            }
        

                // Check if the form is submitted
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // Collectez les données du formulaire
                    $q1 = isset($_POST['q1']) ? htmlspecialchars($_POST['q1']) : 'Non spécifié';
                    $q2 = isset($_POST['q2']) ? htmlspecialchars($_POST['q2']) : 'Non spécifié';
                    $q3 = isset($_POST['q3']) ? htmlspecialchars($_POST['q3']) : 'Non spécifié';
                    $q4 = isset($_POST['q4']) ? htmlspecialchars($_POST['q4']) : 'Non spécifié';
                    $q5 = isset($_POST['q5']) ? htmlspecialchars($_POST['q5']) : 'Non spécifié';
                    $q6 = isset($_POST['q6']) ? htmlspecialchars($_POST['q6']) : 'Non spécifié';
                    $q7 = isset($_POST['q7']) ? htmlspecialchars($_POST['q7']) : 'Non spécifié';
                    $q8 = isset($_POST['q8']) ? htmlspecialchars($_POST['q8']) : 'Non spécifié';
                    $q9 = isset($_POST['q9']) ? htmlspecialchars($_POST['q9']) : 'Non spécifié';
                    $q10 = isset($_POST['q10']) ? htmlspecialchars($_POST['q10']) : 'Non spécifié';
                    $nomemployeur = htmlspecialchars($row['NomEmployeur']);
                    $nometudiant = htmlspecialchars($row['nometu']);
                    $grandTotal = $q1 + $q2 + $q3 + $q4 + $q5 + $q6 + $q7 + $q8 + $q9 + $q10;
                    
                    

                // Prepare the HTML content for the form submission
                $htmlContent = "
            <html>
            <head>
                <title>Rapports de Stage - Évaluation de $nomemployeur </title>
                <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
                <meta charset=\"utf-8\">
                <!-- Bootstrap -->
                <link href=\"../../css/bootstrap.min.css\" rel=\"stylesheet\" media=\"screen\">
                <link href=\"../../css/base.css\" rel=\"stylesheet\">
                <link href=\"../../css/forms.css\" rel=\"stylesheet\">
                <style>
                p {
                    margin-bottom: 25px;
                }
                h4 {
                    display: block;
                    width: 100%;
                    padding: 0;
                    margin-bottom: 20px;
                    font-size: 21px;
                    line-height: inherit;
                    color: #333;
                    border: 0;
                    border-bottom: 1px solid #e5e5e5;
                }
                </style>
            </head>
            <body>
                <div class=\"main container\">
                <div class=\"main row\">
                    <section class=\"main col-9 col-sm-offset-3 container\">
                    <header>
                        <h1>Évaluation d'un stagiaire<br /></h1>
                    </header>
                    <section class=\"contenu\">
                        <h2>Évalué par $nomemployeur pour l'étudiant $nometudiant</h2>
                        <div class=\"row\">
                        <div class=\"col-sm-12\">
                            <p class=\"stagiere\">
                            <span>Motivation</span><span class=\"resultat\"><p><strong>$q1</strong></p></span>
                            </p>
                            <p class=\"stagiere\">
                            <span>Autonomie et initiative </span><span class=\"resultat\"><p><strong>$q2</strong></p></span>
                            </p>
                            <p class=\"stagiere\">
                            <span>Qualité du travail</span><span class=\"resultat\"><p><strong>$q3</strong></p></span>
                            </p>
                            <p class=\"stagiere\">
                            <span>Rythme d'exécution du travail</span><span class=\"resultat\"><p><strong>$q4</strong></p></span>
                            </p>
                            <p class=\"stagiere\">
                            <span>Sens des responsabilités</span><span class=\"resultat\">$<p><strong>$q5</strong></p></span>
                            </p>
                            <p class=\"stagiere\">
                            <span>Aptitudes</span><span class=\"resultat\"><p><strong>$q6</strong></p></span>
                            </p>
                            <p class=\"stagiere\">
                            <span>Résolutions de problèmes</span><span class=\"resultat\"><p><strong>$q7</strong></p></span>
                            </p>
                            <p class=\"stagiere\">
                            <span>Collaboration</span><span class=\"resultat\"><p><strong>$q8</strong></p></span>
                            </p>
                            <p class=\"stagiere\">
                            <span>Assiduité</span><span class=\"resultat\"><p><strong>$q9</strong></p></span>
                            </p>
                            <p class=\"stagiere\">
                            <span>Ponctualité</span><span class=\"resultat\"><p><strong>$q10</strong></p></span>
                            </p>
                            <h3 class=\"stagiere\">Résultat - $grandTotal / 100</h3>
                            <form>
                            <input type=\"button\" value=\"Retour\" onClick=\"history.back()\">
                            </form>
                        </div>
                        </div>
                    </section>
                    </section>
                </div>
                </div>
                <script src=\"http://code.jquery.com/jquery-1.10.1.min.js\"></script>
                <script src=\"../../js/bootstrap.min.js\"></script>
            </body>
            </html>
            ";



                // Ensure the 'stagiaires' directory exists
                $directory = __DIR__ . '/evaluations/stagiaires';
                if (!is_dir($directory)) {
                    mkdir($directory, 0755, true);
                }

                // Use the user's name as the filename
                $filenameex = $directory . '/' . $num . '.html';

                if (file_exists($filenameex)) {
                    $_SESSION['messageerror'] = 'Le fichier existe déjà. Vous ne pouvez pas recréer ce fichier.';
                    header("Location: page_employeur.php");
                    exit(); // Always exit after a header redirection
                }

                // Write the HTML content to a file
                if (file_put_contents($filenameex, $htmlContent)) {
                    $_SESSION['messagesucces'] = 'L\'opération a été effectuée avec succès.';
                    header("Location: page_employeur.php");

                } else {
                    echo "Erreur lors de la création du fichier.";
                }
            }
            ?>
            <form name="jdb" method="post">
                <fieldset id="evalstagiaire">
                    <div class="choix">
                    <p>Motivation</p>
                    <ol>
                        <li>Engagement, participation et persistance remarquables dans les activités.</li>
                        <li>Degré supérieur d’engagement, de participation et de persistance.</li>
                        <li>Degré suffisant d’engagement, de participation et de persistance dans les activités.</li>
                        <li>Peu d’engagement et de participation ; pas de persistance au travail.</li>
                        <li>Ni engagement réel, ni persistance dans les activités ; peu de participation.</li>
                    </ol>
                    </div>
                    <div class="reponses">
                        <label><input type="radio" name="q1" id="q1_a" value="10" />10</label>
                        <label><input type="radio" name="q1" id="q1_b" value="8" />8</label>
                        <label><input type="radio" name="q1" id="q1_c" value="6" />6</label>
                        <label><input type="radio" name="q1" id="q1_d" value="4" />4</label>
                        <label><input type="radio" name="q1" id="q1_e" value="2" />2</label>
                    </div>
                    <div class="choix">
                    <p>Autonomie et initiative</p>
                    <ol>
                        <li>Autonomie remarquable et grande capacité de prendre des initiatives.</li>
                        <li>Bonne capacité de planification et d’exécution de façon autonome.</li>
                        <li>Besoin raisonnable ou acceptable d’assistance dans la planification et la réalisation de ses tâches.</li>
                        <li>Manque d’initiative et d’autonomie.</li>
                        <li>N’entreprend rien personnellement.</li>
                    </ol>
                    </div>
                    <div class="reponses">
                        <label><input type="radio" name="q2" id="q2_a" value="10" class="requis"/>10</label>
                        <label><input type="radio" name="q2" id="q2_b" value="8" />8</label>
                        <label><input type="radio" name="q2" id="q2_c" value="6" />6</label>
                        <label><input type="radio" name="q2" id="q2_d" value="4" />4</label>
                        <label><input type="radio" name="q2" id="q2_e" value="2" />2</label>
                    </div>
                    <div class="choix">
                    <p>Qualité du travail</p>
                    <ol>
                        <li>Travail d’une qualité exceptionnelle, excellents résultats.</li>
                        <li>Travail très bien exécuté; très bons résultats.</li>
                        <li>Qualité du travail et résultats satisfaisants.</li>
                        <li>Qualité du travail et résultats plus ou moins satisfaisants.</li>
                        <li>Qualité du travail et résultats non satisfaisants.</li>
                    </ol>
                    </div>
                    <div class="reponses">
                        <label><input type="radio" name="q3" id="q3_a" value="25" />25</label>
                        <label><input type="radio" name="q3" id="q3_b" value="20" />20</label>
                        <label><input type="radio" name="q3" id="q3_c" value="15" />15</label>
                        <label><input type="radio" name="q3" id="q3_d" value="10" />10</label>
                        <label><input type="radio" name="q3" id="q3_e" value="5" />5</label>
                    </div>
                
                    <div class="choix">
                    <p>Rythme d'exécution du travail</p>
                    <ol>
                        <li>Toujours très rapide.</li>
                        <li>Habituellement plus rapide que la normale.</li>
                        <li>Satisfaisant ; exécution selon le temps normalement requis.</li>
                        <li>Plutôt lent.</li>
                        <li>Très lent.</li>
                    </ol>
                    </div>
                    <div class="reponses">
                        <label><input type="radio" name="q4" id="q4_a" value="5" />5</label>
                        <label><input type="radio" name="q4" id="q4_b" value="4" />4</label>
                        <label><input type="radio" name="q4" id="q4_c" value="3" />3</label>
                        <label><input type="radio" name="q4" id="q4_d" value="2" />2</label>
                        <label><input type="radio" name="q4" id="q4_e" value="1" />1</label>
                    </div>
                    <div class="choix">
                    <p>Sens des responsabilités</p>
                    <ol>
                        <li>Recherche des responsabilités.</li>
                        <li>Acceptation enthousiaste des responsabilités confiées.</li>
                        <li>Acceptation sereine des responsabilités.</li>
                        <li>Réticence à accepter des responsabilités</li>
                        <li>Fuite devant les responsabilités.</li>
                    </ol>
                    </div>
                    <div class="reponses">
                        <label><input type="radio" name="q5" id="q5_a" value="10" />10</label>
                        <label><input type="radio" name="q5" id="q5_b" value="8" />8</label>
                        <label><input type="radio" name="q5" id="q5_c" value="6" />6</label>
                        <label><input type="radio" name="q5" id="q5_d" value="4" />4</label>
                        <label><input type="radio" name="q5" id="q5_e" value="2" />2</label>
                    </div>
                    <div class="choix">
                    <p>Aptitudes</p>
                    <ol>
                        <li>Très grande facilité à apprendre; compréhension très rapide, même sans explication.</li>
                        <li>Facilité à apprendre, mais requiert des explications détaillées.</li>
                        <li>Facilité à apprendre, mais requiert des explications détaillées.</li>
                        <li>Légères difficultés à apprendre; exige des répétitions.</li>
                        <li>Difficultés d’apprentissage exigeant de multiples explications répétitives.</li>
                </ol>
                </div>
                <div class="reponses">
                        <label><input type="radio" name="q6" id="q6_a" value="10" />10</label>
                        <label><input type="radio" name="q6" id="q6_b" value="8" />8</label>
                        <label><input type="radio" name="q6" id="q6_c" value="6" />6</label>
                        <label><input type="radio" name="q6" id="q6_d" value="4" />4</label>
                        <label><input type="radio" name="q6" id="q6_e" value="2" />2</label>
                    </div>
                    <div class="choix">
                    <p>Résolutions de problèmes</p>	
                    <ol>
                        <li>Retient toujours la meilleure solution à un problème.</li>
                        <li>Émet presque toujours une opinion éclairée lorsque surgit un problème.</li>
                        <li>Émet assez souvent une opinion éclairée lorsque surgit un problème.</li>
                        <li>Émet des opinions plus ou moins appropriées à la résolution d’un problème.</li>
                        <li>Les rares opinions émises ne sont presque jamais appropriées pour résoudre un problème.</li>
                </ol>
                </div>
                <div class="reponses">
                        <label><input type="radio" name="q7" id="q7_a" value="10" />10</label>
                        <label><input type="radio" name="q7" id="q7_b" value="8" />8</label>
                        <label><input type="radio" name="q7" id="q7_c" value="6" />6</label>
                        <label><input type="radio" name="q7" id="q7_d" value="4" />4</label>
                        <label><input type="radio" name="q7" id="q7_e" value="2" />2</label>
                    </div>
                
                
                    <div class="choix">
                    <p>Collaboration</p>
                    <ol>
                        <li>Très bonne collaboration participation très active et très efficace à un travail d’équipe.</li>
                        <li>Très bonne collaboration ; participation active à un travail d’équipe.</li>
                        <li>Bonne collaboration; participation valable à un travail d’équipe.</li>
                        <li>Collaboration plus ou moins active et plus ou moins valable à un travail d’équipe.</li>
                        <li>Pas de collaboration; est à la remorque de son équipe.</li>
                    </ol>
                    </div>
                    <div class="reponses">
                        <label><input type="radio" name="q8" id="q8_a" value="10" />10</label>
                        <label><input type="radio" name="q8" id="q8_b" value="8" />8</label>
                        <label><input type="radio" name="q8" id="q8_c" value="6" />6</label>
                        <label><input type="radio" name="q8" id="q8_d" value="4" />4</label>
                        <label><input type="radio" name="q8" id="q8_e" value="2" />2</label>
                    </div>
                    <div class="choix">
                    <p>Assiduité</p>
                    <ol>
                        <li>Aucune absence</li>
                        <li>Une absence</li>
                        <li>Deux absence</li>
                        <li>Trois absence</li>
                        <li>Plus de trois absences</li>
                    </ol>
                    </div>
                    <div class="reponses">
                        <label><input type="radio" name="q9" id="q9_a" value="5" />5</label>
                        <label><input type="radio" name="q9" id="q9_b" value="4" />4</label>
                        <label><input type="radio" name="q9" id="q9_c" value="3" />3</label>
                        <label><input type="radio" name="q9" id="q9_d" value="2" />2</label>
                        <label><input type="radio" name="q9" id="q9_e" value="1" />1</label>
                    </div>
                    <div class="choix">
                    <p>Ponctualité</p>
                    <ol>
                        <li>Jamais en retard; Respecte toujours l'horaire de travail</li>
                        <li>Arrive rarement en retard; respecte généralement l'horaire de travail</li>
                        <li>Arrive quelques fois en retard; respecte l'horaire de travail</li>
                        <li>Arrive souvent en retard; ne respecte généralement pas l'horaire de travail</li>
                        <li>Très souvent en retard; ne respecte pas du tout l'horaire de travail</li>
                    </ol>
                    </div>
                    <div class="reponses">
                        <label><input type="radio" name="q10" id="q10_a" value="5" />5</label>
                        <label><input type="radio" name="q10" id="q10_b" value="4" />4</label>
                        <label><input type="radio" name="q10" id="q10_c" value="3" />3</label>
                        <label><input type="radio" name="q10" id="q10_d" value="2" />2</label>
                        <label><input type="radio" name="q10" id="q10_e" value="1" />1</label>
                    </div>
                </fieldset> 

            
                
                <input  type="submit" name="envoyer" value="envoyer" class="mybutton1" />
                <input type="reset" name="Réinitialiser" value="Réinitialiser"  class="mybutton2" />
                
            </form>
            </div>
                </div> 	<!-- COl-12 -->
            </div> 		<!-- ROW -->
        </section> 
    </div>    		<

</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const form = document.forms["jdb"];

    form.addEventListener("submit", function(event) {
        let isValid = true; // Assume the form is valid
        const questions = 10; // Total number of questions
        const errorMessages = [];

        // Loop through each question and check if an option is selected
        for (let i = 1; i <= questions; i++) {
            const questionName = "q" + i;
            const options = form[questionName];
            const selectedOption = Array.from(options).some(option => option.checked);

            if (!selectedOption) {
                isValid = false;
                errorMessages.push(`S'il vous plaît, répondez à la question ${i}.`);
            }
        }

        if (!isValid) {
            event.preventDefault(); // Prevent form submission
            alert(errorMessages.join("\n")); // Display all error messages
        }
    });
});
</script>

<script>
    document.getElementById('disconnect').addEventListener('click', function() {
        // Submit the form when the "Déconnecter" li element is clicked
        document.getElementById('logoutForm').submit();
    });
</script>


</body>
</html>
<!-- Add the disconnect button -->
