<?php
include 'config.php';
include 'functions.php';

// Lees de zoekterm uit de URL (GET-parameter 'search'), of gebruik lege string
$search = isset($_GET['search']) ? $_GET['search'] : '';
$resultaat = get_users($conn, $search);

// Bericht / meldingsvariabelen (success of fout)
$message = '';
$message_type = '';

if (isset($_GET['message'])) {
    $message = $_GET['message'];
    $message_type = 'success';
}
if (isset($_GET['error'])) {
    $message = $_GET['error'];
    $message_type = 'error';
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gegevensbeheer - Dashboard</title>
    <!-- Zorg dat de mapnaam exact 'CSS' is (met hoofdletters) -->
    <link rel="stylesheet" href="CSS/reset.css">
    <link rel="stylesheet" href="CSS/hoofd.css">
</head>
<body>
    <section class="wrapper-main">
        <div class="container">
            <h1>Gegevensbeheerder</h1>
            
            <?php if(!empty($message)): ?>
                <div class="message message-<?php echo $message_type; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            
            <div class="search-section">
                <form method="GET" class="search-form">
                    <input type="text" name="search" placeholder="Zoek op naam, email of studierichting..." 
                           value="<?php echo htmlspecialchars($search); ?>" class="search-input">
                    <button type="submit" class="btn btn-primary">Zoeken</button>
                    <?php if(!empty($search)): ?>
                        <a href="index.php" class="btn btn-edit" style="background-color: #7f8c8d;">Alles weergeven</a>
                    <?php endif; ?>
                </form>
            </div>
            
            <div class="button-group">
                <a href="forminf.php" class="btn btn-primary">+ Nieuwe gebruiker toevoegen</a>
            </div>
            
            <div class="search-info">
                <p>
                    <?php if(!empty($search)): ?>
                        <strong><?php echo $resultaat->num_rows; ?></strong> resultaten voor "<strong><?php echo htmlspecialchars($search); ?></strong>"
                    <?php else: ?>
                        Totaal <strong><?php echo $resultaat->num_rows; ?></strong> studenten
                    <?php endif; ?>
                </p>
            </div>
            
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Voornaam</th>
                            <th>Achternaam</th>
                            <th>Email</th>
                            <th>Studierichting</th>
                            <th>Geboortedatum</th>
                            <th>Acties</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($resultaat && $resultaat->num_rows > 0) {
                            while($row = $resultaat->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row["Voornaam"]) . "</td>"; 
                                echo "<td>" . htmlspecialchars($row["Achternaam"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["Email"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["Studierichting"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["Geboortedatum"]) . "</td>";
                                echo "<td>
                                        <div class='action-buttons'>
                                            <a href='forminf.php?id=" . $row["StudentID"] . "' class='btn btn-edit'>Bewerk</a>
                                            <a href='delete.php?id=" . $row["StudentID"] . "' class='btn btn-delete' onclick='return confirm(\"Weet je zeker dat je deze student wilt verwijderen?\")'>Verwijder</a>
                                        </div>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' style='text-align:center; padding:30px;'>Geen studenten gevonden.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</body>
</html>
