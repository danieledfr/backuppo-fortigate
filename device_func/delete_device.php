<?php include("bar.php"); ?>
<?php
// Connetti al database SQLite
$db_file = '/var/www/cgi-bin/devices.db';
try {
    $db = new PDO("sqlite:" . $db_file);
} catch (PDOException $e) {
    echo "Errore di connessione al database: " . $e->getMessage();
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Controlla se è stata passata l'ID del dispositivo da eliminare
    if (!empty($_POST["device_id"])) {
        $device_id = $_POST["device_id"];

        // Esegui la query per eliminare il dispositivo dal database
        $sql = "DELETE FROM devices WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":id", $device_id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo "Record eliminato con successo. Attendi...";
            header("refresh: 3; url=../manage_devices.php");
        } else {
            echo "Errore durante l'eliminazione del record.";
        }
        exit;
    } else {
        echo "ID del dispositivo non specificato.";
    }
}

?>
