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
            $movie = $this->model->getMovieById($params[':ID']);  //Sino, traigo solo la tarea cuyo ID solicitaron
            
            if (empty($movie)){
                $this->view->response('La tarea con el id='.$params[':ID'].' no existe.'  , 404);
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
                $this->view->response('La tarea con el id='.$params[':ID'].' no existe.'  , 404);
            }
        }
    }


 }
