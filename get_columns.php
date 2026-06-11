<?php
define('BASEPATH', 'dummy');
include('application/config/database.php');
$db_config = $db['default'];
$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}
$result = $mysqli->query("DESCRIBE tblleads");
while($row = $result->fetch_assoc()){
    echo $row['Field'] . "\n";
}
$mysqli->close();
?>
