<?php
// Deze functie haalt de studenten op uit je nieuwe tabel
function get_users($conn, $search = '') {
    // Jouw exacte tabelnaam uit phpMyAdmin
    $table = "studenten_dataset_kw1c0_2";
    
    if (!empty($search)) {
        // Veilig maken van de zoekterm
        $search = $conn->real_escape_string($search);
        
        // We zoeken in de kolommen met HOOFDLETTERS (zoals in je database)
        $sql = "SELECT * FROM $table 
                WHERE Voornaam LIKE '%$search%' 
                OR Achternaam LIKE '%$search%' 
                OR Email LIKE '%$search%' 
                OR Studierichting LIKE '%$search%'";
    } else {
        // Als er niet gezocht wordt, tonen we gewoon de lijst (beperkt tot 10 voor de snelheid)
        $sql = "SELECT * FROM $table LIMIT 10";
    }
    
    $result = $conn->query($sql);
    
    if (!$result) {
        // Als er iets misgaat, laat de foutmelding zien (handig voor het testen)
        die("Fout in query: " . $conn->error);
    }
    
    return $result;
}

// Functie om een student op te halen op basis van ID
function getUserById($conn, $id) {
    $table = "studenten_dataset_kw1c0_2";
    $sql = "SELECT * FROM $table WHERE StudentID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Functie om een nieuwe student toe te voegen
function addUser($conn, $voornaam, $achternaam, $email, $studierichting, $geboortedatum) {
    $table = "studenten_dataset_kw1c0_2";
    $sql = "INSERT INTO $table (Voornaam, Achternaam, Email, Studierichting, Geboortedatum) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $voornaam, $achternaam, $email, $studierichting, $geboortedatum);
    if ($stmt->execute()) {
        return ['success' => true, 'message' => 'Student toegevoegd'];
    } else {
        return ['success' => false, 'message' => 'Fout bij toevoegen: ' . $stmt->error];
    }
}

// Functie om een student bij te werken
function updateUser($conn, $id, $voornaam, $achternaam, $email, $studierichting, $geboortedatum) {
    $table = "studenten_dataset_kw1c0_2";
    $sql = "UPDATE $table SET Voornaam = ?, Achternaam = ?, Email = ?, Studierichting = ?, Geboortedatum = ? WHERE StudentID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $voornaam, $achternaam, $email, $studierichting, $geboortedatum, $id);
    if ($stmt->execute()) {
        return ['success' => true, 'message' => 'Student bijgewerkt'];
    } else {
        return ['success' => false, 'message' => 'Fout bij bijwerken: ' . $stmt->error];
    }
}

// Functie om een student te verwijderen
function deleteUser($conn, $id) {
    $table = "studenten_dataset_kw1c0_2";
    $sql = "DELETE FROM $table WHERE StudentID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        return ['success' => true, 'message' => 'Student verwijderd'];
    } else {
        return ['success' => false, 'message' => 'Fout bij verwijderen: ' . $stmt->error];
    }
}
?>