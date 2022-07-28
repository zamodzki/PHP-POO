<?php
require_once('class/config.php');
require_once('autoload.php');

if(isset($_GET['cod_confirm']) && !empty($_GET['cod_confirm'])){
    
    //LIMPAR O GET
    $cod = cleanPost($_GET['cod_confirm']);

    if($usuario->confirm($cod)){
        header('location: index.php?result=ok');

    }else{
        echo "<h1>Código de confirmação inválido!</h1>";
    }
       
}
