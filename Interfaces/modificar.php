<?php
    require_once ('../Repositorio/conexion.php'); 

    session_start();
    $tabla = $_SESSION['tabla'];
    

    $_SESSION['tabla'] = $tabla;
    $atributo = 'id';
    $valor = $_GET['id'];
    $_SESSION['valor'] = $valor;
    
    $conexion=conectarBD();
    $listAtributos= [];

    $query = "SELECT * FROM information_schema.columns WHERE table_name = '$tabla' AND table_schema = 'public' ;";
    $resultadocolumnas = pg_query($conexion, $query);

    $query2 = "SELECT * FROM $tabla WHERE id = '$valor' ";
    $valoreditar = pg_query($conexion, $query2);
    $valor = pg_fetch_array($valoreditar);


   
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
    
    <div class="row"">
        <div class="col l4" style=" left:auto; top:15%;">
            <h5 class="blue-text">Tabla --> <?php echo $tabla ?></h5> <br>
            <form action="../Controladores/crudLista.php" method="POST" accept-charset="utf-8">
                <div class="blue-text col l8"> tabla (No Modificar)
                    <input type="text" name="tabla" value="<?php echo $tabla;?>" placeholder="Valor del atributo">
                    <label for="<?php echo $tabla?>"></label>
                </div><br>
                <?php
                    $columna = -1;
                    while (($fila = pg_fetch_array($resultadocolumnas))) {
                        $columna = $columna + 1;
                ?>
                <div  class="blue-text col l10"> <?php echo $fila['column_name']; ?>
                    <input type="text" name="<?php echo $fila['column_name'] ?>" value="<?php echo $valor[$columna] ?>" placeholder="Valor del atributo">
                    <label for="<?php echo $fila['column_name'] ?>"></label>
                </div><br>
                <?php
                }
                ?>
                <div>
                    <button type="submit" class="blue btn small" name="accion" value="modificar">Modificar Informacion</button>
                </div>
            </form>
            
        </div>
    </div> 
</body>
</html>