<?php
    require_once 'config.php';
    require_once 'libs/router.php';

    
   

    $router = new Router();

    #                 endpoint      verbo     controller           método
    $router->addRoute('peliculas',     'GET',    'NombreDelControler', 'metodoAUsar'   ); # ControllerDePeliculas->get($params)
    $router->addRoute('peliculas',     'POST',   'NombreDelController', 'metodoAUsar');
    $router->addRoute('peliculas/:ID', 'GET',    'NombreDelController', 'metodoAUsar'   );
    $router->addRoute('peliculas/:ID', 'PUT',    'NombreDelController', 'metodoAUsar');
    $router->addRoute('peliculas/:ID', 'DELETE', 'NombreDelController', 'metodoAUsar');
    
    $router->addRoute('user/token', 'GET',    'UserApiController', 'getToken'   ); # UserApiController->getToken()
    
    #               del htaccess resource=(), verbo con el que llamo GET/POST/PUT/etc
    $router->route($_GET['resource']        , $_SERVER['REQUEST_METHOD']);
