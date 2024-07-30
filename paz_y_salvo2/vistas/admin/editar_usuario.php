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

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("SELECT u.*, t.TipoDocumento FROM Usuarios u
                            LEFT JOIN TipoDocumento t ON u.TipoDocumento_ID = t.ID
                            WHERE u.ID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result) {
        $usuario = $result->fetch_assoc();

        if (!$usuario) {
            echo "Usuario no encontrado";
            exit;
        }
    } else {
        echo "Error en la consulta: " . $conn->error;
        exit;
    }

    $stmt->close();
} else {
    echo "ID no válido";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="">
</head>
<body>
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Editar Usuario</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="admin.php">Lista de Usuarios</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../login/logout.php">Cerrar Sesión</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <h2 class="mt-4">Bienvenido, <?php echo isset($username) ? htmlspecialchars($username) : ''; ?>!</h2>
        <h3 class="mt-3">Editar Usuario</h3>
        <form action="guardar_edicion_usuario.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $usuario['ID']; ?>">
            
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo htmlspecialchars($usuario['Nombre']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido:</label>
                <input type="text" id="apellido" name="apellido" class="form-control" value="<?php echo htmlspecialchars($usuario['Apellido']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="nombreUsuario" class="form-label">Nombre de Usuario:</label>
                <input type="text" id="nombreUsuario" name="nombreUsuario" class="form-control" value="<?php echo htmlspecialchars($usuario['NombreUsuario']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="contrasena" class="form-label">Contraseña:</label>
                <input type="password" id="contrasena" name="contrasena" class="form-control" placeholder="Dejar en blanco para no cambiar">
            </div>

            <div class="mb-3">
                <label for="rol" class="form-label">Rol:</label>
                <select id="rol" name="rol" class="form-select" required>
                    <option value="empleado" <?php echo ($usuario['Rol'] === 'empleado') ? 'selected' : ''; ?>>Empleado</option>
                    <option value="administrador" <?php echo ($usuario['Rol'] === 'administrador') ? 'selected' : ''; ?>>Administrador</option>
                    <option value="recursos_humanos" <?php echo ($usuario['Rol'] === 'recursos_humanos') ? 'selected' : ''; ?>>Recursos Humanos</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo Documento:</label>
                <select id="tipo" name="tipo" class="form-select" required>
                    <option value="1" <?php echo ($usuario['TipoDocumento'] === 'Cedula') ? 'selected' : ''; ?>>NIT</option>
                    <option value="2" <?php echo ($usuario['TipoDocumento'] === 'Cedula Extranjeria') ? 'selected' : ''; ?>>Cedula Ciudadania</option>
                    <option value="3" <?php echo ($usuario['TipoDocumento'] === 'Cedula Extranjeria') ? 'selected' : ''; ?>>Cedula Extrajenria</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="Numero" class="form-label">Número Documento:</label>
                <input type="number" id="Numero" name="Numero" class="form-control" value="<?php echo htmlspecialchars($usuario['DocumentoIdentidad']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="correo" class="form-label">Correo Electrónico:</label>
                <input type='email' id="correo" name="correo" class="form-control" value="<?php echo htmlspecialchars($usuario['CorreoElectronico']); ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
