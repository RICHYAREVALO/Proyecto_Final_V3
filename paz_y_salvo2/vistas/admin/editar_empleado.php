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

// Verificar si se ha proporcionado un ID válido para editar
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $empleado_id = $_GET['id'];

    // Obtener los datos del empleado a editar
    $sql = "SELECT b.ID, b.Nombre, b.Apellido, b.DocumentoIdentidad, c.Nombre_Departamento, b.Fecha_Contratacion,
            b.Fecha_retiro, b.Otros_Detalles, a.NombreUsuario  
            FROM empleados b
            LEFT JOIN usuarios a ON a.ID = b.Usuario_ID
            LEFT JOIN departamentos c ON b.Departamento_ID=c.ID
            WHERE b.ID = $empleado_id";
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
    <title>Editar Empleado</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="lista_empleados.css">
</head>
<body>
    <div class="container">
<<<<<<< HEAD:paz_y_salvo/vistas/admin/editar_empleado.php
        <nav>
            <ul class="menu">
                <li><a href="../empleado/empleados.php">Lista de Empleados</a></li>
                <li><a href="admin.php">Lista de Usuarios</a></li>
                <li><a href="../login/logout.php">Cerrar Sesión</a></li>
            </ul>
=======
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">Sistema de Gestión de Empleados</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="empleados.php">Lista de Empleados</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="usuarios.php">Lista de Usuarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
>>>>>>> 8368e0b0d83f1043bf12bb79376ddbafe3076c99:paz_y_salvo/editar_empleado.php
        </nav>
        <h2>Bienvenido, <?php echo isset($username) ? htmlspecialchars($username) : ''; ?>!</h2>
        <h3>Editar Empleado</h3>
        <form action="guardar_edicion_empleado.php" method="post">
            <!-- Agrega campos del formulario con los datos del empleado -->
            <input type="hidden" name="empleado_id" value="<?php echo $empleado['ID']; ?>">
            
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" class="form-control" name="nombre" value="<?php echo htmlspecialchars($empleado['Nombre']); ?>">
            </div>
            
            <div class="form-group">
                <label for="apellido">Apellido:</label>
                <input type="text" class="form-control" name="apellido" value="<?php echo htmlspecialchars($empleado['Apellido']); ?>">
            </div>
            
            <div class="form-group">
                <label for="documento">Documento Identidad:</label>
                <input type="text" class="form-control" name="documento" value="<?php echo htmlspecialchars($empleado['DocumentoIdentidad']); ?>">
            </div>

            <div class="form-group">
                <label for="nombre_departamento">Nombre del Departamento:</label>
                <input type="text" class="form-control" name="nombre_departamento" value="<?php echo htmlspecialchars($empleado['Nombre_Departamento']); ?>">
            </div>

            <div class="form-group">
                <label for="fecha_contratacion">Fecha de Contratación:</label>
                <input type="date" class="form-control" name="fecha_contratacion" value="<?php echo htmlspecialchars($empleado['Fecha_Contratacion']); ?>">
            </div>

            <div class="form-group">
                <label for="fecha_retiro">Fecha de Retiro:</label>
                <input type="date" class="form-control" name="fecha_retiro" value="<?php echo htmlspecialchars($empleado['Fecha_retiro']); ?>">
            </div>

            <div class="form-group">
                <label for="otros_detalles">Otros Detalles:</label>
                <textarea class="form-control" name="otros_detalles"><?php echo htmlspecialchars($empleado['Otros_Detalles']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="nombre_usuario">Nombre de Usuario:</label>
                <input type="text" class="form-control" name="nombre_usuario" value="<?php echo htmlspecialchars($empleado['NombreUsuario']); ?>">
            </div>
            
            <!-- Agrega más campos según la estructura de tu tabla -->

            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
    </div>

    <!-- Bootstrap JS y jQuery (necesario para Bootstrap) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
