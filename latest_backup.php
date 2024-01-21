<?php
$server_address = $_SERVER['SERVER_ADDR']; // Ottieni l'indirizzo IP del server
$device_name = $_GET['device_name'];
$backup_dir = "/var/www/backup/$device_name/";
$latest_file = null;
$latest_time = null;

// Scandisci la directory e cerca il file più recente
if (is_dir($backup_dir)) {
    if ($dh = opendir($backup_dir)) {
        while (($file = readdir($dh)) !== false) {
            if (preg_match('/^config-backup_.*\.cfg$/', $file)) {
                $file_time = filemtime($backup_dir . $file);
                if ($latest_time === null || $file_time > $latest_time) {
                    $latest_time = $file_time;
                    $latest_file = $file;
                }
            }
        }
        closedir($dh);
    }
}

// Se è stato trovato un file, fornisce un link per scaricarlo
if ($latest_file !== null) {
    $download_url = "http://$server_address/backup/$device_name/$latest_file";
    $filename = $device_name . "_" . basename($latest_file);
    header('Content-Type: application/octet-stream');
    header("Content-Transfer-Encoding: Binary");
    header("Content-disposition: attachment; filename=\"" . $filename . "\"");
    readfile($download_url);
} else {
    echo "Nessun file di backup trovato per questo dispositivo.";
}
?>
