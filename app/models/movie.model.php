<?php

require_once './app/models/model.php';

class MovieModel extends Model{
  


    public function getAllMovies($sort = "id_pelicula", $order = "ASC"){

        //Campos permitidos por los que se puede filtrar
        $ordenesPermitidos = ["id_pelicula", "titulo", "sinopsis","director","año_lanzamiento","cast","plataforma_id","link_portada"];


        //Si el campo que llega por GET no esta permitido, usa el DEFAULT, el cual es id_pelicula
        if (!in_array($sort, $ordenesPermitidos)){
            $sort = "id_pelicula";
        }

        //Asegura que el orden (ASC o DESC, este siempre en mayusculas para evitar errores de tipeo)
        $order = strtoupper($order); 



        //Si el orden que llega por GET no es valido, setea ASC como default
        if ($order !== "ASC" && $order !== "DESC") {
            $order = "ASC"; 
        }

        $query = $this->db->prepare("SELECT * FROM peliculas ORDER BY $sort $order"); //Estos parametros no deja meterlos en el execute
        $query->execute([]);
        $movies = $query->fetchAll(PDO::FETCH_OBJ);

        return $movies;
    }
    public function getLinksMovies(){
        $query = $this->db->prepare("SELECT link_portada, id_pelicula FROM peliculas");
        $query->execute();
        $movies = $query->fetchAll(PDO::FETCH_OBJ);

        return $movies;
    }

    public function getAllMoviesFilter($sort = "id_pelicula", $order = "ASC",$filter, $value){

        

        //Para poder usar los % del LIKE %value% usamos la funcion CONCAT, de lo contrario los parametros
        //No se concatenan bien y SQL arroja una excepcion por error de sintaxis
        $query = $this->db->prepare("SELECT * FROM peliculas WHERE $filter LIKE CONCAT('%', ?, '%') ORDER BY $sort $order"); 
        $query->execute([$value]);
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

//Merge con branch filtros