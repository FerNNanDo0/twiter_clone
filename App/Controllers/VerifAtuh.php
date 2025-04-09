<?
    namespace App\Controllers;

    class VerifAtuh {
        public static function verifUserAuth() {// verificar se o Usuário está conectado
            session_start();
            if(!empty($_SESSION['id']) || !empty($_SESSION['nome'])){
                return true;
            }else{
                return false;
            }
        }
    }
?>