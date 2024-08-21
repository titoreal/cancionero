<?php
session_start();
require '../../clases/Conexion.php';

// Crear una nueva conexión
$conexion = new Conexion();
$conn = $conexion->conectar();

$id = $conn->real_escape_string($_POST['id']);



$sql = "DELETE FROM cancion WHERE id=$id";
if ($conn->query($sql)) {

    $dir = "caratulas";
    $caratula = $dir . '/' . $id . '.jpg';

    if (file_exists($caratula)) {
        unlink($caratula);
    }

    $_SESSION['color'] = "success";
    $_SESSION['msg'] = "Registro eliminado";
} else {
    $_SESSION['color'] = "danger";
    $_SESSION['msg'] = "Error al eliminar registro";
}
$conn->close(); 

header('Location: index.php');
