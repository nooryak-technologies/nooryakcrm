<?php
$terms = ['lp2-right', 'lp2-anim', 'lp2-visible'];
$paths = [
    'c:\\xampp\\htdocs\\crm\\modules',
    'c:\\xampp\\htdocs\\crm\\application',
    'c:\\xampp\\htdocs\\crm',
];

foreach ($paths as $path) {
    if (is_file($path)) {
        search_file($path, $terms);
    } else if (is_dir($path)) {
        $it = new RecursiveDirectoryIterator($path);
        foreach (new RecursiveIteratorIterator($it) as $file) {
            if ($file->isDir()) continue;
            $filepath = $file->getPathname();
            if (preg_match('/[\\\\\/](vendor|node_modules|\.git|backups|system)[\\\\\/]/i', $filepath)) continue;
            search_file($filepath, $terms);
        }
    }
}

function search_file($filepath, $terms) {
    $ext = pathinfo($filepath, PATHINFO_EXTENSION);
    if (in_array($ext, ['php', 'html', 'js', 'css'])) {
        $content = @file_get_contents($filepath);
        if ($content !== false) {
            foreach ($terms as $term) {
                if (stripos($content, $term) !== false) {
                    echo "Found '$term' in file: $filepath\n";
                }
            }
        }
    }
}
