<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paginación</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        h1 {
            text-align: center;
            color: navy;
        }

        table {
            width: 80%;
            margin: 0 auto;
        }

        table thead tr td {
            padding: 0.5em 1em;
            text-align: center;
            background-color: navy;
            color: white;
            font-size: 18px;
            font-weight: bold;
        }

        table tbody tr td {
            padding: 0.25em 0.5em;
        }

        table tbody tr:nth-child(odd) {
            background-color: aquamarine;

        }

        #enlaces {
            width: 50%;
            margin: 0 auto;
            text-align: center;
        }

        .enlaces {
            display: inline-block;
            text-align: center;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            padding: 0.2em 0.5em;
            color: lightgray;

        }

        #enlaces a:hover {
            transform: scale(1.4);
            color: navy;
        }

        .disabled {
            display: inline-block;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            padding: 0.2em 0.5em;

        }

        .seleccionado {
            background-color: aquamarine;
            color: navy;
            font-weight: bold;
            transform: scale(1.4);
            border-radius: 50%;
        }
    </style>


</head>

<body>
    <h1>PAGINACION</h1>
    <table>
        <thead>
            <tr>
                <td>ID</td>
                <td>SECCION</td>
                <td>NOMBRE ARTÍCULO</td>
                <td>FECHA</td>
                <td>PAÍS DE ORIGEN</td>
                <td>PRECIO</td>
            </tr>
        </thead>
        <tbody>


            <?php



            try {
                $conexion = new PDO("mysql:host=localhost; dbname=pruebas", "root", "iusenma");
                $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $conexion->exec("SET CHARACTER SET utf8");
                $div_paginas = 3;
                $consulta = "SELECT * FROM artículos WHERE SECCIÓN='DEPORTE'";
                $resultados = $conexion->prepare($consulta);
                $resultados->execute();
                $num_filas = $resultados->rowCount();

                $total_paginas = ceil($num_filas / $div_paginas);

                if (isset($_GET["pag"])) {
                    $pagina = $_GET["pag"];
                    $empezar_desde = ($pagina - 1) * $div_paginas;
                } else {
                    $pagina = 1;
                    $empezar_desde = 0;
                }

                $consulta_limite = "SELECT * FROM artículos LIMIT $empezar_desde,$div_paginas";
                $resultados_lim = $conexion->prepare($consulta_limite);
                $resultados_lim->execute();


                foreach ($resultados_lim as $val) {
                    echo "<tr><td>";
                    echo $val["CÓDIGO"] . "</td><td>";
                    echo $val["SECCIÓN"] . "</td><td>";
                    echo $val["NOMBRE ARTÍCULO"] . "</td><td>";
                    echo $val["FECHA"] . "</td><td>";
                    echo $val["PAÍS DE ORIGEN"] . "</td><td>";
                    echo $val["PRECIO"] . "</td></tr>";
                }

                $resultados->closeCursor();
            } catch (Exception $e) {
                die("Se ha producido un error: " . $e->getMessage() . " Linea de error: " . $e->getLine());
            } finally {
                $conexion = null;
            }


            ?>
        </tbody>
    </table>
    <div id="enlaces">

        <?php if ($pagina == 1) { ?>
            <p class="disabled">
                <<</p>
                <?php } else { ?>
                    <a class="enlaces" href="paginacion_completa.php?pag=<?php echo $pagina - 1; ?>">
                        <<</a>
                        <?php } ?>


                        <?php
                        for ($i = 1; $i <= $total_paginas; $i++) :
                        ?>
                            <?php
                                if ($pagina == $i) {

                                ?>
                                    <p class="disabled seleccionado"><?php echo $i ?></p>
                                <?php
                                } else {
                                ?>

                                    <a href="paginacion_completa.php?pag=<?php echo $i; ?>" class="enlaces"><?php echo $i ?> </a>
                                <?php
                                }
                                ?>

                        <?php
                        endfor;
                        ?>

                        <?php if ($pagina == $total_paginas) { ?>
                            <p class="disabled">>></p>
                        <?php } else { ?>
                            <a class="enlaces" href="paginacion_completa.php?pag=<?php echo $pagina + 1; ?>">>></a>
                        <?php } ?>
    </div>

</body>

</html>