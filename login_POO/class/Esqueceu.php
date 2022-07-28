<?php
require_once('Usuario.php');
//REQUERIMENTO DO PHPMAILER
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Esqueceu{
    protected string $tabela = 'usuarios';


    public function esqueci($email){
        $sql = "SELECT * FROM $this->tabela WHERE email=?  LIMIT 1";
        $sql = DB::prepare($sql);
        $sql->execute(array($email));
        return $sql->fetch(PDO::FETCH_ASSOC);
    } 
    public function sendRecupera($email){
        //ENVIAR EMAIL PARA USUARIO FAZER NOVA SENHA
        $mail = new PHPMailer(true);
        $cod = sha1(uniqid());

        //ATUALIZAR O CÓDIGO DE RECUPERACAO DESTE USUARIO NO BANCO
        $sql ="UPDATE $this->tabela SET recupera_senha=? WHERE email=?";
        $sql = DB::prepare($sql);
        if($sql->execute(array($cod,$email))){

            try {
    
                //Recipients
                $mail->setFrom('zamodzki@hotmail.com', 'Sistema de Login'); //QUEM ESTÁ MANDANDO O EMAIL
                $mail->addAddress($email, $this->nome); //PESSOA PARA QUEM VAI O EMAIL
            
            //Content
                $mail->isHTML(true);  //CORPO DO EMAIL COMO HTML
                $mail->Subject = 'Recuperar a senha!'; //TITULO DO EMAIL
                $mail->Body    = '<h1>Recuperar a senha:</h1><a style="background:green; color:white; text-decoration:none; padding:20px; border-radius:5px;" href="https://buscarep.com.br/login/recuperar-senha.php?cod='.$this->recupera_senha.'">Recuperar a senha</a><br><br><p>Equipe do Login</p>';
             
                $mail->send();
                       
    
            } catch (Exception $e) {
                echo "Houve um problema ao enviar e-mail de confirmação: {$mail->ErrorInfo}";
            }
        } 
    }
}