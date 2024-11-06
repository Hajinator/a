<!DOCTYPE html>
<html lang="en">

<!--Header with references to bootstrap and styles.css-->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artists</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/artwork.css">
</head>

<!--Body with bootstrap classes and navigation bar-->
<header class="d-flex justify-content-center align-items-center p-3 flex-column flex-md-row">
    <nav>
        <ul class="nav_links d-flex flex-wrap justify-content-center mb-0">
            <li><a href="index.php">Home</a></li>
            <li><a href="artwork.php">Artwork</a></li>
            <li><a href="artists.php">Artists</a></li>
            <li><a href="about.php">About</a></li>
        </ul>
    </nav>
</header>

<!-- Container to hold buttons and dropdown menus -->
<div class="container">
    <form method="GET" action="artists.php">
        <div class="filter-controls mb-3">
            <div class="row g-2 align-items-center justify-content-center">

                <!-- Century Dropdown Menu -->
                <div class="col-12 col-md-auto text-center">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle w-100" type="button" id="centuryDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <?= isset($_GET['century']) ? htmlspecialchars($_GET['century']) : 'Select Century'; ?>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="centuryDropdown">
                            <li><a class="dropdown-item" href="artists.php?century=Show All">Show All Centuries</a></li>
                            <li><a class="dropdown-item" href="artists.php?century=15th">15th</a></li>
                            <li><a class="dropdown-item" href="artists.php?century=16th">16th</a></li>
                            <li><a class="dropdown-item" href="artists.php?century=17th">17th</a></li>
                            <li><a class="dropdown-item" href="artists.php?century=18th">18th</a></li>
                            <li><a class="dropdown-item" href="artists.php?century=19th">19th</a></li>
                            <li><a class="dropdown-item" href="artists.php?century=20th">20th</a></li>
                            <li><a class="dropdown-item" href="artists.php?century=21st">21st</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Nationality Dropdown Menu -->
                <div class="col-12 col-md-auto text-center">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle w-100" type="button" id="nationalityDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <?= isset($_GET['nationality']) ? htmlspecialchars($_GET['nationality']) : 'Select Nationality'; ?>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="nationalityDropdown">
                            <li><a class="dropdown-item" href="artists.php?nationality=Show All">Show All Nationalities</a></li>
                            <li><a class="dropdown-item" href="artists.php?nationality=American">American</a></li>
                            <li><a class="dropdown-item" href="artists.php?nationality=Dutch">Dutch</a></li>
                            <li><a class="dropdown-item" href="artists.php?nationality=British">British</a></li>
                            <li><a class="dropdown-item" href="artists.php?nationality=French">French</a></li>
                            <li><a class="dropdown-item" href="artists.php?nationality=Italian">Italian</a></li>
                            <li><a class="dropdown-item" href="artists.php?nationality=Spanish">Spanish</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Add Artist Button -->
                <div class="col-12 col-md-auto text-center">
                    <button class="btn btn-secondary w-100" type="button" data-bs-toggle="modal"
                        data-bs-target="#addArtistModal">
                        Add Artist
                    </button>
                </div>

                <!-- Search Input box -->
                <div class="col-12 col-md text-center">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" id="searchInput"
                            placeholder="Search Artists" aria-label="Search for artists"
                            value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                        <button class="btn btn-secondary" type="submit">Search</button>

                        <!-- Back Button -->
                        <a href="artists.php" class="btn btn-secondary ms-2">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Import Scripts -->
<?php include '../includes/display_artists.php'; ?>
<?php include '../includes/add_artist.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
