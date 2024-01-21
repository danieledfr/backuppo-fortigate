<?php include("../bar.php"); ?>
<?php
// Connetti al database SQLite
$db_file = '/var/www/cgi-bin/devices.db';
try {
    $db = new PDO("sqlite:" . $db_file);
} catch (PDOException $e) {
    echo "Errore di connessione al database: " . $e->getMessage();
    exit;
}

// Verifica se il parametro "id" è stato passato
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Esegui la query per ottenere il record del dispositivo specificato dall'id
    $sql = "SELECT * FROM devices WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$id]);
    $device = $stmt->fetch(PDO::FETCH_ASSOC);

    // Se il dispositivo non esiste, reindirizza alla pagina di gestione dei dispositivi
    if (!$device) {
        header("Location: ../manage_devices.php");
        exit;
    }
} else {
    // Se il parametro "id" non è stato passato, reindirizza alla pagina di gestione dei dispositivi
    header("Location: ../manage_devices.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifica dispositivo</title>
    <!-- Includi la libreria Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Modifica dispositivo</h1>
        <form action="update_device.php" method="post">
            <input type="hidden" name="id" value="<?php echo $device['id']; ?>">
            <label for="device_name">Nome dispositivo:</label>
            <input type="text" id="device_name" name="device_name" value="<?php echo htmlspecialchars($device['device_name']); ?>" required><br><br>
            <label for="ip_address">Indirizzo IP:</label>
            <input type="text" id="ip_address" name="ip_address" value="<?php echo htmlspecialchars($device['ip_address']); ?>" required><br><br>
            <label for="https_port">Porta HTTPS:</label>
            <input type="number" id="https_port" name="https_port" value="<?php echo htmlspecialchars($device['https_port']); ?>" required><br><br>
            <label for="token">Token:</label>
            <input type="text" id="token" name="token" value="" placeholder="inserire il token" required><br><br>
            <input type="submit" class="btn btn-success" value="Salva">
            <a href="../manage_devices.php" class="btn btn-secondary">Annulla</a>
        </form>
    </div>

    <!-- Includi le librerie JavaScript di Bootstrap -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
