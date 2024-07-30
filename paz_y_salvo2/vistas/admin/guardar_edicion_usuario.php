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

    // Validar y escapar los datos del formulario
    $id = (isset($_POST['id'])) ? intval($_POST['id']) : 0;
    $nombre = htmlspecialchars($_POST['nombre']);
    $apellido = htmlspecialchars($_POST['apellido']);
    $nombreUsuario = htmlspecialchars($_POST['nombreUsuario']);
    $contrasena = ($_POST['contrasena'] !== '') ? password_hash($_POST['contrasena'], PASSWORD_DEFAULT) : null;
    $rol = htmlspecialchars($_POST['rol']);
    $tipoDocumentoID = (isset($_POST['tipo'])) ? intval($_POST['tipo']) : 0;
    $documentoIdentidad = htmlspecialchars($_POST['Numero']);
    $correoElectronico = htmlspecialchars($_POST['correo']);

    // Iniciar una transacción
    $conn->begin_transaction();

    try {
        // Actualizar en la tabla Usuarios
        $stmtUsuarios = $conn->prepare("UPDATE Usuarios SET Nombre=?, Apellido=?, NombreUsuario=?, Contraseña=?, Rol=?, TipoDocumento_ID=?, DocumentoIdentidad=?, CorreoElectronico=? WHERE ID=?");
        $stmtUsuarios->bind_param("ssssssssi", $nombre, $apellido, $nombreUsuario, $contrasena, $rol, $tipoDocumentoID, $documentoIdentidad, $correoElectronico, $id);

        if (!$stmtUsuarios->execute()) {
            throw new Exception("Error al actualizar los datos del usuario: " . $stmtUsuarios->error);
        }

        // Actualizar en la tabla Empleados
        $stmtEmpleados = $conn->prepare("UPDATE Empleados e JOIN Usuarios u ON e.Usuario_ID = u.ID SET e.DocumentoIdentidad = ?, e.Nombre = ?, e.Apellido = ? WHERE u.ID = ?");
        $stmtEmpleados->bind_param("sssi", $documentoIdentidad, $nombre, $apellido, $id);

        if (!$stmtEmpleados->execute()) {
            throw new Exception("Error al actualizar los datos del empleado: " . $stmtEmpleados->error);
        }

        // Confirmar la transacción si todo fue exitoso
        $conn->commit();
        echo "Datos del usuario y del empleado actualizados correctamente.";
        header('Location: admin.php'); // Redirección a admin.php
        exit();
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    // Cerrar las declaraciones y la conexión
    $stmtUsuarios->close();
    $stmtEmpleados->close();
    $conn->close();
} else {
    echo "Acceso no autorizado";
}
?>
