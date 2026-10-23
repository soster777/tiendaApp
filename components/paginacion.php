<?php
// funciones para paginar las listas, con la misma idea del ejemplo de clase:
// se trae de la base solo un pedazo de los registros con LIMIT (cuantos) y OFFSET (desde cual)
//
// ejemplo con 9 productos por pagina:
//   pagina 1 -> LIMIT 9 OFFSET 0   (registros 1 a 9)
//   pagina 2 -> LIMIT 9 OFFSET 9   (registros 10 a 18)
//   pagina 3 -> LIMIT 9 OFFSET 18  (registros 19 a 27)

// devuelve el numero de pagina que vino por GET (?pagina=2). Si no vino o es invalido, es la 1
function pagina_actual()
{
    $pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
    if ($pagina < 1) {
        $pagina = 1;
    }
    return $pagina;
}

// cuantas paginas hacen falta para mostrar $total registros de a $por_pagina
// (ceil redondea para arriba: 30 productos de a 9 son 3,33 -> 4 paginas)
function total_paginas($total, $por_pagina)
{
    return intval(ceil($total / $por_pagina));
}

// imprime los botones de la paginacion con los estilos de Bootstrap.
// $url es la pagina con los parametros que ya tenia, terminada en ? o en &
// por ejemplo "inicio.php?" o "categoria.php?id=3&", y aca se le agrega pagina=N
function mostrar_paginacion($pagina, $total_paginas, $url)
{
    // si entra todo en una sola pagina no hace falta mostrar nada
    if ($total_paginas <= 1) {
        return;
    }

    $anterior = $pagina - 1;
    $siguiente = $pagina + 1;
    $clase_anterior = ($pagina > 1) ? "" : "disabled";
    $clase_siguiente = ($pagina < $total_paginas) ? "" : "disabled";

    print "<nav aria-label='Paginación' class='d-flex justify-content-center mt-2 mb-4'>";
    print "<ul class='pagination'>";
    print "<li class='page-item $clase_anterior'><a class='page-link' href='{$url}pagina=$anterior'>Anterior</a></li>";

    for ($i = 1; $i <= $total_paginas; $i++) {
        // la pagina en la que estoy queda marcada
        $activa = ($i == $pagina) ? "active" : "";
        print "<li class='page-item $activa'><a class='page-link' href='{$url}pagina=$i'>$i</a></li>";
    }

    print "<li class='page-item $clase_siguiente'><a class='page-link' href='{$url}pagina=$siguiente'>Siguiente</a></li>";
    print "</ul>";
    print "</nav>";
}
