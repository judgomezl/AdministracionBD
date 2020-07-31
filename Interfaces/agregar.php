<?php
    require_once ('../Repositorio/conexion.php'); 

    $tabla = $_POST['formtabla'];

    $conexion=conectarBD();
    $listAtributos= [];

    $query = "SELECT * FROM information_schema.columns WHERE table_name = '$tabla' AND table_schema = 'public' ;";
    $resultado = pg_query($conexion, $query);
   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crud</title>
    <script src="../style/materialize/jquery-3.4.0.min.js"></script>

    <script src="../style/materialize/js/materialize.min.js"></script>
    <link rel="stylesheet" href="../style/materialize/css/materialize.min.css">

</head>
<body>

    <nav class="navbar navbar-light blue" style=" width: 300px;">
        <a class="navbar-brand" href="../index.php" style="position: absolute; left:10%;">    Inicio</a>
    </nav>
    <div class="row">
        <div class="col l4" style=" left:auto; top:15%;">
            <br><h6 class="blue-text">Agregar informacacion a la tabla --> <?php echo $tabla ?></h6> 

            <form action="../Controladores/crudLista.php" method="POST" accept-charset="utf-8"><br>
              <div class="blue-text col l8"> tabla (No Modificar)
                <input type="text" name="tabla" value="<?php echo $tabla ?>" placeholder="Valor del atributo">
                <label for="<?php echo $tabla?>"></label>
              </div>
              <br>
                <?php

                while (($fila = pg_fetch_array($resultado))) {
               
                ?>
                    <div class="blue-text col l8"> <?php echo $fila['column_name']; ?>
                        <input type="text" name="<?php echo $fila['column_name'] ?>" value="" placeholder="Valor del atributo">
                        <label for="<?php echo $fila['column_name'] ?>"></label>
                    </div>
                    <br>
                <?php
                }
                ?>
                <div>
                    <button  type="submit" class="blue btn small" name="accion" value="agregar">Agregar Informacion</button>
                </div>
            </form>
        </div>
    </div>    
</body>
</html>