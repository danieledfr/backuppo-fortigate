<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 'On');
ini_set('error_log', '/var/log/php_errors.log');


// Verifica se il campo "time" è stato definito
if(isset($_POST['time'])){
    $time = $_POST['time'];
    list($hour, $minute) = array_map('intval', explode(':', $time));
    
    // Leggi la frequenza di backup dalla pagina delle impostazioni
    $recurrence = $_POST['recurrence'];

    // Costruisci la configurazione in formato JSON
    $config = [
        'hour' => $hour,
        'minute' => $minute,
        'recurrence' => $recurrence
    ];
    $config_json = json_encode($config);

    // Salva la configurazione nel file di configurazione
    file_put_contents('/var/www/cgi-bin/schedule_backup.conf', $config_json);


    switch ($recurrence) {
        case "daily":
            $schedule = "0 $time * * *";
            break;
        case "weekly":
            $schedule = "0 $time * * 0"; // Esegui il backup ogni domenica
            break;
        case "monthly":
            $schedule = "0 $time 1 * *"; // Esegui il backup il primo giorno del mese
            break;
    }

    // Crea un file temporaneo con la nuova riga di cron
    $temp_file = tempnam(sys_get_temp_dir(), 'cronjob');
    $cronjob = "$minute $hour * * *  /usr/bin/python3 /var/www/cgi-bin/backup.py\n";
    file_put_contents($temp_file, $cronjob);

    // Rimuovi tutte le righe di cron precedentemente create per l'utente www-data
    exec("crontab -u www-data -r");

    // Aggiungi la nuova schedulazione al crontab di www-data
    exec("crontab -u www-data $temp_file");

    // Elimina il file temporaneo
    unlink($temp_file);

    // Reindirizza l'utente alla pagina delle impostazioni
    header("Location: ../settings.php");
    exit();
} else {
    // Gestione dell'errore se il campo "time" non è stato definito
    echo "Errore: il campo 'time' non è stato definito.";
}
