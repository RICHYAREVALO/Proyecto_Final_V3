<?php
session_start();

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
    header('Location: login.php');
    exit;
}

// Obtener el ID del empleado desde el formulario
$id_empleado = isset($_POST['id_empleado']) ? $_POST['id_empleado'] : null;

// Verificar si el ID del empleado está definido
if ($id_empleado !== null) {
    // Verificar si el empleado ya ha solicitado el paz y salvo anteriormente
    $sql_verificar_solicitud = "SELECT COUNT(*) AS total FROM Registro_Paz_Salvo WHERE Empleado_ID = $id_empleado";
    $result_verificar_solicitud = $conn->query($sql_verificar_solicitud);

    if ($result_verificar_solicitud === false) {
        echo "Error al verificar la solicitud de Paz y Salvo: " . $conn->error;
        exit;
    }

    $total_solicitudes = $result_verificar_solicitud->fetch_assoc()['total'];

    if ($total_solicitudes > 0) {
        // Mostrar un mensaje indicando que el empleado ya ha solicitado el paz y salvo anteriormente
        echo "<script>alert('Ya has solicitado un Paz y Salvo anteriormente'); window.history.back();</script>";
    } else {
        // Insertar un nuevo registro en la tabla Registro_Paz_Salvo
        $sql = "INSERT INTO Registro_Paz_Salvo (Empleado_ID, Fecha_Emision, Estado) VALUES ('$id_empleado', CURDATE(), 'Pendiente')";

        if ($conn->query($sql) === TRUE) {
            // Mostrar el mensaje de éxito
            echo "<script>alert('Paz y Salvo solicitado exitosamente'); window.history.back();</script>";
        } else {
            echo "Error al solicitar el Paz y Salvo: " . $conn->error;
        }
    }
} else {
    echo "Error: ID del empleado no definido.";
}

// Cerrar la conexión
$conn->close();
?>
