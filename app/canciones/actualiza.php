<?php
session_start();
require '../../clases/Conexion.php';

// Crear una nueva conexión
$conexion = new Conexion();
$conn = $conexion->conectar();
$id = $conn->real_escape_string($_POST['id']);
$tema = $conn->real_escape_string($_POST['tema']);
$interprete = $conn->real_escape_string($_POST['interprete']);
$disquera = $conn->real_escape_string($_POST['disquera']);
$nacionalidad = $conn->real_escape_string($_POST['nacionalidad']);
$tipo = $conn->real_escape_string($_POST['tipo']);
$letra = $conn->real_escape_string($_POST['letra']);


$sql = "UPDATE cancion SET tema ='$tema', interprete = '$interprete', id_disquera = $disquera, id_nacionalidad = $nacionalidad ,id_tipo = $tipo, letra = '$letra' WHERE id=$id";

if ($conn->query($sql)) {
   
    $_SESSION['color'] = "success";
    $_SESSION['msg'] = "Registro actualizado";

    if ($_FILES['caratula']['error'] == UPLOAD_ERR_OK) {
        $permitidos = array("image/jpg", "image/jpeg");
        if (in_array($_FILES['caratula']['type'], $permitidos)) {
            $dir = "caratulas/";
            $caratula = $dir . $id . '.jpg';
    
            // Eliminar la caratula anterior si existe
            if (file_exists($caratula)) {
                unlink($caratula);
            }
    
            if (!file_exists($dir)) {
                mkdir($dir, 0777);
            }
    
            if (!move_uploaded_file($_FILES['caratula']['tmp_name'], $caratula)) {
                $_SESSION['color'] = "danger";
                $_SESSION['msg'] .= "<br>Error al guardar imagen";
            }
        } else {
            $_SESSION['color'] = "danger";
            $_SESSION['msg'] .= "<br>Formato de imágen no permitido";
        }
    }
}
$conn->close(); 


header('Location: index.php');
