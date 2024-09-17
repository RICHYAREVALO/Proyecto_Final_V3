<?php
session_start();

// Datos de conexión a la base de datos
$servername = "bjgxtiqfs78pgiy7qzux-mysql.services.clever-cloud.com";
$username = "udb0mb339gpdtxkh";
$password = "0PRRJnHNJEdZdU9pCHYR";
$dbname = "bjgxtiqfs78pgiy7qzux";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: ../login/login.php');
    exit;
}

$username = $_SESSION['username'];

// Validate 'id' parameter
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare and execute the query
    $stmt = $conn->prepare("SELECT u.*, t.TipoDocumento FROM usuarios_empleados u
                            LEFT JOIN tipodocumento t ON u.TipoDocumento_ID = t.ID
                            WHERE u.ID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the user data
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

// Obtener la lista de departamentos
$departamentos = [];
$departamentoQuery = "SELECT ID, Nombre FROM departamentos";
$result = $conn->query($departamentoQuery);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $departamentos[] = $row;
    }
} else {
    echo "No se encontraron departamentos.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Add custom styles if needed */
        .logo {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .user-info {
            display: flex;
            align-items: center;
        }
        .user-icon, .logout-icon {
            margin-left: 10px;
            font-size: 1.2rem;
        }
        .main-nav {
            background-color: #f8f9fa;
            padding: 0.5rem;
        }
        .main-nav a.active {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="d-flex justify-content-between align-items-center py-2">
                <div class="logo">Sistema Usuarios</div>
                <link rel="stylesheet" href="admin.css">
                <div class="user-info">
                    Bogota, <?php echo date('d \d\e F \d\e Y'); ?> | <?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>
                    <span class="user-icon" aria-label="User Icon">👤</span>
                    <a href="../login/logout.php" class="logout-icon" aria-label="Logout">⏻</a>
                </div>
            </div>
        </div>
    </header>


    <div class="container mt-3">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">Editar Usuario</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
        </nav>

        <h2 class="mt-4">Bienvenido, <?php echo htmlspecialchars($username); ?>!</h2>
        <h3 class="mt-3">Editar Usuario</h3>
        <form action="guardar_edicion_usuario.php" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($usuario['ID']); ?>">

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
                    <?php foreach ($departamentos as $departamento): ?>
                        <option value="<?php echo htmlspecialchars($departamento['ID']); ?>"
                            <?php echo ($departamento['ID'] === intval($usuario['TipoDocumento_ID'])) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($departamento['Nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="numero" class="form-label">Número Documento:</label>
                <input type="text" id="numero" name="numero" class="form-control" value="<?php echo htmlspecialchars($usuario['DocumentoIdentidad']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="correo" class="form-label">Correo Electrónico:</label>
                <input type="email" id="correo" name="correo" class="form-control" value="<?php echo htmlspecialchars($usuario['CorreoElectronico']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="fechaContratacion" class="form-label">Fecha de Contratación:</label>
                <input type="date" id="fechaContratacion" name="fechaContratacion" class="form-control" value="<?php echo htmlspecialchars($usuario['FechaContratacion']); ?>">
            </div>

            <div class="mb-3">
                <label for="fechaRetiro" class="form-label">Fecha de Retiro:</label>
                <input type="date" id="fechaRetiro" name="fechaRetiro" class="form-control" value="<?php echo htmlspecialchars($usuario['FechaRetiro']); ?>">
            </div>

            <div class="mb-3">
                <label for="departamento" class="form-label">Departamento:</label>
                <select id="departamento" name="departamento" class="form-select" required>
                    <?php foreach ($departamentos as $departamento): ?>
                        <option value="<?php echo htmlspecialchars($departamento['ID']); ?>"
                            <?php echo ($departamento['ID'] === intval($usuario['Departamento_ID'])) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($departamento['Nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
