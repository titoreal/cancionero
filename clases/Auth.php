<?php 
session_start();
include "Conexion.php";

class Auth extends Conexion {
    public function registrar($usuario, $password) {
        $conexion = parent::conectar();
        $sql = "INSERT INTO t_usuarios (usuario, password) 
                VALUES (?,?)";
        $query = $conexion->prepare($sql);
        $query->bind_param('ss', $usuario, $password);
        return $query->execute();
    }

    public function logear($usuario, $password) {
        $conexion = parent::conectar();
        $passwordExistente = "";
        $sql = "SELECT * FROM t_usuarios 
                WHERE usuario = '$usuario'";
        $respuesta = mysqli_query($conexion, $sql);

        if (mysqli_num_rows($respuesta) > 0) {
            $passwordExistente = mysqli_fetch_array($respuesta);
            $passwordExistente = $passwordExistente['password'];
            
            if (password_verify($password, $passwordExistente)) {
                $_SESSION['usuario'] = $usuario;
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }   

    public function verificarSesion() {
        if (!isset($_SESSION['usuario'])) {
            header("location: ../../inicio.php");
            exit(); // Asegurar que el script se detenga después de la redirección
        }
    }
}

// Crear una instancia de la clase Auth para utilizarla en otras partes de tu aplicación
$auth = new Auth();
?>
