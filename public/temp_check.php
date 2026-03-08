<?php
header('Content-Type: text/plain');

echo "sys_get_temp_dir(): " . sys_get_temp_dir() . PHP_EOL;

$u = ini_get('upload_tmp_dir');
$s = ini_get('sys_temp_dir');

echo "upload_tmp_dir: " . ($u ?: '(empty)') . PHP_EOL;
echo "sys_temp_dir: " . ($s ?: '(empty)') . PHP_EOL;

$paths = array_filter([$u, $s, sys_get_temp_dir()]);
foreach ($paths as $p) {
    echo PHP_EOL . "== $p ==" . PHP_EOL;
    echo "exists: " . (is_dir($p) ? "YES" : "NO") . PHP_EOL;
    echo "writable: " . (is_writable($p) ? "YES" : "NO") . PHP_EOL;
}
