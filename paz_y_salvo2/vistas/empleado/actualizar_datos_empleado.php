<?php
session_start();

// Variables de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "paz_y_salvo2";

// Establecer la conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Validar la sesión antes de acceder a los datos del usuario
if (!isset($_SESSION['username'])) {
    header('Location: ../login/login.php');
    exit;
}

// Obtener el nombre de usuario de la sesión
$username = $_SESSION['username'];

// Consultar los datos del empleado asociado al usuario
$sql_obtener_empleado = "SELECT e.*, u.NombreUsuario, u.CorreoElectronico
                          FROM empleados e
                          LEFT JOIN usuarios u ON e.Usuario_ID = u.ID
                          WHERE u.NombreUsuario = '$username'";

$result_obtener_empleado = $conn->query($sql_obtener_empleado);

// Verificar si hubo un error en la consulta
if ($result_obtener_empleado === false) {
    echo "Error al ejecutar la consulta para obtener los datos del empleado: " . $conn->error;
    exit;
}

// Verificar si se encontraron datos del empleado
if ($result_obtener_empleado->num_rows > 0) {
    $empleado = $result_obtener_empleado->fetch_assoc();

    // Obtener los datos del empleado
    $id_empleado = $empleado['ID'];

    // Obtener los datos enviados por el formulario de edición
    $nuevoNombre = $_POST['nuevoNombre'];
    $nuevoApellido = $_POST['nuevoApellido'];
    $nuevoDocumento = $_POST['nuevoDocumento'];

    // Actualizar los datos del empleado en la tabla 'empleados'
    $sql_actualizar_empleado = "UPDATE empleados SET Nombre = '$nuevoNombre', Apellido = '$nuevoApellido', DocumentoIdentidad = '$nuevoDocumento' WHERE ID = $id_empleado";

    // Obtener el ID del usuario asociado al empleado
    $sql_obtener_id_usuario = "SELECT Usuario_ID FROM empleados WHERE ID = $id_empleado";
    $result_obtener_id_usuario = $conn->query($sql_obtener_id_usuario);

    if ($result_obtener_id_usuario->num_rows > 0) {
        $row = $result_obtener_id_usuario->fetch_assoc();
        $id_usuario = $row['Usuario_ID'];

        // Actualizar los datos del usuario en la tabla 'usuarios'
        $sql_actualizar_usuario = "UPDATE usuarios SET Nombre = '$nuevoNombre', Apellido = '$nuevoApellido', DocumentoIdentidad = '$nuevoDocumento' WHERE ID = $id_usuario";

        // Ejecutar las consultas SQL
        if ($conn->query($sql_actualizar_empleado) === true && $conn->query($sql_actualizar_usuario) === true) {
            // Mostrar mensaje de actualización exitosa usando JavaScript
            echo "<script>alert('Los datos se han actualizado correctamente');</script>";
            // Redireccionar a la página principal después de mostrar el mensaje
            echo "<script>window.location.href = 'empleados.php';</script>";
            exit;
        } else {
            echo "Error al actualizar los datos del empleado o usuario: " . $conn->error;
        }
    } else {
        echo "Error: No se encontró el ID de usuario asociado al empleado.";
    }
} else {
    // Manejar el caso si no se encuentra el empleado
    echo "Error: No se encontraron datos del empleado asociado al usuario.";
}

// Cerrar la conexión
$conn->close();
?>
