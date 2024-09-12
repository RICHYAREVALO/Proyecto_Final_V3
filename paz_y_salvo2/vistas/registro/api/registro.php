<?php
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

// Obtener y decodificar los datos JSON
$data = json_decode(file_get_contents("php://input"), true);

// Función para limpiar la entrada
function limpiar_entrada($datos) {
    return htmlspecialchars(stripslashes(trim($datos)));
}

// Recibe y limpia los datos del formulario
$nombre = limpiar_entrada($data['nombre']);
$apellido = limpiar_entrada($data['apellido']);
$nombreUsuario = limpiar_entrada($data['nombre_usuario']);
$contraseña = limpiar_entrada($data['contrasena']);
$confirmarContraseña = limpiar_entrada($data['confirmar_contraseña']);
$tipoDocumentoID = limpiar_entrada($data['tipo_documento']);
$documentoIdentidad = limpiar_entrada($data['documento_identidad']);
$correoElectronico = limpiar_entrada($data['correo_electronico']);
$departamentoID = limpiar_entrada($data['departamento']);
$fechaContratacion = limpiar_entrada($data['fecha_contratacion']);

// Verifica que las contraseñas coincidan
if ($contraseña !== $confirmarContraseña) {
    echo json_encode(["error" => "Las contraseñas no coinciden."]);
    exit;
}

// Hash de la contraseña
$hashContraseña = password_hash($contraseña, PASSWORD_DEFAULT);

// Verifica si ya existe un usuario con el mismo nombre de usuario
$sql_verificar_usuario = "SELECT * FROM usuarios_empleados WHERE NombreUsuario = ?";
$stmt = $conn->prepare($sql_verificar_usuario);
$stmt->bind_param("s", $nombreUsuario);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(["error" => "Ya existe un usuario con el nombre de usuario proporcionado."]);
    $stmt->close();
    $conn->close();
    exit;
}

// Verifica si ya existe un usuario con el mismo número de documento
$sql_verificar_documento = "SELECT * FROM usuarios_empleados WHERE DocumentoIdentidad = ?";
$stmt = $conn->prepare($sql_verificar_documento);
$stmt->bind_param("s", $documentoIdentidad);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(["error" => "Ya existe un usuario con el número de documento proporcionado."]);
    $stmt->close();
    $conn->close();
    exit;
}

// Manejo de la foto de perfil
$fotoPerfilRuta = NULL;
if (isset($_FILES['fotoPerfil']) && $_FILES['fotoPerfil']['error'] == UPLOAD_ERR_OK) {
    $uploadDir = '../empleado/uploads/';
    $fotoPerfilNombre = basename($_FILES['fotoPerfil']['name']);
    $fotoPerfilRuta = $uploadDir . $fotoPerfilNombre;

    // Validación del archivo
    $validExtensiones = ['jpg', 'jpeg', 'png', 'gif'];
    $extension = strtolower(pathinfo($fotoPerfilNombre, PATHINFO_EXTENSION));

    if (!in_array($extension, $validExtensiones) || $_FILES['fotoPerfil']['size'] > 2000000) {
        echo json_encode(["error" => "Formato de archivo no válido o tamaño excesivo."]);
        exit;
    }

    if (!move_uploaded_file($_FILES['fotoPerfil']['tmp_name'], $fotoPerfilRuta)) {
        echo json_encode(["error" => "Error al mover el archivo cargado."]);
        exit;
    }
}

// Prepara y ejecuta la consulta SQL
$sql = "INSERT INTO usuarios_empleados (Nombre, Apellido, NombreUsuario, Contrasena, Rol, TipoDocumento_ID, DocumentoIdentidad, CorreoElectronico, Departamento_ID, FechaContratacion, FotoPerfil) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if ($stmt) {
    $rol = 'empleado';
    $stmt->bind_param("sssssiisiss", $nombre, $apellido, $nombreUsuario, $hashContraseña, $rol, $tipoDocumentoID, $documentoIdentidad, $correoElectronico, $departamentoID, $fechaContratacion, $fotoPerfilRuta);

    if ($stmt->execute()) {
        echo json_encode(["success" => "Registro exitoso."]);
    } else {
        echo json_encode(["error" => "Error al registrar usuario: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "Error en la preparación de la consulta: " . $conn->error]);
}
/*link api registro
http://localhost/Proyecto_Final_V3/paz_y_salvo2/vistas/registro/api/registro.php
{
  "nombre": "Juan",
  "apellido": "Pérez",
  "nombre_usuario": "juanperez",
  "contrasena": "123456",
  "confirmar_contraseña": "123456",
  "tipo_documento": "2",
  "documento_identidad": "12345678",
  "correo_electronico": "juan@example.com",
  "departamento": "3",
  "fecha_contratacion": "2024-09-11"
}*/
$conn->close();
?>
