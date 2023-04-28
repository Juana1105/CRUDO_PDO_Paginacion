
<?php


    try{ 

      $conexion=new PDO('mysql:host=localhost; dbname=pruebas', 'root', 'iusenma');
      $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $conexion->exec("SET CHARACTER SET utf8");

     // CONSULTA
// $consulta="SELECT * FROM datos_usuarios";
// $resultado=$conexion->prepare($consulta);
// $resultado->execute();



      }// Comprobamos la conexión
      catch(Exception $e){ 
        die ("Se ha producido el error: " . $e->getMessage());
        echo "Línea del error " . $e->getLine();
      }
  



?>