<?php
//Script for updating painting

include 'db_connect.php'; // Include database connection

// Get the PaintingID from the POST request
$painting = null; 
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['PaintingID'])) {
    $paintingID = $_POST['PaintingID'];

    // Prepare the SQL statement to fetch the painting details
    $stmt = $pdo->prepare("SELECT * FROM Paintings WHERE PaintingID = :paintingID");
    $stmt->bindParam(':paintingID', $paintingID, PDO::PARAM_INT);
    $stmt->execute();
    $painting = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$painting) {
        echo "Painting not found.";
        exit;
    }

    // Get the painting details
    $title = $painting['Title'];
    $artistID = $painting['ArtistID'];
    $style = $painting['Style'];
    $media = $painting['Media'];
    $finished = $painting['Finished'];

    // Fetch artists and styles for the dropdowns
    $artistsStmt = $pdo->query("SELECT * FROM Artists"); // Assuming you have an Artists table
    $artists = $artistsStmt->fetchAll(PDO::FETCH_ASSOC);

    $stylesStmt = $pdo->query("SELECT DISTINCT Style FROM Paintings"); // Assuming styles are stored in Paintings
    $styles = $stylesStmt->fetchAll(PDO::FETCH_ASSOC);
}

// If the form is submitted with new data, handle the update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['title'])) {
    // Prepare to update painting in the database
    $updateStmt = $pdo->prepare("UPDATE Paintings SET Title = :title, ArtistID = :artistID, Style = :style, Media = :media, Finished = :finished WHERE PaintingID = :paintingID");
    $updateStmt->bindParam(':title', $_POST['title']);
    $updateStmt->bindParam(':artistID', $_POST['artist']);
    $updateStmt->bindParam(':style', $_POST['style']);
    $updateStmt->bindParam(':media', $_POST['media']);
    $updateStmt->bindParam(':finished', $_POST['finished']);
    $updateStmt->bindParam(':paintingID', $paintingID, PDO::PARAM_INT);
    
    if ($updateStmt->execute()) {
        // Redirect after successful update
        header("Location:  ../pages/artwork.php");
        exit;
    } else {
        echo "Failed to update painting.";
    }
}
?>


<!-- Modal for Editing a Painting -->
<div class="modal fade" id="editPaintingModal" tabindex="-1" aria-labelledby="editPaintingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPaintingModalLabel">Edit Painting</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="PaintingID" value="<?= isset($painting) ? $painting['PaintingID'] : ''; ?>">

                    <!-- Title -->
                    <div class="mb-3">
                        <label for="editTitle" class="form-label">Title</label>
                        <input type="text" class="form-control" id="editTitle" name="title" value="<?= isset($painting) ? htmlspecialchars($painting['Title']) : ''; ?>" required>
                    </div>
                    
                    <!-- Artist Dropdown -->
                    <div class="mb-3">
                        <label for="editArtistId" class="form-label">Artist</label>
                        <select class="form-select" id="editArtistId" name="artist" required>
                            <option value="" disabled>Select an artist</option>
                            <?php foreach ($artists as $artist): ?>
                                <option value="<?= htmlspecialchars($artist['ArtistID']); ?>" <?= ($artist['ArtistID'] == $artistID) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($artist['Name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Style Dropdown -->
                    <div class="mb-3">
                        <label for="editStyle" class="form-label">Style</label>
                        <select class="form-select" id="editStyle" name="style" required>
                            <option value="" disabled>Select a style</option>
                            <?php foreach ($styles as $styleOption): ?>
                                <option value="<?= htmlspecialchars($styleOption['Style']); ?>" <?= ($styleOption['Style'] == $style) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($styleOption['Style']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Media -->
                    <div class="mb-3">
                        <label for="editMedia" class="form-label">Media</label>
                        <input type="text" class="form-control" id="editMedia" name="media" value="<?= htmlspecialchars($media); ?>" required>
                    </div>

                    <!-- Finished Year -->
                    <div class="mb-3">
                        <label for="editFinished" class="form-label">Finished</label>
                        <input type="text" class="form-control" id="editFinished" name="finished" value="<?= htmlspecialchars($finished); ?>" required>
                    </div>

                    <!-- Image File -->
                    <div class="mb-3">
                        <label for="editImage" class="form-label">Upload Image (optional)</label>
                        <input type="file" class="form-control" id="editImage" name="image" accept="image/*">
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
