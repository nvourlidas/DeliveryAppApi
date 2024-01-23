<?php

 $host='localhost';
 $db = 'deliveryapp1';
 $user = 'postgres';
 $password = '16971214';
 $conn;

 try {
    $dsn = "pgsql:host=$host;port=5432;dbname=$db;";

    $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    if ($pdo) {
		echo "Connected to the $db database successfully!";
	}
 }catch (PDOException $e) {
    die($e->getMessage());
 }finally {
    if ($pdo) {
		$pdo = null;
	}
 }
        
?>