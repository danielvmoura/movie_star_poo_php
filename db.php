<?php

$db_name = "moviestar";
$db_host = "localhost";
$db_user = "danielvmoura";
$db_pass = "dm31032003";

$conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);

//HABILITAR erros PDO /aqui são confgs que se fizermos algo errado com o db, vai dar erro na tela
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
