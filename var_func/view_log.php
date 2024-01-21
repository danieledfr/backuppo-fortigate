<?php
$file = $_GET['file']; // ottieni il nome del file di log dalla query string
$log_dir = "logs/"; // sostituisci con il percorso della cartella contenente i file di log
$file_path = $log_dir . $file; // percorso completo del file di log

if (!file_exists($file_path)) { // verifica se il file esiste
    die("File non trovato.");
}

$log_content = file_get_contents($file_path); // ottieni il contenuto del file di log
$log_content = htmlspecialchars($log_content); // converte i caratteri speciali in entità HTML

?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $file; ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        pre {
            white-space: pre-wrap;
            word-wrap: break-word;
            font-family: monospace;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo $file; ?></h1>
        <pre><?php echo $log_content; ?></pre>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
