<?php
require_once('../class/config.php');
require_once('../autoload.php');

$login = new Login();
$login->isAuth($_SESSION['TOKEN']);

echo "<h1>Bem-vindo $login->nome!<br> Email: $login->email";
echo "<br><br><a style='background:green; color:white; text-decoration:none; padding:20px; border-radius:5px;' href='../logout.php'>Sair do sistema</a>";

