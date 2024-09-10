<?php
session_start();

// Datos de conexión a la base de datos
$servername = "bjgxtiqfs78pgiy7qzux-mysql.services.clever-cloud.com";
$username = "udb0mb339gpdtxkh";
$password = "0PRRJnHNJEdZdU9pCHYR";
$dbname = "bjgxtiqfs78pgiy7qzux";

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
    $sql_verificar_solicitud = "SELECT COUNT(*) AS total FROM registro_paz_salvo WHERE Usuario_Empleado_ID = ?";
    $stmt = $conn->prepare($sql_verificar_solicitud);
    $stmt->bind_param("i", $id_empleado);
    $stmt->execute();
    $result_verificar_solicitud = $stmt->get_result();

    if ($result_verificar_solicitud === false) {
        echo "Error al verificar la solicitud de Paz y Salvo: " . $conn->error;
        exit;
    }

    $total_solicitudes = $result_verificar_solicitud->fetch_assoc()['total'];

    if ($total_solicitudes > 0) {
        // Mostrar un mensaje indicando que el empleado ya ha solicitado el paz y salvo anteriormente
        echo "<script>alert('Ya has solicitado un Paz y Salvo anteriormente'); window.history.back();</script>";
    } else {
        // Insertar un nuevo registro en la tabla registro_paz_salvo
        $sql_insert = "INSERT INTO registro_paz_salvo (Usuario_Empleado_ID, Fecha_Emision, Estado) VALUES (?, CURDATE(), 'Pendiente')";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("i", $id_empleado);

        if ($stmt_insert->execute()) {
            // Mostrar el mensaje de éxito
            echo "<script>alert('Paz y Salvo solicitado exitosamente'); window.history.back();</script>";
        } else {
            echo "Error al solicitar el Paz y Salvo: " . $conn->error;
        }

        $stmt_insert->close();
    }

    $stmt->close();
} else {
    echo "Error: ID del empleado no definido.";
}

// Cerrar la conexión
$conn->close();
?>
