<?php 
    namespace App\Controllers;

    // recursos do miniframework
    use MF\Controller\Action;
    use MF\Model\Container;


    class AuthController extends Action {

        // metodo para login
        public function autenticar() {//action
            // instanciando o model
            $user = Container::getModel('Usuario');

            // recebendo os dados do formulario
            $user->__set('email', $_POST['email']);
            $user->__set('senha', $_POST['senha']);

            // verificando se os dados estao corretos
            $user->autenticarUser();

            if( !empty($user->__get('id')) && !empty($user->__get('nome')) ) {
                // definir os dados do usuario logado na sessao
                session_start();

                $_SESSION['id'] = $user->__get('id');
                $_SESSION['nome'] = $user->__get('nome');
                
                header('Location: /timeline');
            }else{
                header('Location: /?login=erro');
                exit;
            }
        }

        // metodo para logout
        public function sair() {//action
            session_start();
            session_destroy();
            header('Location: /');
        }
    }
?>