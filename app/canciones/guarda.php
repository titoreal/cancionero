<?php

require '../../clases/Conexion.php';

// Crear una nueva conexión
$conexion = new Conexion();
$conn = $conexion->conectar();

$tema = $conn->real_escape_string($_POST['tema']);
$interprete = $conn->real_escape_string($_POST['interprete']);
$disquera = $conn->real_escape_string($_POST['disquera']);
$nacionalidad = $conn->real_escape_string($_POST['nacionalidad']);
$tipo = $conn->real_escape_string($_POST['tipo']);
$letra = $conn->real_escape_string($_POST['letra']);


$sql = "INSERT INTO cancion (tema, interprete, id_disquera, id_nacionalidad,id_tipo,fecha_alta,letra)
VALUES ('$tema', '$interprete', $disquera, $nacionalidad, $tipo,NOW(), '$letra')";
if ($conn->query($sql)) {

    $id = $conn->insert_id;
    $_SESSION['color'] = "success";
    $_SESSION['msg'] = "Registro guardado";



    if ($_FILES['caratula']['error'] == UPLOAD_ERR_OK) {
        $permitidos = array("image/jpg", "image/jpeg");
        if (in_array($_FILES['caratula']['type'], $permitidos)) {

            $dir = "caratulas";

            $info_img = pathinfo($_FILES['caratula']['name']);
            $info_img['extension'];

            $caratula = $dir . '/' . $id . '.jpg';

            if (!file_exists($dir)) {
                mkdir($dir, 0777);
            }

            if (!move_uploaded_file($_FILES['caratula']['tmp_name'], $caratula)) {
                $_SESSION['msg'] .= "<br>Error al guardar imagen";

            }
        } else {
            $_SESSION['color'] = "danger";
                $_SESSION['msg'] .= "<br>Error al guardar imagen";
        }
    }
} else {
    $_SESSION['color'] = "danger";
    $_SESSION['msg'] = "Error al guarda imágen";
}

$conn->close(); 

header ('Location: index.php');
