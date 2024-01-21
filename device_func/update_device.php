<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Connetti al database SQLite
    $db_file = '/var/www/cgi-bin/devices.db';
    try {
        $db = new PDO("sqlite:" . $db_file);
    } catch (PDOException $e) {
        echo "Errore di connessione al database: " . $e->getMessage();
        exit;
    }

    // Esegui l'update del dispositivo con l'id specificato
    $id = $_POST['id'];
    $device_name = $_POST['device_name'];
    $ip_address = $_POST['ip_address'];
    $https_port = $_POST['https_port'];
    $token = $_POST['token'];

    $sql = "UPDATE devices SET device_name=:device_name, ip_address=:ip_address, https_port=:https_port, token=:token  WHERE id=:id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':device_name', $device_name);
    $stmt->bindParam(':ip_address', $ip_address);
    $stmt->bindParam(':https_port', $https_port);
    $stmt->bindParam(':token', $token);
    $stmt->execute();

    // Redirect alla pagina dei dispositivi
    header('Location: ../manage_devices.php');
    exit;
} else {
    // Se la richiesta non è di tipo POST, mostra un messaggio di errore
    echo "Metodo HTTP non valido.";
    exit;
}
?>
