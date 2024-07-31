<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "pazysalvo_db";

    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Comprobar conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Validar y escapar los datos del formulario
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $nombre = htmlspecialchars($_POST['nombre']);
    $apellido = htmlspecialchars($_POST['apellido']);
    $nombreUsuario = htmlspecialchars($_POST['nombreUsuario']);
    $contrasena = isset($_POST['contrasena']) && $_POST['contrasena'] !== '' ? password_hash($_POST['contrasena'], PASSWORD_DEFAULT) : null;
    $rol = htmlspecialchars($_POST['rol']);
    $tipoDocumentoID = isset($_POST['tipo']) ? intval($_POST['tipo']) : 0;
    $documentoIdentidad = htmlspecialchars($_POST['numero']);
    $correoElectronico = htmlspecialchars($_POST['correo']);
    $fechaContratacion = isset($_POST['fechaContratacion']) ? $_POST['fechaContratacion'] : null;
    $fechaRetiro = isset($_POST['fechaRetiro']) ? $_POST['fechaRetiro'] : null;
    $departamentoID = isset($_POST['departamento']) ? intval($_POST['departamento']) : 0;

    // Iniciar una transacción
    $conn->begin_transaction();

    try {
        // Construir la consulta SQL
        $queryUsuarios = "UPDATE usuarios_empleados SET Nombre=?, Apellido=?, NombreUsuario=?, Rol=?, TipoDocumento_ID=?, DocumentoIdentidad=?, CorreoElectronico=?, FechaContratacion=?, FechaRetiro=?, Departamento_ID=?";

        // Añadir la columna Contrasena si es necesario
        if ($contrasena !== null) {
            $queryUsuarios .= ", Contrasena=?";
        }

        $queryUsuarios .= " WHERE ID=?";

        // Preparar la declaración
        $stmtUsuarios = $conn->prepare($queryUsuarios);

        // Verificar si la declaración fue preparada correctamente
        if ($stmtUsuarios === false) {
            throw new Exception("Error en la preparación de la declaración: " . $conn->error);
        }

        // Comprobar si se incluye la contraseña en los parámetros
        if ($contrasena !== null) {
            // Añadir la contraseña al final de la lista de parámetros
            $stmtUsuarios->bind_param("sssssssssssi", $nombre, $apellido, $nombreUsuario, $rol, $tipoDocumentoID, $documentoIdentidad, $correoElectronico, $fechaContratacion, $fechaRetiro, $departamentoID, $contrasena, $id);
        } else {
            // No se incluye la contraseña
            $stmtUsuarios->bind_param("ssssssssssi", $nombre, $apellido, $nombreUsuario, $rol, $tipoDocumentoID, $documentoIdentidad, $correoElectronico, $fechaContratacion, $fechaRetiro, $departamentoID, $id);
        }

        // Ejecutar la consulta
        if (!$stmtUsuarios->execute()) {
            throw new Exception("Error al actualizar los datos del usuario: " . $stmtUsuarios->error);
        }

        // Confirmar la transacción si todo fue exitoso
        $conn->commit();
        header('Location: admin.php'); // Redirección a admin.php
        exit();
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    // Cerrar las declaraciones y la conexión
    if (isset($stmtUsuarios)) {
        $stmtUsuarios->close();
    }
    $conn->close();
} else {
    echo "Acceso no autorizado";
}
?>
