<?php
session_start();
// Datos de conexión a la base de datos
$servername = "bjgxtiqfs78pgiy7qzux-mysql.services.clever-cloud.com";
$username = "udb0mb339gpdtxkh";
$password = "0PRRJnHNJEdZdU9pCHYR";
$dbname = "bjgxtiqfs78pgiy7qzux";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];

// Inicializar variables de búsqueda
$search = isset($_POST['search']) ? trim($_POST['search']) : '';

// Consulta SQL con filtro de búsqueda
$sql = "SELECT u.*, t.tipodocumento, d.Nombre AS NombreDepartamento
FROM usuarios_empleados u
LEFT JOIN tipodocumento t ON u.tipodocumento_ID = t.ID
LEFT JOIN departamentos d ON u.departamento_ID = d.ID
WHERE u.Nombre LIKE ? 
   OR u.Apellido LIKE ? 
   OR u.NombreUsuario LIKE ?;
";

// Preparar y ejecutar la consulta
$stmt = $conn->prepare($sql);
$search_param = "%$search%";
$stmt->bind_param("sss", $search_param, $search_param, $search_param);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $usuarios = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $usuarios = array();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios Beyonder</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tu archivo CSS personalizado -->
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                Usuarios Beyonder
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
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
    
    <h2 class="mt-4">Bienvenido, <?php echo isset($username) ? htmlspecialchars($username) : ''; ?>!</h2>
    
    <h3 class="mt-3">Lista de Usuarios</h3>

    <!-- Formulario de búsqueda -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Buscar por nombre, apellido o usuario" value="<?php echo htmlspecialchars($search); ?>">
            <button class="btn btn-primary" type="submit">Buscar</button>
        </div>
        <br>
        <br>
    </form>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Documento Identidad</th>
                <th>Area</th>
                <th>Nombre Usuario</th>
                <th>Contraseña</th>
                <th>Rol</th>
                <th>Tipo Documento</th>
                <th>Correo Electrónico</th>
                <th>Fecha Contratación</th>
                <th>Fecha Retiro</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($usuario['ID']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['Nombre']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['Apellido']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['DocumentoIdentidad']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['NombreDepartamento']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['NombreUsuario']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['Contrasena']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['Rol']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['tipodocumento']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['CorreoElectronico']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['FechaContratacion']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['FechaRetiro']); ?></td>
                    <td>
                        <a href="editar_usuario.php?id=<?php echo $usuario['ID']; ?>" class="btn btn-primary">Editar</a>
                        <a href="eliminar_usuario.php?id=<?php echo $usuario['ID']; ?>" class="btn btn-danger">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="crear_usuario.php" class="btn btn-success">Crear Nuevo Usuario</a>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>