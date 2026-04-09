<?php
include 'config.php';
include 'functions.php';

// Check of we een id meekrijgen voor bewerken
$id = isset($_GET['id']) ? intval($_GET['id']) : null;
$user = null;
$message = '';
$message_type = '';

// Bij een id: haal bestaande gebruiker op (edit-modus)
if ($id) {
    $user = getUserById($conn, $id);
    if (!$user) {
        $message = 'Student niet gevonden';
        $message_type = 'error';
    }
}

// Verwerk formulierdata bij POST-aanvraag
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $voornaam = $_POST["voornaam"] ?? '';
    $achternaam = $_POST["achternaam"] ?? '';
    $email = $_POST["email"] ?? '';
    $studierichting = $_POST["studierichting"] ?? '';
    $geboortedatum = $_POST["geboortedatum"] ?? '';
    
    // Bij id: update (bewerken), anders toevoegen
    if ($id) {
        $result = updateUser($conn, $id, $voornaam, $achternaam, $email, $studierichting, $geboortedatum);
    } else {
        $result = addUser($conn, $voornaam, $achternaam, $email, $studierichting, $geboortedatum);
    }
    
    // Bij succes -> terug naar index met message, anders toon fout
    if ($result['success']) {
        header("Location: index.php?message=" . urlencode($result['message']));
        exit();
    } else {
        $message = $result['message'];
        $message_type = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $id ? 'Student Bewerken' : 'Nieuwe Student'; ?></title>
    <link rel="stylesheet" href="CSS/reset.css">
    <link rel="stylesheet" href="CSS/hoofd.css">
</head>
<body>
    <section class="wrapper-main">
        <div class="container">
            <h1><?php echo $id ? 'Student Bewerken' : 'Nieuwe Student Toevoegen'; ?></h1>
            
            <?php if(!empty($message)): ?>
                <div class="message message-<?php echo $message_type; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            
            <?php if(!$id || $user): ?>
                <form method="POST" class="form-group">
                    <div class="form-field">
                        <label for="voornaam">Voornaam *</label>
                        <input type="text" id="voornaam" name="voornaam" 
                               value="<?php echo $user ? htmlspecialchars($user['Voornaam']) : ''; ?>" 
                               placeholder="Voornaam" required>
                    </div>
                    
                    <div class="form-field">
                        <label for="achternaam">Achternaam *</label>
                        <input type="text" id="achternaam" name="achternaam" 
                               value="<?php echo $user ? htmlspecialchars($user['Achternaam']) : ''; ?>" 
                               placeholder="Achternaam" required>
                    </div>
                    
                    <div class="form-field">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" 
                               value="<?php echo $user ? htmlspecialchars($user['Email']) : ''; ?>" 
                               placeholder="Email@example.com" required>
                    </div>
                    
                    <div class="form-field">
                        <label for="studierichting">Studierichting *</label>
                        <input type="text" id="studierichting" name="studierichting" 
                               value="<?php echo $user ? htmlspecialchars($user['Studierichting']) : ''; ?>" 
                               placeholder="Bijv. ICT" required>
                    </div>
                    
                    <div class="form-field">
                        <label for="geboortedatum">Geboortedatum *</label>
                        <input type="date" id="geboortedatum" name="geboortedatum" 
                               value="<?php echo $user ? htmlspecialchars($user['Geboortedatum']) : ''; ?>" 
                               required>
                    </div>
                    
                    <div class="button-group">
                        <button type="submit" class="btn btn-primary">
                            <?php echo $id ? 'Bijwerken' : 'Toevoegen'; ?>
                        </button>
                        <a href="index.php" class="btn btn-secondary">Annuleren</a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </section>
</body>
</html>