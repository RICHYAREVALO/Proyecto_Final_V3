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
    echo "<script>alert('Error de conexión: " . $conn->connect_error . "');</script>";
}

// Validar la sesión antes de acceder a los datos del usuario
if (!isset($_SESSION['username'])) {
    echo "<script>alert('Error: Debes iniciar sesión para acceder a esta página.'); window.location.href = '../login/login.php';</script>";
}

// Obtener el nombre de usuario de la sesión
$username = $_SESSION['username'];

// Obtener los datos del empleado asociado al usuario
$sql_obtener_empleado = "SELECT e.*, u.NombreUsuario, u.CorreoElectronico
                          FROM empleados e
                          LEFT JOIN usuarios u ON e.Usuario_ID = u.ID
                          WHERE u.NombreUsuario = '$username'";

$result_obtener_empleado = $conn->query($sql_obtener_empleado);

// Verificar si hubo un error en la consulta
if ($result_obtener_empleado === false) {
    echo "<script>alert('Error al ejecutar la consulta para obtener los datos del empleado: " . $conn->error . "');</script>";
    exit;
}

// Verificar si se encontraron datos del empleado
if ($result_obtener_empleado->num_rows > 0) {
    $empleado = $result_obtener_empleado->fetch_assoc();

    // Consultar el estado del Paz y Salvo
    $sql_consultar_paz_y_salvo = "SELECT Estado, Razon_Rechazo FROM Registro_Paz_Salvo WHERE Empleado_ID = {$empleado['ID']} ORDER BY Fecha_Emision DESC LIMIT 1";
    $result_consultar_paz_y_salvo = $conn->query($sql_consultar_paz_y_salvo);

    if ($result_consultar_paz_y_salvo === false) {
        echo "<script>alert('Error al consultar el estado del Paz y Salvo: " . $conn->error . "');</script>";
        exit;
    }

    if ($result_consultar_paz_y_salvo->num_rows > 0) {
        $row = $result_consultar_paz_y_salvo->fetch_assoc();
        $estadoPazYSalvo = $row['Estado'];

        // Mostrar mensaje según el estado
        if ($estadoPazYSalvo === 'Aprobado') {
            echo "<script>alert('¡El Paz y Salvo ha sido aprobado!'); window.location.href = '../empleado/empleados.php';</script>";
            // Aquí podrías agregar el enlace para descargar el Paz y Salvo
        } elseif ($estadoPazYSalvo === 'Rechazado') {
            $razonRechazo = $row['Razon_Rechazo'];
            $correoRH = 'recursohumano@beyonder.com';
            $telefonoRH = '3212655328';
            echo "<script>alert('¡El Paz y Salvo ha sido rechazado! Razón: $razonRechazo. Por favor, comunícate con Recursos Humanos: Correo Electrónico: $correoRH, Teléfono: $telefonoRH'); window.location.href = '../empleado/empleados.php';</script>";
        } elseif ($estadoPazYSalvo === 'Pendiente') {
            echo "<script>alert('El Paz y Salvo está en proceso de aprobación por Recursos Humanos. Por favor, espere.'); window.location.href = '../empleado/empleados.php';</script>";
        }
    } else {
        echo "<script>alert('No se encontraron registros de Paz y Salvo para el empleado.'); window.location.href = '../empleado/empleados.php';</script>";
    }

} else {
    // Manejar el caso si no se encuentra el empleado
    echo "<script>alert('Error: No se encontraron datos del empleado asociado al usuario.'); window.location.href = '../empleados/empleados.php';</script>";
}

// Cerrar la conexión
$conn->close();
?>
