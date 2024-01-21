<?php
  $page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" type="text/css" href="assets/style.css">
  <!-- Includi la libreria Bootstrap -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
  <!-- Aggiungi la barra di navigazione -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="index.php">Backuppo</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item <?php echo $page === 'index.php' ? 'active' : ''; ?>">
          <a class="nav-link" href="index.php">Home</a>
        </li>
        <li class="nav-item <?php echo $page === 'manage_devices.php' ? 'active' : ''; ?>">
          <a class="nav-link" href="manage_devices.php">Gestione Dispositivi</a>
        </li>
        <li class="nav-item <?php echo $page === 'log.php' ? 'active' : ''; ?>">
          <a class="nav-link" href="log.php">Log</a>
        </li>
        <li class="nav-item <?php echo $page === 'settings.php' ? 'active' : ''; ?>">
          <a class="nav-link" href="settings.php">Impostazioni</a>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <span class="navbar-text">beta</span>
        </li>
      </ul>
    </div>
  </nav>

  <!-- Includi le librerie JavaScript di Bootstrap -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
