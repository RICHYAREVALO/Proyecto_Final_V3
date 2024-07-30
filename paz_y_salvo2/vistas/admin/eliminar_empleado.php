<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "paz_y_salvo2";;

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if (!isset($_SESSION['username'])) {
    header('Location: ../login/login.php');
    exit;
}

$username = $_SESSION['username'];

// Verificar si se ha proporcionado un ID válido para eliminar
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $empleado_id = $_GET['id'];

    // Obtener los datos del empleado a eliminar
    $sql = "SELECT ID, Nombre, Apellido FROM empleados WHERE ID = $empleado_id";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $empleado = $result->fetch_assoc();
    } else {
        // Redirigir si el empleado no se encuentra
        header('Location: ../empleado/empleados.php');
        exit;
    }
} else {
    // Redirigir si no se proporciona un ID válido
    header('Location: ../empleado/empleados.php');
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Empleado</title>
    <!-- Puedes enlazar aquí el mismo archivo CSS que utilizas para la lista de empleados -->
    <link rel="stylesheet" href="lista_empleados.css">
</head>
<body>
    <div class="container">
        <nav>
            <ul class="menu">
                <li><a href="../empleado/empleados.php">Lista de Empleados</a></li>
                <li><a href="admin.php">Lista de Usuarios</a></li>
                <li><a href="../login/logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
        <h2>Bienvenido, <?php echo isset($username) ? htmlspecialchars($username) : ''; ?>!</h2>
        <h3>Eliminar Empleado</h3>
        <p>¿Estás seguro de que quieres eliminar al empleado "<?php echo htmlspecialchars($empleado['Nombre'] . ' ' . $empleado['Apellido']); ?>"?</p>
        <form action="procesar_eliminacion_empleado.php" method="post">
            <input type="hidden" name="empleado_id" value="<?php echo $empleado['ID']; ?>">
            <button type="submit">Eliminar</button>
            <a href="../empleado/empleados.php">Cancelar</a>
        </form>
    </div>
</body>
</html>
