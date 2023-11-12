<?php
    require_once './app/models/model.php';

class UserModel extends Model {
    public function getUserByUsername($username) {
        $query = $this->db->prepare('SELECT * FROM usuarios WHERE username = ?');
        $query->execute([$username]);

        return $query->fetch(PDO::FETCH_OBJ);
    }
}