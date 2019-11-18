## About this aplication

Comando: `php artisan play:rps`

La aplicación busca en la carpeta game el nombre de los archivos y te pregunta a cual quieres jugar, le dices el nombre y te explica de que va el juego (esta explicación está en el key "Info" del json.)
(Si no encontrase ningún juego devolvería una excepción NoGamesFoundException)

Una vez confirmado el juego se va al método play donde se realizan 100 partidas del juego en cuestión y guarda el resultado en un array.

Con este array de resultados se crea un csv que se almacena en la carpeta storage/app/game_reports.csv y se pinta una tabla output en la consola.

La aplicación está pensada para que se puedan añadir más juegos (repetando el formato json) Estos JSON se añaden en storage/games y ya la aplicación los reconoce.

La he creado de esta forma ya que sería muy facil, por ejemplo, crear una funcionalidad para directamente desde un comando o un formulario crear estos json (de hecho he dejado getters y setters en la clase Game sin usar solo para ilustrar esto)
