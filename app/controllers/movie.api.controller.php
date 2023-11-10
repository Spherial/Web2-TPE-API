<?php

require_once './app/controllers/api.controller.php';
require_once './app/models/movie.model.php';

 class MovieApiController extends ApiController{
    private $model;

    function __construct() {
        parent::__construct();
        $this->model = new MovieModel();
    }





    function get($params = []) {
        
        
        if (empty($params)){    //Si no hay parametros, traigo todas las peliculas
            $movies = $this->model->getAllMovies();
            $this->view->response($movies, 200);
        } else {
            $movie = $this->model->getMovieById($params[':ID']);  //Sino, traigo solo la pelicula cuyo ID solicitaron
            
            if (empty($movie)){
                $this->view->response('La pelicula con el id='.$params[':ID'].' no existe.'  , 404);
            }
            else{
                $this->view->response($movie, 200); // Envio la pelicula (Sin subrecurso)
            }
        }
        }
                
            
    
    

    function getSubRecurso($params = []){
        if (empty($params)){    //Si no hay parametros, traigo todas las peliculas
            $movies = $this->model->getAllMovies();
            $this->view->response($movies, 200);
        } else {
            $movie = $this->model->getMovieById($params[':ID']);  //Sino, traigo solo la tarea cuyo ID solicitaron
            if(!empty($movie)) { //Si dicha tarea existe
                if(isset($params[':subrecurso'])) {                //Pregunto si quiere obtener un dato en particular
                    switch ($params[':subrecurso']) {
                        case 'titulo':
                            $this->view->response($movie->titulo, 200);
                            break;
                        case 'sinopsis':
                            $this->view->response($movie->sinopsis, 200);
                            break;

                        case 'director':
                            $this->view->response($movie->director, 200);
                            break;
                            
                        default:
                        $this->view->response('La pelicula no contiene '.$params[':subrecurso'].'.'
                            , 404);
                            break;
                    }
                } else
                    $this->view->response($movie, 200); // Envio la pelicula (Sin subrecurso)
            } else {
                $this->view->response('La pelicula con el id='.$params[':ID'].' no existe.'  , 404);
            }
        }
    }


    function create($params = []){

        $body = $this->getData();



        $titulo = $body->titulo;
        $sinopsis = $body->sinopsis;
        $director = $body->director;
        $fecha = $body->año_lanzamiento;
        $cast = $body->cast;
        $plataforma = $body->plataforma_id;
        $link_portada = $body->link_portada;


        //Corregir esto
        if (empty($titulo) || empty($sinopsis) || empty($director) || empty($fecha)
        || empty($cast) || empty($plataforma) || empty($link_portada)) {
            $this->view->response("Complete los datos", 400);
        } else {
            $id = $this->model->POSTmovie($titulo,$sinopsis,$director,$fecha,$cast,$plataforma,$link_portada);

            // Devuelvo la pelicula creada
            $pelicula = $this->model->getMovieById($id);
            $this->view->response($pelicula, 201);
        }


    }

    public function update ($params = []){
        $id = $params[':ID'];
        $pelicula = $this->model->getMovieById($id);


        //Compruebo que la pelicula que estoy queriendo editar exista
        if($pelicula) {
            $body = $this->getData();    //Me traigo los datos nuevos

            //Datos nuevos
            $titulo = $body->titulo;
            $sinopsis = $body->sinopsis;
            $director = $body->director;
            $fecha = $body->año_lanzamiento;
            $cast = $body->cast;
            $plataforma = $body->plataforma_id;
            $link_portada = $body->link_portada;

            if (empty($titulo) || empty($sinopsis) || empty($director) || empty($fecha)
            || empty($cast) || empty($plataforma) || empty($link_portada)){
                $this->view->response("Complete los datos", 400);
            }
            else{
                 //Llamo a la funcion del update
                $this->model->PUTmovie($id,$titulo,$sinopsis,$director,$fecha,$cast,$plataforma,$link_portada);

                $this->view->response('La pelicula con id='.$id.' ha sido modificada.', 200);
            }
            

           
        } else {
            $this->view->response('La pelicula con id='.$id.' no existe.', 404);
        }

    }


 }
