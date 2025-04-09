<?php

    namespace App\Controllers;

    // recursos do miniframework
    use MF\Controller\Action;
    use MF\Model\Container;

    // verificar autenticação
    use App\Controllers\VerifAtuh;

    use App\Controllers\AppController;


    class IndexController extends Action{

        // persistir os dados do formulario na view inscreverse
        private function persistirDadosView($nome = "", $senha = ""){
            $this->view->usuario = array(
                'nome' => $nome,
                'senha' => $senha
            );
        }   

        // metodo para chamar a view index
        public function index(){//action
            /* OBJETO PARA A VIEW RECEBER DADOS
            $this->view->dados = array('Sofá', 'Cadeira', 'Cama');
            $produto = Container::getModel('');
            chamando o metodo getProdutos
            $this->view->dados = $produto->getProdutos();*/

            // verificar autenticação
            if(VerifAtuh::verifUserAuth()){
                header('Location: /timeline');
            }else{
                $this->view->login = isset($_GET['login']) ? $_GET['login'] : '';

                $this->render('index', 'layout2');
            }

            // $this->view->login = isset($_GET['login']) ? $_GET['login'] : '';
            // $this->render('index');
        }

        // metodo para chamar a view inscreverse
        public function inscreverse(){//action
            // persistir os dados do formulario na view inscreverse
            $this->persistirDadosView();

            $this->view->erroCadastro = false;

            $this->render('inscreverse', 'layout2');
        }

        // metodo para registrar que sera chamado pela ROTA
        public function registrar(){//action
            // receber dados do formulario 
            $user = Container::getModel('Usuario');
            $user->__set('nome', $_POST['nome']);
            $user->__set('email', $_POST['email']);
            $user->__set('senha', $_POST['senha']);
            
            // validar os dados e apos isso verificar se o email ja existe no DB
            if($user->validarCadastro() && count($user->getUsuarioPorEmail()) == 0){
                // Sucesso se os dados tiver preenchidos corretamente
                // e salvar se o email não existir no DB
                $user->salvar();

                // redirecionar para a view cadastrado com sucesso
                $this->render('cadastro');
                
            }else{// Erro se os dados estiverem incorretos ou o email já existir no DB

                // persistir os dados do formulario na view inscreverse
                $this->persistirDadosView($_POST['nome'], $_POST['senha']);
                
                // feeback visual de Erro para o usuario
                $this->view->erroCadastro = true;

                // redirecionar para a view inscreverse com erro   
                $this->render('/inscreverse');
            }
        }
    }
?>