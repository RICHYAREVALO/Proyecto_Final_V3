<?php
session_start();

// Verifica si el formulario se ha enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexión a la base de datos
    $servername = "bkqu3uk3ewxyehltqf2t-mysql.services.clever-cloud.com";
$username = "uwwounruhaizndvh";
$password = "91JGBP3BP37TC6be2NIi";
$dbname = "bkqu3uk3ewxyehltqf2t";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Función para limpiar la entrada
    function limpiar_entrada($datos) {
        $datos = trim($datos);
        $datos = stripslashes($datos);
        $datos = htmlspecialchars($datos);
        return $datos;
    }

    // Recibe y limpia los datos del formulario
    $nombre = limpiar_entrada($_POST['nombre']);
    $apellido = limpiar_entrada($_POST['apellido']);
    $nombreUsuario = limpiar_entrada($_POST['nombre_usuario']);
    $contraseña = limpiar_entrada($_POST['contrasena']);
    $confirmarContraseña = limpiar_entrada($_POST['confirmar_contraseña']);
    $tipoDocumentoID = limpiar_entrada($_POST['tipo_documento']);
    $documentoIdentidad = limpiar_entrada($_POST['documento_identidad']);
    $correoElectronico = limpiar_entrada($_POST['correo_electronico']);
    $departamentoID = limpiar_entrada($_POST['departamento']);
    $fechaContratacion = limpiar_entrada($_POST['fecha_contratacion']);

    // Verifica que las contraseñas coincidan
    if ($contraseña !== $confirmarContraseña) {
        echo "<script>alert('Las contraseñas no coinciden. Por favor, inténtalo de nuevo.');window.location.href = 'registro.html';</script>";
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
        echo "<script>alert('Error: Ya existe un usuario con el nombre de usuario proporcionado.');window.location.href = 'registro.html';</script>";
        $stmt->close();
        $conn->close();
        exit;
    } else {
        // Verifica si ya existe un usuario con el mismo número de documento
        $sql_verificar_documento = "SELECT * FROM usuarios_empleados WHERE DocumentoIdentidad = ?";
        $stmt = $conn->prepare($sql_verificar_documento);
        $stmt->bind_param("s", $documentoIdentidad);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "<script>alert('Error: Ya existe un usuario con el número de documento proporcionado.');window.location.href = 'registro.html';</script>";
            $stmt->close();
            $conn->close();
            exit;
        } else {
            // Manejo de la foto de perfil
$fotoPerfilRuta = NULL;
if (isset($_FILES['fotoPerfil']) && $_FILES['fotoPerfil']['error'] == UPLOAD_ERR_OK) {
    // Cambia la ruta de carga para que esté en la carpeta 'empleados/uploads'
    $uploadDir = '../empleado/uploads/';
    $fotoPerfilRuta = $uploadDir . basename($_FILES['fotoPerfil']['name']);
    
    // Mueve el archivo cargado al directorio de destino
    if (!move_uploaded_file($_FILES['fotoPerfil']['tmp_name'], $fotoPerfilRuta)) {
        die("Error al mover el archivo cargado.");
    }
}

            $rol = 'empleado'; // Rol predeterminado para los usuarios

            // Prepara y ejecuta la consulta SQL utilizando sentencias preparadas
            $sql = "INSERT INTO usuarios_empleados (Nombre, Apellido, NombreUsuario, Contrasena, Rol, TipoDocumento_ID, DocumentoIdentidad, CorreoElectronico, Departamento_ID, FechaContratacion, FotoPerfil) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);

            if ($stmt) {
                // Vincula los parámetros
                $stmt->bind_param("sssssiisiss", $nombre, $apellido, $nombreUsuario, $hashContraseña, $rol, $tipoDocumentoID, $documentoIdentidad, $correoElectronico, $departamentoID, $fechaContratacion, $fotoPerfilRuta);

                // Ejecuta la consulta
                if ($stmt->execute()) {
                    // Redirige al usuario después de completar el registro
                    header("Location: registro_confirmado.php");
                    exit;
                } else {
                    echo "Error al registrar usuario: " . $stmt->error;
                }

                // Cierra la sentencia preparada
                $stmt->close();
            } else {
                echo "Error en la preparación de la consulta: " . $conn->error;
            }
        }
    }

    // Cierra la conexión a la base de datos
    $conn->close();
} else {
    // Si el formulario no se ha enviado, redirige a la página de registro
    header("Location: registro.html");
    exit;
}
?>
