<?php
    namespace MVC;

class Router
{
    public array $getRoutes = [];
    public array $postRoutes = [];

    public function get($url, $fn)
    {
        $this->getRoutes[$url] = $fn;
    }

    public function post($url, $fn)
    {
        $this->postRoutes[$url] = $fn;
    }

    public function checkRoutes()
    {
        //Para que funcione bien en producción.
        //Separa el string $_SERVER['REQUEST_URI'] por el elemento '?'. Sólo toma la primera parte en $currentUrl para validar
        $currentUrl = strtok($_SERVER['REQUEST_URI'], '?') ?? '/';
        $method = $_SERVER['REQUEST_METHOD']; //Método GET o POST
        if ($method === 'GET') {
            $fn = $this->getRoutes[$currentUrl] ?? null;
        } else {
            $fn = $this->postRoutes[$currentUrl] ?? null;
        }
        if ( $fn ) {
            // Call user fn va a llamar una función cuando no sabemos cual será
            call_user_func($fn, $this); // This es para pasar argumentos
        } else {
            echo "Page not found or invalid path.";
        }
    }

    public function render($view, $data = [])
    {
        // Leer lo que le pasamos  a la vista
        foreach ($data as $key => $value) {
            $$key = $value; // Doble signo de dolar significa: variable variable,
                            // básicamente nuestra variable sigue siendo la original,
                            // pero al asignarla a otra no la reescribe, mantiene su valor,
                            // de esta forma el nombre de la variable se asigna dinamicamente
        }
        ob_start(); // Almacenamiento en memoria durante un momento...
        // Entonces incluimos la vista en el layout
        include_once __DIR__ . "/views/$view.php";
        $contents = ob_get_clean(); // Limpia el Buffer poniendo su contenido en la variable $contents que pasa al layout.php
        include_once __DIR__ . '/views/layout.php';
    }
}
?>