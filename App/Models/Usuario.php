<?php
    namespace App\Models;

    use MF\Model\Model;

    class Usuario extends Model{
        private $id;
        private $nome;
        private $email;
        private $senha;

        public function __get($atributo){
            return $this->$atributo;
        }

        public function __set($atributo, $valor){
            $this->$atributo = $valor;
        }

        // salvar sera chamado no -> IndexController
        public function salvar(){
            $query = 'INSERT INTO usuarios(nome, email, senha) 
                            VALUES(:nome, :email, :senha)';

            $stmt = $this->db->prepare($query);

            $stmt->bindValue(':nome', $this->__get('nome'));
            $stmt->bindValue(':email', $this->__get('email'));
            $stmt->bindValue(':senha', md5($this->__get('senha'))); // md5() -> hash 32 caracteres

            $stmt->execute();

            return $this;//true;
        }

        // validar cadastro antes de salvar
        // validarCadastro sera chamado no -> IndexController
        public function validarCadastro(){
            $valido = true;

            if(strlen($this->__get('nome')) < 4){
                $valido = false;
            }

            if(strlen($this->__get('email')) < 11){
                $valido = false;
            }

            if(strlen($this->__get('senha')) < 5){
                $valido = false;
            }

            return $valido;
        }
        
        // recuperar um usuario por email para verificar se ja existe
        // getUsuarioPorEmail sera chamado no -> IndexController
        public function getUsuarioPorEmail(){
            $query = 'SELECT nome, email FROM usuarios WHERE email = :email';

            $stmt = $this->db->prepare($query);

            $stmt->bindValue(':email', $this->__get('email'));

            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        // autenticar o usuario
        // autenticarUser sera chamado no -> AuthController
        public function autenticarUser(){
            $query = 'SELECT id, nome, email FROM usuarios WHERE email = :email AND senha = :senha';

            $stmt = $this->db->prepare($query);

            $stmt->bindValue(':email', $this->__get('email'));
            $stmt->bindValue(':senha', md5($this->__get('senha')));

            $stmt->execute();

            $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

            if(!empty($usuario['id']) && !empty($usuario['nome']) && !empty($usuario['email'])) {
                // definir os dados do usuario logado na sessao
                $this->__set('id', $usuario['id']);
                $this->__set('nome', $usuario['nome']);
            }

            return $this;
        }

        // listar usuarios para seguir
        // getAll sera chamado no -> AppController
        public function getAll(){
            $query = "SELECT u.id, u.nome, u.email,
                      (
                        SELECT COUNT(*)
                         FROM usuarios_seguidores as us
                            WHERE us.id_usuario = :id_usuario AND us.id_usuario_seguindo = u.id
                      ) as seguindo_sn
                      FROM usuarios as u
                      WHERE u.nome like :nome and u.id != :id_usuario";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':nome', '%'.$this->__get('nome').'%');
            $stmt->bindValue(':id_usuario', $this->__get('id'));
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } 

        // Seguir Usuario
        // seguirUsuario sera chamado no -> AppController
        public function seguirUsuario($id_usuario_seguindo){
            $query = 'INSERT INTO usuarios_seguidores(id_usuario, id_usuario_seguindo) 
                            VALUES(:id_usuario, :id_usuario_seguindo)';

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id'));
            $stmt->bindValue(':id_usuario_seguindo', $id_usuario_seguindo);

            $stmt->execute();

            return true;
        }

        // deixar Seguir Usuario
        // deixarSeguirUsuario sera chamado no -> AppController
        public function deixarSeguirUsuario($id_usuario_seguindo){
            $query = 'DELETE FROM usuarios_seguidores 
                            WHERE id_usuario = :id_usuario AND id_usuario_seguindo = :id_usuario_seguindo';

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id'));
            $stmt->bindValue(':id_usuario_seguindo', $id_usuario_seguindo);

            $stmt->execute();

            return true;
        }

        // retorna o total de tweets feita pelo usuario logado
        // getTotalTweets sera chamado no -> AppController
        public function getTotalTweets(){
            $query = "SELECT COUNT(*) AS total_tweets
                      FROM tweets AS t
                      WHERE t.id_user = :id_user";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_user', $this->__get('id'));
            $stmt->execute();

            $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $resultado['total_tweets'];
        }
            
        // retorna o total de seguidores do usuario logado
        // getTotalSeguidores sera chamado no -> AppController
        public function getTotalSeguidores(){
            $query = "SELECT COUNT(*) AS total_seguidores
                      FROM usuarios_seguidores
                      WHERE id_usuario_seguindo = :id_usuario";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id'));
            $stmt->execute();

            $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $resultado['total_seguidores'];
        }


        // retorna o total de usuarios que o usuario logado esta seguindo
        // getTotalSeguindo sera chamado no -> AppController
        public function getTotalSeguindo(){
            $query = "SELECT COUNT(*) AS total_seguindo
                      FROM usuarios_seguidores
                      WHERE id_usuario = :id_usuario";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id'));
            $stmt->execute();

            $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $resultado['total_seguindo'];
        }
    }
?>