<?php
    namespace MF\Controller;

    abstract class Action{
            
        protected $view;

        public function __construct(){
            $this->view = new \stdClass();
        }
    
        // Metodo para renderizar a view
        protected function render($view, $layout = "layout"){
            $this->view->page = $view;
            // renderizar o layout
            // verificar se existe o layout que foi passado
            if(file_exists("../App/Views/".$layout.".phtml")){
                // pode mudar todo layout somente aqui se quiser
                require_once "../App/Views/".$layout.".phtml";
            }else{
                $this->content();
            }
            
        }

        protected function content(){
            $caminhoClassAtual = get_class($this);
            $classAtual = str_replace('App\\Controllers\\', '', $caminhoClassAtual);
            $classAtual = strtolower(str_replace('Controller', '', $classAtual));
            
            require_once "../App/Views/".$classAtual."/".$this->view->page.".phtml";
        }
    }
?>