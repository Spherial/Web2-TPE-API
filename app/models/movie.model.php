<?php

require_once './app/models/model.php';

class MovieModel extends Model{
  


    public function getAllMovies(){
        $query = $this->db->prepare("SELECT * FROM peliculas");
        $query->execute();
        $movies = $query->fetchAll(PDO::FETCH_OBJ);

        return $movies;
    }
    public function getLinksMovies(){
        $query = $this->db->prepare("SELECT link_portada, id_pelicula FROM peliculas");
        $query->execute();
        $movies = $query->fetchAll(PDO::FETCH_OBJ);

        return $movies;
    }



    //Obtiene una pelicula segun una ID
    public function getMovieById($id){
        $query = $this->db->prepare("SELECT * FROM peliculas WHERE id_pelicula = ?");
        $query->execute([$id]);
        $movie = $query->fetch(PDO::FETCH_OBJ);

        return $movie;
    }


    //Obtiene la informacion de determinada pelicula, incluyendo su plataforma
    public function getMovieDetail($movie_id){
        $query = $this->db->prepare("SELECT a.*, b.nombre FROM peliculas a INNER JOIN plataformas b ON a.plataforma_id = b.id_plataforma WHERE id_pelicula = ?");
        $query->execute([$movie_id]);
        $details = $query->fetch(PDO::FETCH_OBJ);
        return $details;
    }



    public function POSTmovie($titulo,$sinopsis,$director,$fecha,$cast,$plataforma,$link_portada){
        $query = $this->db->prepare("INSERT INTO `peliculas`(`titulo`, `sinopsis`, `director`, `año_lanzamiento`, `cast`, `plataforma_id`,`link_portada`) VALUES (?,?,?,?,?,?,?)");
        $query->execute([$titulo,$sinopsis,$director,$fecha,$cast,$plataforma,$link_portada]);
        return $this->db->lastInsertId();
    }

    public function PUTmovie($id,$titulo,$sinopsis,$director,$fecha,$cast,$plataforma,$link_portada){
        $query = $this->db->prepare("UPDATE peliculas SET `titulo`= ?,`sinopsis`= ?,`director`= ? ,`año_lanzamiento`= ? ,`cast`= ? ,`plataforma_id`= ?,`link_portada`= ? WHERE id_pelicula = ?");
        $query->execute([$titulo,$sinopsis,$director,$fecha,$cast,$plataforma,$link_portada,$id]);
        return $query->rowCount();
    }

    public function DELETEmovie($id){
        $query = $this->db->prepare("DELETE FROM peliculas WHERE id_pelicula = ? ");
        $query->execute([$id]);
        return $query->rowCount();
    }
}