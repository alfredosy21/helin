<?php
$a = require __DIR__ . '/lang/es/cms.php';
function find_dupes($arr, $path = '') {
    $keys = [];
    foreach ($arr as $k => $v) {
        if (isset($keys[$k])) {
            echo "DUPE: $path.$k\n";
        }
        $keys[$k] = true;
        if (is_array($v)) {
            find_dupes($v, $path ? "$path.$k" : $k);
        }
    }
}
find_dupes($a);
echo "Done\n";
