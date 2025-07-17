<?php
ob_start();
session_start();

// Check if the user is not an étudiant or not logged in
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'etudiant') {
    header("Location: index.php");
    exit();
}

include "bd.php"; // Database connection
mysqli_set_charset($con, "utf8");

$userName = "";
$dateJournal = "";

// Check if $_GET['num'] is set
if (isset($_GET['num'])) {
    $num = $_GET['num'];

    // Use prepared statement for security
    $stmt = $con->prepare("SELECT * FROM journal WHERE numero = ?");
    $stmt->bind_param("s", $num);
    $stmt->execute();
    $result_etudiant = $stmt->get_result();

    if ($result_etudiant && mysqli_num_rows($result_etudiant) > 0) {
        $row = mysqli_fetch_assoc($result_etudiant);
        $userName = $row['NomEtu'];
        $dateJournal = $row['DateJournal'];
        $commentaire = $row['commentaire'];
        mysqli_data_seek($result_etudiant, 0); // Reset pointer for the table loop
    } else {
        exit("No data found for the provided number.");
    }
}




// Handle disconnect
if (isset($_POST['disconnect'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultat du rapport</title>

<link href="css/connexion.css" rel="stylesheet">
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
            color: #f2f2f2;
            text-decoration: none;
            font-size: 18px;
            font-weight: 500;
            padding: 9px 15px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .nav-links li a:hover {
            background: #3A3B3C;
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
        .sub-menu {
            color: black !important;
        }
  
        .nav-links li a:hover {
            background: #3A3B3C;
            color: white !important;
        }
        
        input[type=submit] {

            width: 0; 
            height: 0; 
            border-bottom: 30px solid transparent;  /* left arrow slant */
            border-top: 30px solid transparent; /* right arrow slant */
            border-left: 50px solid #FFF; /* bottom, add background color here */
            border-right:none;
            background-color:transparent;
            font-size: 0;
            line-height: 0;

            }

            div.round div.round:hover {
            background-color:#999!important;

            }

            input[type=submit]:hover {

            border-left-color:#000;

            }

            input[type=submit]:active {

            border-left-color:#111;

            }

            input[name=noadm] {	
            width:430px;
            height:50px;
            margin:5px 0;
            font-size:1.2em;
            padding-left:65px;
            color:grey;
            letter-spacing:2px;

            background-color:#bbb;
            border:none;
            }

            input[name=noadm]:focus {

            background-color:#999;
            }

            input[name=mdp] {
            width:430px;
            height:50px;
            margin:5px 0;
            font-size:1.2em;
            padding-left:65px;
            color:grey;
            letter-spacing:2px;

            background-color:#bbb;
            border:none;
            }

            input[name=mdp]:focus {

            background-color:#999;

            }

            input:focus
            { 
            outline: none;
            color:black;
            }

    </style>
</head>
<body>


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
<div class="container">
    


<h2>Rapport d'étape</h2>
    <p class="text" style="text-align: left; margin-top:10px; font-weight: bold;">
        Voici le rapport d'étape de <?php echo htmlspecialchars($userName); ?>, 
        réalisé le <?php echo htmlspecialchars($dateJournal); ?>
    </p>
<hr>

<table style="border-collapse: collapse; width: 90%;">
<?php
// Reset result set pointer
mysqli_data_seek($result_etudiant, 0);

$rowCount = mysqli_num_rows($result_etudiant);
$columns = 1;
$rows = ceil($rowCount / $columns);

        for ($i = 0; $i < $rows; $i++) {
            echo '<tr>';
            for ($j = 0; $j < $columns; $j++) {
                $index = $i * $columns + $j;
                if ($index < $rowCount) {
                    $row = mysqli_fetch_assoc($result_etudiant);
                    echo '<td style="border: 1px solid black; padding: 8px; margin: 10px; text-align: center;">';
                    
                    // Creating the content with sections stacked one after another
                    echo '<div style="text-align: left; padding:20px;">'; // Using left text-align for better readability
                    
                    // Section 1: Activités significatives
                    echo '<h3>1. Activités significatives :</h3>';
                    echo '<div style="display: flex; gap: 40%; padding:50px;">'; // Flexbox for AS1 and AS2 side by side
                    echo '<p>' . $row['AS1'] . '</p>';
                    echo '<p>' . $row['AS2'] . '</p>';
                    echo '</div>'; // Close flex container
                    
                    // Section 2: Apprentissages réalisés
                    echo '<h3>2. Apprentissages réalisés :</h3>';
                    echo '<div style="display: flex; gap: 40%; padding:50px;">'; // Flexbox for AR1 and AR2 side by side
                    echo '<p>' . $row['AR1'] . '</p>';
                    echo '<p>' . $row['AR2'] . '</p>';
                    echo '</div>'; // Close flex container
                    
                    // Section 3: Difficultés rencontrées (s'il y a lieu)
                    echo '<h3>3. Difficultés rencontrées (s\'il y a lieu) :</h3>';
                    echo '<div style="display: flex; gap: 40%; padding:50px;">'; // Flexbox for DR1 and DR2 side by side
                    echo '<p>' . $row['DR1'] . '</p>';
                    echo '<p>' . $row['DR2'] . '</p>';
                    echo '</div>'; // Close flex container
                    
                    // Section 4: Commentaires - Questions
                    echo '<h3>4. Commentaires - Questions :</h3>';
                    echo '<div style="display: flex; gap: 40%; padding:50px;">'; // Flexbox for C1 and C2 side by side
                    echo '<p>' . $row['C1'] . '</p>';
                    echo '<p>' . $row['C2'] . '</p>';
                    echo '</div>'; // Close flex container
                    
                    echo '</div>'; // Close the content div
                    echo '</td>';
                }
                 else {
                    echo '<td style="border: 1px solid black; padding: 8px;"></td>';
                }
            }
            echo '</tr>';
        }
        ?>
        
    </table>

    <h2>Commentaire de votre superviseur</h2>
    <?php echo htmlspecialchars($commentaire); ?>
    
    <div class="form-group" style="text-align: left;">
        <button><a href="dash_etudiant.php">Retour</a></button>
    </div>
</div>

</body>
</html>
