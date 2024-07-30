<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $servername = "localhost";
$username = "root";
$password = "";
$dbname = "paz_y_salvo2";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $nombreUsuario = $_POST['nombreUsuario'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
    $rol = $_POST['rol'];
    $TipoDocumentoID = $_POST['tipo_documento'];
    $DocumentoIdentidad = $_POST['DocumentoIdentidad'];
    $CorreoElectronico = $_POST['CorreoElectronico'];

    $stmt = $conn->prepare("INSERT INTO Usuarios (Nombre, Apellido, NombreUsuario, Contraseña, Rol, TipoDocumento_ID, DocumentoIdentidad, CorreoElectronico) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $nombre, $apellido, $nombreUsuario, $contrasena, $rol, $TipoDocumentoID, $DocumentoIdentidad, $CorreoElectronico);

    if ($stmt->execute()) {
        
        header("Location: admin.php");
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
    <link rel="stylesheet" href="">
</head>
<body>
    <div class="container">
        <h3 class="mt-4">Crear Nuevo Usuario</h3>
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
                    <option value="2">Cedula de Ciudadania</option>
                    <option value="3">Cedula Extrajenria</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="DocumentoIdentidad" class="form-label">Número Documento:</label>
                <input type="number" id="DocumentoIdentidad" name="DocumentoIdentidad" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="CorreoElectronico" class="form-label">Correo Electrónico</label>
                <input type='email' id="CorreoElectronico" name="CorreoElectronico" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Crear Usuario</button>
        </form>
        <a href="admin.php" class="mt-3">Volver a la lista de usuarios</a>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
