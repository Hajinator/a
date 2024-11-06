<?php
//This script deletes paintings

include 'db_connect.php'; //Connect to database

 // Get the PaintingID from the form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $paintingID = $_POST['PaintingID'];

    // Prepare the SQL statement to delete the painting
    $stmt = $pdo->prepare("DELETE FROM Paintings WHERE PaintingID = :paintingID");
    $stmt->bindParam(':paintingID', $paintingID, PDO::PARAM_INT);

    // Execute the delete statement
    if ($stmt->execute()) {
        // Redirect back to main page after deletion
        header("Location: ../pages/artwork.php");
        exit;
    } else {
        echo "Error deleting painting.";
    }
}