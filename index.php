<?php

ob_start();
session_start();



$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username
    if (empty($_POST["numero"])) {
        $errors[] = "Le nom d'utilisateur est requis.";
    }

    // Validate password
    if (empty($_POST["mdp"])) {
        $errors[] = "Le mot de passe est requis.";
    }

    // If there are no errors, proceed with login
    if (empty($errors)) {
        include "bd.php";
        mysqli_set_charset($con, "utf8mb4");

        // Query admin table
        $sql_admin = "SELECT * FROM acces_adm WHERE noemploye = ? AND motdepasse = ?";
        $stmt_admin = mysqli_prepare($con, $sql_admin);
        mysqli_stmt_bind_param($stmt_admin, "ss", $_POST["numero"], $_POST["mdp"]);
        mysqli_stmt_execute($stmt_admin);
        $result_admin = mysqli_stmt_get_result($stmt_admin);
        $admin_row = mysqli_fetch_assoc($result_admin);

        // Query etudiant table
        $sql_etudiant = "SELECT * FROM journal WHERE numetu = ? AND EXISTS (SELECT * FROM acces_etu WHERE motdepasse = ? AND numetu = ?)";
        $stmt_etudiant = mysqli_prepare($con, $sql_etudiant);
        mysqli_stmt_bind_param($stmt_etudiant, "sss", $_POST["numero"], $_POST["mdp"], $_POST["numero"]);
        mysqli_stmt_execute($stmt_etudiant);
        $result_etudiant = mysqli_stmt_get_result($stmt_etudiant);
        $etudiant_row = mysqli_fetch_assoc($result_etudiant);

        // Query employeur table
        $sql_employeur = "SELECT * FROM acces_employeurs WHERE noemployeur = ? AND mdpemployeur = ?";
        $stmt_employeur = mysqli_prepare($con, $sql_employeur);
        mysqli_stmt_bind_param($stmt_employeur, "ss", $_POST["numero"], $_POST["mdp"]);
        mysqli_stmt_execute($stmt_employeur);
        $result_employeur = mysqli_stmt_get_result($stmt_employeur);
        $employeur_row = mysqli_fetch_assoc($result_employeur);

        // Check login credentials
        if ($admin_row) {
            // Admin login successful
            $_SESSION['user_id'] = $admin_row['id'];
            $_SESSION['user_type'] = 'admin';
            $_SESSION['welcome_message'] = $admin_row['nomsup'];
            header("Location: page_admin.php");
            exit();
        } elseif ($etudiant_row) {
            // Etudiant login successful
            $_SESSION['user_id'] = $_POST["numero"]; // Assuming $_POST["numero"] contains the student's ID
            $_SESSION['user_type'] = 'etudiant';
            $_SESSION['welcome_message'] = $etudiant_row['NomEtu'];
            header("Location: dash_etudiant.php");
            exit();
        } elseif ($employeur_row) {
            // Employeur login successful
            $_SESSION['user_id'] = $employeur_row['id'];
            $_SESSION['user_type'] = 'employeur';
            $_SESSION['welcome_message'] = $employeur_row['Nom de l\'employeur'];
            $_SESSION['user_id2'] = $employeur_row['noemployeur'];
            header("Location: page_employeur.php");
            exit();
        } else {
            // Login failed
            $errors[] = "Identifiants invalides. Veuillez réessayer.";
        }
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Armando - Rapports de Stage</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="css/base.css" rel="stylesheet">
    <link href="css/connexion.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script>
    // Log off user when navigating back
    window.onpageshow = function(event) {
        if (event.persisted) {
            // Destroy all session data
            <?php
            session_unset();
            session_destroy();
            ?>
            // Redirect to login page or any other page as needed
            window.location.href = "index.php";
        }
    };
</script>
</head>
<style>
    body {
        background-color: #f0f0f0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .container {
        max-width: 600px;
        margin: 100px auto;
        padding: 40px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    h1 {
        text-align: center;
        color: #333;
    }
    .form-group {
        margin-bottom: 20px;
    }
    input[type="text"], input[type="password"] {
        width: 100%;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
        box-sizing: border-box;
        transition: border-color 0.3s ease;
    }
    input[type="text"]:focus, input[type="password"]:focus {
        border-color: #28282d;
    }
    
    .error-message {
        color: #ff0000;
        text-align: center;
        margin-top: 10px;
    }

    .button-86 {
    all: unset;
    width: 100px;
    height: 30px;
    font-size: 16px;
    background: transparent;
    border: none;
    position: relative;
    color: #f0f0f0;
    cursor: pointer;
    z-index: 1;
    padding: 10px 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    white-space: nowrap;
    user-select: none;
    -webkit-user-select: none;
    touch-action: manipulation;
    }

    .button-86::after,
    .button-86::before {
    content: '';
    position: absolute;
    bottom: 0;
    right: 0;
    z-index: -99999;
    transition: all .4s;
    }

    .button-86::before {
    transform: translate(0%, 0%);
    width: 100%;
    height: 100%;
    background: #28282d;
    border-radius: 10px;
    }

    .button-86::after {
    transform: translate(10px, 10px);
    width: 35px;
    height: 35px;
    background: #ffffff15;
    backdrop-filter: blur(5px);
    -webkit-backdrop-filter: blur(5px);
    border-radius: 50px;
    }

    .button-86:hover::before {
    transform: translate(5%, 20%);
    width: 110%;
    height: 110%;
    }

    .button-86:hover::after {
    border-radius: 10px;
    transform: translate(0, 0);
    width: 100%;
    height: 100%;
    }

    .button-86:active::after {
    transition: 0s;
    transform: translate(0, 5%);
    }
</style>
<body>
<div class="container">
    <h1>Rapports de stages - Intégration Multimédia</h1>
    <form method="post" action="">
        <div class="form-group">
            <input name="numero" type="text" size="15" maxlength="7" placeholder="Entrez votre nom d'utilisateur" required>
        </div>
        <div class="form-group">
            <input name="mdp" type="password" size="15" maxlength="7" placeholder="Entrez votre mot de passe" required>
        </div>
        <div class="form-group" style="text-align: center;">
            <button type="submit" class="button-86">Connexion</button>
        </div>
    </form>
    <?php
    // Display errors if any
    if (!empty($errors)) {
        echo '<div class="error-message">' . implode("<br>", $errors) . '</div>';
    }
    ?>
</div>
</body>
</html>
