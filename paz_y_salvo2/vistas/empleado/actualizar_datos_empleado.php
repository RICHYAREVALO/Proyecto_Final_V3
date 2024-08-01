<?php
session_start();

// Variables de conexión a la base de datos
$servername = "bkqu3uk3ewxyehltqf2t-mysql.services.clever-cloud.com";
$username = "uwwounruhaizndvh";
$password = "91JGBP3BP37TC6be2NIi";
$dbname = "bkqu3uk3ewxyehltqf2t";

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

// Preparar la consulta para obtener los datos del empleado asociado al usuario
$stmt_obtener_empleado = $conn->prepare("
    SELECT ID AS empleadoID, Nombre, Apellido, DocumentoIdentidad
    FROM usuarios_empleados
    WHERE NombreUsuario = ?
");
$stmt_obtener_empleado->bind_param("s", $username);
$stmt_obtener_empleado->execute();
$result_obtener_empleado = $stmt_obtener_empleado->get_result();

// Verificar si se encontraron datos del empleado
if ($result_obtener_empleado->num_rows > 0) {
    $empleado = $result_obtener_empleado->fetch_assoc();
    $id_empleado = $empleado['empleadoID'];

    // Obtener los datos enviados por el formulario de edición y validar
    $nuevoNombre = isset($_POST['nuevoNombre']) ? trim($_POST['nuevoNombre']) : '';
    $nuevoApellido = isset($_POST['nuevoApellido']) ? trim($_POST['nuevoApellido']) : '';
    $nuevoDocumento = isset($_POST['nuevoDocumento']) ? trim($_POST['nuevoDocumento']) : '';

    // Validar los datos
    if (empty($nuevoNombre) || empty($nuevoApellido) || empty($nuevoDocumento)) {
        echo "<script>alert('Todos los campos son obligatorios.'); window.location.href = 'editar_empleado.php';</script>";
        exit;
    }

    // Preparar la consulta para actualizar los datos del empleado en la tabla 'usuarios_empleados'
    $stmt_actualizar_empleado = $conn->prepare("
        UPDATE usuarios_empleados 
        SET Nombre = ?, Apellido = ?, DocumentoIdentidad = ? 
        WHERE ID = ?
    ");
    $stmt_actualizar_empleado->bind_param("sssi", $nuevoNombre, $nuevoApellido, $nuevoDocumento, $id_empleado);

    // Ejecutar la consulta SQL
    if ($stmt_actualizar_empleado->execute()) {
        // Mostrar mensaje de actualización exitosa usando JavaScript
        echo "<script>alert('Los datos se han actualizado correctamente'); window.location.href = 'empleados.php';</script>";
    } else {
        echo "<script>alert('Error al actualizar los datos del empleado: " . $conn->error . "'); window.location.href = 'editar_empleado.php';</script>";
    }

    // Cerrar el prepared statement
    $stmt_actualizar_empleado->close();
} else {
    // Manejar el caso si no se encuentra el empleado
    echo "<script>alert('Error: No se encontraron datos del empleado asociado al usuario.'); window.location.href = 'editar_empleado.php';</script>";
}

// Cerrar el prepared statement y la conexión
$stmt_obtener_empleado->close();
$conn->close();
?>
