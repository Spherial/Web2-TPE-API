<?php

require_once './app/controllers/api.controller.php';
require_once './app/models/movie.model.php';
require_once './app/helpers/auth.api.helper.php';
require_once './app/controllers/user.api.controller.php';

class MovieApiController extends ApiController{
    private $model;

    function __construct() {
        parent::__construct();
        $this->authHelper = new AuthHelper();
        $this->model = new MovieModel();
    }





    function get($params = []) {
        $sort = isset($_GET['sort']) ? $_GET['sort'] : '';
        $order = isset($_GET['order']) ? $_GET['order'] : '';
        $filter = isset($_GET['filter']) ? $_GET['filter'] : '';
        $value = isset($_GET['value']) ? $_GET['value'] : '';
        
        //var_dump($sort);
        //var_dump($order);
        //var_dump($filter);
        //var_dump($value);
        
        
        if (empty($params)){    //Si no hay parametros, traigo todas las peliculas

               //Campos permitidos por los que se puede ordenar
            $ordenesPermitidos = ["id_pelicula", "titulo", "sinopsis","director","año_lanzamiento","cast","plataforma_id","link_portada"];


            //Si el campo que llega por GET no esta permitido, usa el DEFAULT, el cual es id_pelicula
            if  (!in_array($sort, $ordenesPermitidos)){
                $sort = "id_pelicula";
            }

            //Asegura que el orden (ASC o DESC, este siempre en mayusculas para evitar errores de tipeo)
            $order = strtoupper($order); 



            //Si el orden que llega por GET no es valido, setea ASC como default
            if ($order !== "ASC" && $order !== "DESC") {
                $order = "ASC"; 
            }   


            if (empty($filter) || empty($value)){
                $movies = $this->model->getAllMovies($sort,$order);
                $this->view->response($movies, 200);
            }
            else{
                $movies = $this->model->getAllMoviesFilter($sort,$order,$filter,$value);
                $this->view->response($movies, 200);
            }
            


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
            $movie = $this->model->getMovieById($params[':ID']);  //Sino, traigo solo la pelicula cuyo ID solicitaron
            if(!empty($movie)) { //Si dicha pelicula existe
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
                        case 'año_lanzamiento':
                            $this->view->response($movie->año_lanzamiento, 200);
                            break;

                        case 'cast':
                            $this->view->response($movie->cast, 200);
                            break;

                         case 'plataforma_id':
                            $this->view->response($movie->plataforma_id, 200);
                            break;

                         case 'link_portada':
                            $this->view->response($movie->link_portada, 200);
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
        $user = $this->authHelper->currentUser();
        if(!$user) {
            $this->view->response('Unauthorized', 401);
            return;
        }
        $body = $this->getData();  //Obtengo la pelicula a crear (El RAW transformado a Json)



        //Obtengo sus atributos

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



    //Actualiza los datos de una pelicula (En el body recibe el JSON actualizado y cambia al original)
    public function update ($params = []){
        $user = $this->authHelper->currentUser();
        if(!$user) {
            $this->view->response('Unauthorized', 401);
            return;
        }
        $id = $params[':ID'];
        $pelicula = $this->model->getMovieById($id); //Obtengo la pelicula a editar


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






    //Borra una pelicula dada una ID
    function delete ($params = []){
        $user = $this->authHelper->currentUser();
        if(!$user) {
            $this->view->response('Unauthorized', 401);
            return;
        }
        $id = $params[':ID'];
        $pelicula = $this->model->getMovieById($id);

            if($pelicula) {
                $this->model->DELETEmovie($id);
                $this->view->response('La pelicula con id='.$id.' ha sido borrada.', 200);
            } else {
                $this->view->response('La pelicula con id='.$id.' no existe.', 404);
            }

    }

 }

 //Megeado ABM