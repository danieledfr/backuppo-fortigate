<?php
// Connessione al database
$db_file = '/var/www/cgi-bin/devices.db';
$db = new PDO("sqlite:" . $db_file);

// Verifica se i parametri sono stati ricevuti correttamente
if (isset($_POST['id']) && isset($_POST['enable'])) {
    $id = $_POST['id'];
    $enable = $_POST['enable'];

    // Esegui l'aggiornamento nel database
    $stmt = $db->prepare("UPDATE devices SET enable = :enable WHERE id = :id");
    $stmt->bindParam(':enable', $enable, PDO::PARAM_INT);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    echo "Aggiornamento completato con successo";
} else {
    echo "Errore: Parametri mancanti";
}
?>