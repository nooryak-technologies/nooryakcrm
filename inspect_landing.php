<?php
$files = [
    'c:\\xampp\\htdocs\\crm\\landing.html',
    'c:\\xampp\\htdocs\\crm\\landing_test.html',
    'c:\\xampp\\htdocs\\crm\\landing_test2.html',
    'c:\\xampp\\htdocs\\crm\\landing_test3.html',
];

foreach ($files as $file) {
    if (!file_exists($file)) {
        echo "File does not exist: $file\n";
        continue;
    }
    $content = file_get_contents($file);
    echo "File: $file (size: " . strlen($content) . ")\n";
    // check some terms
    $terms = ['growth', 'popular', 'Rs700', 'Rs900', 'Free Trail', 'Team Size', 'Trial'];
    foreach ($terms as $term) {
        if (stripos($content, $term) !== false) {
            echo "  Matched: $term\n";
        }
    }
}
