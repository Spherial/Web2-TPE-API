# Web2-TPE-API

Integrantes: Aceto Simón Santiago, Andrés Lautaro Acosta

Trabajo Práctico Especial Obligatorio. Tercera Entrega.

Tema: API RESTful







Api Peliculas

Esta api permite obtener información de las peliculas y series almacenadas en la DB.
También brinda la opcion de agregar nuevas peliculas, eliminar las ya existentes y crear nuevas.


---------------------------------------------------------------

ENDPOINTS


/api/peliculas

Comandos permitidos
-GET
-POST


/api/peliculas  [GET]

Este endpoint trae un arreglo de objetos Json conteniendo todas y cada una de las peliculas presentes en la 
base de datos.

Cada uno de los objetos posee la siguiente estructura:

{
"id_pelicula": ,
"titulo": ,
"sinopsis": ,
"director": ,
"año_lanzamiento": ,
"cast": ,
"plataforma_id": ,
"link_portada": 
}




PARAMETROS OPCIONALES:


---------------------------------------------------------------


//COMO AGREGAR PARAMETROS
Para agregar parametros, luego de escribir el ENDPOINT, se ingresa un signo de interrogación, indicando que la siguiente información ingresada serán parámetros.
Los parámetros se escriben de la siguiente manera:

nombreParametro=valorParametro

Para agregar múltiples parametros, se separan con un &

Por ejemplo:

/api/endpoint?parametro1=valor_del_parametro1&parametro2=valor_del_parametro_2


---------------------------------------------------------------


-sort ( /api/peliculas?sort=CAMPO )

El parametro sort nos permite especificar un atributo y los objetos se ordenarán segun este.
-Si no es especificado o no existe, se ordenan por defecto segun "id_pelicula".




-order (/api/peliculas?order=ORDEN)

Elige si los elementos se ordenan de manera ascendente (ASC) o descendente (DESC)
-Por defecto, se ordenan ascendentemente
-Si no es especificado o no existe, se ordenan por defecto como (ASC)
-El campo que se tiene en cuenta a la hora de ordenar es el indicado por sort,
por lo que si no es especificado, toma "id_pelicula" como el campo por defecto.



-filter y value  ( /api/peliculas?filter=CAMPO&value=VALOR)
Estos parametros nos permiten filtrar los resultados segun determinados campos.
filter representa el campo por el cual queremos filtrar, y value representa
el valor que queremos buscar. 
Por ejemplo, la siguiente url

/api/peliculas?filter=titulo&value=aventura

Traeria todas las peliculas cuyo titulo contenga la palabra "aventura"
(Por defecto, ordenadas por id de forma ascendente)


ACLARACION: Para filtrar, ambos filter y value deben estar presentes en la query, de lo contrario no se tomarán en cuenta a la hora de obtener los resultados.






Los anteriores parametros se pueden combinar de multiples formas para lograr una consulta mas especifica, por ejemplo:


/api/peliculas?sort=año_lanzamiento&order=desc&filter=director&value=vince

Esta consulta nos traeria todas las peliculas cuyo director sea Vince, ordenadas por fecha de lanzamiento
de manera descendente.





---------------------------------------------------------------

/api/peliculas  [POST]


-REQUERE TOKEN BEARER



Permite agregar una nueva pelicula. Al body se le debe brindar un objeto Json con la siguiente estructura:

{
"titulo": ,
"sinopsis": ,
"director": ,
"año_lanzamiento": ,
"cast": ,
"plataforma_id": ,
"link_portada": 
}


Como medida de autenticacion, se debe proporcionar un token bearer, caso contrario la peticion es negada.

Devuelve la pelicula creada.


---------------------------------------------------------------

/api/peliculas/:ID
Comandos permitidos -GET -PUT - DELETE

/api/peliculas/:ID [GET]

:ID ---------> numero entero correspondiente a la id de la pelicula.

Nos permite obtener una pelicula determinada dada su id.

Por ejemplo:
/api/peliculas/15

Devuelve la pelicula cuyo ID es 15.

Este endpoint siempre devuelve un unico objeto Json.


---------------------------------------------------------------

/api/peliculas/:ID/:subrecurso [GET]

:ID ---------> numero entero correspondiente a la id de la pelicula.
:subrecurso ---------> El campo que queremos obtener

Permite obtener un campo en particular de la pelicula, por ejemplo su titulo o su sinopsis.

/api/peliculas/15/sinopsis

Devuelve un String cuyo contenido es la sinopsis de la pelicula con id = 15.



---------------------------------------------------------------


/api/peliculas/:ID [PUT]

-REQUERE TOKEN BEARER

Nos permite modificar una pelicula. En el body se debe brindar un objeto Json con la siguiente estructura:

{
"titulo": ,
"sinopsis": ,
"director": ,
"año_lanzamiento": ,
"cast": ,
"plataforma_id": ,
"link_portada": 
}

Como medida de autenticacion, se debe proporcionar un token bearer, caso contrario la peticion es negada.

Devuelve la id de la pelicula que ha sido editada.



---------------------------------------------------------------

/api/peliculas/:ID [DELETE]

-REQUERE TOKEN BEARER

Nos permite eliminar una pelicula.

Ejemplo:

/api/peliculas/15 (DELETE)

Elimina la pelicula cuya ID sea 15.

Como medida de autenticacion, se debe proporcionar un token bearer, caso contrario la peticion es negada.

Devuelve la id de la pelicula que ha sido eliminada.





---------------------------------------------------------------

/api/user/token [GET]

Permite obtener un token bearer, dando la posibilidad al usuario de crear, modificar, y eliminar información.

Previo a obtener el token, el usuario debe ser aceptado por una autenticacion tipo Basic (Un formulario, por ejemplo).
En este caso, el usuario de ejemplo es el siguiente:

usuario: webadmin
contraseña: admin

Si el usuario falla en autenticarse, no recibe el token.
(Se puede probar en POSTMAN, en la pestaña Authorization, eligiendo Basic Auth como método de autenticación, y posteriormente ingresando los datos)


El token proporcionado tiene una duración de 1 hora. Pasado ese tiempo se debe solicitar uno nuevo.

Es obligatorio incluir este token cada vez que se realice una request de tipo POST, PUT y DELETE.