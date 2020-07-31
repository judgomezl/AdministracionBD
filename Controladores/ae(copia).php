<?php
    require_once ('../Repositorio/conexion.php'); 
    

    date_default_timezone_set("America/Bogota");
    $fecha_actual = date("Y-m-d H:i:s");
    $tabla = $_POST['formtabla'];
    
    
    
    $conexion=conectarBD();
    $listAtributos= [];

    $query = "SELECT * FROM information_schema.columns WHERE table_name = '$tabla' AND table_schema = 'public' ;";
    $resultado = pg_query($conexion, $query);
    $resultado3 = pg_query($conexion, $query);
    $resultado4 = pg_query($conexion, $query);

    $query2 = "SELECT * FROM $tabla ";
    $resultado5 = pg_query($conexion, $query2);

    while ($fila = pg_fetch_array($resultado)) {
        array_push($listAtributos, $fila['column_name']);
    }
    $cantAtributos = count($listAtributos);
   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Crud</title>

    <script src="jquery-3.5.1.min.js"></script>
    <script src="../style/materialize/js/materialize.min.js"></script>
    <link rel="stylesheet" href="../style/materialize/css/materialize.min.css">
</head>

<body>
    <div class="row">
        <div class="col l4" style="position:absolute; top:15%;">
            <h5 class="blue-text">Tabla --> <?php echo $tabla ?></h5> <br>
            <form action="guardar.php" method="POST" accept-charset="utf-8">
                <div class="blue-text col l8"> Fecha Actual
                    <input type="text" name="fecha" value="<?php echo $fecha_actual;?>" placeholder="">
                    <label for="<?php echo $tabla?>"></label>
                </div><br>
                <div class="blue-text col l8"> tabla (No Modificar)
                    <input type="text" name="tabla" value="<?php echo $tabla;?>" placeholder="">
                    <label for="<?php echo $tabla?>"></label>
                </div><br>
                <?php
                    
                    while (($fila = pg_fetch_array($resultado3))) {
                
                ?>
                <div  class="blue-text col l10"> <?php echo $fila['column_name']; ?>
                    <input type="text" name="<?php echo $fila['column_name'] ?>" value="" placeholder="Valor del atributo">
                    <label for="<?php echo $fila['column_name'] ?>"></label>
                </div><br>
                <?php
                }
                ?>
                <div>
                    <button type="submit" class="blue btn small" name="guardar" value="guardar">Guardar Informacion</button>
                </div>
            </form>
            
            <table>
                <br><h5 class="blue-text">Lista</h5><br><br>
                <thead>
                    <tr>
                        <?php
                            
                            while (($fila = pg_fetch_array($resultado4))) {
                        ?>
                            <th><?php echo $fila['column_name'] ?></th>
                        <?php
                            }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        while ($atributos = pg_fetch_array($resultado5)) {
                    ?>
                    <tr>
                        <?php
                            for ($s=0; $s < $cantAtributos; $s++){
                        ?>
                        <td><?php echo "".$atributos[$s].""; ?></td>

                        <?php        
                            }
                        ?>
                        <td><a href="modificar.php?id= "<?php echo $atributos[0]?>" class="btn btn-small">Editar</a></td>
                        <td><a href="modificar.php?id= "<?php echo $atributos[0]?>" class="btn btn-big">Borrar</a></td>
                    </tr>
                    <?php
                        }
                    ?>
                </tbody>
            </table>
            
        </div>
    </div> 
</body>
</html>