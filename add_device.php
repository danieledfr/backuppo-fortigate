<?php include("bar.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aggiungi dispositivo FortiGate</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        label {
            font-weight: bold;
        }
        form {
            max-width: 500px;
            margin: 0 auto;
            margin-top: 50px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        form input[type="text"], form input[type="number"] {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            box-sizing: border-box;
            border: none;
            border-bottom: 1px solid #ddd;
            outline: none;
        }
        form input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            float: right;
        }
        form input[type="submit"]:hover {
            background-color: #45a049;
        }
        form::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>
    <div class="container">
    <h1>Aggiungi dispositivo FortiGate</h1>
    <form action="device_func/insert_device.php" method="post">
        <div class="form-group">
            <label for="device_name">Nome dispositivo:</label>
            <input type="text" class="form-control" id="device_name" name="device_name" required>
        </div>
        <div class="form-group">
            <label for="ip_address">Indirizzo IP:</label>
            <input type="text" class="form-control" id="ip_address" name="ip_address" required>
        </div>
        <div class="form-group">
            <label for="https_port">Porta HTTPS:</label>
            <input type="number" class="form-control" id="https_port" name="https_port" required>
        </div>
        <div class="form-group">
            <label for="token">Token:</label>
            <input type="text" class="form-control" id="token" name="token" required>
        </div>
        <input type="submit" value="Aggiungi">
    </form>
    </div>

    <!-- Includi le librerie JavaScript di Bootstrap -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
