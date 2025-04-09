<?php 
   namespace App\Models;

   use MF\Model\Model;

   class Tweet extends Model{
        private $id;
        private $id_user;
        private $tweet;
        private $data;
        private $hora;

        // geter
        public function __get($attr){ 
            return $this->$attr;
        }

        // seter
        public function __set($attr,$value){ 
            $this->$attr = $value;
        }

        //salvar tweet
        public function salvar(){
            $query = 'INSERT INTO tweets(id_user, tweet) VALUES(:id_user, :tweet)';
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_user', $this->__get('id_user'));
            $stmt->bindValue(':tweet', $this->__get('tweet'));
            $stmt->execute();
        }

        // recuperar tweets
        public function getAllTweets($limit, $offset){
            $query = "SELECT 
                        t.id, 
                        t.id_user, 
                        t.tweet,
                        DATE_FORMAT(t.data, '%d/%m/%Y %H:%i') AS data_formatada,
                        CONCAT(SUBSTRING_INDEX(u.nome, ' ', 1), ' ') AS nome
                       
                         FROM tweets t
                          INNER JOIN usuarios u ON t.id_user = u.id
                           WHERE t.id_user = :id_user 
                            OR t.id_user IN (
                              SELECT id_usuario_seguindo 
                               FROM usuarios_seguidores 
                                WHERE id_usuario = :id_user
                             )
                            ORDER BY t.data DESC
                            LIMIT $limit OFFSET $offset";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_user', $this->__get('id_user'));
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        // count registros de tweets
        public function countRegistros(){
            $query = "SELECT COUNT(*) AS total_registros FROM tweets";
            $stmt = $this->db->prepare($query);

            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $result['total_registros'];
        }

        //remover tweet
        public function removerTweet(){
            $query = 'DELETE FROM tweets WHERE id = :id AND id_user = :id_user';
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $this->__get('id'));
            $stmt->bindValue(':id_user', $this->__get('id_user'));
            $stmt->execute();
        }

   }

?>