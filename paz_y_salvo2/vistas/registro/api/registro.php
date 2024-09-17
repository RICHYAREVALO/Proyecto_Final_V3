<?php
// Habilitar la depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Datos de conexión a la base de datos
$servername = "bjgxtiqfs78pgiy7qzux-mysql.services.clever-cloud.com";
$username = "udb0mb339gpdtxkh";
$password = "0PRRJnHNJEdZdU9pCHYR";
$dbname = "bjgxtiqfs78pgiy7qzux";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    echo json_encode(["error" => "Error de conexión: " . $conn->connect_error]);
    exit;
}

// Obtener los datos del formulario
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$nombreUsuario = $_POST['nombre_usuario'];
$contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
$tipoDocumento = $_POST['tipo_documento'];
$documentoIdentidad = $_POST['documento_identidad'];
$correoElectronico = $_POST['correo_electronico'];
$departamento = $_POST['departamento'];
$fechaContratacion = $_POST['fecha_contratacion'];

// Función para validar unicidad
function validarUnicidad($conn, $campo, $valor) {
    $sql = "SELECT COUNT(*) FROM usuarios_empleados WHERE $campo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $valor);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    return $count == 0;
}

// Verificar unicidad del nombre de usuario
if (!validarUnicidad($conn, 'NombreUsuario', $nombreUsuario)) {
    echo json_encode(['error' => 'El nombre de usuario ya está en uso.']);
    exit;
}

// Verificar unicidad del correo electrónico
if (!validarUnicidad($conn, 'CorreoElectronico', $correoElectronico)) {
    echo json_encode(['error' => 'El correo electrónico ya está en uso.']);
    exit;
}

// Verificar unicidad del documento de identidad
if (!validarUnicidad($conn, 'DocumentoIdentidad', $documentoIdentidad)) {
    echo json_encode(['error' => 'El documento de identidad ya está en uso.']);
    exit;
}

// Comprobar si la carpeta de uploads existe y es escribible
$uploadDir = '../../empleado/uploads/';
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0777, true)) {
        echo json_encode(["error" => "No se pudo crear la carpeta de uploads."]);
        exit;
    }
}

if (!is_writable($uploadDir)) {
    echo json_encode(["error" => "La carpeta de uploads no es escribible."]);
    exit;
}

// Procesar el archivo de imagen
$fotoPerfilRuta = '';
if (isset($_FILES['fotoPerfil'])) {
    $fotoPerfil = $_FILES['fotoPerfil'];
    $fotoPerfilRuta = $uploadDir . basename($fotoPerfil['name']);
    if (!move_uploaded_file($fotoPerfil['tmp_name'], $fotoPerfilRuta)) {
        echo json_encode(["error" => "Error al mover el archivo cargado."]);
        exit;
    }
}

// Preparar y ejecutar la consulta
$sql = "INSERT INTO usuarios_empleados (Nombre, Apellido, NombreUsuario, Contrasena, Rol, TipoDocumento_ID, DocumentoIdentidad, CorreoElectronico, Departamento_ID, FechaContratacion, FotoPerfil) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["error" => "Error en la preparación de la consulta: " . $conn->error]);
    exit;
}

$rol = 'empleado'; // Asumiendo que el rol es fijo
$stmt->bind_param('sssssiisiss', $nombre, $apellido, $nombreUsuario, $contrasena, $rol, $tipoDocumento, $documentoIdentidad, $correoElectronico, $departamento, $fechaContratacion, $fotoPerfilRuta);

if ($stmt->execute()) {
    echo json_encode(["success" => "Usuario registrado con éxito."]);
} else {
    echo json_encode(["error" => "Error al registrar el usuario: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
