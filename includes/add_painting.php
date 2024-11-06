<?php
//This script is for adding paintings and also contains the modal for adding paintings.

include 'db_connect.php'; //Connect to database

//Fetch artists for artsit drop down in modal
$artistQuery = "SELECT ArtistID, Name FROM Artists"; 
$artistsStmt = $pdo->query($artistQuery);
$artists = $artistsStmt->fetchAll(PDO::FETCH_ASSOC);


//Fetch styles for style drop down in modal
$styleQuery = "SELECT DISTINCT Style FROM Paintings"; 
$stylesStmt = $pdo->query($styleQuery);
$styles = $stylesStmt->fetchAll(PDO::FETCH_ASSOC);


// Get the form data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $artist = $_POST['artist'];
    $style = $_POST['style'];
    $media = $_POST['media'];
    $finished = $_POST['finished'];
    

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $image = $_FILES['image']['tmp_name'];
        $imageBlob = file_get_contents($image);

        // Prepare the SQL insert statement
        $sql = "INSERT INTO Paintings (Title, ArtistID, Style, Media, Finished, Image) 
                VALUES (:title, :artistID, :style, :media, :finished, :image)";
        $stmt = $pdo->prepare($sql);


        // Bind parameters
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':artistID', $artist, PDO::PARAM_INT);
        $stmt->bindValue(':style', $style);
        $stmt->bindValue(':media', $media);
        $stmt->bindValue(':finished', $finished);
        $stmt->bindValue(':image', $imageBlob, PDO::PARAM_LOB); 

        
        //Execute
        if ($stmt->execute()) {
            //Once painting has been added, call javascript to reload page
            echo "<script>
                    window.onload = function() {
                        reloadAfterAdd();
                    }
                  </script>";
            exit;
        } else {
            echo "Error adding painting: " . implode(" ", $stmt->errorInfo());
        }
    } else {
        echo "Error uploading image: " . $_FILES['image']['error'];
    }
}
?>

<!--HTML for Modal -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Painting</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="../js/reloadafteradd.js"></script>
</head>
<body>

<!-- Modal for Adding a Painting -->
<div class="modal fade" id="addPaintingModal" tabindex="-1" aria-labelledby="addPaintingModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPaintingModalLabel">Add a New Painting</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="addPaintingForm" method="POST" enctype="multipart/form-data">
                    
                <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title"
                               placeholder="Enter painting title" required>
                    </div>

                    <!-- Artist Dropdown Menu -->
                    <div class="mb-3">
                        <label for="artistId" class="form-label">Artist</label>
                        <select class="form-select" id="artistId" name="artist" required>
                            <option value="" disabled selected>Select an artist</option>
                            <?php foreach ($artists as $artist): ?>
                                <option value="<?php echo htmlspecialchars($artist['ArtistID']); ?>">
                                    <?php echo htmlspecialchars($artist['Name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Style Drop Down Menu -->
                    <div class="mb-3">
                        <label for="style" class="form-label">Style</label>
                        <select class="form-select" id="style" name="style" required>
                            <option value="" disabled selected>Select a style</option>
                            <?php foreach ($styles as $style): ?>
                                <option value="<?php echo htmlspecialchars($style['Style']); ?>">
                                    <?php echo htmlspecialchars($style['Style']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Media -->
                    <div class="mb-3">
                        <label for="media" class="form-label">Media</label>
                        <input type="text" class="form-control" id="media" name="media"
                               placeholder="Enter media type" required>
                    </div>

                    <!-- Finished -->
                    <div class="mb-3">
                        <label for="finished" class="form-label">Finished</label>
                        <input type="text" class="form-control" id="finished" name="finished"
                               placeholder="Enter finished year" required>
                    </div>

                    
                    <!-- Image File -->
                    <div class="mb-3">
                        <label for="image" class="form-label">Upload Image</label>
                        <input type="file" class="form-control" id="image" name="image"
                               accept="image/*" required>
                    </div>
                    
                    
                     <!-- Submit button -->
                    <button type="submit" class="btn btn-primary">Add Painting</button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>