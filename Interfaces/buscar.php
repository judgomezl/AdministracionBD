  <?php 
    require_once ('../Repositorio/conexion.php'); 


    $conexion=conectarBD();
    $tabla = $_POST['formtabla'];

    session_start();
    $_SESSION['tabla'] = $tabla;

    $listAtributos= [];

    $query1 = "SELECT * FROM information_schema.columns WHERE table_name = '$tabla' AND table_schema = 'public' ;";
    $resultado1 = pg_query($conexion, $query1);
    $resultado2 = pg_query($conexion, $query1);

    $query = "SELECT * FROM $tabla";
    $lista = pg_query($conexion, $query);

    while ($fila = pg_fetch_array($resultado1)) {
        array_push($listAtributos, $fila['column_name']);
    }
    $cantAtributos = count($listAtributos);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista</title>

    <script src="../style/materialize/jquery-3.4.0.min.js"></script>
    <script src="../style/materialize/js/materialize.min.js"></script>
    <link rel="stylesheet" href="../style/materialize/css/materialize.min.css">
    <link rel="stylesheet" href="../style/style.css">

    <script language = "javascript">
        $(document).ready(function(){
            $("#formatributo").change(function(){
                $("#formatributo option:selected").each(function(){
                    formatributo = $(this).val();
                    $.post("../Repositorio/getValores.php", { formatributo: formatributo}, function(data){
                        $("#formvalor").html(data);
                    });
                });
            });
        });
    </script>

</head>
<body>

    <nav class="navbar navbar-light blue" style=" width: 300px;">
        <a class="navbar-brand" href="../index.php" style="position: absolute; left:10%;">    Inicio</a>
    </nav>

<!-- filtros -->
    <div  class="col l4 offset-l5" style="position:center; top:15%;">
        <form action="../Controladores/crudLista.php" id="tb" name="tabla" method="POST">
            <div> Seleccionar atributo: 
                <select name="formatributo" id="formatributo">
                    <option value="0">Seleccione el atributo</option>
                    <?php
                        while ($fila = pg_fetch_array($resultado2)) {
                    ?>
                    
                    <option value="<?php echo $fila['column_name']; ?>"><?php echo $fila['column_name']; ?></option>

                    <?php
                        }
                    ?>
                </select>
            </div>  

            <div>Seleccione el valor: 
                <select name="formvalor" id="formvalor"> </select>        
            </div> 

            <input type="submit" id="buscar" name="accion" value="Buscar">
        </form>
    </div>



<!-- Lista -->
    <table>
        <h5 class="blue-text">Resultado de la busqueda: </h5>
        <tr>
            <?php
                for ($i=0; $i < $cantAtributos; $i++){
            ?>
            <td><?php echo $listAtributos[$i]; ?></td>

            <?php        
                }
            ?>
        </tr>
        <?php
            while ($atributos = pg_fetch_array($lista)) {
        ?>
        <tr>
            <?php
                for ($s=0; $s < $cantAtributos; $s++){
            ?>
            <td><?php echo $atributos[$s]; ?></td>

            <?php        
                }
            ?>
            <td><a href="modificar.php?id=<?php echo $atributos[0]?>" class="btn btn-small">Editar</a></td>
            <td><a href="borrar.php?id=<?php echo $atributos[0]?>" color="red" class="btn btn-small">Borrar</a></td>
        </tr>
        <?php
        }
        ?>
    </table>

</body>
</html>