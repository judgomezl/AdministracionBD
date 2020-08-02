<?php
    require_once ('../Repositorio/conexion.php'); 
    
    $conexion=conectarBD();
    $accion = $_POST['accion'];

    if ($accion == 'modificar') {

        session_start();
        $id = $_SESSION['valor'];
        $tabla = $_SESSION['tabla'];
        $schema_log = $_SESSION['schema_log'];
        $schema = $_SESSION['schema'];
        $tablaLogs = "log_".$tabla;

        /********************************************************************/
        /******************Crea  las tablas log_'s si no existen ************/
        /********************************************************************/
        
        $query = "SELECT * FROM information_schema.tables where table_schema = '$schema';";

        $resultado = pg_query($conexion, $query);
        $resultado2 = pg_query($conexion, $query);

        $listAtributos= [];

        while ($f = pg_fetch_array($resultado)) {
            array_push($listAtributos, $f['table_name']);
        }
        $cantAtributos = count($listAtributos);

        $cont = 0;
        while ($fila = pg_fetch_array($resultado2)) {
            if ($fila['table_name'] != $tablaLogs) {
                $cont = $cont + 1;
            }
        }

        if ($cantAtributos == $cont) {
            $crearTablaLogs = 'CREATE TABLE '.$schema_log.'.'.$tablaLogs.' AS SELECT * FROM '.$tabla.' WHERE 1 = 0;';
            $resulNuevaTabla = pg_query($conexion, $crearTablaLogs);
        }

        /********************************************************************/
        /**********Guarda la informacion en las tablas log_'s  **************/
        /********************************************************************/


        $query2 = "SELECT column_name FROM information_schema.columns where table_name = '$tabla' AND table_schema = 'public';";
        $resultado3 = pg_query($conexion, $query2);

        
        $valores = "SELECT * from $tabla WHERE id = '$id';";
        $resultado4 = pg_query($conexion, $valores);
        $resu = pg_fetch_array($resultado4);

        while ($atri = pg_fetch_array($resultado3)) {
            $atributo = $atri['column_name'];
            $valor = $resu[$atributo];
            echo $valor;
            if ($atributo == 'id') {
                $query = 'INSERT INTO '.$schema_log.'.'.$tablaLogs.' ('.$atributo.') VALUES (\''.$valor.'\');';
                $resultado = pg_query($conexion, $query);
            }else{
                $query2 = ' UPDATE '.$schema_log.'.'.$tablaLogs.' SET '.$atributo.' = \''.$valor.'\' WHERE id = \''.$id.'\';';
                $resultado = pg_query($conexion, $query2);
            }
        }

        /********************************************************************/
        /*****Actualiza la informacion que el usuario alla elegido***********/
        /********************************************************************/


        $query1 = "SELECT * FROM information_schema.columns where column_name != 'id' AND table_name = '$tabla' AND table_schema = 'public';";
        $resultado1 = pg_query($conexion, $query1);
        while ($atributos = pg_fetch_array($resultado1)) {

            $valor = $_POST[$atributos['column_name']];
            $atributo = $atributos['column_name'];
            if ($atributo != 'id') {
                $query2 = " UPDATE $tabla SET $atributo = '$valor' WHERE id = '$id';";
                $resultado = pg_query($conexion, $query2);
            }
        }
        if ($resultado) {
            echo "Datos actualizados";
        }


        /********************************************************************/
        /*************Valores para la lista de resultados********************/
        /********************************************************************/
        $listAtributos= [];

        $query1 = "SELECT * FROM information_schema.columns WHERE table_name = '$tabla' AND table_schema = 'public' ;";
        $resultado1 = pg_query($conexion, $query1);

        $query = "SELECT * FROM $tabla ;";
        $lista = pg_query($conexion, $query);

        while ($fila = pg_fetch_array($resultado1)) {
            array_push($listAtributos, $fila['column_name']);
        }
        $cantAtributos = count($listAtributos);


    }elseif ($accion == 'Borrar') {
        # code...
        $listAtributos = [];
        session_start();
        $tabla = $_SESSION['tabla'];
        $atributo = 'id';
        $valor = $_SESSION['valor'];

        $querytabla = " SELECT table_name FROM information_schema.tables WHERE table_schema = 'public' ;";
        $existe = pg_query($conexion, $querytabla);


        while ($fila = pg_fetch_array($existe)) {
            array_push($listAtributos, $fila['table_name']);
        }

        $cantAtributos = count($listAtributos);

        echo $cantAtributos;


        $reptabla = "rep_".$tabla;
        echo $reptabla;
        $numtab = 0;
        while ($tablas = pg_fetch_array($existe)) {      
            if($reptabla != $tablas['table_name']){
                $numtab =$numtab + 1;
            }
        }

        if ($numtab == $cantAtributos) {
            # code...crear tabla
            $querytabla = "CREATE TABLE $reptabla LIKE $tabla;";
            $existe = pg_query($conexion, $querytabla);
        }


    


        // DELETE FROM nombre_tabla
        // WHERE nombre_columna = valor
        $query2 = " DELETE FROM $tabla WHERE $atributo = '$valor';";
        $resultado = pg_query($conexion, $query2);

        if ($resultado) {
            # code...
            echo "Datos borrados";
        }

        $listAtributos= [];

        $query1 = "SELECT * FROM information_schema.columns WHERE table_name = '$tabla' AND table_schema = 'public' ;";
        $resultado1 = pg_query($conexion, $query1);

        $query = "SELECT * FROM $tabla ";
        $lista = pg_query($conexion, $query);

        while ($fila = pg_fetch_array($resultado1)) {
            array_push($listAtributos, $fila['column_name']);
        }
        $cantAtributos = count($listAtributos);


    }elseif ($accion == 'agregar') {
        # code...
        $tabla = $_POST['tabla'];
        $query1 = "SELECT * FROM information_schema.columns where table_name = '$tabla' AND table_schema = 'public';";
        $resultado1 = pg_query($conexion, $query1);

        $id = $_POST['id'];
        while ($atributos2 = pg_fetch_array($resultado1)) {
            # code...
            

            $valor = $_POST[$atributos2['column_name']];
            $atributo = $atributos2['column_name'];
            if ($atributo == 'id') {
                $query = "INSERT INTO $tabla ($atributo) VALUES ('$valor');";
                $resultado = pg_query($conexion, $query);
            }else{

                $query2 = " UPDATE $tabla SET $atributo = '$valor' WHERE id = '$id';";
                $resultado = pg_query($conexion, $query2);
            }

        }
        if ($resultado) {
            # code...
            echo "Datos agregados";
        }

        $listAtributos= [];

        $query1 = "SELECT * FROM information_schema.columns WHERE table_name = '$tabla' AND table_schema = 'public' ;";
        $resultado1 = pg_query($conexion, $query1);

        $query = "SELECT * FROM $tabla";
        $lista = pg_query($conexion, $query);

        while ($fila = pg_fetch_array($resultado1)) {
            array_push($listAtributos, $fila['column_name']);
        }
        $cantAtributos = count($listAtributos);

    }elseif ($accion == 'Buscar') {

        // session_start();
        // $tabla = $_SESSION['tabla'] ;
        
        // $atributo = $_POST['formatributo'];
        // $valor = $_POST['formvalor'];

        session_start();
        $tabla = $_SESSION['tabla'];



        $atributo = $_POST['formatributo'];
        $valor = $_POST['formvalor'];

        $listAtributos= [];

        $query1 = "SELECT * FROM information_schema.columns WHERE table_name = '$tabla' AND table_schema = 'public' ;";
        $resultado1 = pg_query($conexion, $query1);

        $query = "SELECT * FROM $tabla WHERE $atributo = '$valor';";
        $lista = pg_query($conexion, $query);

        while ($fila = pg_fetch_array($resultado1)) {
            array_push($listAtributos, $fila['column_name']);
        }
        $cantAtributos = count($listAtributos);
    }
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista</title>

    <script src="../style/materialize/jquery-3.4.0.min.js"></script>
    <script src="../style/materialize/js/materialize.min.js"></script>
    <link rel="stylesheet" href="../style/materialize/css/materialize.min.css">
</head>
<body>
    <nav class="navbar navbar-light blue" style=" width: 300px;">
        <a class="navbar-brand" href="../index.php" style="position: absolute; left:10%;">    Inicio</a>
    </nav>
    <div class="col l4 offset-l5" style=" top:15%;">
        <table border="1">
            <h5 class="blue-text">Resultado de la busqueda: </h5>
            <tr>
                <?php
                    for ($i=0; $i < $cantAtributos; $i++){
                ?>
                <td><?php echo "".$listAtributos[$i].""; ?></td>

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
                <td><a href="../Interfaces/modificar.php?id=<?php echo $atributos[0]?>" class="btn btn-small">Editar</a></td>
                <td><a href="../Interfaces/borrar.php?id=<?php echo $atributos[0]?>" color="red" class="btn btn-small">Borrar</a></td>
            </tr>
            <?php
            }
            ?>
        </table>
    </div>
</body>
</html>