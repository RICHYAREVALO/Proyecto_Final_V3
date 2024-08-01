<?php
session_start();

// Verifica si el formulario se ha enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexión a la base de datos
    $servername = "bkqu3uk3ewxyehltqf2t-mysql.services.clever-cloud.com";
    $username = "uwwounruhaizndvh";
    $password = "91JGBP3BP37TC6be2NIi";
    $dbname = "bkqu3uk3ewxyehltqf2t";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Función para limpiar la entrada
    function limpiar_entrada($datos) {
        $datos = trim($datos);
        $datos = stripslashes($datos);
        $datos = htmlspecialchars($datos);
        return $datos;
    }

    // Recibe y limpia los datos del formulario
    $nombreUsuario = limpiar_entrada($_POST['nombre_usuario']);
    $nuevaContraseña = limpiar_entrada($_POST['nueva_contraseña']);
    $confirmarContraseña = limpiar_entrada($_POST['confirmar_contraseña']);

    // Verifica que las contraseñas coincidan
    if ($nuevaContraseña !== $confirmarContraseña) {
        // Las contraseñas no coinciden, mostrar alerta y redireccionar a la página de olvido_contraseña.html
        echo "<script>alert('Las contraseñas no coinciden. Por favor, inténtalo de nuevo.'); window.location.href = 'forgot_password.php';</script>";
        exit;
    }

    // Hash de la nueva contraseña
    $hashContraseña = password_hash($nuevaContraseña, PASSWORD_DEFAULT);

    // Verifica si el usuario existe en la tabla de usuarios
    $sql_verificar_usuario = "SELECT * FROM usuarios WHERE NombreUsuario = '$nombreUsuario'";
    $result_verificar_usuario = $conn->query($sql_verificar_usuario);

    if ($result_verificar_usuario->num_rows == 0) {
        // El usuario no existe, mostrar alerta y redireccionar a la página de registro
        echo "<script>alert('El usuario no está registrado.'); window.location.href = '../registro/registro.html';</script>";
        exit;
    }

    // Actualiza la contraseña en la tabla de usuarios
    $sql_actualizar_contraseña = "UPDATE usuarios SET Contraseña = '$hashContraseña' WHERE NombreUsuario = '$nombreUsuario'";
    if ($conn->query($sql_actualizar_contraseña) === TRUE) {
        // Contraseña actualizada correctamente, mostrar alerta y redireccionar al usuario a la página de index.php
        echo "<script>alert('Contraseña actualizada correctamente.'); window.location.href = '../../index.php';</script>";
        exit;
    } else {
        echo "Error al actualizar contraseña: " . $conn->error;
    }

    // Cierra la conexión a la base de datos
    $conn->close();
} else {
    // Si el formulario no se ha enviado, redirige a la página de olvido de contraseña
    header("Location: olvido_contraseña.html");
    exit;
}
?>
