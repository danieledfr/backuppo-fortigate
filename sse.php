<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

$cmd = "/usr/bin/python3 -u /var/www/cgi-bin/backup.py";
$descriptorspec = array(
    0 => array('pipe', 'r'), // stdin
    1 => array('pipe', 'w'), // stdout
    2 => array('pipe', 'w') // stderr
);

$process = proc_open($cmd, $descriptorspec, $pipes);
if (is_resource($process)) {
    while ($s = fgets($pipes[1])) {
        echo "data: $s\n\n";
        ob_flush();
        flush();
    }
}
