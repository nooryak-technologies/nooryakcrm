<?php
$handle = fopen('bazaarwa_ps_demo (4).sql', 'r');
if ($handle) {
    $line_num = 0;
    while (($line = fgets($handle)) !== false) {
        $line_num++;
        if ($line_num >= 3845 && $line_num <= 3865) {
            echo "Line $line_num: $line\n";
        }
    }
    fclose($handle);
}
?>
