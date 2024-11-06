<?php
// This script is for adding artists and also contains the modal for adding artists.

include 'db_connect.php'; // Connect to database

// Get the form data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $lifespan = $_POST['lifespan'];
    $nationality = $_POST['nationality'];
    $century = $_POST['century']; // Retrieve century data

    // Handle thumbnail upload
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == UPLOAD_ERR_OK) {
        $thumbnail = $_FILES['thumbnail']['tmp_name'];
        $thumbnailBlob = file_get_contents($thumbnail);

        // Prepare the SQL insert statement
        $sql = "INSERT INTO Artists (Name, LifeSpan, Nationality, Century, Thumbnail) 
                VALUES (:name, :lifespan, :nationality, :century, :thumbnail)";
        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':lifespan', $lifespan);
        $stmt->bindValue(':nationality', $nationality);
        $stmt->bindValue(':century', $century); // Bind century parameter
        $stmt->bindValue(':thumbnail', $thumbnailBlob, PDO::PARAM_LOB);

        // Execute
        if ($stmt->execute()) {
            // Once artist has been added, call javascript to reload page
            echo "<script>
                    window.onload = function() {
                        reloadAfterAdd();
                    }
                  </script>";
            exit;
        } else {
            echo "Error adding artist: " . implode(" ", $stmt->errorInfo());
        }
    } else {
        echo "Error uploading thumbnail: " . $_FILES['thumbnail']['error'];
    }
}
?>

<!-- HTML for Modal -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Artist</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="../js/reloadafteradd.js"></script>
</head>
<body>

<!-- Modal for Adding an Artist -->
<div class="modal fade" id="addArtistModal" tabindex="-1" aria-labelledby="addArtistModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addArtistModalLabel">Add a New Artist</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="addArtistForm" method="POST" enctype="multipart/form-data">

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name"
                               placeholder="Enter artist name" required>
                    </div>

                    <!-- Life Span -->
                    <div class="mb-3">
                        <label for="lifespan" class="form-label">Life Span</label>
                        <input type="text" class="form-control" id="lifespan" name="lifespan"
                               placeholder="Enter artist life span (e.g., 1841-1919)" required>
                    </div>

                    <!-- Nationality -->
                    <div class="mb-3">
                        <label for="nationality" class="form-label">Nationality</label>
                        <input type="text" class="form-control" id="nationality" name="nationality"
                               placeholder="Enter artist nationality" required>
                    </div>

                    <!-- Century -->
                    <div class="mb-3">
                        <label for="century" class="form-label">Century</label>
                        <input type="text" class="form-control" id="century" name="century"
                               placeholder="Enter artist century (e.g., 19th)" required>
                    </div>

                    <!-- Thumbnail Upload -->
                    <div class="mb-3">
                        <label for="thumbnail" class="form-label">Upload Thumbnail</label>
                        <input type="file" class="form-control" id="thumbnail" name="thumbnail"
                               accept="image/*" required>
                    </div>

                    <!-- Submit button -->
                    <button type="submit" class="btn btn-primary">Add Artist</button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
