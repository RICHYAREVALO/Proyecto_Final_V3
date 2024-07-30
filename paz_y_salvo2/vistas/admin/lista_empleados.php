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
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];

$sql = "SELECT b.ID, b.Nombre, b.Apellido, b.DocumentoIdentidad, c.Nombre_Departamento, b.Fecha_Contratacion,
b.Fecha_retiro, b.Otros_Detalles, a.NombreUsuario  
FROM empleados b
LEFT JOIN usuarios a ON a.ID = b.Usuario_ID
LEFT JOIN departamentos c ON b.Departamento_ID=c.ID";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $empleados = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $empleados = array();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empleados</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Estilos personalizados -->
    <style>
        .custom-img {
            width: 300px; /* Cambia el tamaño de la imagen según lo necesites */
            height: 300px; /* Cambia la altura de la imagen según lo necesites */
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Empleados Beyonder</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                    <a class="nav-link" href="admin.php">Lista de Usuarios</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="../departamentos/listar_departamentos.php">Lista de Departamentos</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="../login/logout.php">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container text-center">
        <img src="../../imagen/beyonder.jpeg" alt="Imagen de fondo" class="img-fluid rounded mb-4 custom-img">
    </div>
        <h2>Bienvenido, <?php echo isset($username) ? htmlspecialchars($username) : ''; ?>!</h2>
        <h3>Lista de Empleados</h3>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <?php
                    // Mostrar encabezados de columnas basados en las claves del primer registro
                    if (!empty($empleados)) {
                        foreach ($empleados[0] as $key => $value) {
                            echo '<th>' . htmlspecialchars($key) . '</th>';
                        }
                    }
                    ?>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($empleados as $empleado) : ?>
                    <tr>
                        <?php
                        // Mostrar datos de empleados
                        foreach ($empleado as $value) {
                            echo '<td>' . htmlspecialchars($value) . '</td>';
                        }
                        ?>
                        <td>
                            <!-- Puedes agregar enlaces de acciones según sea necesario -->
                            <a href="editar_empleado.php?id=<?php echo htmlspecialchars($empleado['ID']); ?>" class="btn btn-primary">Editar</a>
                            <a href="eliminar_empleado.php?id=<?php echo htmlspecialchars($empleado['ID']); ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que quieres eliminar este empleado?')">Eliminar</a> 
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <!-- Puedes agregar enlaces para acciones adicionales -->
        <!-- <a href="crear_empleado.php">Crear Nuevo Empleado</a> -->
    </div>

    <!-- Bootstrap JS y jQuery (necesario para Bootstrap) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
