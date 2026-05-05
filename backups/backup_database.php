<?php

include "../includes/init.php";  // Include Init File

$host = "localhost";
$user = "root";
$pass = "";
$db   = "flour_mill_system";

// Backup file name
$backup_file = "backup_" . date('Y-m-d_H-i-s') . ".sql";

// Create backup command
$command = "C:/xampp/mysql/bin/mysqldump --host=$host --user=$user --password=$pass $db > $backup_file";

// Execute command
system($command);

// Download backup
header('Content-Type: application/sql');
header('Content-Disposition: attachment; filename="' . basename($backup_file) . '"');

readfile($backup_file);

exit;
?>