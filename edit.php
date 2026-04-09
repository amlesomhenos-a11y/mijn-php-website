<?php
include 'config.php';
include 'functions.php';

// Controleer of StudentID in de URL staat
if (!isset($_GET['id'])) {
    header("Location: index.php?error=Geen student geselecteerd");
    exit;
}

$student_id = $_GET['id'];

// Haal de huidige gegevens op
$query = "SELECT * FROM gebruikers WHERE StudentID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

// Controleer of student bestaat
if ($result->num_rows === 0) {
    header("Location: index.php?error=Student niet gevonden");
    exit;
}

$student = $result->fetch_assoc();

// Meldingsvariabelen
$message = '';
$message_type = '';

// Verwerk het formulier als het is ingediend
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $voornaam = $_POST['voornaam'] ?? '';
    $achternaam = $_POST['achternaam'] ?? '';
    $email = $_POST['email'] ?? '';
    $studierichting = $_POST['studierichting'] ?? '';
    $geboortedatum = $_POST['geboortedatum'] ?? '';

    // Validatie
    if (empty($voornaam) || empty($achternaam) || empty($email) || empty($studierichting) || empty($geboortedatum)) {
        $message = "Alle velden zijn verplicht!";
        $message_type = 'error';
    } else {
        // Update de database
        $update_query = "UPDATE gebruikers SET Voornaam = ?, Achternaam = ?, Email = ?, Studierichting = ?, Geboortedatum = ? WHERE StudentID = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("sssssi", $voornaam, $achternaam, $email, $studierichting, $geboortedatum, $student_id);

        if ($update_stmt->execute()) {
            header("Location: index.php?message=Student gegevens succesvol bijgewerkt!");
            exit;
        } else {
            $message = "Fout bij het bijwerken: " . $conn->error;
            $message_type = 'error';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student bewerken</title>
    <link rel="stylesheet" href="CSS/reset.css">
    <link rel="stylesheet" href="CSS/hoofd.css">
</head>
<body>
    <section class="wrapper-main">
        <div class="container">
            <h1>Student bewerken</h1>
            
            <?php if(!empty($message)): ?>
                <div class="message message-<?php echo $message_type; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" class="form-group">
                <div class="form-field">
                    <label for="voornaam">Voornaam:</label>
                    <input type="text" id="voornaam" name="voornaam" value="<?php echo htmlspecialchars($student['Voornaam']); ?>" required>
                </div>
                
                <div class="form-field">
                    <label for="achternaam">Achternaam:</label>
                    <input type="text" id="achternaam" name="achternaam" value="<?php echo htmlspecialchars($student['Achternaam']); ?>" required>
                </div>
                
                <div class="form-field">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($student['Email']); ?>" required>
                </div>
                
                <div class="form-field">
                    <label for="studierichting">Studierichting:</label>
                    <input type="text" id="studierichting" name="studierichting" value="<?php echo htmlspecialchars($student['Studierichting']); ?>" required>
                </div>
                
                <div class="form-field">
                    <label for="geboortedatum">Geboortedatum:</label>
                    <input type="date" id="geboortedatum" name="geboortedatum" value="<?php echo htmlspecialchars($student['Geboortedatum']); ?>" required>
                </div>
                
                <div class="button-group">
                    <button type="submit" class="btn btn-primary">Opslaan</button>
                    <a href="index.php" class="btn btn-secondary">Annuleren</a>
                </div>
            </form>
        </div>
    </section>
</body>
</html>
