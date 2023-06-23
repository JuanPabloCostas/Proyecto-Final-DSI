<?php
function Conectar(){
    $Server = "localhost";
    $User = "root";
    $Pws = "";
    $BD = "controlvehicular30 f";
    $Con = mysqli_connect($Server, $User, $Pws, $BD);
    return $Con; // Va a retornar un objeto
}

function Ejecutar($Con, $SQL){
    $Result = mysqli_query($Con, $SQL);
    return $Result; // Va a retornar un 1, 0 (error) o la relacion
}

function Procesar(){

}

function Desconectar($Con){
    $R = mysqli_close($Con);
    return $R; // Va a regresar un 1 o un 0 (error)
}
?>