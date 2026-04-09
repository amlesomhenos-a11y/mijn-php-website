<?php
// Database configuratie (pas dit aan als je andere credentials gebruikt)
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'mijnwebsite');

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS);

// Check connection
if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
if ($conn->query($sql) === TRUE) {
    // Select database
    $conn->select_db(DB_NAME);
} else {
    die("Database aanmaak fout: " . $conn->error);
}

// We maken geen tabel aan, maar halen de gegevens op uit je nieuwe studenten tabel
$sql = "SELECT * FROM studenten_dataset_kw1c0_2";

// Als je de zoekbalk gebruikt:
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    $sql = "SELECT * FROM studenten_dataset_kw1c0_2 
            WHERE Voornaam LIKE '%$search%' 
            OR Achternaam LIKE '%$search%' 
            OR Email LIKE '%$search%'";
}

$resultaat = $conn->query($sql);
?>

