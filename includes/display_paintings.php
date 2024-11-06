<?php
// This script handles fetching, filtering, pagination and displaying paintings from the database

include 'db_connect.php'; //Connect to database

$limit = 6; //Set number of paintings to 6 per page
// Get the current page number from the query string (default to 1)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$conditions = []; //Create a array to hold the artist/style filtering conditions


// Check for artist filter
if (!empty($_GET['artist'])) {
    $artist = $_GET['artist'];
    if ($artist !== 'Show All') { 
        $conditions[] = "A.Name = :artist";
    }
}


// Check for style filter
if (!empty($_GET['style'])) {
    $style = $_GET['style'];
    if ($style !== 'Show All') { 
        $conditions[] = "P.Style = :style";
    }
}


// Check for search term
if (!empty($_GET['search'])) {
    $search = $_GET['search'];
    $conditions[] = "(P.Title LIKE :search OR A.Name LIKE :search)";
}


// Build the SQL query for fetching paintings
$sql = "SELECT P.PaintingID, P.Title, P.Style, P.Finished, P.Media, P.Image AS image_blob, A.Name as artist_name, P.ArtistID
        FROM Paintings P
        INNER JOIN Artists A ON P.ArtistID = A.ArtistID";


// Append conditions if any
if (count($conditions) > 0) {
    $sql .= " WHERE " . implode(' AND ', $conditions);
}


// Add LIMIT and OFFSET for pagination
$sql .= " LIMIT :limit OFFSET :offset";


$stmt = $pdo->prepare($sql);


// Bind artist parameter
if (isset($artist) && $artist !== 'Show All') {
    $stmt->bindValue(':artist', $artist);
}


// Bind style parameter 
if (isset($style) && $style !== 'Show All') {
    $stmt->bindValue(':style', $style);
}


// Bind search parameter 
if (!empty($search)) {
    $stmt->bindValue(':search', '%' . $search . '%'); // Use wildcard for LIKE
}


// Bind limit and offset parameters for pagination
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);


// Fetch the paintings
if ($stmt->execute()) {
    $paintings = $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// Count total paintings for pagination with the same filters
$countSql = "SELECT COUNT(*) FROM Paintings P INNER JOIN Artists A ON P.ArtistID = A.ArtistID";
if (count($conditions) > 0) {
    $countSql .= " WHERE " . implode(' AND ', $conditions);
}


// Prepare and execute the count statement
$totalCountStmt = $pdo->prepare($countSql);
if (isset($artist) && $artist !== 'Show All') {
    $totalCountStmt->bindValue(':artist', $artist);
}
if (isset($style) && $style !== 'Show All') {
    $totalCountStmt->bindValue(':style', $style);
}

if (!empty($search)) {
    $totalCountStmt->bindValue(':search', '%' . $search . '%'); // Bind search parameter
}


// Execute the count statement
$totalCountStmt->execute();
$totalPaintings = $totalCountStmt->fetchColumn();
$totalPages = ceil($totalPaintings / $limit);


// Convert BLOB data to base64 image strings
foreach ($paintings as &$painting) {
    if ($painting['image_blob']) {
        $painting['image_blob'] = 'data:image/png;base64,' . base64_encode($painting['image_blob']);
    } else {
        $painting['image_blob'] = 'path/to/default-image.jpg'; 
    }
}
?>

<!-- Paintings are displayed in bootstrap cards-->
<div id="paintingCards" class="row g-3">
    <?php if (count($paintings) > 0): ?>
    <?php array_map(function($painting) { ?>
    <div class="col-md-4">
        <div class="card mb-3 mt-3">
            <img src="<?= htmlspecialchars($painting['image_blob']); ?>" class="card-img-top"
                alt="<?= htmlspecialchars($painting['Title']); ?>"
                onerror="this.onerror=null;this.src='path/to/default-image.jpg';">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($painting['Title']); ?></h5>
                <p class="card-text">Artist: <?= htmlspecialchars($painting['artist_name']); ?></p>
                <p class="card-text">Style: <?= htmlspecialchars($painting['Style']); ?></p>
                <p class="card-text">Media: <?= htmlspecialchars($painting['Media']); ?></p>
                <p class="card-text">Finished: <?= htmlspecialchars($painting['Finished']); ?></p>

                <div class="d-flex">
                    <!-- Edit button that submits the painting ID -->
                    <form method="POST" action="../includes/edit_painting.php">
                        <input type="hidden" name="PaintingID" value="<?= $painting['PaintingID']; ?>">
                        <button type="submit" class="btn btn-outline-warning">Edit</button>
                    </form>
                    
                    <div style="margin-left: 10px;"></div>
                
                <!-- Delete button with post -->
                <form method="POST" action="../includes/delete_painting.php">
                    <input type="hidden" name="PaintingID" value="<?= $painting['PaintingID']; ?>">
                    <button type="submit" class="btn btn-outline-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php }, $paintings); ?>
<?php else: ?>
<p>No unique paintings found.</p>
<?php endif; ?>
</div>


<!-- Pagination Controls -->
<div class="d-flex justify-content-center mt-4">
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= ($i == $page) ? 'active' : ''; ?>">
                <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
            </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>