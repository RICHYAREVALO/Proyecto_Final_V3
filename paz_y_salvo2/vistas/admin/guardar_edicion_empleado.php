<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "paz_y_salvo2";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if (!isset($_SESSION['username'])) {
    header('Location: ../login/login.php');
    exit;
}

$username = $_SESSION['username'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si se han proporcionado los datos necesarios
    if (isset($_POST['empleado_id'], $_POST['nombre'], $_POST['apellido'], $_POST['documento'], $_POST['nombre_departamento'], $_POST['fecha_contratacion'], $_POST['fecha_retiro'], $_POST['otros_detalles'], $_POST['nombre_usuario'])) {
        $empleado_id = $_POST['empleado_id'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $documento = $_POST['documento'];
        $nombre_departamento = $_POST['nombre_departamento'];
        $fecha_contratacion = $_POST['fecha_contratacion'];
        $fecha_retiro = $_POST['fecha_retiro'];
        $otros_detalles = $_POST['otros_detalles'];
        $nombre_usuario = $_POST['nombre_usuario'];

        // Preparar la consulta SQL
        $updateEmpleadosSql = "UPDATE empleados e
                               INNER JOIN departamentos d ON e.Departamento_ID = d.ID
                               SET e.Nombre = ?, e.Apellido = ?, e.DocumentoIdentidad = ?, 
                                   d.Nombre_Departamento = ?, e.Fecha_Contratacion = ?, 
                                   e.Fecha_Retiro = ?, e.Otros_Detalles = ?
                               WHERE e.ID = ?";
        $stmtEmpleados = $conn->prepare($updateEmpleadosSql);

        if ($stmtEmpleados === false) {
            die("Error al preparar la consulta: " . $conn->error);
        }

        // Asociar los parámetros a la consulta
        $stmtEmpleados->bind_param("sssssssi", $nombre, $apellido, $documento, $nombre_departamento, $fecha_contratacion, $fecha_retiro, $otros_detalles, $empleado_id);

        // Ejecutar la consulta
        if (!$stmtEmpleados->execute()) {
            die("Error al ejecutar la consulta: " . $stmtEmpleados->error);
        }

        // Cerrar la consulta preparada
        $stmtEmpleados->close();

        // Redirigir a la lista de empleados después de la edición exitosa
        header('Location: lista_empleados.php');
        exit;
    } else {
        echo "Datos insuficientes para la edición del empleado.";
    }
}

$conn->close();
?>
