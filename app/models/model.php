<?php
require_once './config.php';
    class Model{

        
        protected $db;

        //Asignar su correspondiente PDO a cada model hijo
        public function __construct(){
            $this->db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASSWORD);
            $this->deploy();
        }



        //Pregunta si la base de datos "streaming_peliculas" tiene tablas, de no tenerlas las crea
        // e inserta el contenido
        function deploy() {
            // Chequear si hay tablas
            $query = $this->db->query('SHOW TABLES');
            $tables = $query->fetchAll(); // Nos devuelve todas las tablas de la db
            if (count($tables) == 0) {
                // Si no hay, crearlas
        
                $sql = <<<SQL
                --
                -- Estructura de tabla para la tabla `peliculas`
                --
                
                CREATE TABLE `peliculas` (
                  `id_pelicula` int(11) NOT NULL,
                  `titulo` varchar(255) NOT NULL,
                  `sinopsis` varchar(500) NOT NULL,
                  `director` varchar(255) DEFAULT NULL,
                  `año_lanzamiento` date DEFAULT NULL,
                  `cast` varchar(500) NOT NULL,
                  `plataforma_id` int(11) DEFAULT NULL,
                  `link_portada` varchar(500) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
        
                --
                -- Volcado de datos para la tabla `peliculas`
                --
        
                INSERT INTO `peliculas` (`id_pelicula`, `titulo`, `sinopsis`, `director`, `año_lanzamiento`, `cast`, `plataforma_id`, `link_portada`) VALUES
                (9, 'Better Call Saul', 'Ambientada en 2002, seis años antes de los acontecimientos relatados en Breaking Bad, Better Call Saul es un spin-off centrado en el personaje de James "Jimmy" McGill (Bob Odenkirk), antes de que asuma la identidad de Saul Goodman, un abogado corrupto con un humor políticamente incorrecto vinculado al mundo criminal que empieza a crear una importante red de contactos en los bajos mundos. La serie narra los acontecimientos que llevan a McGill a convertirse en Saul antes de trabajar con', 'Vince Gilligan', '2015-02-08', 'Bob Odenkirk, Sea Reehorn, etc', 2, 'https://www.aceprensa.com/wp-content/uploads/2015/10/60059-1.jpg'),
                (14, 'Fionna & Cake', 'Fionna y Cake, con la ayuda del antiguo Rey Helado, Simon Petrikov, se embarcan en una aventura de salto multiversal y en un viaje de autodescubrimiento. Mientras tanto, un nuevo y poderoso antagonista, decidido a seguirles la pista y borrarlos de la existencia, acecha en las sombras.', 'Adam Muto', '2023-08-31', 'Cast Fionna', 3, 'https://images.justwatch.com/poster/307356966/s332/temporada-1'),
                (15, 'Breaking Bad', 'Breaking Bad sigue la transformación de Walter White, un profesor de química, en un narcotraficante mientras enfrenta su diagnóstico de cáncer terminal. La serie explora la decadencia moral y las consecuencias de sus decisiones.', 'Vince Gilligan', '2008-01-20', 'Cast BB', 2, 'https://es.web.img3.acsta.net/pictures/18/04/04/22/52/3191575.jpg'),
                (18, 'Cars 2', 'Sinopsis de Cars 2', 'John Lasseter 2', '2023-10-07', 'CAST CARS', 5, 'https://es.web.img2.acsta.net/medias/nmedia/18/82/02/41/19753255.jpg'),
                (21, 'Harry Potter y la Piedra Filosofal', 'El día de su cumpleaños, Harry Potter descubre que es hijo de dos conocidos hechiceros, de los que ha heredado poderes mágicos. Debe asistir a una famosa escuela de magia y hechicería, donde entabla una amistad con dos jóvenes que se convertirán en sus compañeros de aventura.', 'Chris Columbus', '2001-11-29', 'insertar', 4, 'https://es.web.img2.acsta.net/pictures/14/04/30/11/55/592219.jpg'),
                (22, 'Titanic', 'Jack es un joven artista que gana un pasaje para viajar a América en el Titanic, el transatlántico más grande y seguro jamás construido. A bordo del buque conoce a Rose, una chica de clase alta que viaja con su madre y su prometido Cal, un millonario engreído a quien solo le interesa el prestigio de la familia de su prometida. Jack y Rose se enamoran a pesar de las trabas que ponen la madre de ella y Cal en su relación. Mientras, el lujoso transatlántico se acerca a un inmenso iceberg.', 'James Cameron', '1998-02-05', 'dsufghuisdfhgiudsfhguisdhuidfsg', 3, 'https://i.pinimg.com/1200x/75/1f/54/751f5482facac19cff49ca5e0a0861cf.jpg'),
                (23, 'El Padrino', '"El Padrino" es un clásico del cine que sigue la vida de Don Vito Corleone, el patriarca de una familia de la mafia, y su hijo Michael, mientras enfrentan desafíos en el mundo del crimen organizado, la política y la traición.', 'Francis Ford Coppola', '1972-03-15', 'Marlon Brando como Don Vito Corleone. Al Pacino como Michael Corleone. James Caan como Santino', 4, 'https://r3.abcimg.es/resizer/resizer.php?imagen=https%3A%2F%2Fs3.abcstatics.com%2Fmedia%2Fpeliculas%2F000%2F001%2F606%2Fel-padrino-1.jpg&amp;nuevoancho=683&amp;medio=abc');
        
                --
                -- Estructura de tabla para la tabla `plataformas`
                --
        
                CREATE TABLE `plataformas` (
                  `id_plataforma` int(11) NOT NULL,
                  `nombre` varchar(255) NOT NULL,
                  `enlace` varchar(255) NOT NULL,
                  `tipo_contenido` varchar(255) DEFAULT NULL,
                  `disponibilidad_ar` tinyint(1) NOT NULL,
                  `precio` double NOT NULL,
                  `link_logo` varchar(500) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
        
                --
                -- Volcado de datos para la tabla `plataformas`
                --
        
                INSERT INTO `plataformas` (`id_plataforma`, `nombre`, `enlace`, `tipo_contenido`, `disponibilidad_ar`, `precio`, `link_logo`) VALUES
                (2, 'Netflix', 'https://www.netflix.com/ar/', 'Series, Películas, Documentales', 1, 5000, 'https://images.ctfassets.net/y2ske730sjqp/6bhPChRFLRxc17sR8jgKbe/6fa1c6e6f37acdc97ff635cf16ba6fb3/Logos-Readability-Netflix-logo.png'),
                (3, 'HBO Max', 'https://www.hbomax.com/', 'Series, Películas, Documentales', 1, 4000, 'https://hbomax-images.warnermediacdn.com/2020-05/square%20social%20logo%20400%20x%20400_0.png'),
                (4, 'Amazon Prime Video', 'https://www.primevideo.com', 'Series, Películas, Documentales', 1, 4500, 'https://static.vecteezy.com/system/resources/previews/019/040/290/original/amazon-prime-video-logo-editorial-free-vector.jpg'),
                (5, 'Disney +', 'https://www.disneyplus.com/es-ar', 'Familiar', 1, 4000, 'https://4.bp.blogspot.com/-wVqCH-lLaCI/W-VfgKBApGI/AAAAAAAAQ_Q/_boURyzLyjMw3B3DJMiayzyuAxVtg0byQCLcBGAs/s1600/disney%252B.jpg'),
                (8, 'Star+', 'https://www.starplus.com', 'Series, Películas, Documentales y Deporte', 1, 1749, 'https://static-assets.bamgrid.com/product/starplus/images/share-default.d72cf588f6d06cba22171f5ae44289d3.png');
        
                -- Estructura de tabla para la tabla `usuarios`
                CREATE TABLE `usuarios` (
                  `id_usuario` int(11) NOT NULL,
                  `username` varchar(200) NOT NULL,
                  `password` varchar(250) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
        
                -- Volcado de datos para la tabla `usuarios`
                INSERT INTO `usuarios` (`id_usuario`, `username`, `password`) VALUES
                (1, 'webadmin', '$2y$10\$sIAxMnXh9.08IV3ZHfiMaODX//GYaxNiyFXrJxrz8PphHK.Mx1dyy');
        
                -- Índices para tablas volcadas
                -- Índices de la tabla `peliculas`
                ALTER TABLE `peliculas`
                  ADD PRIMARY KEY (`id_pelicula`),
                  ADD KEY `plataforma_id` (`plataforma_id`);
        
                -- Índices de la tabla `plataformas`
                ALTER TABLE `plataformas`
                  ADD PRIMARY KEY (`id_plataforma`);
        
                -- Índices de la tabla `usuarios`
                ALTER TABLE `usuarios`
                  ADD PRIMARY KEY (`id_usuario`);
        
                -- AUTO_INCREMENT de las tablas volcadas
                -- AUTO_INCREMENT de la tabla `peliculas`
                ALTER TABLE `peliculas`
                  MODIFY `id_pelicula` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
        
                -- AUTO_INCREMENT de la tabla `plataformas`
                ALTER TABLE `plataformas`
                  MODIFY `id_plataforma` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
        
                -- AUTO_INCREMENT de la tabla `usuarios`
                ALTER TABLE `usuarios`
                  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
        
                -- Restricciones para tablas volcadas
                -- Filtros para la tabla `peliculas`
                ALTER TABLE `peliculas`
                  ADD CONSTRAINT `peliculas_ibfk_1` FOREIGN KEY (`plataforma_id`) REFERENCES `plataformas` (`id_plataforma`);
                  COMMIT;
            SQL;
        
            $this->db->exec($sql);
        }


    }

}