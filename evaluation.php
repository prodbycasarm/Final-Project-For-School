<?php
ob_start();
session_start();

// Check if the user is not a student or not logged in
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'etudiant') {
    // Redirect to the login page or any other page as needed
    header("Location: index.php");
    exit();
}

include "bd.php"; // Assuming this file contains your database connection





// Check if the disconnect button is clicked
if (isset($_POST['disconnect'])) {
    // Destroy all session data
    session_unset();
    session_destroy();
    // Redirect to the login page or any other page as needed
    header("Location: index.php");
    exit();
}

// Initialize the session type variable
$_SESSION['type'] = "";

// Fetch data from the database
// Prepare SQL query
$sql_etudiant = "SELECT * FROM journal WHERE numetu = '{$_SESSION['user_id']}'";
$result_etudiant = mysqli_query($con, $sql_etudiant);

// Loop through the fetched data
while ($row = mysqli_fetch_assoc($result_etudiant)) {
    $_SESSION['type'] = $row['NomEtu'];
}



// Fetch data from the database
// Prepare SQL query
$sql_etudiant = "SELECT * FROM journal WHERE numetu = '{$_SESSION['user_id']}'";
$result_etudiant = mysqli_query($con, $sql_etudiant);

$sql_etudiant_employeur = "
    SELECT ae.`Nom de l'entreprise`
    FROM acces_etu ae2
    INNER JOIN acces_employeurs ae ON ae.noemployeur = ae2.noemployeur
    WHERE ae2.numetu = '{$_SESSION['user_id']}';
";
$result_etudiant2 = mysqli_query($con, $sql_etudiant_employeur);


// Check if there is a row fetched
if ($row = mysqli_fetch_assoc($result_etudiant)) {
    // Retrieve and store values from the fetched row
    $userName = $row['NomEtu']; // Assuming 'NomEtu' is the column you want to store as the user's name
    $userNum = $row['numetu'];
    $userSupervisor = $row['nomsup']; // Assuming 'nomsup' is the column for supervisor's name
} else {
    // If no row is fetched, set $userName and $userSupervisor to empty strings or handle it as needed
    $userName = "";
    $userNum = "";
    $userSupervisor = "";
}
// Check if there is a row fetched
if ($row = mysqli_fetch_assoc($result_etudiant2)) {
    // Retrieve and store values from the fetched row

    $useremployeur = $row['Nom de l\'entreprise']; // Assuming 'nomsup' is the column for supervisor's name
} else {
    // If no row is fetched, set $userName and $userSupervisor to empty strings or handle it as needed

    $useremployeur = "";
}


$directory = __DIR__ . '/evaluations/entreprises';
    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
    }
$filename = $directory . '/' . $userNum . '.html';
if (file_exists($filename)) {
    $_SESSION['messageerror'] = 'Le fichier existe déjà, l\'évaluation a déjà été faite..';
    header("Location: dash_etudiant.php"); // Redirige vers le tableau de bord
    exit;
}




// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $q1 = isset($_POST['q1']) ? $_POST['q1'] : 'Non spécifié';
    $q1_autre = isset($_POST['q1_autre']) ? $_POST['q1_autre'] : 'Non applicable';
    $q2 = isset($_POST['q2']) ? $_POST['q2'] : 'Non spécifié';
    $q3 = isset($_POST['q3']) ? $_POST['q3'] : 'Non spécifié';
    $q4 = isset($_POST['q4']) ? $_POST['q4'] : 'Non spécifié';
    $q5 = isset($_POST['q5']) ? $_POST['q5'] : 'Non spécifié';
    $q6 = isset($_POST['q6']) ? $_POST['q6'] : 'Non spécifié';
    $tq6 = isset($_POST['tq6']) ? $_POST['tq6'] : 'Non spécifié';
    $q7 = isset($_POST['q7']) ? $_POST['q7'] : 'Non spécifié';
    $tq7 = isset($_POST['tq7']) ? $_POST['tq7'] : 'Non spécifié';
    $q8 = isset($_POST['q8']) ? $_POST['q8'] : 'Non spécifié';
    $tq8 = isset($_POST['tq8']) ? $_POST['tq8'] : 'Non spécifié';
    $q9 = isset($_POST['q9']) ? $_POST['q9'] : 'Non spécifié';
    $tq9 = isset($_POST['tq9']) ? $_POST['tq9'] : 'Non spécifié';
    $q10 = isset($_POST['q10']) ? $_POST['q10'] : 'Non spécifié';
    $q11 = isset($_POST['q11']) ? $_POST['q11'] : 'Non spécifié';
    $tq11 = isset($_POST['tq11']) ? $_POST['tq11'] : 'Non spécifié';
    $q12 = isset($_POST['q12']) ? $_POST['q12'] : 'Non spécifié';
    $q13_a = isset($_POST['q13_a']) ? $_POST['q13_a'] : 'Non spécifié';
    $q13_b = isset($_POST['q13_b']) ? $_POST['q13_b'] : 'Non spécifié';
    $q13_c = isset($_POST['q13_c']) ? $_POST['q13_c'] : 'Non spécifié';
    $q13_d = isset($_POST['q13_d']) ? $_POST['q13_d'] : 'Non spécifié';
    $q13_e = isset($_POST['q13_e']) ? $_POST['q13_e'] : 'Non spécifié';
    $q14 = isset($_POST['q14']) ? $_POST['q14'] : 'Non spécifié';


    $useremployeur = htmlspecialchars($row['Nom de l\'entreprise']);
    $nometu = htmlspecialchars($row['NomEtu']);
    // Prepare the HTML content for the form submission
    $htmlContent = "
    <html>
    <head>
        <title>Rapports de Stage - Évaluation de $userName</title>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <meta charset='utf-8'>
        <!-- Bootstrap -->
        <link href='../../css/bootstrap.min.css' rel='stylesheet' media='screen'>
        <link href='../../css/base.css' rel='stylesheet'>
        <link href='../../css/forms.css' rel='stylesheet'>
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
        <div class='main container'>
            <div class='main row'>
                <section class='main col-9 col-sm-offset-3 container'>
                    <header>
                        <h1>Évaluation de l'entreprise<br /></h1>
                    </header>
                    <section class='contenu'>
                        <h2>Évaluation fait par $userName pour l'entreprise $useremployeur</h2>
                        <div class='row'>
                            <div class='col-sm-12'>
                                <h4 class='col-sm-12'>1- Le plan élaboré pour préparer votre stage a-t-il été respecté ? :</h4>
                                <p class='col-sm-12'>$q1</p>
                                <p><strong>Si non, pourquoi ?</strong> $q1_autre</p>

                                <h4 class='col-sm-12'>2- Lors de la première journée de votre stage, par qui et de quelle façon avez-vous été accueillie ou accueilli ? :</h4>
                                <p class='col-sm-12'>$q2</p>

                                <h4 class='col-sm-12'>3- La personne responsable de votre supervision vous a-t-elle invitée ou invité à aller la rencontrer pour discuter des problèmes que vous pourriez rencontrer ? :</h4>
                                <p class='col-sm-12'>$q3</p>

                                <h4 class='col-sm-12'>4- L'attitude générale des membres de l'entreprise était-elle accueillante ? :</h4>
                                <p class='col-sm-12'>$q4</p>

                                <h4 class='col-sm-12'>5- Vous sentiez-vous à l'aise dans l'entreprise ? :</h4>
                                <p class='col-sm-12'>$q5</p>

                                <h4 class='col-sm-12'>6- Avez-vous eu l'impression d'avoir été considérée ou considéré comme le personnel régulier, ou avez-vous senti avoir été tenue ou tenu à l'écart ? :</h4>
                                <p class='col-sm-12'>$q6</p>
                                <p class='col-sm-12'>$tq6</p>

                                <h4 class='col-sm-12'>7- Avez-vous eu l'impression d'avoir appris quelque chose de nouveau pendant votre stage ?</h4>
                                <p class='col-sm-12'>$q7</p>
                                <p class='col-sm-12'>$tq7</p>

                                <h4 class='col-sm-12'>8- Votre stage vous a-t-il permis d'appliquer les connaissances acquises à l'intérieur des cours que vous avez suivis au Collège ?</h4>
                                <p class='col-sm-12'>$q8</p>
                                <p class='col-sm-12'>$tq8</p>

                                <h4 class='col-sm-12'>9- Dans l'accomplissement de votre travail, avez-vous eu l'impression de bénéficier d'une supervision appropriée ou d'être l'objet d'une trop grande surveillance ?</h4>
                                <p class='col-sm-12'>$q9</p>
                                <p class='col-sm-12'>$tq9</p>

                                <h4 class='col-sm-12'>10- Combien de fois avez-vous rencontré la personne ayant la responsabilité de vous superviser ?</h4>
                                <p class='col-sm-12'>$q10</p>

                                <h4 class='col-sm-12'>11- Selon vous, les moyens utilisés pour favoriser votre apprentissage et pour vous évaluer étaient-ils appropriés ?</h4>
                                <p class='col-sm-12'>$q11</p>
                                <p class='col-sm-12'>$tq11</p>

                                <h4 class='col-sm-12'>12- Votre rapport d'étape a-t-il fait l'objet d'une discussion avec la personne affectée à votre supervision ?</h4>
                                <p class='col-sm-12'>$q12</p>

                                <h4 class='col-sm-12'>13- À votre avis, les tâches qui vous ont été confiées par l'entreprise avaient pour but:</h4>
                                <table>
                                    <tr>
                                        <td>De vous faire passer le temps</td>
                                        <td>$q13_a</td>
                                    </tr>
                                    <tr>
                                        <td>D'utiliser vos compétences</td>
                                        <td>$q13_b</td>
                                    </tr>
                                    <tr>
                                        <td>De vous apprendre quelque chose</td>
                                        <td>$q13_c</td>
                                    </tr>
                                    <tr>
                                        <td>De vous apprendre à relever des défis ou à prendre des initiatives.</td>
                                        <td>$q13_d</td>
                                    </tr>
                                    <tr>
                                        <td>De vous apprendre à résoudre des problèmes</td>
                                        <td>$q13_e</td>
                                    </tr>
                                </table>

                                <h4 class='col-sm-12'>14- Qu'est-ce qui pourrait améliorer les conditions de stage dans l'entreprise où vous venez de terminer le vôtre ?</h4>
                                <p class='col-sm-12'>$q14</p>

                                <form>
                                    <input type='button' value='Retour' onClick='history.back()'>
                                </form>
                            </div>
                        </div>
                    </section>
                </section>
            </div>
        </div>

        <script src='http://code.jquery.com/jquery-1.10.1.min.js'></script>
        <script src='../../js/bootstrap.min.js'></script>
    </body>
</html>";



    // Ensure the 'stagiaires' directory exists
    $directory = __DIR__ . '/evaluations/entreprises';
    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
    }

    // Use the user's name as the filename
    $filename = $directory . '/' . $userNum . '.html';

    if (file_exists($filename)) {
        $_SESSION['messageerror'] = 'Le fichier existe déjà. Vous ne pouvez pas recréer ce fichier.';
        header("Location: dash_etudiant.php");
        exit(); // Always exit after a header redirection
    }

    // Write the HTML content to a file
    if (file_put_contents($filename, $htmlContent)) {
        $_SESSION['messagesucces'] = 'L\'opération a été effectuée avec succès.';
        header("Location: dash_etudiant.php");

    } else {
        echo "Erreur lors de la création du fichier.";
    }
}
?>






<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Évaluation de l'entreprise - 2023 Etudiant</title>
    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            }
        /* Container for the main content */
        .container {
            max-width: 1500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.6);
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
            padding-left:30%;
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

        /* Styles for the table */
        table {
            border-collapse: collapse;
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 15px;
            text-align: left;
            color: #333;
        }

        th {
            background-color: #3A3B3C;
            color: #f2f2f2;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e2e3e5;
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
            <div class="menu">
                <div class="titre"><h1 href="#">Stage technique d'intégration multimédia</h1></div>
                <ul class="nav-links-menu">
                <li style="color:white; font-size:18px;"><?php echo $_SESSION['type']; ?> | </li>
                <li><a href="dash_etudiant.php">Accueil</a></li>
                <li id="disconnect"><a>Déconnecter</a></li>
                </ul>
            </div>
        </nav>

    <div class="header">
        <div class="wrapper">
            <ul class="nav-links">
                <li><a class="sub-menu" href="page_etudiant.php">Rapports d'étape</a></li>
                <li><a class="sub-menu" href="experience.php">Expériences de stage</a></li>
                <li><a class="sub-menu" href="#">Évaluation de l'entreprise</a></li>
            </ul> 
        </div>
    </div>

    <!-- Hidden form for logout -->
    <form id="logoutForm" method="post" action="">
        <input type="hidden" name="disconnect">
    </form>

    <div class="tableau">
        <div class="text">        <!-- FORMS -->
            <h3>Évaluation de l'entreprise </h3>
            <p class="create">Évaluation de l'entreprise créé par <?php echo $userName; ?> et remis à <?php echo $userSupervisor; ?> le <?php echo date('Y-m-d'); ?></p>
        </div>
        
        

        <form  name="evalent" method="post">
            <fieldset title="question 1" id="fld1">
                <legend>1- Le plan élaboré pour préparer votre stage a-t-il été respecté ?</legend>
                <input type="radio" name="q1" id="q1_a" value="oui" /> 
                <label for="q1_a">Oui </label><br />
                
                <input type="radio" name="q1" id="q1_b" value="non" /> 
                <label for="q1_b">Non </label><br />
                
                <label for="q1_autre" class="marginLeft autre">Si non, pourquoi? </label><br />
                <input type="text" name="q1_autre" id="q1_autre" class="autre requis" disabled />
            </fieldset>
		  
		    <fieldset id="fld2" title="question 2">
				<legend>2- Lors de la première journée de votre stage, par qui et de quelle façon avez-vous été accueillie ou accueilli ?</legend>
				<label >Justifiez. </label>
                <div><textarea name="q2" id="q2" cols="5" rows="5" class="requis"></textarea></div>
			</fieldset>
		  
			<fieldset id="fld3" title="question 3">
			<legend>3- La personne responsable de votre supervision vous a-t-elle invitée ou invité à aller la rencontrer pour discuter des problèmes que vous pourriez rencontrer ?</legend>
				<input type="radio" name="q3" id="q3_a" value="Oui" /><label for="q3_a">Oui </label><br />
				<input type="radio" name="q3" id="q3_b" value="Non" /><label for="q3_b">Non </label><br />
			</fieldset>
		  
		    <fieldset id="fld4" title="question 4">
			<legend>4- L'attitude générale des membres de l'entreprise était-elle accueillante ?</legend>
				<input type="radio" name="q4" id="q4_a" value="Oui" /><label for="q4_a">Oui </label><br />
				<input type="radio" name="q4" id="q4_b" value="Non" /><label for="q4_b">Non </label><br />
			</fieldset>
		  
			<fieldset id="fld5" title="question 5">
			<legend>5- Vous sentiez-vous à l'aise dans l'entreprise ?</legend>
				<input type="radio" name="q5" id="q5_a" value="Oui" /><label for="q5_a">Oui </label><br />
				<input type="radio" name="q5" id="q5_b" value="Non" /><label for="q5_b">Non </label><br />
			</fieldset>
		  
			<fieldset id="fld6" title="question 6">
			<legend>6- Avez-vous eu l'impression d'avoir été considérée ou considéré comme le personnel régulier, ou avez-vous senti avoir été tenue ou tenu à l'écart ?</legend>
			
				<input type="radio" name="q6" id="q6_a" value="Comme le personnel régulier" /><label for="q6_a">Comme le personnel régulier </label><br />
				<input type="radio" name="q6" id="q6_b" value="Plutôt à l'écart" /><label for="q6_b">Plutôt à l'écart </label><br /><br />
			<label >Justifiez. </label><div><textarea name="tq6" id="tq6" cols="5" rows="5" class="requis"></textarea></div>
			</fieldset>
		  
		  <fieldset id="fld7" title="question 7">
		  <legend>7- Avez-vous eu l'impression d'avoir appris quelque chose de nouveau pendant votre stage ?</legend>
			
				<input type="radio" name="q7" id="q7_a" value="Oui" /><label for="q7_a">Oui </label><br />
				<input type="radio" name="q7" id="q7_b" value="Non" /><label for="q7_b">Non </label><br /><br />
				<label >Si oui, qu'avez-vous appris de plus important ? Si non, commentez.</label><div><textarea name="tq7" id="tq7" cols="5" rows="5" class="requis"></textarea></div>
		  </fieldset>
		  
			<fieldset id="fld8" title="question 8">
			<legend>8- Votre stage vous a-t-il permis d'appliquer les connaissances acquises à l'intérieur des cours que vous avez suivis au Collège ?</legend>
			
				<input type="radio" name="q8" id="q8_a" value="Oui" /><label for="q8_a">Oui </label><br />
				<input type="radio" name="q8" id="q8_b" value="Non" /><label for="q8_b">Non </label><br /><br />
			
			<label >Justifiez. </label><div><textarea name="tq8" id="tq8" cols="5" rows="5" class="requis"></textarea></div>
			</fieldset>
		  
			<fieldset id="fld9" title="question 9">
			<legend>9- Dans l'accomplissement de votre travail, avez-vous eu l'impression de bénéficier d'une supervision appropriée ou d'être l'objet d'une trop grande surveillance ?</legend>
			
				<input type="radio" name="q9" id="q9_a" value="Supervision appropriée" /><label for="q9_a">Supervision appropriée. </label><br />
				<input type="radio" name="q9" id="q9_b" value="Trop grande supervision" /><label for="q9_b">Trop grande surveillance. </label><br /><br />
			<label >Justifiez. </label><div><textarea name="tq9" id="tq9" cols="5" rows="5" class="requis"></textarea></div>
			</fieldset>
		  
			<fieldset id="fld10" title="question 10">
			<legend>10- Combien de fois avez-vous rencontré la personne ayant la responsabilité de vous superviser ?</legend>
			
				<input type="radio" name="q10" id="q10_a" value="1 fois" /><label for="q10_a">1 fois </label><br />
				<input type="radio" name="q10" id="q10_b" value="2 fois" /><label for="q10_b">2 fois </label><br />
				<input type="radio" name="q10" id="q10_c" value="3 fois" /><label for="q10_c">3 fois </label><br />
				<input type="radio" name="q10" id="q10_d" value="4 fois +" /><label for="q10_d">4 fois+ </label><br />
			</fieldset>
			
			<fieldset id="fld11" title="question 11">
			<legend>11- Selon vous, les moyens utilisés pour favoriser votre apprentissage et pour vous évaluer étaient-ils appropriés ?</legend>
			
				<input type="radio" name="q11" id="q11_a" value="Oui" /><label for="q11_a">Oui </label><br />
				<input type="radio" name="q11" id="q11_b" value="Non" /><label for="q11_b">Non </label><br /><br />
			<label >Précisez. </label>
			<div><textarea name="tq11" id="tq11" cols="55" rows="3" class="requis"></textarea></div>
			</fieldset>
		  
			<fieldset id="fld12" title="question 12">
			<legend>12- Votre journal de bord a-t-il fait l'objet d'une discussion avec la personne affectée à votre supervision ?</legend>
			
				<input type="radio" name="q12" id="q12_a" value="Oui" /><label for="q12_a">Oui </label><br />
				<input type="radio" name="q12" id="q12_b" value="Non" /><label for="q12_b">Non </label><br />
			
			</fieldset>
		  
		  <fieldset id="fld13" title="question 13">
		  <legend>13- À votre avis, les tâches qui vous ont été confiées par l'entreprise avaient pour but: </legend>
			<table style="position:relative;">
			<tr><td></td>
			  <td>Pas du tout</td>
			  <td>Un peu</td>
			  <td>Beaucoup</td>
			  <td>Exclusivement</td></tr>
			<tr><td>De vous faire passer le temps</td>
			  <td style="position:relative;">
			  <input type="radio" name="q13_a" id="q13_a1" value="Pas du tout" /></td>
			  <td><input type="radio" name="q13_a" id="q13_a2" value="Un peu" /></td>
			  <td><input type="radio" name="q13_a" id="q13_a3" value="Beaucoup" /></td>
			  <td><input type="radio" name="q13_a" id="q13_a4" value="Exclusivement" /></td>
			</tr>
			<tr>
			<td>D'utiliser vos compétences</td>
			  <td style="position:relative;">
			  <input type="radio" name="q13_b" id="q13_b1" value="Pas du tout" /></td>
			  <td><input type="radio" name="q13_b" id="q13_b2" value="Un peu" /></td>
			  <td><input type="radio" name="q13_b" id="q13_b3" value="Beaucoup" /></td>
			  <td><input type="radio" name="q13_b" id="q13_b4" value="Exclusivement" /></td>
			</tr>
			<tr>
			<td>De vous apprendre quelque chose</td>
			  <td style="position:relative;">
			  <input type="radio" name="q13_c" id="q13_c1" value="Pas du tout" /></td>
			  <td><input type="radio" name="q13_c" id="q13_c2" value="Un peu" /></td>
			  <td><input type="radio" name="q13_c" id="q13_c3" value="Beaucoup" /></td>
			  <td><input type="radio" name="q13_c" id="q13_c4" value="Exclusivement" /></td>
			</tr>
			<tr>
			<td>De vous apprendre à relever des défis ou à prendre des initiatives.</td>
			  <td style="position:relative;">
			  <input type="radio" name="q13_d" id="q13_d1" value="Pas du tout" /></td>
			  <td><input type="radio" name="q13_d" id="q13_d2" value="Un peu" /></td>
			  <td><input type="radio" name="q13_d" id="q13_d3" value="Beaucoup" /></td>
			  <td><input type="radio" name="q13_d" id="q13_d4" value="Exclusivement" /></td>
			</tr>
			<tr>
			<td>De vous apprendre à résoudre des problèmes</td>
			  <td style="position:relative;">
			  <input type="radio" name="q13_e" id="q13_e1" value="Pas du tout" /></td>
			  <td><input type="radio" name="q13_e" id="q13_e2" value="Un peu" /></td>
			  <td><input type="radio" name="q13_e" id="q13_e3" value="Beaucoup" /></td>
			  <td><input type="radio" name="q13_e" id="q13_e4" value="Exclusivement" /></td>
			</tr>
			</table>
			</fieldset>
		  
			<fieldset id="fld14" title="question 14">
			<legend>14- Qu'est-ce qui pourrait améliorer les conditions de stage dans l'entreprise où vous venez de terminer le vôtre ?</legend>
			<label >Justifiez. </label><div><textarea name="q14" id="q14" cols="5" rows="5" class="requis"></textarea></div>
			</fieldset>
			
	
		
			
			  <input  type="submit" name="envoyer" value="envoyer" class="mybutton1" />
			  <input type="reset" name="Réinitialiser" value="Réinitialiser"  class="mybutton2" />
			
		</form>
            </div>
</div> <!-- Main -->


<!-- Pour l'option si non -->
<script>
  // Get the radio buttons and the text input
  const radioOui = document.getElementById("q1_a");
  const radioNon = document.getElementById("q1_b");
  const inputText = document.getElementById("q1_autre");

  // Function to enable/disable the text input based on the selection
  function toggleInput() {
    inputText.disabled = !radioNon.checked;
  }

  // Add event listeners for the radio buttons
  radioOui.addEventListener("change", toggleInput);
  radioNon.addEventListener("change", toggleInput);
</script>

<script>
        document.getElementById('disconnect').addEventListener('click', function() {
            // Submit the form when the "Déconnecter" li element is clicked
            document.getElementById('logoutForm').submit();
        });
</script>

<script>
    document.getElementById('myForm').addEventListener('submit', function(event) {
        // Reload the page when the form is submitted
        location.reload();
    });
</script>


</body>
</html>