<?php
    require_once 'config.php';
    require_once 'libs/router.php';

    require_once './app/controllers/movie.api.controller.php';

    require_once './app/controllers/user.api.controller.php';

    require_once './app/controllers/api.controller.php';

    require_once './app/helpers/auth.api.helper.php';
    



    $router = new Router();

    #                 endpoint      verbo     controller           método
    $router->addRoute('peliculas',     'GET',    'MovieApiController', 'get'   ); # ControllerDePeliculas->get($params)

    $router->addRoute('peliculas/:ID', 'GET',    'MovieApiController', 'get'   );
    
    $router->addRoute('peliculas/:ID/:subrecurso', 'GET',    'MovieApiController', 'getSubRecurso'   );
    
    $router->addRoute('peliculas',     'POST',   'MovieApiController', 'create');
    
    $router->addRoute('peliculas/:ID', 'PUT',    'MovieApiController', 'update');
    
    $router->addRoute('peliculas/:ID', 'DELETE', 'MovieApiController', 'delete');
    
    $router->addRoute('user/token', 'GET',    'UserApiController', 'getToken'   ); # UserApiController->getToken()
    #               del htaccess resource=(), verbo con el que llamo GET/POST/PUT/etc
    $resource = isset($_GET['resource']) ? $_GET['resource'] : '';
    $router->route($resource, $_SERVER['REQUEST_METHOD']);
