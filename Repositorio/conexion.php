<?php

function conectarBD(){
    $host = "host=localhost";
    $port = "port=5432";
    $dbname = "dbname=crud"; 
    $user = "user=judgomezlo";
    $password = "password=1234";
    


    $conect = pg_connect("$host $port $dbname $user $password"); //or die ("Error de Conexion".pg_last_error());

    if(!$conect){
        echo ("Error: ".pg_last_error());
    }else{
        echo ("<h3>Administracion BD</h3><hr>");
        return $conect;
    }
}
?>