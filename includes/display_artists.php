<?php
// This script handles fetching, filtering, pagination, and displaying artists from the database

include 'db_connect.php'; // Connect to database

$limit = 6; // Set number of artists to display 6 per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Initialize conditions for filtering
$conditions = []; // Create an array to hold filtering conditions

// Check for search term
if (!empty($_GET['search'])) {
    $search = $_GET['search'];
    $conditions[] = "(A.Name LIKE :search OR A.LifeSpan LIKE :search OR A.Nationality LIKE :search OR A.Century LIKE :search)";
}

// Check for century filter
if (!empty($_GET['century']) && $_GET['century'] != 'Show All') {
    $century = $_GET['century'];
    $conditions[] = "A.Century = :century"; // Add condition for century
}

// Check for nationality filter
if (!empty($_GET['nationality']) && $_GET['nationality'] != 'Show All') {
    $nationality = $_GET['nationality'];
    $conditions[] = "A.Nationality = :nationality"; // Add condition for nationality
}

// Build the SQL query for fetching artists
$sql = "SELECT A.ArtistID, A.Name, A.LifeSpan, A.Nationality, A.Century, A.Thumbnail AS thumbnail_blob
        FROM Artists A";

// Append conditions if any
if (count($conditions) > 0) {
    $sql .= " WHERE " . implode(' AND ', $conditions);
}

// Add LIMIT and OFFSET for pagination
$sql .= " LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($sql);

// Bind search parameter 
if (!empty($search)) {
    $stmt->bindValue(':search', '%' . $search . '%'); // Use wildcard for LIKE
}

// Bind century parameter if filtering by century
if (!empty($century) && $century != 'Show All') {
    $stmt->bindValue(':century', $century); // Bind century parameter
}

// Bind nationality parameter if filtering by nationality
if (!empty($nationality) && $nationality != 'Show All') {
    $stmt->bindValue(':nationality', $nationality); // Bind nationality parameter
}

// Bind limit and offset parameters for pagination
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

// Fetch the artists
if ($stmt->execute()) {
    $artists = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Count total artists for pagination with the same filters
$countSql = "SELECT COUNT(*) FROM Artists A";
if (count($conditions) > 0) {
    $countSql .= " WHERE " . implode(' AND ', $conditions);
}

$totalCountStmt = $pdo->prepare($countSql);
if (!empty($search)) {
    $totalCountStmt->bindValue(':search', '%' . $search . '%'); // Bind search parameter
}
if (!empty($century) && $century != 'Show All') {
    $totalCountStmt->bindValue(':century', $century); // Bind century parameter for count
}
if (!empty($nationality) && $nationality != 'Show All') {
    $totalCountStmt->bindValue(':nationality', $nationality); // Bind nationality parameter for count
}

// Execute the count statement
$totalCountStmt->execute();
$totalArtists = $totalCountStmt->fetchColumn();
$totalPages = ceil($totalArtists / $limit);

// Convert BLOB data to base64 image strings
foreach ($artists as &$artist) {
    if ($artist['thumbnail_blob']) {
        $artist['thumbnail_blob'] = 'data:image/png;base64,' . base64_encode($artist['thumbnail_blob']);
    } else {
        $artist['thumbnail_blob'] = 'path/to/default-thumbnail.jpg'; 
    }
}
?>

<!-- Artists are displayed in bootstrap cards -->
<div id="artistCards" class="row g-3">
    <?php if (count($artists) > 0): ?>
        <?php array_map(function($artist) { ?>
            <div class="col-md-4 col-sm-12"> <!-- Use col-sm-12 for smaller devices -->
                <div class="card mb-3 mt-3">
                    <img src="<?= htmlspecialchars($artist['thumbnail_blob']); ?>" class="card-img-top"
                         alt="<?= htmlspecialchars($artist['Name']); ?>"
                         onerror="this.onerror=null;this.src='path/to/default-thumbnail.jpg';">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($artist['Name']); ?></h5>
                        <p class="card-text">Life Span: <?= htmlspecialchars($artist['LifeSpan']); ?></p>
                        <p class="card-text">Nationality: <?= htmlspecialchars($artist['Nationality']); ?></p>
                        <p class="card-text">Century: <?= htmlspecialchars($artist['Century']); ?></p>

                        <div class="d-flex">
                            <form method="POST" action="../includes/edit_artist.php">
                                <input type="hidden" name="ArtistID" value="<?= $artist['ArtistID']; ?>">
                                <button type="submit" class="btn btn-outline-warning">Edit</button>
                            </form>

                            <div style="margin-left: 10px;"></div>

                            <form method="POST" action="../includes/delete_artist.php">
                                <input type="hidden" name="ArtistID" value="<?= $artist['ArtistID']; ?>">
                                <button type="submit" class="btn btn-outline-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php }, $artists); ?>
    <?php else: ?>
        <p>No artists found.</p>
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
