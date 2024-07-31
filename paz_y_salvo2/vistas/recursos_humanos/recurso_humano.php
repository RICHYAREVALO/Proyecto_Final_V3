<?php
session_start();

// Verificar si el usuario ha iniciado sesión y es de Recursos Humanos
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'recursos_humanos') {
    header('Location: login.php');
    exit;
}

// Establecer la conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pazysalvo_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Manejar la actualización del estado si se ha enviado un formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && isset($_POST['registro_id'])) {
    $accion = $_POST['accion'];
    $registro_id = $_POST['registro_id'];

    // Verificar si se rechaza el Paz y Salvo
    if ($accion === 'Rechazado' && isset($_POST['razon_rechazo'])) {
        $razon_rechazo = $_POST['razon_rechazo'];

        // Actualizar el estado del registro y la razón de rechazo
        $sql_update = "UPDATE registro_paz_salvo SET Estado = ?, Razon_Rechazo = ? WHERE ID = ?";
        $stmt = $conn->prepare($sql_update);
        $stmt->bind_param("ssi", $accion, $razon_rechazo, $registro_id);
        $stmt->execute();
        $stmt->close();
    } elseif ($accion === 'Aprobado') {
        // Actualizar solo el estado del registro si se aprueba
        $sql_update = "UPDATE registro_paz_salvo SET Estado = ? WHERE ID = ?";
        $stmt = $conn->prepare($sql_update);
        $stmt->bind_param("si", $accion, $registro_id);
        $stmt->execute();
        $stmt->close();

        // Mostrar mensaje emergente de éxito
        echo "<script>alert('Paz y Salvo aprobado exitosamente');</script>";
    }
}

// Consultar las solicitudes de Paz y Salvo pendientes
$sql_pendientes = "SELECT r.*, u.Nombre, u.Apellido
                   FROM registro_paz_salvo r
                   INNER JOIN usuarios_empleados u ON r.Usuario_Empleado_ID = u.ID
                   WHERE r.Estado = 'Pendiente'";
$result_pendientes = $conn->query($sql_pendientes);

// Consultar las solicitudes de Paz y Salvo aprobadas
$sql_aprobados = "SELECT r.*, u.Nombre, u.Apellido
                  FROM registro_paz_salvo r
                  INNER JOIN usuarios_empleados u ON r.Usuario_Empleado_ID = u.ID
                  WHERE r.Estado = 'Aprobado'";
$result_aprobados = $conn->query($sql_aprobados);

// Consultar las solicitudes de Paz y Salvo rechazadas
$sql_rechazados = "SELECT r.*, u.Nombre, u.Apellido
                   FROM registro_paz_salvo r
                   INNER JOIN usuarios_empleados u ON r.Usuario_Empleado_ID = u.ID
                   WHERE r.Estado = 'Rechazado'";
$result_rechazados = $conn->query($sql_rechazados);

// Cerrar la conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recursos Humanos</title>
    <link rel="stylesheet" href="recurso_humano.css">
</head>
<body>
    <div class="container">
        <h2>Bienvenido, <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : ''; ?>!</h2>

        <h3>Solicitudes Pendientes de Paz y Salvo</h3>
        <table>
            <thead>
                <tr>
                    <th>Empleado</th>
                    <th>Fecha de Emisión</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $result_pendientes->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['Nombre']} {$row['Apellido']}</td>";
                    echo "<td>{$row['Fecha_Emision']}</td>";
                    echo "<td>";
                    echo "<form method='post'>";
                    echo "<input type='hidden' name='registro_id' value='{$row['ID']}'>";
                    echo "<button type='submit' name='accion' value='Aprobado'>Aprobar</button>";
                    echo "<button type='submit' name='accion' value='Rechazado'>Rechazar</button>";

                    // Mostrar campo de texto para la razón de rechazo si se rechaza el Paz y Salvo
                    if ($row['Estado'] === 'Pendiente') {
                        echo "<input type='text' name='razon_rechazo' placeholder='Razón de rechazo'>";
                    }

                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <h3>Solicitudes Aprobadas de Paz y Salvo</h3>
        <table>
            <thead>
                <tr>
                    <th>Empleado</th>
                    <th>Fecha de Emisión</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $result_aprobados->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['Nombre']} {$row['Apellido']}</td>";
                    echo "<td>{$row['Fecha_Emision']}</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <h3>Solicitudes Rechazadas de Paz y Salvo</h3>
        <table>
            <thead>
                <tr>
                    <th>Empleado</th>
                    <th>Fecha de Emisión</th>
                    <th>Razón de Rechazo</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $result_rechazados->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['Nombre']} {$row['Apellido']}</td>";
                    echo "<td>{$row['Fecha_Emision']}</td>";
                    echo "<td>{$row['Razon_Rechazo']}</td>";
                    echo "<td>";
                    echo "<form method='post'>";
                    echo "<input type='hidden' name='registro_id' value='{$row['ID']}'>";
                    // Botón para aprobar el Paz y Salvo
                    echo "<button type='submit' name='accion' value='Aprobado'>Aprobar</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <a href="../login/logout.php">Cerrar Sesión</a>
    </div>
</body>
</html>
