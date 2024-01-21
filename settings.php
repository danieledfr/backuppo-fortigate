<?php 
    include("bar.php");

    // Verifica se il file di configurazione esiste già
    $config_file = '/var/www/cgi-bin/schedule_backup.conf';
    if (file_exists($config_file)) {
        // Leggi la configurazione attuale
        $config_data = json_decode(file_get_contents($config_file), true);
        $hour = $config_data['hour'];
        $minute = $config_data['minute'];
        $recurrence = $config_data['recurrence'];
    } else {
        // Se il file non esiste, usa la configurazione di default
        $hour = '00';
        $minute = '20';
        $recurrence = 'daily';
    }
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impostazioni - Schedulatore orario</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Impostazioni</h1>
        <h4>Schedulatore orario</h4>
        <form action="var_func/createschedule.php" method="post">
            <div class="form-group">
                <label>Frequenza di backup:</label>
                <select class="form-control" name="recurrence">
                    <option value="daily" <?php if ($recurrence == 'daily') echo 'selected'; ?>>Giornaliera</option>
                    <option value="weekly" <?php if ($recurrence == 'weekly') echo 'selected'; ?>>Settimanale</option>
                    <option value="monthly" <?php if ($recurrence == 'monthly') echo 'selected'; ?>>Mensile</option>
                </select>
            </div>
            <div class="form-group">
                <label>Orario di backup:</label>
                <input class="form-control" type="time" name="time" value="<?php echo $hour . ':' . $minute; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Salva</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
</body>
</html>
<?php include("footer.php"); ?>
