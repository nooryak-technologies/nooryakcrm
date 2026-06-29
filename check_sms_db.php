<?php
define('BASEPATH', 'dummy');
define('FCPATH', __DIR__ . '/');
require_once('application/config/app-config.php');

$conn = mysqli_connect(APP_DB_HOSTNAME_DEFAULT, APP_DB_USERNAME_DEFAULT, APP_DB_PASSWORD_DEFAULT, APP_DB_NAME_DEFAULT);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT name, value FROM tbloptions WHERE name LIKE 'sms_meraotp_%'";
$result = mysqli_query($conn, $sql);

if ($result) {
    echo "<table border='1'>\n";
    echo "<tr><th>Name</th><th>Value</th></tr>\n";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['value']) . "</td>";
        echo "</tr>\n";
    }
    echo "</table>\n";
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
