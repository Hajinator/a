<?php
// This script deletes an artist

include 'db_connect.php'; // Connect to database

// Get the ArtistID from the form
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ArtistID'])) {
    $artistID = $_POST['ArtistID'];

    // Optional: Prepare the SQL statement to fetch the artist details before deletion
    $fetchStmt = $pdo->prepare("SELECT Name, LifeSpan, Nationality, Century FROM Artists WHERE ArtistID = :artistID");
    $fetchStmt->bindParam(':artistID', $artistID, PDO::PARAM_INT);
    $fetchStmt->execute();
    $artist = $fetchStmt->fetch(PDO::FETCH_ASSOC);

    // Prepare the SQL statement to delete the artist
    $stmt = $pdo->prepare("DELETE FROM Artists WHERE ArtistID = :artistID");
    $stmt->bindParam(':artistID', $artistID, PDO::PARAM_INT);

    // Execute the delete statement
    if ($stmt->execute()) {
        // Optional: Log the deletion with artist details
        // You can add logging functionality here if needed

        // Redirect back to the main page after deletion
        header("Location: ../pages/display_artist.php");
        exit;
    } else {
        echo "Error deleting artist.";
    }
}
