<?php include("bar.php"); ?>

<?php
$output = "";
if (isset($_POST['backup'])) {
    $output = shell_exec('/usr/bin/python3 cgi-bin/backup.py');
}
?>

<body>
    <div class="container">
        <h1>Home</h1>
        <div class="btn-group mb-3" role="group">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#backupModal">Esegui Backup</button>
        </div>

        <div class="row">
            <div class="col">
                <textarea class="form-control" rows="10" readonly><?php echo htmlspecialchars($output); ?></textarea>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="backupModal" tabindex="-1" role="dialog" aria-labelledby="backupModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="backupModalLabel">Esegui Backup</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Sei sicuro di voler eseguire il backup di tutti i dispositivi? Questa operazione non può essere annullata.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
                    <form method="post" action="">
                        <input type="hidden" name="backup" value="true">
                        <button type="submit" class="btn btn-primary">Conferma</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Includi le librerie JavaScript di Bootstrap -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
<?php include("footer.php"); ?>
