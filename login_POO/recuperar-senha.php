<?php
require_once('class/config.php');
require_once('autoload.php');

if(isset($_GET['cod']) && !empty($_GET['cod'])){
    //LIMPAR O GET
    $cod = cleanPost($_GET['cod']);
        //VERIFICAR SE A POSTAGEM EXISTE DE ACORDO COM OS CAMPOS
    if(isset($_POST['senha']) && isset($_POST['repete_senha'])){
    //VERIFICAR SE TODOS OS CAMPOS FORAM PREENCHIDOS
    }
    if(empty($_POST['senha']) or empty($_POST['repete_senha'])){
        $erro_geral= "Todos os campos são obrigatórios!";
    }else{
        $senha = cleanPost($_POST['senha']);
        $repete_senha = cleanPost($_POST['repete_senha']);

        $recuperar = new Recuperar($senha,$repete_senha);

        if(empty($recuperar->erro)){
            if($recuperar->novaSenha($cod)){
                header('location: index.php');
            }
        }
    }
}else{
    header('location: index.php');

}
?>



<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/estilo.css" rel="stylesheet">
    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
  />
    <title>Trocar Senha</title>
</head>
<body>
    <form method="post">
        <h1>Trocar a Senha</h1>
        
        <?php if(isset($erro_geral)){?>
        <div class="erro-geral animate__animated animate__rubberBand">
            <?php echo $erro_geral;?>
        </div>
        <?php }?>
        
        
        <div class="input-group">
            <img class="input-icon" src="img/lock.png">
            <input <?php if(isset($recuperar->erro["erro_senha"]) or isset($erro_geral)){echo 'class="erro-input"'; }?> type="password" name="senha" <?php if(isset($_POST['senha'])){echo 'value="'.$_POST['senha'].'"';}?> placeholder="Senha mínimo 6 Dígitos" required>
            <div class="erro"><?php if(isset($recuperar->erro["erro_senha"])) {echo$recuperar->erro["erro_senha"];}?></div>
        </div>

        <div class="input-group">
            <img class="input-icon" src="img/lock-open.png">
            <input <?php if(isset($recuperar->erro["erro_repete"]) or isset($erro_geral)){echo 'class="erro-input"'; }?> type="password" name="repete_senha" <?php if(isset($_POST['repete_senha'])){echo 'value="'.$_POST['repete_senha'].'"';}?>  placeholder="Repita a senha criada" required>
            <div class="erro"><?php if(isset($recuperar->erro["erro_repete"])) {echo$recuperar->erro["erro_repete"];}?></div>
        </div>       
        
        <button class="btn-blue" type="submit">Alterar a Senha</button>
       
    </form>
</body>
</html>