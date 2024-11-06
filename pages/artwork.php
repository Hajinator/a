<!DOCTYPE html>
<html lang="en">

<!--Header with references to bootstrap and styles.css-->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artwork</title>
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
    <form method="GET" action="artwork.php">
        <div class="filter-controls mb-3">
            <div class="row g-2 align-items-center">

                <!-- Artist Dropdown Menu -->
                <div class="col-12 col-md-auto">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle w-100" type="button" id="artistDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <?= isset($_GET['artist']) ? htmlspecialchars($_GET['artist']) : 'Select Artist'; ?>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="artistDropdown">
                            <li><a class="dropdown-item" href="artwork.php?artist=Show All">Show All</a></li>
                            <li><a class="dropdown-item" href="artwork.php?artist=August Renoir">August Renoir</a></li>
                            <li><a class="dropdown-item" href="artwork.php?artist=Michelangelo">Michelangelo</a></li>
                            <li><a class="dropdown-item" href="artwork.php?artist=Vincent Van Gogh">Vincent Van Gogh</a></li>
                            <li><a class="dropdown-item" href="artwork.php?artist=Leonardo da Vinci">Leonardo da Vinci</a></li>
                            <li><a class="dropdown-item" href="artwork.php?artist=Claude Monet">Claude Monet</a></li>
                            <li><a class="dropdown-item" href="artwork.php?artist=Pablo Picasso">Pablo Picasso</a></li>
                            <li><a class="dropdown-item" href="artwork.php?artist=Salvador Dali">Salvador Dali</a></li>
                            <li><a class="dropdown-item" href="artwork.php?artist=Paul Cezanne">Paul Cezanne</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Style Dropdown Menu -->
                <div class="col-12 col-md-auto">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle w-100" type="button" id="styleDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <?= isset($_GET['style']) ? htmlspecialchars($_GET['style']) : 'Select Style'; ?>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="styleDropdown">
                            <li><a class="dropdown-item" href="artwork.php?style=Show All">Show All</a></li>
                            <li><a class="dropdown-item" href="artwork.php?style=Impressionism">Impressionism</a></li>
                            <li><a class="dropdown-item" href="artwork.php?style=Still-life">Still-life</a></li>
                            <li><a class="dropdown-item" href="artwork.php?style=Mannerism">Mannerism</a></li>
                            <li><a class="dropdown-item" href="artwork.php?style=Realism">Realism</a></li>
                            <li><a class="dropdown-item" href="artwork.php?style=Portrait">Portrait</a></li>
                            <li><a class="dropdown-item" href="artwork.php?style=Cubism">Cubism</a></li>
                            <li><a class="dropdown-item" href="artwork.php?style=Surrealism">Surrealism</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Add Painting Button -->
                <div class="col-12 col-md-auto">
                    <button class="btn btn-secondary w-100" type="button" data-bs-toggle="modal"
                        data-bs-target="#addPaintingModal">
                        Add Painting
                    </button>
                </div>

                <!-- Search Input box -->
                <div class="col-12 col-md">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" id="searchInput"
                            placeholder="Search Paintings" aria-label="Search for paintings"
                            value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                        <button class="btn btn-secondary" type="submit">Search</button>

                        <!-- Back Button -->
                        <a href="artwork.php" class="btn btn-secondary ms-2">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Import Scripts -->
<?php include '../includes/display_paintings.php'; ?>
<?php include '../includes/add_painting.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
