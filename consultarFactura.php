<?php

include("conexion.php");
$SQL = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND password = '$password'";
$CON = Conectar();
$RESULT = Ejecutar($CON, $SQL);
$ROW = mysqli_fetch_array($RESULT);
Desconectar($CON);



?>