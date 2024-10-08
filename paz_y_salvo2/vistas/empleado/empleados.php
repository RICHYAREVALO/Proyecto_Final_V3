<?php
session_start();

// Variables de conexión a la base de datos
$servername = "bjgxtiqfs78pgiy7qzux-mysql.services.clever-cloud.com";
$username = "udb0mb339gpdtxkh";
$password = "0PRRJnHNJEdZdU9pCHYR";
$dbname = "bjgxtiqfs78pgiy7qzux";

// Establecer la conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Validar la sesión antes de acceder a los datos del usuario
if (!isset($_SESSION['username'])) {
    header('Location: ../login/login.php');
    exit;
}

// Obtener el nombre de usuario de la sesión
$username = $_SESSION['username'];

// Preparar una declaración SQL para obtener los datos del empleado asociado al usuario
$stmt = $conn->prepare("SELECT ID, Nombre, Apellido, DocumentoIdentidad, NombreUsuario, CorreoElectronico, FotoPerfil 
                        FROM usuarios_empleados 
                        WHERE NombreUsuario = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result_obtener_empleado = $stmt->get_result();

// Verificar si hubo un error en la consulta
if ($result_obtener_empleado === false) {
    echo "Error al ejecutar la consulta para obtener los datos del empleado: " . $conn->error;
    exit;
}

// Verificar si se encontraron datos del empleado
if ($result_obtener_empleado->num_rows > 0) {
    $empleado = $result_obtener_empleado->fetch_assoc();

    // Obtener los datos del empleado
    $nombreEmpleado = htmlspecialchars($empleado['Nombre']);
    $apellidoEmpleado = htmlspecialchars($empleado['Apellido']);
    $documentoIdentidadEmpleado = htmlspecialchars($empleado['DocumentoIdentidad']);
    $id_empleado = htmlspecialchars($empleado['ID']);
    $fotoPerfil = htmlspecialchars($empleado['FotoPerfil']);

    // Ruta base de la carpeta de fotos de perfil
    $baseUrl = 'http://localhost/Proyecto_Final_V3/paz_y_salvo2/vistas/empleado/uploads/';
    $fotoPerfilUrl = $fotoPerfil ? $baseUrl . basename($fotoPerfil) : $baseUrl . 'default-profile.png';
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Empleados</title>
        <link rel="stylesheet" href="empleados.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
    </head>
    <body>
        <br> <br> <br> <br> <br> <br>
        <div class="container">
            <h2>
                <div class="user-info">
                    Bienvenido, <?php echo $nombreEmpleado . " " . $apellidoEmpleado; ?>!
                    <img src="../../imagen/iconos/user.png" alt="Usuario" class="edit-icon">
                </div>
            </h2>
            <div class="text-center">
                <!-- Mostrar la foto de perfil -->
                <img src="<?php echo $fotoPerfilUrl; ?>" alt="Foto de Perfil" class="img-thumbnail" style="max-width: 150px;">
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Documento de Identidad</th>
                        <th>Acciones</th>
                    </tr>
                    <tr>
                        <td><?php echo $nombreEmpleado; ?></td>
                        <td><?php echo $apellidoEmpleado; ?></td>
                        <td><?php echo $documentoIdentidadEmpleado; ?></td>
                        <td>
                            <button class="btn btn-primary" onclick="openEditForm('<?php echo $nombreEmpleado; ?>', '<?php echo $apellidoEmpleado; ?>', '<?php echo $documentoIdentidadEmpleado; ?>')">
                                Editar Datos <img src="../../imagen/iconos/lapiz.png" alt="editar" class="edit-icon">
                            </button>
                        </td>
                    </tr>
                </table>
            </div>
            <br><br>
            <form action='../paz_y_salvo/solicitar_paz_y_salvo.php' method='post'>
                <input type='hidden' name='id_empleado' value='<?php echo $id_empleado; ?>'>
                <button type='submit' class="btn btn-success">Solicitar Paz y Salvo</button><br><br>
            </form>
            <form action='../consultas/consultar_paz_y_salvo.php' method='post'>
                <input type='hidden' name='id_empleado' value='<?php echo $id_empleado; ?>'>
                <button type='submit' class="btn btn-info">Consultar Estado del Paz y Salvo</button>
            </form>
            <div class="container">
                <a href="../paz_y_salvo/generar_pdf.php" target="_blank" class="btn btn-primary">
                    Descargar Paz y Salvo PDF
                    <img src="../../imagen/iconos/pdf.png" alt="PDF" class="edit-icon">
                </a>
            </div>
            <br><a href='../login/logout.php' class="btn btn-danger">Cerrar Sesión</a>
        </div>

        <div id="editFormModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeEditForm()">&times;</span>
                <form id="editForm" action="actualizar_datos_empleado.php" method="post">
                    <label for="nuevoNombre">Nuevo Nombre:</label>
                    <input type="text" id="nuevoNombre" name="nuevoNombre" required><br><br>
                    <label for="nuevoApellido">Nuevo Apellido:</label>
                    <input type="text" id="nuevoApellido" name="nuevoApellido" required><br><br>
                    <label for="nuevoDocumento">Nuevo Documento de Identidad:</label>
                    <input type="text" id="nuevoDocumento" name="nuevoDocumento" required><br><br>
                    <button type="submit" class="btn btn-primary">Actualizar Datos</button>
                </form>
            </div>
        </div>

        <script>
            function openEditForm(nombre, apellido, documentoIdentidad) {
                document.getElementById('editFormModal').style.display = 'block';
                document.getElementById('nuevoNombre').value = nombre;
                document.getElementById('nuevoApellido').value = apellido;
                document.getElementById('nuevoDocumento').value = documentoIdentidad;
            }

            function closeEditForm() {
                document.getElementById('editFormModal').style.display = 'none';
            }
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
} else {
    echo "Error: No se encontraron datos del empleado asociado al usuario.";
}

// Cerrar la conexión
$conn->close();
?>
