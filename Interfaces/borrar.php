<?php
    require_once ('../Repositorio/conexion.php'); 
    $conexion=conectarBD();

    session_start();
    $tabla = $_SESSION['tabla'];

    $_SESSION['tabla'] = $tabla;
    $id = $_GET['id'];
    $_SESSION['valor'] = $id;

    $listAtributos= [];
    $atributo = 'id';

    

    $query1 = "SELECT * FROM information_schema.columns WHERE table_name = '$tabla' AND table_schema = 'public' ;";
    $resultado1 = pg_query($conexion, $query1);
    

    $query = "SELECT * FROM $tabla WHERE id = '$id';";
    $lista = pg_query($conexion, $query);


    while ($fila = pg_fetch_array($resultado1)) {
        array_push($listAtributos, $fila['column_name']);
    }
    $cantAtributos = count($listAtributos);


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <script src="../style/materialize/jquery-3.4.0.min.js"></script>
    <script src="../style/materialize/js/materialize.min.js"></script>
    <link rel="stylesheet" href="../style/materialize/css/materialize.min.css">
</head>
<body>

    <nav class="navbar navbar-light blue" style=" width: 300px;">
        <a class="navbar-brand" href="../index.php" style="position: absolute; left:10%;">    Inicio</a>
    </nav>

    <div class="col l4" style=" left:auto; top:15%;">

        <table>
            <h5 class="blue-text">Resultado de la busqueda: </h5>
            <tr>
                <?php
                    for ($i=0; $i < $cantAtributos; $i++){
                ?>
                <th><?php echo "".$listAtributos[$i].""; ?></th>

                <?php        
                    }
                ?>
            </tr>
            <?php
                echo $lista[0];
                while ($atri = pg_fetch_array($lista)) {
            ?>
            <tr>
                <?php
                
                    for ($s=0; $s < $cantAtributos; $s++){
                ?>
                <td><?php echo $atri[$s]; ?></td>

                <?php        
                    }
                ?>

            </tr>
            <?php
                }
            ?>
        </table>


    </div>

    <div>
        <br><br><br>
        <h5>¿Desea eliminar la informacion?</h5>
        
        <form action="../Controladores/crudLista.php" method="POST">
            <input  type="submit" name="accion" value="Borrar">
        </form>
    </div>

    
</body>
</html>