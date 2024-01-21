<?php include("bar.php"); ?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log</title>
    <!-- Includi la libreria Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Log</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Nome File</th>
                    <th>Dimensione</th>
                    <th>Data Creazione</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $dir = "logs/"; // directory contenente i file di log
                    $files = scandir($dir); // scandisce la directory e restituisce un array dei file

                    // Funzione per ordinare i file per data di creazione
                    function sort_by_date($a, $b) {
                    global $dir;
                    return filectime($dir . $b) - filectime($dir . $a);
                    }
                    
                    // Ordina l'array dei file per data di creazione
                    usort($files, "sort_by_date");

                    foreach ($files as $file) { // ciclo sui file della directory
                        if (strpos($file, '.log') !== false) { // verifica se il file è un file di log
                            $path = $dir . $file;
                            $size = filesize($path); // dimensione in byte del file
                            $created = date('d/m/Y H:i:s', filectime($path)); // data creazione del file

                            echo "<tr>";
                            echo "<td>$file</td>";
                            echo "<td>" . human_filesize($size) . "</td>"; // funzione per formattare la dimensione in modo più leggibile
                            echo "<td>$created</td>";
                            echo "<td>";
                            echo "<a href='$dir$file' download='$file' class='btn btn-primary mr-2' role='button'>Scarica</a>";
                            echo "<a href='#' data-file='$file' class='btn btn-secondary mr-2 ml-2 view-log-btn' role='button'>Visualizza</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    }

                    function human_filesize($size) { // funzione per formattare la dimensione in modo più leggibile
                        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
                        $power = $size > 0 ? floor(log($size, 1024)) : 0;
                        return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
                    }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Includi le librerie JavaScript di Bootstrap -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min

    <?php include("footer.php"); ?>