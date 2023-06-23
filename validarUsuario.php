<?php
    include("conexion.php");
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];
    $query = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND password = '$password'";
    $resultado = mysqli_query($conexion, $query);
    if(mysqli_num_rows($resultado) > 0){
        $response = array();
        $response["success"] = true;
        echo json_encode($response);
    }else{
        $response = array();
        $response["success"] = false;
        echo json_encode($response);
    }




?>