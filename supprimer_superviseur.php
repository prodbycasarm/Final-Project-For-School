<?php
ob_start();
include "bd.php";

// Validate the query parameter
if (isset($_GET['num']) && !empty($_GET['num'])) {
    // Use prepared statements to prevent SQL injection
    $numsup = $_GET['num'];

    // Prepare the delete query
    $sql = "DELETE FROM acces_adm WHERE noemploye = ?";
    $stmt = $con->prepare($sql);

    if ($stmt) {
        // Bind the parameter and execute the query
        $stmt->bind_param('s', $numsup);

        if ($stmt->execute()) {
            // Redirect after successful deletion
            header("Location: admin_superviseur.php?message=success");
            exit;
        } else {
            // Redirect with an error message
            header("Location: admin_superviseur.php?message=error&details=" . urlencode($stmt->error));
            exit;
        }

        $stmt->close();
    } else {
        // Handle query preparation errors
        header("Location: admin_superviseur.php?message=error&details=" . urlencode($con->error));
        exit;
    }
} else {
    // Redirect if 'num' parameter is missing or invalid
    header("Location: admin_superviseur.php?message=invalid");
    exit;
}

// Close the database connection
$con->close();
?>
