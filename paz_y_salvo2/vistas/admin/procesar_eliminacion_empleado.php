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
    // Verificar si se ha proporcionado el ID del empleado a eliminar
    if (isset($_POST['empleado_id'])) {
        $empleado_id = $_POST['empleado_id'];

        // Iniciar una transacción para asegurar la consistencia de las operaciones
        $conn->begin_transaction();

        try {
            // Eliminar registros de registro_paz_salvo asociados al empleado
            $deleteRegistroSql = "DELETE FROM registro_paz_salvo WHERE Empleado_ID = ?";
            $stmtRegistro = $conn->prepare($deleteRegistroSql);
            $stmtRegistro->bind_param("i", $empleado_id);

            if (!$stmtRegistro->execute()) {
                throw new Exception("Error al eliminar los registros de registro_paz_salvo asociados al empleado: " . $stmtRegistro->error);
            }

            $stmtRegistro->close();

            // Eliminar empleado
            $deleteEmpleadoSql = "DELETE FROM empleados WHERE ID = ?";
            $stmtEmpleado = $conn->prepare($deleteEmpleadoSql);
            $stmtEmpleado->bind_param("i", $empleado_id);

            if (!$stmtEmpleado->execute()) {
                throw new Exception("Error al eliminar el empleado: " . $stmtEmpleado->error);
            }

            $stmtEmpleado->close();

            // Commit si todas las operaciones son exitosas
            $conn->commit();

            // Redirigir a la lista de empleados después de la eliminación exitosa
            header('Location: admin.php');
            exit;
        } catch (Exception $e) {
            // Rollback en caso de error
            $conn->rollback();

            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "ID de empleado no proporcionado.";
    }
}

$conn->close();
?>