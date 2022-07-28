<?php
require_once('class/config.php');
require_once('autoload.php');

if(isset($_POST['email'])&& !empty($_POST['email'])){
    // RECEBER OS DADOS DO POST E LIMPAR
    $email = cleanPost($_POST['email']);
    $esqueci= new Esqueceu();
    if($esqueci->esqueci($email)){
        $esqueci->sendRecupera($email);
        header('location: env_email_recupera.php');
    }else{
        $user_error = "Houve uma falha ao buscar este e-mail. Tente novamente!";
    }

}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/estilo.css">
    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
    />
    <title>Esqueceu a senha</title>
    
</head>
<body>
    <form method="post">
        <h1>Recuperar senha</h1>
        <?php if(isset($user_error)){ ?>
            <div  style="text-align:center" class="erro-geral animate__animated animate__rubberBand">
            <?php  echo $user_error; ?>

            </div>;
        <?php } ?>

        <div class="input-group">
            <img class= "input-icon"src="img/user.png">
            <input type="email" name="email" placeholder="Digite seu email" required>
        </div>
        
        <button  class="btn-blue"type="submit">Recuperar senha</button>
        <a href="index.php">Voltar para login</a>
    </form>
</body>
</html>