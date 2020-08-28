<?php

session_start();

$_SESSION['user_id'] = 7;
$db = new PDO('mysql:host=localhost; dbname=website', 'root', '');

?>