<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $db = new PDO('sqlite:/var/www/cgi-bin/devices.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Svuota il contenuto della tabella 'devices'
    $query = "DELETE FROM devices";
    $stmt = $db->prepare($query);
    $stmt->execute();

    echo "La tabella 'devices' è stata svuotata con successo.<br>";
    echo "Verrai reindirizzato alla pagina Gestione Dispositivi tra 3 secondi...";
    
    // Reindirizza l'utente a manage_devices.php dopo 3 secondi (3000 millisecondi)
    header("refresh: 3; url=../manage_devices.php");

} catch (PDOException $e) {
    echo "Errore nello svuotare la tabella 'devices': " . $e->getMessage();
}
?>
