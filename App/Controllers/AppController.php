<?php 
    namespace App\Controllers;

    // recursos do miniframework
    use MF\Controller\Action;
    use MF\Model\Container;
    // verificar autenticação
    use App\Controllers\VerifAtuh;
    
    class AppController extends Action {

        // formatar nome usuario logado 
        private function formatarNome($nome) {
            $nome = trim($nome); // Remove espaços extras no início e no fim.
            $nome = array_filter(explode(" ", $nome)); // Garante que não haja elementos vazios no array.
            return count($nome) > 1 ? 
                    ucfirst(reset($nome)) . " " . ucfirst(end($nome)) : ucfirst(reset($nome)); // Retorna o primeiro e último elemento
        }

        private function getTotalEstatisticas(){
            $estatiscas = array();
            $usuario = Container::getModel('Usuario');
            $usuario->__set('id', $_SESSION['id']);

            $estatiscas['total_tweets'] = $usuario->getTotalTweets();// chamar o metodo la no model
            $estatiscas['total_seguindo'] = $usuario->getTotalSeguindo();// chamar o metodo la no model
            $estatiscas['total_seguidores'] = $usuario->getTotalSeguidores();// chamar o metodo la no model
            return $estatiscas;
        }

        public function timeline() {//action
            // verificar autenticação
            if(VerifAtuh::verifUserAuth()){

                // recuperar tweets
                $tweet = Container::getModel('Tweet');
                $tweet->__set('id_user', $_SESSION['id']);



                // configurações de paginação
                $limite = 7; // Defina o número de registros por página
                $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1; // Página inicial
                $offset = ($pagina - 1) * $limite; // Deslocamento inicial

                // recuperar todos Tweets
                $this->view->tweets = $tweet->getAllTweets($limite, $offset);// chamar o metodo la no model

                //total de paginas
                $this->view->total_paginas = ceil($tweet->countRegistros() / $limite); // Arredonda para cima

                // pagina ativa
                $this->view->pagina_ativa = $pagina; // Página atual



                // recuperar as estatiscas do usuario logado total[tweets, seguidores, seguindo]
                $this->view->estatiscas = $this->getTotalEstatisticas();
                
                // recuperar o nome formatado do usuario logado
                $this->view->nome = $this->formatarNome($_SESSION['nome']); // Formatar o nome do usuário logado

                $this->render('timeline');   
            }else{
                header('Location: /?login=erro');
                exit;
            }
        }

        // Definir e salvar os tweet 
        public function tweet() {//action
            // verificar autenticação
            if(VerifAtuh::verifUserAuth()){

                $tweet = Container::getModel('Tweet');
                $tweet->__set('tweet', $_POST['tweet']);
                $tweet->__set('id_user', $_SESSION['id']);

                $tweet->salvar();// chamar o metodo la no model

                header('Location: /timeline');   
            }else{
                header('Location: /?login=erro');
                exit;
            }
        }

        
        // Remover tweet
        public function removerTweet() {//action
            // verificar autenticação
            if(VerifAtuh::verifUserAuth()){

                $tweet = Container::getModel('Tweet');
                $tweet->__set('id', $_GET['id']);
                $tweet->__set('id_user', $_SESSION['id']);

                $tweet->removerTweet();// chamar o metodo la no model

                header('Location: /timeline');   
            }else{
                header('Location: /?login=erro');
                exit;
            }
        }

        // Pesquisar por outro usuarios
        public function quem_seguir() {//action
            // verificar autenticação
            if(VerifAtuh::verifUserAuth()){
                // recuperar o nome formatado do usuario logado
                $this->view->nome = $this->formatarNome($_SESSION['nome']); // Formatar o nome do usuário logado

                // criar um array vazio para armazenar os usuarios
                $usuarios = array();

                // recuperar as estatiscas do usuario logado total[tweets, seguidores, seguindo]
                $this->view->estatiscas = $this->getTotalEstatisticas();

                // recuperar nome a ser pesquisado no db
                $pesquisarPor = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor'] : "";

                // verificar se o campo de pesquisa não está vazio
                if( !empty($pesquisarPor) ){
                    $usuario = Container::getModel('Usuario');
                    $usuario->__set('nome', $pesquisarPor);
                    $usuario->__set('id', $_SESSION['id']);
                    $usuarios = $usuario->getAll();// chamar o metodo la no model
                }

                $this->view->usuarios = $usuarios;
                $this->render('quemSeguir');

            }else{
                header('Location: /?login=erro');
                exit;
            }
        }

        // Seguir usuario ou deixar de seguir
        public function acao() {//action
            // verificar autenticação
            if(VerifAtuh::verifUserAuth()){
                
                $acao = isset($_GET['acao']) ? $_GET['acao'] : "";
                $id_usuario_seguindo = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : "";

                $usuario = Container::getModel('Usuario');
                $usuario->__set('id', $_SESSION['id']);

                // verificar a acao a ser realizada
                // seguir usuario ou deixar de seguir
                if($acao == 'seguir'){
                    $usuario->seguirUsuario($id_usuario_seguindo);// chamar o metodo la no model

                }else if($acao == 'deixar_de_seguir'){
                    $usuario->deixarSeguirUsuario($id_usuario_seguindo);// chamar o metodo la no model
                }

                // redirecionar para a pagina de quem seguir
                header('Location: /quem_seguir');

            }else{
                header('Location: /?login=erro');
                exit;
            }
        }

    }
?>