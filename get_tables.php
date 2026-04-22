<?php
require 'config/db.php';
$tables = ['orders', 'order_items', 'products', 'users'];
foreach ($tables as $t) {
    echo "\nTable $t:\n";
    try {
        $stmt = $pdo->query("DESCRIBE $t");
        if($stmt){
            foreach ($stmt as $r) {
                echo "- {$r['Field']} ({$r['Type']})\n";
            }
        }
    } catch (Exception $e) { echo "error or doesn't exist\n"; }
}
