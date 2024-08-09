<?php
session_start();
error_reporting(E_ALL); // Mostrar todos los errores

require_once __DIR__ . '/../../vendor/autoload.php'; // Ajusta la ruta si es necesario

// Iniciar el búfer de salida
ob_start();

// Parámetros de conexión a la base de datos
$servername = "bkqu3uk3ewxyehltqf2t-mysql.services.clever-cloud.com";
$username = "uwwounruhaizndvh";
$password = "91JGBP3BP37TC6be2NIi";
$dbname = "bkqu3uk3ewxyehltqf2t";

// Establecer la conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Validar la sesión antes de acceder a los datos del usuario
if (!isset($_SESSION['username'])) {
    echo "<script>alert('Error: Debes iniciar sesión para acceder a esta página.'); window.location.href = '../login/login.php';</script>";
    exit;
}

// Obtener el nombre de usuario de la sesión
$username = $_SESSION['username'];

// Obtener los datos del empleado asociado al usuario
$sql_obtener_empleado = "
    SELECT u.ID AS empleadoID, u.Nombre, u.Apellido, u.DocumentoIdentidad, u.CorreoElectronico
    FROM usuarios_empleados u
    WHERE u.NombreUsuario = ?
";

$stmt_obtener_empleado = $conn->prepare($sql_obtener_empleado);
$stmt_obtener_empleado->bind_param("s", $username);
$stmt_obtener_empleado->execute();
$result_obtener_empleado = $stmt_obtener_empleado->get_result();

if ($result_obtener_empleado === false) {
    die("Error al ejecutar la consulta para obtener los datos del empleado: " . $conn->error);
}

if ($result_obtener_empleado->num_rows > 0) {
    $empleado = $result_obtener_empleado->fetch_assoc();

    $sql_estado_paz_y_salvo = "
        SELECT Estado
        FROM registro_paz_salvo
        WHERE Usuario_Empleado_ID = ?
        ORDER BY Fecha_Emision DESC
        LIMIT 1
    ";

    $stmt_estado_paz_y_salvo = $conn->prepare($sql_estado_paz_y_salvo);
    $stmt_estado_paz_y_salvo->bind_param("i", $empleado['empleadoID']);
    $stmt_estado_paz_y_salvo->execute();
    $result_estado_paz_y_salvo = $stmt_estado_paz_y_salvo->get_result();

    if ($result_estado_paz_y_salvo === false) {
        die("Error al obtener el estado del Paz y Salvo: " . $conn->error);
    }

    if ($result_estado_paz_y_salvo->num_rows > 0) {
        $estado_paz_y_salvo = $result_estado_paz_y_salvo->fetch_assoc()['Estado'];

        if ($estado_paz_y_salvo === 'Aprobado') {
            // Crear un nuevo objeto TCPDF
            $pdf = new TCPDF();

            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Tu Nombre');
            $pdf->SetTitle('Paz y Salvo');
            $pdf->SetSubject('Paz y Salvo');
            $pdf->SetKeywords('Paz y Salvo, Empleado');

            $pdf->AddPage();

            $fecha_generacion = date("Y-m-d");

            $image_file = __DIR__ . '/../../imagen/beyon2.jpg'; // Verifica esta ruta

            if (file_exists($image_file)) {
                $pdf->Image($image_file, 20, 20, 50, 0, 'JPG');
            } else {
                die("La imagen no se encuentra en la ruta especificada.");
            }

            $contenido = "Fecha de Generación: " . $fecha_generacion . "\n\n\n\n\n\n\n\n\n\n";
            $contenido .= "Reciba un cordial saludo\n\n\n";
            $contenido .= "Por medio de la presente, nuestra empresa Be Yonder Colombia SAS con Nit 901263518-1\n";
            $contenido .= "Informa que el Señor@ " . $empleado['Nombre'] . " " . $empleado['Apellido'] . " Identificado con el numero de documento " . $empleado['DocumentoIdentidad'] . " ";
            $contenido .= "Se encuentra a Paz Y Salvo por todo concepto con nuestra organización.\n\n\n\n";
            $contenido .= "Datos del Empleado Solicitante:\n";
            $contenido .= "Nombre: " . $empleado['Nombre'] . "\n";
            $contenido .= "Apellido: " . $empleado['Apellido'] . "\n";
            $contenido .= "Documento de Identidad: " . $empleado['DocumentoIdentidad'] . "\n";
            $contenido .= "Correo Electrónico: " . $empleado['CorreoElectronico'] . "\n\n";
            $contenido .= "El empleado está a paz y salvo con la empresa.\n\n\n\n";
            $contenido .= "Cordialmente.\n\n\n\n";
            $contenido .= "FIRMA REPRESENTANTE LEGAL\n\n";
            $contenido .= "Jacsson Muñoz Cuervo\n\n";
            $contenido .= "Representante Legal";

            $pdf->MultiCell(0, 10, $contenido, 0, 'L', 0, 1, '', '', true);

            // Salida del PDF (descarga directamente en el navegador)
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="paz_y_salvo.pdf"');
            $pdf->Output('paz_y_salvo.pdf', 'I'); // 'I' para mostrar en el navegador

            exit;
        } else {
            echo "<script>alert('Error: El estado del Paz y Salvo no está aprobado.');</script>";
        }
    } else {
        echo "<script>alert('Error: No se encontró el estado del Paz y Salvo.');</script>";
    }

    $stmt_estado_paz_y_salvo->close();
} else {
    echo "<script>alert('Error: No se encontraron datos del empleado asociado al usuario.');</script>";
}

$conn->close();

// Limpiar el búfer de salida
ob_end_clean();
?>
