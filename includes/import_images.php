<?php
//This script imports images from images\500_wide and the images into BLOBS
include 'db_connect.php';// Connect to database

function setPaintingImage($paintingID, $imagePath) {
    global $pdo;

    // Check if the file exists
    if (!file_exists($imagePath)) {
        echo "Image file not found: $imagePath<br>";
        return;
    }

    // Read the image file into a binary string
    $imageData = file_get_contents($imagePath);

    // Prepare the SQL statement to update the image
    $sql = "UPDATE Paintings SET Image = :image WHERE PaintingID = :paintingID";
    $stmt = $pdo->prepare($sql);

    // Bind the values
    $stmt->bindValue(':image', $imageData, PDO::PARAM_LOB);
    $stmt->bindValue(':paintingID', $paintingID, PDO::PARAM_INT);

    // Execute the query
    if ($stmt->execute()) {
        echo "Image uploaded successfully for Painting ID: $paintingID<br>";
    } else {
        echo "Failed to upload image for Painting ID: $paintingID<br>";
    }
}

//Create a array with Painting IDs and their corresponding image paths
$paintings = [
    1 => 'C:/xampp/htdocs/ArtWebsite/images/500_wide/baldumoulindelagalette.png',
    2 => 'C:/xampp/htdocs/ArtWebsite/images/500_wide/donitondo.png',
    3 => 'C:/xampp/htdocs/ArtWebsite/images/500_wide/vasewithtwelvesunflowers.png',
    4 => 'C:/xampp/htdocs/ArtWebsite/images/500_wide/monalisa.png',
    5 => 'C:/xampp/htdocs/ArtWebsite/images/500_wide/thepotatoeaters.png',
    6 => 'C:/xampp/htdocs/ArtWebsite/images/500_wide/sunrise.png',
    7 => 'C:/xampp/htdocs/ArtWebsite/images/500_wide/weaver.png',
    8 => 'C:/xampp/htdocs/ArtWebsite/images/500_wide/naturemorteaucompotier.png',
    9 => 'C:/xampp/htdocs/ArtWebsite/images/500_wide/housesofparliament.png',
    10 => 'C:/xampp/htdocs/ArtWebsite/images/500_wide/cafeterraceatnight.png',
    11 => 'C:/xampp/htdocs/ArtWebsite/images/500_wide/atthelapinagile.png',
    12 => 'C:/xampp/htdocs/ArtWebsite/images/500_wide/thepersistenceofmemory.png',
    13 => 'C:/xampp/htdocs/ArtWebsite/images/500_wide/thehallucinogenictoreador.png',
    14 => 'C:/xampp/htdocs/ArtWebsite/images/500_wide/jazdebouffan.png',
    15 => 'C:/xampp/htdocs/ArtWebsite/images/500_wide/vitruvianman.png',
    16 => 'C:/xampp/htdocs/ArtWebsite/images/500_wide/thekingfisher.png'
];

//Loop through the array and update each painting's image to binary
foreach ($paintings as $paintingID => $imagePath) {
    setPaintingImage($paintingID, $imagePath);
}