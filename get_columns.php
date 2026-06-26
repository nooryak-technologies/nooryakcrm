<?php
define('BASEPATH', 'dummy');
define('APPPATH', 'application/');
define('FCPATH', 'c:\\xampp\\htdocs\\crm\\');
include('application/config/database.php');
$db_config = $db['default'];
$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}
// Get active SMS gateways
$result = $mysqli->query("SELECT * FROM tbloptions WHERE name LIKE 'sms_%'");
while($row = $result->fetch_assoc()){
    echo $row['name'] . ": " . $row['value'] . "\n";
}
$mysqli->close();
?>
