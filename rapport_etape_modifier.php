<?php
ob_start();
session_start();


if (isset($_GET['message'])) {
    echo "<div class='message'>{$_GET['message']}</div>";
}

// Check if the user is not an étudiant or not logged in
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    // Redirect to login page or any other page as needed
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

// Set welcome message if it's not set
$welcome_message = isset($_SESSION['welcome_message']) ? $_SESSION['welcome_message'] : '';

?>

<?php
include "bd.php";
mysqli_set_charset($con, "utf8");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $updatedComment = trim($_POST['commentaires']); // Trim to remove extra whitespace
    $num = $_GET['num'] ?? null; // Get the 'numero' from the URL or original source

    if ($num) {
        // Use a prepared statement to update the record
        $stmt = $con->prepare("UPDATE journal SET commentaire = ? WHERE numero = ?");
        $stmt->bind_param("ss", $updatedComment, $num); // Bind both parameters

        if ($stmt->execute()) {
            $_SESSION['message'] = 'Commentaire mis à jour avec succès!';  // Store success message in session
            header("Location: rapport_etape_modifier.php?num=" . urlencode(htmlspecialchars($num)));
            exit(); // Stop further execution after the redirect
        } else {
            $_SESSION['message'] = 'Erreur lors de la mise à jour du commentaire : ' . $stmt->error;  // Store error message in session
            header("Location: rapport_etape_modifier.php?num=" . urlencode(htmlspecialchars($num)));
            exit(); // Stop further execution after the redirect
        }
        
        $stmt->close(); // Always close the statement after use
    } else {
        $_SESSION['message'] = "Numéro de rapport non spécifié."; // Handle error case if no 'num' is provided
        header("Location: rapport_etape_modifier.php?num=" . urlencode(htmlspecialchars($num)));
        exit(); // Stop further execution after the redirect
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superviseurs - Résultat des rapports</title>
</head>
<link href="css/connexion.css" rel="stylesheet">
<style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
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
        
        
        
        
            /* Container for the main content */
        .container {
            max-width: 1500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.6);
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

    h1{
        font-size: 1.5em !important;
    }

    </style>
</head>
<body>







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
                    <li><a class="sub-menu" href="experience.php">Évaluations des Stagiaires</a></li>
                </ul> 
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
    

<?php
// Pour le sous titre
if(isset($_GET['NAME'])){
    // Get the value from $_GET['num']
    $num = $_GET['NAME'];
    
    // Fetch data from the database
    // Prepare SQL query
    $sql_etudiant = "SELECT * FROM journal WHERE numero AND numetu = '$name'";
    $result_etudiant = mysqli_query($con, $sql_etudiant);
    

    // Check if there is a row fetched
    if ($row = mysqli_fetch_assoc($result_etudiant)) {
        // Retrieve and store values from the fetched row
        $userName = $row['NomEtu']; // Assuming 'NomEtu' is the column you want to store as the user's name
        $userSupervisor = $row['nomsup']; // Assuming 'nomsup' is the column for supervisor's name
    } else {
        // Handle the case where no row is fetched, for example:
        // Redirect to an error page or display a message
        exit("No data found for the provided number.");
    }
}




?>
    <h2>Rapport d'étape</h2>
    <p class="text" style="text-align: left; margin-top:10px; font-weight: bold;">
        Voici le rapport d'étape de <?php echo htmlspecialchars($userName); ?>, 
        réalisé le <?php echo htmlspecialchars($dateJournal); ?>
    </p>
    <?php if (!empty($_SESSION['message'])): ?>
    <p style="color: <?php echo (strpos($_SESSION['message'], 'succès') !== false) ? 'green' : 'red'; ?>;">
        <?php echo $_SESSION['message']; ?>
    </p>
    <?php unset($_SESSION['message']);  // Clear the message after displaying it
endif;
?>
    <table style="border-collapse: collapse; width: 90%;">
        <?php
        $rowCount = mysqli_num_rows($result_etudiant); // Get the total number of rows
        $columns = 1; // Number of columns per row
        $rows = ceil($rowCount / $columns); // Calculate the number of rows needed

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

    
    <h2>Commentaire</h2>
    <?php echo htmlspecialchars($commentaire); ?>
    <br><br>
    <?php
    $rapport_etape_link = "rapport_etape_modifier.php?num=" . htmlspecialchars($row['numero']);
                ?>
    <form method="post" action="<?php echo $rapport_etape_link; ?>">
        <label for="commentaires">Modifier un commentaire</label><br><br>
        <textarea style="width:100%; height:200px; white-space: nowrap;" id="commentaires" name="commentaires" required>
        <?php echo htmlspecialchars(trim($commentaire)); ?>
        </textarea>
        <br><br>
        
        <div class="form-group" style="text-align: left;">
            <button type="submit" name="update">Modifier</button>
        </div>
    </form>    
    <div class="form-group" style="text-align: left;">
        <button><a href="page_admin.php">Retour</a></button>
    </div>
</div>


</body>
</html>
