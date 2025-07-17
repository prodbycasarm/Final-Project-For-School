<?php
ob_start();
session_set_cookie_params([
    'secure' => true, // Only send cookies over HTTPS
    'httponly' => true, // Prevent JavaScript access to session cookie
    'samesite' => 'Strict' // Restrict cross-site access
]);
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

$stmt = $con->prepare("SELECT * FROM journal WHERE numetu = ?");
$stmt->bind_param("s", $_SESSION['user_id']);
$stmt->execute();
$result_etudiant = $stmt->get_result();


// Loop through the fetched data
while ($row = mysqli_fetch_assoc($result_etudiant)) {
    $_SESSION['type'] = $row['NomEtu'];
}



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
    // Retrieve form data
    $date = $_POST['date'];
    $activity1 = $_POST['activity1'];
    $activity2 = $_POST['activity2'];
    $learning1 = $_POST['learning1'];
    $learning2 = $_POST['learning2'];
    $difficulty1 = $_POST['difficulty1'];
    $difficulty2 = $_POST['difficulty2'];
    $comment1 = $_POST['comment1'];
    $comment2 = $_POST['comment2'];

    // Insert data into the database including the user's name
    $sql_insert = "INSERT INTO journal (numetu, NomEtu, nomsup, DateJournal, AS1, AS2, AR1, AR2, DR1, DR2, C1, C2) VALUES ('{$_SESSION['user_id']}', '$userName', '$userSupervisor', '$date', '$activity1', '$activity2', '$learning1', '$learning2', '$difficulty1', '$difficulty2', '$comment1', '$comment2')";

    if (mysqli_query($con, $sql_insert)) {
        echo "Nouveau Rapport Enrigistré";
    } else {
        echo "Error: " . $sql_insert . "<br>" . mysqli_error($con);
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashbord étudiant</title>
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
        
    </style>
</head>
<body>

<div class="main">
        <nav>
            <div class="menu">
                <div class="titre"><h1 href="#">Stage technique d'intégration multimédia</h1></div>
                <ul class="nav-links-menu">
                <li style="color:white; font-size:18px;"><?php echo htmlspecialchars($_SESSION['type'], ENT_QUOTES, 'UTF-8'); ?> | </li>

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

    <?php
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
    ?>
    <div class="tableau">
    <h2 class="tous">Tous les rapports</h2>
    <p class="tous">Vous avez complété  <?php echo mysqli_num_rows($result_etudiant); ?> de rapports d'étape. <a class="rapport-link"href="page_etudiant.php">Créez un nouveau rapport d'étape.</a></p>
    <div class="tous">
        <?php 
        // Display success message if available
        if (!empty($_SESSION['messagesucces'])): ?>
            <p style="color: green;">
                <?php echo $_SESSION['messagesucces']; ?>
            </p>
            <?php unset($_SESSION['messagesucces']); // Clear the message after displaying it
        endif;

        // Display error message if available
        if (!empty($_SESSION['messageerror'])): ?>
            <p style="color: red;">
                <?php echo $_SESSION['messageerror']; ?>
            </p>
            <?php unset($_SESSION['messageerror']); // Clear the message after displaying it
        endif; 
        ?>   
    </div>
    
    <table style="border-collapse: collapse; width: 70%;">
        <tr>
            <th style="border: 1px solid black; padding: 8px; text-align: center;">Numéro</th>
            <th style="border: 1px solid black; padding: 8px;">Date</th>
            <th style="border: 1px solid black; padding: 8px;">Action</th>
        </tr>
        
        <?php
        // Reset the data pointer to the beginning of the result set
        mysqli_data_seek($result_etudiant, 0);
        
        // Initialize a counter for the number of DateJournal entries
        $counter = 1;

        while ($row = mysqli_fetch_assoc($result_etudiant)) {
            ?>
        
            <tr>
                <td style="border: 1px solid black; padding: 8px; text-align: center;"><?php echo $counter; ?></td>
                <td style="border: 1px solid black; padding: 8px;"><?php echo $row['DateJournal']; ?></td>
                <td style="border: 1px solid black; padding: 8px;">
                    <?php 
                    // Check if there is a row fetched
                    if ($row) {
                        // Retrieve and store values from the fetched row
                        $userName = $row['NomEtu']; // Assuming 'NomEtu' is the column you want to store as the user's name
                        $userSupervisor = $row['nomsup']; // Assuming 'nomsup' is the column for supervisor's name
                        $rapport_etape_link = "rapport_etape.php?num=" . $row['numero'];
                        $commentaire = $row['commentaire'];
                        ?>
                        <a href="<?php echo $rapport_etape_link; ?>">
                            <img src="img/voir.png"  width="20" height="20">
                        </a>

                        <a href="<?php echo $rapport_etape_link; ?>" title="Modifier un commentaire">
                                <?php
                                if (empty($commentaire)) {
                                    // If the commentaire is empty, show 'no comment' icon
                                    echo '';
                                } else {
                                    // If there is a commentaire, show 'comment' icon
                                    echo '<img src="img/icon_comment.png" width="20" height="20" alt="Modifier le rapport" style="vertical-align: middle; margin-right: 8px;">';
                                }
                                ?>
                            </a>
                        <?php
                    } else {
                        // If no row is fetched, set $userName and $userSupervisor to empty strings or handle it as needed
                        $userName = "";
                        $userSupervisor = "";
                    }
                    ?>
                </td>
            </tr>
            <?php
            // Increment the counter
            $counter++;
        }
        ?>
    </table>
    </div>
</div>    
</body>
</html>
