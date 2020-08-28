<?php

session_start();

$_SESSION['user_id'] = 6;
$db = new PDO('mysql:host=localhost; dbname=website', 'root', '');

?>