<?php
require_once('Usuario.php');

class Recuperar{
    protected string $tabela = 'usuarios';
    private  string $senha;
    private  string $repete_senha;

    
    public function verificar_senha(){
        //VERIFICAR SE SENHA TEM MAIS DE 6 DÍGITOS
        if(strlen($this->senha) < 6 ){
            $erro_senha = "Senha deve ter 6 caracteres ou mais!";
        }

        //VERIFICAR SE RETEPE SENHA É IGUAL A SENHA
        if($this->senha !== $this->repete_senha){
            $erro_repete_senha = "Senha e repetição de senha diferentes!";
        }
    }

    public function novaSenha($cod){
         //VERIFICAR SE ESTE RECUPERACAO DE SENHA EXISTE
         $sql = "SELECT * FROM usuarios WHERE recupera_senha=? LIMIT 1";
         $sql = DB::prepare($sql);
         $sql->execute(array($cod));
         $usuario = $sql->fetch();
         //SE NÃO EXISTIR O USUARIO 
         if(!$usuario){
             $this->erro["erro_geral"]="Recuperação de senha inválida!";
         }else{
             //JÁ EXISTE USUARIO COM ESSE CÓDIGO DE RECUPERAÇÃO
              //ATUALIZAR O TOKEN DESTE USUARIO NO BANCO   
            $senha_cripto = sha1($this->senha);   
            $sql = "UPDATE usuarios SET senha=? WHERE recupera_senha=?";
            $sql = DB::prepare($sql);
            return $sql->execute(array($senha_cripto,$cod)); 
         }
    }
}