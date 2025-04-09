<?php
    namespace App;
    use MF\Init\Bootstrap;

    class Route extends Bootstrap{
        
        protected function initRoutes(){

            // Rotas do IndexController ->
            $routes['home'] = array(
                'route' => '/',
                'controller' => 'IndexController',
                'action' => 'index'
            );

            $routes['inscreverse'] = array(
                'route' => '/inscreverse',
                'controller' => 'IndexController',
                'action' => 'inscreverse'
            );

            $routes['registrar'] = array(
                'route' => '/registrar',
                'controller' => 'IndexController',
                'action' => 'registrar'
            );// Rotas do IndexController <-
            

            // Rotas do AuthController ->
            $routes['autenticar'] = array(
                'route' => '/autenticar',
                'controller' => 'AuthController',
                'action' => 'autenticar'
            );

            $routes['sair'] = array(
                'route' => '/sair',
                'controller' => 'AuthController',
                'action' => 'sair'
            );// Rotas do AuthController <-


            // Rotas do AppController ->
            $routes['timeline'] = array(
                'route' => '/timeline',
                'controller' => 'AppController',
                'action' => 'timeline'
            );

            $routes['tweet'] = array(
                'route' => '/tweet',
                'controller' => 'AppController',
                'action' => 'tweet'
            );
            
            $routes['removerTweet'] = array(
                'route' => '/removerTweet',
                'controller' => 'AppController',
                'action' => 'removerTweet'
            );

            $routes['quem_seguir'] = array(
                'route' => '/quem_seguir',
                'controller' => 'AppController',
                'action' => 'quem_seguir'
            );
            
            $routes['acao'] = array(
                'route' => '/acao',
                'controller' => 'AppController',
                'action' => 'acao'
            ); // Rotas do AppController <-
             


            $this->setRoutes($routes);
        }
    }
?>