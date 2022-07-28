<?php
require_once('Crud.php');
//REQUERIMENTO DO PHPMAILER
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';


class Usuario extends Crud{
    protected string $tabela = 'usuarios';

    function __construct(
        public string $nome,
        private string $email,
        private  string $senha,
        private  string $repete_senha="",
        private  string $recupera_senha="",
        private  string $token="",
        private  string $codigo_confirmacao="",
        private  string $status="",
        public array $erro=[]
    ){}

    public function set_repeticao($repete_senha){
        $this->repete_senha = $repete_senha;

    }
    public function validar_cadastro(){
        
        //VALIDANDO O NOME
        if (!preg_match("/^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ'\s]+$/",$this->nome)){
            $this->erro["erro_nome"]= "Por favor informe um nome válido!";
        }

        // VERIFICAÇÃO DE EMAIL
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            $this->erro["erro_email"] = "Por favor insiria um e-mail válido!";
        }

        // VERIFICACAO DE SENHA
        if(strlen($this->senha)<6){
            $this->erro["erro_senha"] = "A senha deve conter 6 ou mais dígitos!";
        }

        if($this->senha !== $this->repete_senha){
            $this->erro["erro_repete"] = "As senhas não coincidem!";
        }

    }

    public function insert(){
        //VERIFICANDO SE O EMAIL JA FOI CADASTRADO
        $sql = "SELECT * FROM usuarios WHERE email=? LIMIT 1";
        $sql = DB::prepare($sql);
        $sql->execute(array($this->email));
        $usuario = $sql->fetch();
        //SE O USUARIO NAO EXISTIR
        if(!$usuario){
            $data_cadastro = date('d/m/Y');
            $senha_cripto = sha1($this->senha);
            $codigo_confirmacao= uniqid();
            $status = "novo";
            $sql="INSERT INTO $this->tabela VALUES (null,?,?,?,?,?,?,?,?)";
            $sql = DB::prepare($sql);
            return $sql->execute(array($this->nome,$this->email,$senha_cripto,$this->recupera_senha,$this->token,$codigo_confirmacao,$status,$data_cadastro));
        }else{
            $this->erro["erro_geral"] = "Usuário já cadastrado!";

        }
    }
   

    public function sendEmail(){
        $sql = "SELECT * FROM usuarios WHERE email=? LIMIT 1";
        $sql = DB::prepare($sql);
        $sql->execute(array($this->email));
        $usuario = $sql->fetch();
        if($usuario){
            $mail = new PHPMailer(true);

            try {
                        
            //Recipients
            $mail->setFrom('zamodzki@hotmail.com', 'Sistema de Login'); //QUEM ESTÁ MANDANDO O EMAIL
            $mail->addAddress($this->email, $this->nome); //PESSOA PARA QUEM VAI O EMAIL
                        
            //Content
            $mail->isHTML(true);  //CORPO DO EMAIL COMO HTML
            $mail->Subject = 'Confirme seu cadastro!'; //TITULO DO EMAIL
            $mail->Body    = '<h1>Por favor confirme seu e-mail abaixo:</h1><br><br><a style="background:green; color:white; text-decoration:none; padding:20px; border-radius:5px;" href="https://buscarep.com.br/login/confirm.php?cod_confirm='.$this->codigo_confirmacao.'">Confirmar E-mail</a>';
                         
            $mail->send();
        
        } catch (Exception $e) {
            echo "Houve um problema ao enviar e-mail de confirmação: {$mail->ErrorInfo}";
        }
        }
        
    }
    
    public function confirm($cod){
        $sql ="SELECT * FROM usuarios WHERE codigo_confirmacao=? LIMIT 1";
        $sql = DB::prepare($sql);
        $sql->execute(array($cod));
        $usuario = $sql->fetch(PDO::FETCH_ASSOC);
        if($usuario){
            //ATUALIZAR O STATUS PARA CONFIRMADO
            $this->status = "confirmado";
            $sql ="UPDATE usuarios SET status=? WHERE codigo_confirmacao=?";
            $sql = DB::prepare($sql);
            return $sql->execute(array($this->status,$cod));
        }              
            
    }

    public function validarSenha(){
        // VERIFICACAO DE SENHA
        if(strlen($this->senha)<6){
            $this->erro["erro_senha"] = "A senha deve conter 6 ou mais dígitos!";
        }

        if($this->senha !== $this->repete_senha){
            $this->erro["erro_repete"] = "As senhas não coincidem!";
        }
        //RECEBER VALORES VINDOS DO POST E LIMPAR        
    }
   

    public function update($id){
        $sql = "UPDATE $this->tabela SET token=? WHERE id=?";
        $sql = DB::prepare($sql);
        return $sql->execute(array($this->token,$id));

    }
}