<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$device_name = $_POST['device_name'];
$ip_address = $_POST['ip_address'];
$https_port = $_POST['https_port'];
$token = $_POST['token'];

try {
    $db = new PDO('sqlite:/var/www/cgi-bin/devices.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "INSERT INTO devices (device_name, ip_address, https_port, token, lastbackup) VALUES (:device_name, :ip_address, :https_port, :token, 'none')";
    $stmt = $db->prepare($query);

    // Controllo per verificare se la preparazione della query ha avuto successo
    if ($stmt === false) {
        echo "Errore nella preparazione della query SQL: " . print_r($db->errorInfo(), true);
        exit;
    }

    $stmt->bindParam(':device_name', $device_name);
    $stmt->bindParam(':ip_address', $ip_address);
    $stmt->bindParam(':https_port', $https_port);
    $stmt->bindParam(':token', $token);

    $stmt->execute();

    echo "Dispositivo aggiunto con successo. Attendi...";
    header("refresh: 3; url=../manage_devices.php");
} catch (PDOException $e) {
    echo "Errore nell'aggiunta del dispositivo: " . $e->getMessage();
}
?>
