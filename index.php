<?php

    require_once('Repositorio/conexion.php');
    $conexion = conectarBD();
    
    session_start();
    $schema_log = '"98.logs"';
    $_SESSION['schema_log'] = $schema_log ;

    $schema = "98.logs";
    $_SESSION['schema'] = $schema ;

    
    $query = "SELECT table_name FROM information_schema.tables WHERE table_name not like 'log_%' AND table_schema = 'public';";
    $tablas = pg_query($conexion, $query);
    $tablas2 = pg_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <link rel="stylesheet" href="style/style.css">
        <title>AdministracioBD</title>  


    </head>
    <body>
        
        <div > 
        <h1 title="Seleccione la tabla en la seccion que corresponda a la accion que desea realizar">Elija una opcion</h1>
            <form action="Interfaces/buscar.php" name="buscar" id="buscar" method="POST">
                <div>Selecciones la tabla
                    <select name="formtabla" id="formtabla">
                            <option value="0">Seleccione tabla</option>
                        <?php
                            while ($fila = pg_fetch_array($tablas)) {
                        ?>
                            <option value="<?php echo $fila['table_name']; ?>"><?php echo $fila['table_name']; ?></option>
                        <?php
                            }
                        ?>
                    </select>
                </div>
                <input type="submit" color="blue" id="buscar" name="accion" value="Buscar" title="Busca dentro de la base de datos la tabla seleccionada">
            </form><br>    

            <form action="Interfaces/agregar.php" name="buscar" id="buscar" method="POST">
                <div>Selecciones la tabla en la que desea agregar informacion
                    <select name="formtabla" id="formtabla">
                            <option value="0">Seleccione tabla</option>
                        <?php
                            while ($fila = pg_fetch_array($tablas2)) {
                        ?>
                            <option value="<?php echo $fila['table_name']; ?>"><?php echo $fila['table_name']; ?></option>
                        <?php
                            }
                        ?>
                    </select>
                </div>
                <input type="submit" id="agregar" name="accion" value="Ir" title="Agregar informacion dentro de la tabla seleccionada">
            </form><br>    
        </div>


    </body>
</html>