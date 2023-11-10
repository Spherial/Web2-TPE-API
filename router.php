<?php
    require_once 'config.php';
    require_once 'libs/router.php';

    require_once './app/controllers/movie.api.controller.php';
    


    
   

    $router = new Router();

    #                 endpoint      verbo     controller           método
    $router->addRoute('peliculas',     'GET',    'MovieApiController', 'get'   ); # ControllerDePeliculas->get($params)
    $router->addRoute('peliculas/:ID', 'GET',    'MovieApiController', 'get'   );
    $router->addRoute('peliculas/:ID', 'PUT',    'NombreDelController', 'metodoAUsar');
    $router->addRoute('peliculas',     'POST',   'NombreDelController', 'metodoAUsar');
    $router->addRoute('peliculas/:ID', 'DELETE', 'NombreDelController', 'metodoAUsar');
    
    $router->addRoute('user/token', 'GET',    'UserApiController', 'getToken'   ); # UserApiController->getToken()
    $router->addRoute('peliculas/:ID/:subrecurso', 'GET',    'MovieApiController', 'getSubRecurso'   );
    #               del htaccess resource=(), verbo con el que llamo GET/POST/PUT/etc
    $router->route($_GET['resource']        , $_SERVER['REQUEST_METHOD']);