<?php
// Script for updating artist

include 'db_connect.php'; // Include database connection

// Get the ArtistID from the POST request
$artist = null; 
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ArtistID'])) {
    $artistID = $_POST['ArtistID'];

    // Prepare the SQL statement to fetch the artist details
    $stmt = $pdo->prepare("SELECT * FROM Artists WHERE ArtistID = :artistID");
    $stmt->bindParam(':artistID', $artistID, PDO::PARAM_INT);
    $stmt->execute();
    $artist = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$artist) {
        echo "Artist not found.";
        exit;
    }

    // Get the artist details
    $name = $artist['Name'];
    $lifeSpan = $artist['LifeSpan'];
    $nationality = $artist['Nationality'];
    $century = $artist['Century']; // Get the new Century field
}

// If the form is submitted with new data, handle the update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name'])) {
    // Prepare to update artist in the database
    $updateStmt = $pdo->prepare("UPDATE Artists SET Name = :name, LifeSpan = :lifeSpan, Nationality = :nationality, Century = :century WHERE ArtistID = :artistID");
    $updateStmt->bindParam(':name', $_POST['name']);
    $updateStmt->bindParam(':lifeSpan', $_POST['lifeSpan']);
    $updateStmt->bindParam(':nationality', $_POST['nationality']);
    $updateStmt->bindParam(':century', $_POST['century']); // Bind the new Century field
    $updateStmt->bindParam(':artistID', $artistID, PDO::PARAM_INT);
    
    if ($updateStmt->execute()) {
        // Redirect after successful update
        header("Location: ../pages/artists.php");
        exit;
    } else {
        echo "Failed to update artist.";
    }
}
?>

<!-- Modal for Editing an Artist -->
<div class="modal fade" id="editArtistModal" tabindex="-1" aria-labelledby="editArtistModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editArtistModalLabel">Edit Artist</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <input type="hidden" name="ArtistID" value="<?= isset($artist) ? $artist['ArtistID'] : ''; ?>">

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="editArtistName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="editArtistName" name="name" value="<?= isset($artist) ? htmlspecialchars($artist['Name']) : ''; ?>" required>
                    </div>

                    <!-- Life Span -->
                    <div class="mb-3">
                        <label for="editLifeSpan" class="form-label">Life Span</label>
                        <input type="text" class="form-control" id="editLifeSpan" name="lifeSpan" value="<?= isset($artist) ? htmlspecialchars($artist['LifeSpan']) : ''; ?>" required>
                    </div>

                    <!-- Nationality -->
                    <div class="mb-3">
                        <label for="editNationality" class="form-label">Nationality</label>
                        <input type="text" class="form-control" id="editNationality" name="nationality" value="<?= isset($artist) ? htmlspecialchars($artist['Nationality']) : ''; ?>" required>
                    </div>

                    <!-- Century -->
                    <div class="mb-3">
                        <label for="editCentury" class="form-label">Century</label>
                        <input type="text" class="form-control" id="editCentury" name="century" value="<?= isset($artist) ? htmlspecialchars($artist['Century']) : ''; ?>" required>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
