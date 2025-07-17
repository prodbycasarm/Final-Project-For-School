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

// Check if there is a row fetched
if ($row = mysqli_fetch_assoc($result_etudiant)) {
    // Retrieve and store values from the fetched row
    $userName = $row['NomEtu']; // Assuming 'NomEtu' is the column you want to store as the user's name
    $userSupervisor = $row['nomsup']; // Assuming 'nomsup' is the column for supervisor's name
} else {
    // If no row is fetched, set $userName and $userSupervisor to empty strings or handle it as needed
    $userName = "";
    $userSupervisor = "";
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $date = $_POST['date'];
    $activity1 = $_POST['as1'];
    $activity2 = $_POST['as2'];
    $learning1 = $_POST['ar1'];
    $learning2 = $_POST['ar2'];
    $difficulty1 = $_POST['dr1'];
    $difficulty2 = $_POST['dr2'];
    $comment1 = $_POST['c1'];
    $comment2 = $_POST['c2'];

    // Insertion des données dans la base de données
    $sql_insert = "INSERT INTO journal (numetu, NomEtu, nomsup, DateJournal, AS1, AS2, AR1, AR2, DR1, DR2, C1, C2) 
                   VALUES ('{$_SESSION['user_id']}', '$userName', '$userSupervisor', '$date', '$activity1', '$activity2', '$learning1', '$learning2', '$difficulty1', '$difficulty2', '$comment1', '$comment2')";

    if (mysqli_query($con, $sql_insert)) {


        // Récupération de l'adresse e-mail du superviseur
        $sql_adm_email = "SELECT courriel FROM acces_adm WHERE nomsup = '$userSupervisor'";
        $result_email = mysqli_query($con, $sql_adm_email);

        if ($email_row = mysqli_fetch_assoc($result_email)) {
            $adm_email = $email_row['courriel'];

            // Configuration de l'e-mail
            
            $to = $adm_email;
            $subject = "Nouveau Rapport Enregistré";
            $message = "Un nouveau rapport de l'étudiant $userName a été enregistré.\n\nDétails :\nDate : $date\nActivités : $activity1, $activity2\nApprentissages : $learning1, $learning2\nDifficultés : $difficulty1, $difficulty2\nCommentaires : $comment1, $comment2";
            $headers = "From: contact@casarm.com";

   
            // Envoi de l'e-mail
            if (mail($to, $subject, $message, $headers)) {
                $_SESSION['messagesucces'] = 'L\'opération a été effectuée avec succès.';
                header("Location: dash_etudiant.php");
                
            } else {
                echo " Échec de l'envoi de l'e-mail.";
            }
            } else {
            echo "Error: " . $sql_insert . "<br>" . mysqli_error($con);
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapports d'étape</title>
    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            }
        /* Container for the main content */
        .container {
            max-width: 900px;
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
                <li><a class="sub-menu" href="#">Rapports d'étape</a></li>
                <li><a class="sub-menu" href="experience.php">Expériences de stage</a></li>
                <li><a class="sub-menu" href="evaluation.php">Évaluation de l'entreprise</a></li>
            </ul> 
            </div>
    </div>


    <!-- Hidden form for logout -->
    <form id="logoutForm" method="post" action="">
        <input type="hidden" name="disconnect">
    </form>
    <script>
        document.getElementById('disconnect').addEventListener('click', function() {
            // Submit the form when the "Déconnecter" li element is clicked
            document.getElementById('logoutForm').submit();
        });
    </script>


    <div class="tableau">
        <div class="text">
            <h2>Rapport d'étape</h2>
            <p class="text1">Rapport d'étape créé par <?php echo $userName; ?> et remis à  <?php echo $userSupervisor; ?> le <?php echo date('Y-m-d'); ?></p>
        </div>
        <div>
            <hr style="margin:15px; border: 2px solid black">
			<h3>Rapport d'étape  </h3>
			<p>Rapport d'étape est un outil de réflexion personnalisé; tout au long de son stage, la ou le stagiaire y consigne plus particulièrement les tâches professionnelles qu’elle ou qu'il accomplit dans l'entreprise ainsi que les apprentissages que ces dernières lui permettent de réaliser.  </p>
			<h3>Objectifs  </h3>
			<p><ul><li>Objectiver de façon continue son vécu professionnel.</li>
			 <li> Consigner ses réflexions. </li></ul></p>
			 <h3> Directives</h3>
			 <p> Tout au long de son stage, la ou le stagiaire doit complétéer des rapports d'étapes. <strong>Deux ou trois fois dans la session</strong>, elle ou il complète un rapport d'étape et le transmet à son superviseur de stage par l'entremise du site Web de stages. </p>
			  
			<p class="end"></p>
            <hr style="margin:15px; border: 2px solid black">
        </div>
        <form name="jdb" method="post">

            <!-- Section 1: Activités significatives -->
            <fieldset>
                <legend>1. Activités significatives :</legend>
                <div class="row">
                    <p class="col-50">
                        <label for="question1a">Veuillez entrer une première activité significative cette semaine :</label>
                        <textarea name="as1" cols="50" rows="3" id="question1a" class="requis"></textarea>
                    </p>
                    <p class="col-50">
                        <label for="question1b">Veuillez entrer une deuxième activité significative cette semaine :</label>
                        <textarea name="as2" cols="50" rows="3" id="question1b" class="requis"></textarea>
                    </p>
                </div>
            </fieldset>

            <!-- Section 2: Apprentissages réalisés -->
            <fieldset>
                <legend>2. Apprentissages réalisés :</legend>
                <div class="row">
                    <p class="col-50">
                        <label for="question2a">Veuillez indiquer l'apprentissage relié à la première activité significative :</label>
                        <textarea name="ar1" cols="50" rows="3" id="question2a" class="requis"></textarea>
                    </p>
                    <p class="col-50">
                        <label for="question2b">Veuillez indiquer l'apprentissage relié à la deuxième activité significative :</label>
                        <textarea name="ar2" cols="50" rows="3" id="question2b" class="requis"></textarea>
                    </p>
                </div>    
            </fieldset>

            <!-- Section 3: Difficultés rencontrées -->
            <fieldset>
                <legend>3. Difficultés rencontrées (s'il y a lieu) :</legend>
                <div class="row">
                    <p class="col-50">
                        <label for="question3a">Veuillez indiquer une première difficulté rencontrée :</label>
                        <textarea name="dr1" cols="50" rows="3" id="question3a" class="requis"></textarea>
                    </p>
                    <p class="col-50">
                        <label for="question3b">Veuillez indiquer une deuxième difficulté rencontrée :</label>
                        <textarea name="dr2" cols="50" rows="3" id="question3b" class="requis"></textarea>
                    </p>
                </div>    
            </fieldset>

            <!-- Section 4: Commentaires - Questions -->
            <fieldset>
                <legend>4. Commentaires - Questions :</legend>
                <div class="row">
                    <p class="col-50">
                        <label for="question4a">Veuillez indiquer un premier commentaire ou une question pertinente :</label>
                        <textarea name="c1" cols="50" rows="3" id="question4a" class="requis"></textarea>
                    </p>
                    <p class="col-50">
                        <label for="question4b">Veuillez indiquer un deuxième commentaire ou une question pertinente :</label>
                        <textarea name="c2" cols="50" rows="3" id="question4b" class="requis"></textarea>
                    </p>
                </div>    
            </fieldset>

            <!-- Date Field -->
            <div class="form-group">
                <label class="date" for="date">Date:</label>
                <input type="text" id="date" name="date" value="<?php echo date('Y-m-d'); ?>" readonly required>
            </div>

            <!-- Submit and Reset Buttons -->
            <input type="submit" name="envoyer" value="Envoyer" class="mybutton1">
            <input type="reset" name="Réinitialiser" value="Réinitialiser" class="mybutton2">
        </form>


    </div>


<div>
<script>
    document.getElementById('myForm').addEventListener('submit', function(event) {
        // Reload the page when the form is submitted
        location.reload();
    });
</script>


</body>
</html>
