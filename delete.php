<?php
include 'config.php';
include 'functions.php';

// Haal ID op uit query-parameter, en zorg dat het een integer is
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

// Als ID ontbreekt of ongeldig is, terugsturen met foutmelding
if (!$id) {
    header("Location: index.php?error=Geen ID opgegeven");
    exit();
}

$result = deleteUser($conn, $id);

$redirect_url = "index.php";
if ($result['success']) {
    $redirect_url .= "?message=" . urlencode($result['message']);
} else {
    $redirect_url .= "?error=" . urlencode($result['message']);
}

header("Location: " . $redirect_url);
exit();
?>
