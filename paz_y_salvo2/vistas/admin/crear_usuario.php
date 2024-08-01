<?php
session_start();

// Conexión a la base de datos
$servername = "bkqu3uk3ewxyehltqf2t-mysql.services.clever-cloud.com";
$username = "uwwounruhaizndvh";
$password = "91JGBP3BP37TC6be2NIi";
$dbname = "bkqu3uk3ewxyehltqf2t";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener departamentos
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

// Procesar formulario si se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitizar y validar las entradas del usuario
    $nombre = filter_var(trim($_POST['nombre']), FILTER_SANITIZE_STRING);
    $apellido = filter_var(trim($_POST['apellido']), FILTER_SANITIZE_STRING);
    $nombreUsuario = filter_var(trim($_POST['nombreUsuario']), FILTER_SANITIZE_STRING);
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
    $rol = filter_var(trim($_POST['rol']), FILTER_SANITIZE_STRING);
    $TipoDocumentoID = filter_var($_POST['tipo_documento'], FILTER_SANITIZE_NUMBER_INT);
    $DocumentoIdentidad = filter_var(trim($_POST['DocumentoIdentidad']), FILTER_SANITIZE_STRING);
    $CorreoElectronico = filter_var(trim($_POST['CorreoElectronico']), FILTER_VALIDATE_EMAIL);
    $FechaContratacion = $_POST['FechaContratacion'];
    $FechaRetiro = isset($_POST['FechaRetiro']) ? $_POST['FechaRetiro'] : null;
    $DepartamentoID = filter_var($_POST['departamento'], FILTER_SANITIZE_NUMBER_INT);

    if (!$CorreoElectronico) {
        echo "Correo electrónico inválido.";
        exit;
    }

    // Validación adicional para fechas
    if (!DateTime::createFromFormat('Y-m-d', $FechaContratacion)) {
        echo "Fecha de contratación inválida.";
        exit;
    }
    if ($FechaRetiro && !DateTime::createFromFormat('Y-m-d', $FechaRetiro)) {
        echo "Fecha de retiro inválida.";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO usuarios_empleados (Nombre, Apellido, NombreUsuario, Contrasena, Rol, TipoDocumento_ID, DocumentoIdentidad, CorreoElectronico, FechaContratacion, FechaRetiro, Departamento_ID) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssss", $nombre, $apellido, $nombreUsuario, $contrasena, $rol, $TipoDocumentoID, $DocumentoIdentidad, $CorreoElectronico, $FechaContratacion, $FechaRetiro, $DepartamentoID);

    if ($stmt->execute()) {
        header("Location: admin.php?success=1");
        exit;
    } else {
        echo "Error al agregar nuevo usuario: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nuevo Usuario</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h3 class="mt-4">Crear Nuevo Usuario</h3>
        <?php
        if (isset($_GET['success']) && $_GET['success'] == 1) {
            echo '<div class="alert alert-success">Usuario creado exitosamente.</div>';
        }
        ?>
        <form action="crear_usuario.php" method="post">

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" id="nombre" name="nombre" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido:</label>
                <input type="text" id="apellido" name="apellido" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="nombreUsuario" class="form-label">Nombre de Usuario:</label>
                <input type="text" id="nombreUsuario" name="nombreUsuario" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="contrasena" class="form-label">Contraseña:</label>
                <input type="password" id="contrasena" name="contrasena" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="rol" class="form-label">Rol:</label>
                <select id="rol" name="rol" class="form-select" required>
                    <option value="empleado">Empleado</option>
                    <option value="administrador">Administrador</option>
                    <option value="recursos_humanos">Recursos Humanos</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="tipo_documento" class="form-label">Tipo Documento:</label>
                <select id="tipo_documento" name="tipo_documento" class="form-select" required>
                    <option value="1">NIT</option>
                    <option value="2">Cédula de Ciudadanía</option>
                    <option value="3">Cédula Extranjera</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="DocumentoIdentidad" class="form-label">Número Documento:</label>
                <input type="text" id="DocumentoIdentidad" name="DocumentoIdentidad" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="CorreoElectronico" class="form-label">Correo Electrónico:</label>
                <input type="email" id="CorreoElectronico" name="CorreoElectronico" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="FechaContratacion" class="form-label">Fecha de Contratación:</label>
                <input type="date" id="FechaContratacion" name="FechaContratacion" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="FechaRetiro" class="form-label">Fecha de Retiro:</label>
                <input type="date" id="FechaRetiro" name="FechaRetiro" class="form-control">
            </div>

            <div class="mb-3">
                <label for="departamento" class="form-label">Departamento:</label>
                <select id="departamento" name="departamento" class="form-select" required>
                    <?php foreach ($departamentos as $departamento): ?>
                        <option value="<?php echo htmlspecialchars($departamento['ID']); ?>">
                            <?php echo htmlspecialchars($departamento['Nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Crear Usuario</button>
        </form>
        <a href="admin.php" class="mt-3 btn btn-secondary">Volver a la lista de usuarios</a>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
