<!DOCTYPE html>
<html>
<head>
    <title>Update Currency Symbol to Indian Rupee (₹)</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #4CAF50;
            padding-bottom: 10px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
            border-left: 4px solid #28a745;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
            border-left: 4px solid #dc3545;
        }
        .info {
            background: #d1ecf1;
            color: #0c5460;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
            border-left: 4px solid #17a2b8;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .button {
            background-color: #4CAF50;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }
        .button:hover {
            background-color: #45a049;
        }
        .button-secondary {
            background-color: #6c757d;
        }
        .button-secondary:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🪙 Update Currency Symbol to Indian Rupee (₹)</h1>
        
        <?php
        require_once('application/config/app-config.php');

        $conn = mysqli_connect(APP_DB_HOSTNAME_DEFAULT, APP_DB_USERNAME_DEFAULT, APP_DB_PASSWORD_DEFAULT, APP_DB_NAME_DEFAULT);

        if (!$conn) {
            echo '<div class="error"><strong>Error:</strong> Connection failed: ' . htmlspecialchars(mysqli_connect_error()) . '</div>';
            exit;
        }

        // Set character set
        mysqli_set_charset($conn, 'utf8mb4');

        // Check if update is requested
        if (isset($_GET['action']) && $_GET['action'] === 'update') {
            echo '<div class="info"><strong>Processing Update...</strong></div>';
            
            // Update query - change all $ symbols to ₹
            $updateQuery = "UPDATE `tbl_currencies` SET `symbol` = '₹' WHERE `symbol` = '$'";
            
            if (mysqli_query($conn, $updateQuery)) {
                $affected = mysqli_affected_rows($conn);
                echo '<div class="success"><strong>✓ Success!</strong> Updated ' . $affected . ' currency record(s) from $ to ₹</div>';
            } else {
                echo '<div class="error"><strong>Error:</strong> ' . htmlspecialchars(mysqli_error($conn)) . '</div>';
            }
            
            echo '<a href="update_currency_symbol.php" class="button button-secondary">View Updated Currencies</a><br><br>';
        }

        // Display current currencies
        echo '<h2>Current Currencies in Database</h2>';
        
        $sql = "SELECT id, name, symbol, isdefault FROM `tbl_currencies` ORDER BY isdefault DESC, name ASC";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            echo '<table>';
            echo '<tr><th>ID</th><th>Currency Name</th><th>Symbol</th><th>Is Default</th></tr>';
            
            $hasDollar = false;
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['id']) . '</td>';
                echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                echo '<td style="font-size: 20px; font-weight: bold;">' . htmlspecialchars($row['symbol']) . '</td>';
                echo '<td>' . ($row['isdefault'] == 1 ? '✓ Yes' : 'No') . '</td>';
                echo '</tr>';
                
                if ($row['symbol'] === '$') {
                    $hasDollar = true;
                }
            }
            echo '</table>';
            
            if ($hasDollar) {
                echo '<div class="info">';
                echo '<strong>Note:</strong> Dollar symbol ($) detected in your currencies. Click the button below to update all $ symbols to ₹ (Indian Rupee).<br>';
                echo '<a href="update_currency_symbol.php?action=update" class="button" onclick="return confirm(\'Are you sure you want to update all $ symbols to ₹?\')">Update $ to ₹</a>';
                echo '</div>';
            } else {
                echo '<div class="success">';
                echo '<strong>✓ Great!</strong> No dollar ($) symbols found in your database. All currencies are already configured.';
                echo '</div>';
            }
        } else {
            echo '<div class="error"><strong>Error:</strong> No currencies found in database or query failed.</div>';
        }

        mysqli_close($conn);
        ?>
        
        <hr style="margin: 30px 0;">
        
        <h2>📋 Instructions</h2>
        <ol>
            <li><strong>Review currencies:</strong> Check the table above to see current currency symbols</li>
            <li><strong>Update symbols:</strong> If you see $ symbols, click "Update $ to ₹" button</li>
            <li><strong>Delete this file:</strong> After updating, delete this file (update_currency_symbol.php) for security</li>
            <li><strong>Clear cache:</strong> Clear your browser cache and refresh your CRM</li>
        </ol>
        
        <div class="info">
            <strong>⚠️ Security Notice:</strong> Please delete this file after completing the update to prevent unauthorized access.
        </div>
    </div>
</body>
</html>
