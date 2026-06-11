<?php
// Temporarily check currency symbols in database
require_once('application/config/app-config.php');

$conn = mysqli_connect(APP_DB_HOSTNAME_DEFAULT, APP_DB_USERNAME_DEFAULT, APP_DB_PASSWORD_DEFAULT, APP_DB_NAME_DEFAULT);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT id, name, symbol, isdefault FROM tbl_currencies";
$result = mysqli_query($conn, $sql);

if ($result) {
    echo "<table border='1'>\n";
    echo "<tr><th>ID</th><th>Name</th><th>Symbol</th><th>Is Default</th></tr>\n";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['symbol']) . "</td>";
        echo "<td>" . htmlspecialchars($row['isdefault']) . "</td>";
        echo "</tr>\n";
    }
    echo "</table>\n";
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
