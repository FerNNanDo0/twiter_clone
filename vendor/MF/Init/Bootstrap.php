<?php
    namespace MF\Init;

    abstract class Bootstrap{
        private $routes;

        abstract protected function initRoutes();

        public function __construct(){
            $this->initRoutes();
            $this->run($this->getUrl());
        }

        protected function getUrl(){
            return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        }

        public function getRoutes(){
            return $this->routes;
        }

        public function setRoutes(Array $route){
            $this->routes = $route;
        }

        protected function run($url){
            foreach($this->getRoutes() as $key => $route){
                if($url == $route['route']){
                    $indexController = "App\\Controllers\\" . ucfirst( $route['controller'] );

                    $controller = new $indexController();
                    $action = $route['action'];
                   // $controller->{$route['action']}();
                    $controller->$action();
                    break;
                }
            }
        }

    }
        
?>