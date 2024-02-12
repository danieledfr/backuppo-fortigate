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

// Esegui la query per ottenere tutti i record dalla tabella "devices"
$sql = "SELECT * FROM devices";
$result = $db->query($sql);
?>


<?php
function is_token_valid($token) {
    return (strlen($token) == 30 && ctype_alnum($token));
}
?>

<?php
// Impostazioni di default per la paginazione
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;

// Calcola l'offset per la query
$offset = ($page - 1) * $limit;

// Esegui la query con limit e offset
$sql = "SELECT * FROM devices LIMIT :limit OFFSET :offset";
$stmt = $db->prepare($sql);
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll();

// Calcola il numero totale di dispositivi
$total_devices = $db->query("SELECT COUNT(*) FROM devices")->fetchColumn();

// Calcola il numero totale di pagine
$total_pages = ceil($total_devices / $limit);


?>

<!DOCTYPE html>
<html lang="it">
<head>
    <link rel="stylesheet" type="text/css" href="assets/style.css">
    <link rel="stylesheet" href="assets/fontawesome/css/all.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dispositivi</title>
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

        <?php
        echo '<h1>Elenco dei dispositivi (' . $total_devices . ')</h1>';

        // Genera i link di paginazione
        echo '<ul class="pagination">';
        for ($i = 1; $i <= $total_pages; $i++) {
            $active = ($i == $page) ? ' active' : '';
            $url = '?page=' . $i . '&limit=' . $limit;
            echo '<li class="page-item' . $active . '"><a class="page-link" href="' . $url . '">' . $i . '</a></li>';
        }
        echo '</ul>';

        ?>

        <div class="btn-group mb-3" role="group">
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#truncateModal">Elimina tutti i dispositivi</button>
            <div style="width: 10px;"></div>
            <form action="add_device.php" method="post">
            <button type="submit" class="btn btn-primary">Aggiungi un dispositivo</button>
            </form>
        </div>

        <table class="table" id="devices-table">
            <thead>
                <tr>
                    <th>Enable</th>
                    <th>Nome dispositivo</th>
                    <th>Indirizzo IP</th>
                    <th>Porta HTTPS</th>
                    <th>Token</th>
                    <th>Last Backup</th>
                    <th>Azioni</th>
                    <th>Ultima Configurazione</th>
                </tr>
            </thead>
            
            <tbody>
            <?php foreach ($result as $row): ?>
            <?php
            $lastBackup = $row['lastbackup'];
            $twoDaysAgo = date('Y-m-d', strtotime('-2 days'));
            $rowClass = '';
            if ($lastBackup == 'none' || $lastBackup < $twoDaysAgo) {
                $rowClass = 'table-danger';
            }
            ?>
            <tr class="<?php echo $rowClass; ?>">
                <td><input type="checkbox" class="enable-checkbox" data-id="<?php echo $row['id']; ?>" <?php echo $row['enable'] ? 'checked' : ''; ?>></td>
                <td><?php echo htmlspecialchars($row['device_name']); ?></td>
                <td><?php echo htmlspecialchars($row['ip_address']); ?></td>
                <td><?php echo htmlspecialchars($row['https_port']); ?></td>
                <td><?php echo is_token_valid($row['token']) ? 'Token valido' : 'Token non valido'; ?></td>
                <td><?php echo htmlspecialchars($lastBackup); ?></td>
                <td>
                <div class="btn-group-vertical">
                <button type="button" class="btn btn-danger" data-id="<?php echo $row['id']; ?>" id="deleteDeviceBtn">Elimina</button>
                <form action="device_func/edit_device.php" method="get">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <button type="submit" class="btn btn-primary mt-2">Modifica</button>
                </form>
                </div>
                </td>

                <td style="text-align:center;">
                    <a href="latest_backup.php?device_name=<?php echo urlencode($row['device_name']); ?>">
                        <i class="fas fa-save"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>

        </table>
    </div>

    <!-- Modal conferma eliminazione di tutti i dispositivi -->
<div class="modal fade" id="truncateModal" tabindex="-1" role="dialog" aria-labelledby="modalConfermaEliminazioneDispositiviLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalConfermaEliminazioneDispositiviLabel">Conferma eliminazione tutti i dispositivi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Sei sicuro di voler eliminare tutti i dispositivi? Questa operazione non può essere annullata.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
        <form action="device_func/truncate_devices.php" method="post">
            <button type="submit" class="btn btn-danger">Elimina</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
    $(document).ready(function() {
        $('.enable-checkbox').change(function() {
            const deviceId = $(this).data('id');
            const enable = $(this).prop('checked') ? 1 : 0;

            // Invia il nuovo valore al server tramite AJAX
            $.ajax({
                url: 'update_enable.php',
                method: 'POST',
                data: { id: deviceId, enable: enable },
                success: function(response) {
                    // Aggiorna la pagina o effettua altre azioni necessarie
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>


<script>
    $(document).ready(function() {
        // Event listener per il pulsante "Elimina"
        $(document).on('click', '#deleteDeviceBtn', function() {
            const deviceId = $(this).data('id');
            
            // Creazione del modal di eliminazione del dispositivo
            const deleteDeviceModal = `
                <div class="modal fade" id="deleteDeviceModal" tabindex="-1" role="dialog" aria-labelledby="modalConfermaEliminazioneDispositiviLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalConfermaEliminazioneDispositiviLabel">Conferma eliminazione dispositivo</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Sei sicuro di voler eliminare questo dispositivo? Questa operazione non può essere annullata.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
                                <form action="device_func/delete_device.php" method="post">
                                    <input type="hidden" name="device_id" value="${deviceId}">
                                    <button type="submit" class="btn btn-danger">Elimina</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>`;
            
            // Aggiungi il modal al body e mostralo
            $('body').append(deleteDeviceModal);
            $('#deleteDeviceModal').modal('show');

            // Rimuovi il modal dal DOM una volta nascosto
            $('#deleteDeviceModal').on('hidden.bs.modal', function() {
                $(this).remove();
            });
        });
    });
</script>
    <!-- Includi le librerie JavaScript di Bootstrap -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>



</body>
</html>
<?php include("footer.php"); ?>