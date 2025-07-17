<?php
ob_start();
session_start();

// Vérification des droits d'accès
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'etudiant') {
    header("Location: index.php");
    exit();
}

include "bd.php"; // Assurez-vous que ce fichier sécurise aussi les accès DB

// Déconnexion
if (isset($_POST['disconnect'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}

// Initialiser les variables de session
$_SESSION['type'] = "";


// Requête sécurisée pour récupérer les données de l'utilisateur
if ($stmt = $con->prepare("SELECT * FROM journal WHERE numetu = ?")) {
    $stmt->bind_param("s", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $_SESSION['type'] = htmlspecialchars($row['NomEtu'], ENT_QUOTES, 'UTF-8');
        $userName = htmlspecialchars($row['NomEtu'], ENT_QUOTES, 'UTF-8');
        $userNum = htmlspecialchars($row['numetu'], ENT_QUOTES, 'UTF-8');
        $userSupervisor = htmlspecialchars($row['nomsup'], ENT_QUOTES, 'UTF-8');
    } else {
        $userName = "";
        $userNum = "";
        $userSupervisor = "";
    }
    $stmt->close();
}




// Vérification et création du répertoire pour les expériences
$directory = __DIR__ . '/experiences';
if (!is_dir($directory)) {
    if (!mkdir($directory, 0755, true)) {
        die("Erreur : Impossible de créer le répertoire requis.");
    }
}

$filenameex = $directory . '/' . $userNum . '.html';
if (file_exists($filenameex)) {
    $_SESSION['messageerror'] = 'Le fichier existe déjà. Vous ne pouvez pas recréer ce fichier.';
    header("Location: dash_etudiant.php");
    exit();
}

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Collectez les données du formulaire
        $qsa1 = isset($_POST['champ1']) ? $_POST['champ1'] : 'Non spécifié';
        $qsb1 = isset($_POST['champ2']) ? $_POST['champ2'] : 'Non spécifié';
        $qsa2 = isset($_POST['champ3']) ? $_POST['champ3'] : 'Non spécifié';
        $qsb2 = isset($_POST['champ4']) ? $_POST['champ4'] : 'Non spécifié';
        $qsa3 = isset($_POST['champ5']) ? $_POST['champ5'] : 'Non applicable';
    
        $qsa4 = isset($_POST['champ6']) ? $_POST['champ6'] : 'Non spécifié';
        $qsb4 = isset($_POST['champ7']) ? $_POST['champ7'] : 'Non spécifié';
        $qsa5 = isset($_POST['champ8']) ? $_POST['champ8'] : 'Non spécifié';
        $qsb5 = isset($_POST['champ9']) ? $_POST['champ9'] : 'Non spécifié';
        $qsa6 = isset($_POST['champ10']) ? $_POST['champ10'] : 'Non spécifié';
    
        $qsa7 = isset($_POST['champ11']) ? $_POST['champ11'] : 'Non spécifié';
        $qsb7 = isset($_POST['champ12']) ? $_POST['champ12'] : 'Non spécifié';
    
        $qsa8 = isset($_POST['champ13']) ? $_POST['champ13'] : 'Non spécifié';
        $qsb8 = isset($_POST['champ14']) ? $_POST['champ14'] : 'Non spécifié';
    
        $qsa9 = isset($_POST['champ15']) ? $_POST['champ15'] : 'Non spécifié';
        $qsa10 = isset($_POST['champ16']) ? $_POST['champ16']  : 'Non spécifié';
    
        $qsa11 = isset($_POST['champ17']) ? $_POST['champ17']  : 'Non spécifié';
        $qsa12 = isset($_POST['champ18']) ? $_POST['champ18']  : 'Non spécifié';

        $nomsuper = htmlspecialchars($row['nomsup']);
        $nometu = htmlspecialchars($row['NomEtu']);
        


    // Prepare the HTML content for the form submission
    $htmlContent = "
    <html>
    <head>
        <title>Rapports de Stage - Évaluation de " . htmlspecialchars($nometu) . "</title>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <meta charset='utf-8'>
        <!-- Bootstrap -->
        <link href='../css/bootstrap.min.css' rel='stylesheet' media='screen'>
        <link href='../css/base.css' rel='stylesheet'>
        <link href='../css/forms.css' rel='stylesheet'>
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
                        <h1>Évaluation du stage<br /></h1>
                    </header>
                    <section class='contenu'>
                        <h2>Évaluation du stage fait par " . htmlspecialchars($nometu) . " pour " . htmlspecialchars($nomsuper) . "</h2>
                        <div class='row'>
                            <div class='col-sm-12'>
                                <h3>1. Parmi les objectifs que vous avez retenus à l'intérieur du plan de votre stage, lesquels avez-vous atteints ?</h3>
                                <h4 class='col-sm-12'>1. Attitudes personnelles (objectifs généraux) :</h4>
                                <p class='col-sm-6'>" . htmlspecialchars($qsa1) . "</p>
                                <p class='col-sm-6'>" . htmlspecialchars($qsb1) . "</p>

                                <h4 class='col-sm-12'>2. Habiletés professionnelles (objectifs spécifiques) :</h4>
                                <p class='col-sm-6'>" . htmlspecialchars($qsa2) . "</p>
                                <p class='col-sm-6'>" . htmlspecialchars($qsb2) . "</p>

                                <div class='clearfix'></div>

                                <h4 class='col-sm-12'>Pourquoi et comment êtes-vous parvenue ou parvenu à atteindre ces objectifs ?</h4>
                                <p class='col-sm-12'>" . htmlspecialchars($qsa3) . "</p>
                                <div class='clearfix'></div>

                                <h3>2. S'il y a lieu, identifiez quelques objectifs que vous n’avez pas atteints de façon satisfaisante.</h3>
                                <h4 class='col-sm-12'>1. Attitudes personnelles (objectifs généraux) :</h4>
                                <p class='col-sm-6'>" . htmlspecialchars($qsa4) . "</p>
                                <p class='col-sm-6'>" . htmlspecialchars($qsb4) . "</p>

                                <h4 class='col-sm-12'>2. Habiletés professionnelles (objectifs spécifiques) :</h4>
                                <p class='col-sm-6'>" . htmlspecialchars($qsa5) . "</p>
                                <p class='col-sm-6'>" . htmlspecialchars($qsb5) . "</p>

                                <div class='clearfix'></div>

                                <h4 class='col-sm-12'>Pourquoi n'avez-vous pas réussi à les atteindre ?</h4>
                                <p class='col-sm-12'>" . htmlspecialchars($qsa6) . "</p>
                                <div class='clearfix'></div>

                                <h3>3. S'il y a lieu, identifiez les craintes que vous aviez au regard du stage avant de l'entreprendre ?</h3>
                                <h4 class='col-sm-12'>Parmi vos craintes (si vous en aviez), lesquelles étaient justifiées et lesquelles ne l'étaient pas ? :</h4>
                                <p class='col-sm-12'>" . htmlspecialchars($qsa7) . "</p>

                                <h4 class='col-sm-12'>Commentez très brièvement votre réponse :</h4>
                                <p class='col-sm-12'>" . htmlspecialchars($qsb7) . "</p>
                                <div class='clearfix'></div>

                                <h3>4. Identifiez les impacts que votre séjour en entreprise a eus sur vous, d'une part, et sur l'entreprise, d'autre part.</h3>
                                <h4 class='col-sm-12'>a. Pour vous : (votre vie personnelle, vos études, etc.)</h4>
                                <p class='col-sm-12'>" . htmlspecialchars($qsa8) . "</p>

                                <h4 class='col-sm-12'>b. Pour l'entreprise :</h4>
                                <p class='col-sm-12'>" . htmlspecialchars($qsb8) . "</p>
                                <div class='clearfix'></div>

                                <h3>5. Votre stage affectera-t-il votre orientation professionnelle ?</h3>
                                <p class='col-sm-12'>" . htmlspecialchars($qsa9) . "</p>
                                <div class='clearfix'></div>

                                <h3>6. En quoi votre stage a-t-il validé ou plus ou moins invalidé la formation que vous avez reçue au Cégep de l’Outaouais ?</h3>
                                <p class='col-sm-12'>" . htmlspecialchars($qsa10) . "</p>
                                <div class='clearfix'></div>

                                <h3>7. Suite à l'expérience que vous avez vécue en entreprise, pouvez-vous dégager une ou des faiblesses qu'aurait votre formation ? Si oui, lesquelles ?</h3>
                                <p class='col-sm-12'>" . htmlspecialchars($qsa11) . "</p>
                                <div class='clearfix'></div>

                                <h3>8. Suite à l'expérience que vous avez vécue en stage, identifiez, s'il y a lieu, les éléments qui devraient préférablement être ajoutés à notre programme.</h3>
                                <p class='col-sm-12'>" . htmlspecialchars($qsa12) . "</p>
                                <div class='clearfix'></div>

                                <form>
                                    <input type='button' value='Retour' onclick='history.back()'>
                                </form>
                            </div>
                        </div>
                    </section>
                </section>
            </div>
        </div>

        <script src='http://code.jquery.com/jquery-1.10.1.min.js'></script>
        <script src='../js/bootstrap.min.js'></script>
    </body>
    </html>";


        
    // Ensure the 'stagiaires' directory exists
    $directory = __DIR__ . '/experiences';
    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
    }

    // Écriture sécurisée du fichier
    if (file_put_contents($filenameex, $htmlContent)) {
        $_SESSION['messagesucces'] = 'L\'opération a été effectuée avec succès.';
        header("Location: dash_etudiant.php");
        exit();
    } else {
        die("Erreur : Impossible de créer le fichier.");
    }

}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Évaluation du stage</title>
    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            }
        /* Container for the main content */
        .container {
   
            padding: 20px;
            display: flex;
            gap: 20px;
     
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
        
        .column {
            flex: 1; /* Equal width for both columns */
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


        textarea {
            height:150px;
            width:100%;
            vertical-align:middle;
            padding:15px;
            letter-spacing:0.17em;
            margin-bottom:10px;
            border-radius: 4px 4px 4px 4px;
            background-color:#FFF !important;
            }

        form {
            padding:10px;
            border-radius:15px;
            
        }

        fieldset {
        display:block;

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
                <li><a class="sub-menu" href="#">Expériences de stage</a></li>
                <li><a class="sub-menu" href="evaluation.php">Évaluation de l'entreprise</a></li>
            </ul> 
        </div>
    </div>

    <!-- Hidden form for logout -->
    <form id="logoutForm" method="post" action="">
        <input type="hidden" name="disconnect">
    </form>

    <div class="tableau">
        <div class="text">        <!-- FORMS -->
            <h3>Évaluation du stage</h3>
            <p class="create">Évaluation du stage créé par <?php echo $userName; ?> le <?php echo date('j-m-y'); ?></p>
        </div>
        
        <hr style="margin-bottom:10px;">

        <form name="evalstage" action="" method="post">
		   
			<legend>1. Parmi les objectifs que vous avez retenus à l'intérieur du plan de votre stage, lesquels avez-vous atteints ? </legend> 
            
            <hr style="margin-top:20px;">
            <p>Pour vous aider à les identifier, consultez votre plan, ANNEXE 1, et vos rapports de stage, ANNEXE 2.	 <br /><br /></legend>
			Attitudes personnelles (objectifs g&eacute;n&eacute;raux) :</p>

            <div class="container">
                <p class="col-50"><label class="label_txt" for="question1a">a. </label>
                <textarea name="champ1" id="question1a" cols="50" rows="8" class="requis"></textarea></p>	
                
                <p class="col-50"><label class="label_txt" for="question1b">b.</label>
                <textarea name="champ2" id="question1b" cols="50" rows="8" class="requis"></textarea> </p>
                <div style="clear:both"></div>
            </div>
            
            <p>Habilet&eacute;s professionnelles (objectifs sp&eacute;cifiques) :</p>
            <div class="container">
                
                <p class="col-50"><label class="label_txt" for="question1a-2">a.</label>
                <textarea name="champ3" id="question1a-2" cols="50" rows="8" class="requis"></textarea></p>
                
                <p class="col-50"><label class="label_txt" for="question1b-2">b.</label>
                <textarea name="champ4" id="question1b-2" cols="50" rows="8" class="requis"></textarea></p>
            </div>
			
            
            <label class="label_txt" for="question1-5">Pourquoi et comment &ecirc;tes-vous parvenue ou parvenu &agrave; atteindre ces objectifs ?</label>	
			<textarea name="champ5" id="question1-5" cols="50" rows="8" class="requis"></textarea>
		 
		  
		  
			<legend>2. S'il y a lieu, identifiez quelques objectifs que vous n&rsquo;avez pas atteints de fa&ccedil;on satisfaisante.</legend>
			<p>Attitudes personnelles :</p>	
			<div class="container">
                <p class="col-50"><label class="label_txt" for="question2a">a.</label>
                <textarea name="champ6" id="question2a" cols="50" rows="8" class="requis"></textarea></p>
                <p class="col-50"><label class="label_txt" for="question2b">b.</label>
                <textarea name="champ7" id="question2b" cols="50" rows="8" class="requis"></textarea></p>
                <div style="clear:both"></div>
			</div>

			<p>Habilet&eacute;s professionnelles</p>
            <div class="container">
			<p class="col-50"><label class="label_txt" for="question2a-2">a.</label>
			<textarea name="champ8" id="question2a-2" cols="50" rows="8" class="requis"></textarea></p>
			<p class="col-50"><label class="label_txt" for="question2b-2">b.</label>
			<textarea name="champ9" id="question2b-2" cols="50" rows="8" class="requis"></textarea></p>
            </div>

			<p><label class="label_txt" for="question2-5">Pourquoi n'avez-vous pas r&eacute;ussi &agrave; les atteindre ?</label>
			</p>
            <textarea name="champ10" id="question2-5" cols="50" rows="8" class="requis"></textarea>
		 
		  
		  
			<legend><label class="label_txt_num" for="question3a">3. S'il y a lieu, identifiez les craintes que vous aviez au regard du stage avant de l'entreprendre ?</label></legend>
			<textarea name="champ11" id="question3a" cols="50" rows="8" class="requis"></textarea>
			<label class="label_txt" for="question3b">Parmi vos craintes (si vous en aviez), lesquelles &eacute;taient justifi&eacute;es et lesquelles ne l'&eacute;taient pas ? </label>
			<p>Commentez tr&egrave;s bri&egrave;vement votre r&eacute;ponse.</p>	
			<textarea name="champ12" id="question3b" cols="50" rows="8" class="requis"></textarea> 
		  
		  
		     
			<legend>4. Identifiez les impacts que votre s&eacute;jour en entreprise a eus sur vous, d 'une part, et sur l'entreprise, d 'autre part.</legend>
			<label class="label_txt" for="question4a">a. Pour vous : (votre vie personelle, vos &eacute;tudes, etc.)</label>
			<textarea name="champ13" id="question4a" cols="50" rows="8" class="requis"></textarea>
			<label class="label_txt" for="question4b">b. Pour l'entreprise :</label>
			<textarea name="champ14" id="question4b" cols="50" rows="8" class="requis"></textarea>
		  
		  
		    
			<legend><label class="label_txt_num" for="question5">5. Votre stage affectera-t-il votre orientation professionnelle ?</label></legend>
			<p>Justifiez votre r&eacute;ponse.</p>
			<textarea name="champ15" id="question5" cols="50" rows="8" class="requis"></textarea>
		  
		  
		   
			<legend><label class="label_txt_num" for="question6">6. En quoi votre stage a-t-il valid&eacute; ou plus ou moins invalid&eacute; la formation que vous avez re&ccedil;ue au C&eacute;gep de l&rsquo;Outaouais ?</label></legend>	
			<textarea name="champ16" id="question6" cols="50" rows="8" class="requis"></textarea>
		 
		  
		   
			<legend><label class="label_txt_num" for="question7">7. Suite &agrave; l'exp&eacute;rience que vous avez v&eacute;cue en entreprise, pouvez-vous d&eacute;gager une ou des faiblesses qu'aurait votre formation ?</label></legend>
			<p>Si oui, lesquelles ?</p>
			<textarea name="champ17" id="question7" cols="50" rows="8" class="requis"></textarea>
		  
		  
		 
			<legend><label class="label_txt_num" for="question8"> 
			8. Suite &agrave; l'exp&eacute;rience que vous avez v&eacute;cue en stage, identifiez, s'il y a lieu, les &eacute;l&eacute;ments qui devraient pr&eacute;f&eacute;rablement &ecirc;tre ajout&eacute;s &agrave; notre programme. </label></legend>	
			<textarea name="champ18" id="question8" cols="50" rows="8" class="requis"></textarea>
		  
		 	
		
			
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

