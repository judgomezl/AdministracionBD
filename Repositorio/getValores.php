<?php
    require_once('conexion.php');

    $conexion = conectarBD();

    session_start();
    $tabla = $_SESSION['tabla'];
    $atributo = $_POST['formatributo'];

    $queryValores = "SELECT $atributo FROM $tabla;";
    $valores = pg_query($conexion, $queryValores);
    
    $html = "<option value='0'>Seleccione columna</option>";

    while ($fila = pg_fetch_array($valores)) {
        $html = "<option value='".$fila[$atributo]."'>".$fila[$atributo]."</option>";
        echo $html;
    }

?>