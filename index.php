<!-- CON DATOS DE USUARIO SIEMPRE EN METODO POST -->
<!-- porque con el get se muestra en la barra de navegacion -->

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>

  <style>
    body {
      box-sizing: border-box;
      background-color: rgb(242, 248, 254);
    }

    table {
      /* width: 70%; */
      margin: 0 auto;
      /* border:1px solid black; */
      border-collapse: collapse;
      text-align: center;
    }

    form {
      margin: 0 auto;
      padding: 0;
    }

    h1 {
      width: 20%;
      margin: 0 auto;
      text-align: center;
      margin-top: 1.5rem;
      margin-bottom: 1.5rem;
      font-size: 2.5rem;
    }

    h3 {
      text-align: center;
      margin-bottom: 2rem;
      color: gray;
    }

    thead {
      background-color: #1A4AF5;
      color: antiquewhite;
      font-size: 20px;
    }

    td {
      border: 1px solid black;
      padding: 5px 10px;
    }

    tbody tr:first-child td {
      padding: 5px 10px;
    }

    .boton {
      border: none;
      font-size: 18px;
    }

    .valor {
      padding: 5px 0;
      color: navy;
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
  <!-- Encabezado de la tabla del formulario -->
  <h1>CRUD</h1>
  <h3>Create Read Update Delete</h3>

  <table>
    <thead>
      <tr>
        <td>ID</td>
        <td>Nombre</td>
        <td>Primer Apellido</td>
        <td>Segundo Apellido</td>
        <td>Provincia</td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <!-- fila del formulario para insertar -->

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
          <?php
          //  Conexion 
          require("conexion.php");
          

//--------------------------------PAGINACION-----------------------------------
          $div_paginas = 3;

            if (isset($_GET["pag"])) {
              $pagina = $_GET["pag"];
              $empezar_desde = ($pagina - 1) * $div_paginas;
            } else {
              $pagina = 1;
              $empezar_desde = 0;
            }

          $consulta = "SELECT * FROM datos_usuarios";
          $resultados = $conexion->prepare($consulta);
          $resultados->execute();
          $num_filas = $resultados->rowCount();

          $total_paginas = ceil($num_filas / $div_paginas);

          $resultados->closeCursor();
//-----------------------------------------------------------------------------



          // Hacemos el actualizado 
          if (isset($_POST['actualizar'])) {
            $id_act = $_POST['id'];
            $consulta = "SELECT * FROM datos_usuarios";
            $resultados = $conexion->query($consulta)->fetchAll(PDO::FETCH_OBJ);

            foreach ($resultados as $value) {
              echo "<td>" . $value->ID . "<input type='hidden' name='id' value='" . $value->ID . "'></td>";
              echo '<td><input type="text" name="nombre" value ="' . $value->NOMBRE . '"></td>';
              echo '<td><input type="text" name="apellido1" value = "' . $value->APELLIDO1 . '"></td>';
              echo '<td><input type="text" name="apellido2"value = "' . $value->APELLIDO2 . '"></td>';
              echo '<td><input type="text" name="provincia"value = "' . $value->PROVINCIA . '"></td>';
              echo '<td class="boton"><input type="submit" name="guardar" value="Guardar cambios"></td>';
            }
          } else {
          ?>
            <td></td>
            <td><input type="text" name="nombre"></td>
            <td><input type="text" name="apellido1"></td>
            <td><input type="text" name="apellido2"></td>
            <td><input type="text" name="provincia"></td>
            <td class="boton"><input type="submit" name="insertar" value="Insertar" class="boton"></td>;
          <?php
          }
          ?>
        </form>
      </tr>
      <?php


          // Hacemos el insert
          if (isset($_POST["insertar"])) {
              $nombre = $_POST["nombre"];
              $apellido1 = $_POST["apellido1"];
              $apellido2 = $_POST["apellido2"];
              $provincia = $_POST["provincia"];

              $consulta = "INSERT INTO datos_usuarios (NOMBRE, APELLIDO1, APELLIDO2, PROVINCIA)
                          VALUES (:nombre, :apellido1, :apellido2, :provincia)";

              $resultados = $conexion->prepare($consulta);
              $resultados->bindValue(":nombre", $nombre);
              $resultados->bindValue(":apellido1", $apellido1);
              $resultados->bindValue(":apellido2", $apellido2);
              $resultados->bindValue(":provincia", $provincia);
              $resultados->execute();

              
              if(($num_filas%$div_paginas)==0){ // % es el modulo, el resto =0_ ej: 18(registros)/3(div)=6 ->RESTO =0, por tanto te va a dirigir a la ultimapagina+1 xq ha empezado una pagina nueva__ 17/3=5,6 ->RESTO=0,6, por tanto no es igual a 0 y te lleva a la total_paginas(pagina final normal);
                $num_paginas=$total_paginas+1;
              }else{
                $num_paginas=$total_paginas;
              }
              header("Location:index.php?pag=$num_paginas");
          }


            // Hacemos el borrado
            if (isset($_POST["borrar"])) {
                $id = $_POST["id"];

                $consulta = "DELETE FROM datos_usuarios WHERE ID=:id";

                $resultados = $conexion->prepare($consulta);
                $resultados->bindValue(":id", $id);
                $resultados->execute();
            }




            // Hacemos el actualizado 
            if (isset($_POST["guardar"])) {
                $id_ac = $_POST["id"];
                $nombre_ac = $_POST["nombre"];
                $apellido1_ac = $_POST["apellido1"];
                $apellido2_ac = $_POST["apellido2"];
                $provincia_ac = $_POST["provincia"];

                $consulta = "UPDATE datos_usuarios SET NOMBRE=:nombre, APELLIDO1=:apellido1, APELLIDO2=:apellido2, 
                            PROVINCIA=:provincia
                            where  ID=:id";
                $resultados = $conexion->prepare($consulta);
                $resultados->bindValue(":id", $id_ac);
                $resultados->bindValue(":nombre", $nombre_ac);
                $resultados->bindValue(":apellido1", $apellido1_ac);
                $resultados->bindValue(":apellido2", $apellido2_ac);
                $resultados->bindValue(":provincia", $provincia_ac);
                $resultados->execute();
                $resultados->closeCursor();
            }



      //  Consulta ----------------------PAGINACION-----------------------
      $consulta_limite = "SELECT * FROM datos_usuarios LIMIT $empezar_desde, $div_paginas";
      $resultados = $conexion->prepare($consulta_limite);
      $resultados->execute();
      //------------------------------------------------------------------



      //  Recorremos el array con los valores devueltos
      foreach ($resultados as $valor) {
      ?>
        <tr>
          <td class="valor"><?php echo $valor["ID"] ?></td>
          <td class="valor"><?php echo $valor["NOMBRE"] ?></td>
          <td class="valor"><?php echo $valor["APELLIDO1"] ?></td>
          <td class="valor"><?php echo $valor["APELLIDO2"] ?></td>
          <td class="valor"><?php echo $valor["PROVINCIA"] ?></td>
          <td class="boton">
            <form action="" method="post">
              <input type="hidden" value="<?php echo $valor['ID'] ?>" name="id">
              <input type="submit" value="Borrar" name="borrar">
              <input type="submit" value="Actualizar" name="actualizar">
            </form>
          </td>
        </tr>
      <?php
      }
      // cerramos cursor
      $resultados->closeCursor();
      ?>

    </tbody>
  </table>
<br><br>

  <div id="enlaces">

<?php if ($pagina == 1) { ?>
    <p class="disabled">
        <<</p>
        <?php } else { ?>
            <a class="enlaces" href="index.php?pag=<?php echo $pagina - 1; ?>">
                <<</a>
                <?php } ?>

                <?php
                for ($i = 1; $i <= $total_paginas; $i++) ://GENERA TANTAS PAGINAS COMO REGISTROS HAYA
                ?>
                    <?php
                        if ($pagina == $i) {
                        ?>
                            <p class="disabled seleccionado"><?php echo $i ?></p>
                        <?php
                        } else {
                        ?>
                            <a href="index.php?pag=<?php echo $i; ?>" class="enlaces"><?php echo $i ?> </a>
                        <?php
                        }
                        ?>
                <?php
                endfor;
                ?>
                    <?php if ($pagina == $total_paginas) { ?>
                        <p class="disabled">>></p>
                    <?php } else { ?>
                        <a class="enlaces" href="index.php?pag=<?php echo $pagina + 1; ?>">>></a>
                    <?php } ?>
</div>



</body>

</html>