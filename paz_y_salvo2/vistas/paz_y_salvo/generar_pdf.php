<?php
session_start();

require_once '../../vendor/autoload.php'; // Incluye el autoloader de Composer

use TCPDF as TCPDF;

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "paz_y_salvo2";

// Establecer la conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    echo "<script>alert('Error de conexión: " . $conn->connect_error . "');</script>";
}

// Validar la sesión antes de acceder a los datos del usuario
if (!isset($_SESSION['username'])) {
    echo "<script>alert('Error: Debes iniciar sesión para acceder a esta página.'); window.location.href = '../login/login.php';</script>";
}

// Obtener el nombre de usuario de la sesión
$username = $_SESSION['username'];

// Obtener los datos del empleado asociado al usuario
$sql_obtener_empleado = "SELECT e.*, u.NombreUsuario, u.CorreoElectronico
                          FROM empleados e
                          LEFT JOIN usuarios u ON e.Usuario_ID = u.ID
                          WHERE u.NombreUsuario = '$username'";

$result_obtener_empleado = $conn->query($sql_obtener_empleado);

// Verificar si hubo un error en la consulta
if ($result_obtener_empleado === false) {
    echo "<script>alert('Error al ejecutar la consulta para obtener los datos del empleado: " . $conn->error . "');</script>";
    exit;
}

// Verificar si se encontraron datos del empleado
if ($result_obtener_empleado->num_rows > 0) {
    $empleado = $result_obtener_empleado->fetch_assoc();

    // Obtener el estado del Paz y Salvo del empleado
    $sql_estado_paz_y_salvo = "SELECT Estado FROM Registro_Paz_Salvo WHERE Empleado_ID = {$empleado['ID']} ORDER BY Fecha_Emision DESC LIMIT 1";
    $result_estado_paz_y_salvo = $conn->query($sql_estado_paz_y_salvo);

    if ($result_estado_paz_y_salvo === false) {
        echo "<script>alert('Error al obtener el estado del Paz y Salvo: " . $conn->error . "');</script>";
        exit;
    }

    // Verificar si se encontró el estado del Paz y Salvo
    if ($result_estado_paz_y_salvo->num_rows > 0) {
        $estado_paz_y_salvo = $result_estado_paz_y_salvo->fetch_assoc()['Estado'];

        // Verificar si el estado del Paz y Salvo es 'Aprobado'
        if ($estado_paz_y_salvo === 'Aprobado') {
            // Crear un nuevo objeto TCPDF
            $pdf = new TCPDF();

            // Establecer metadatos
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Tu Nombre');
            $pdf->SetTitle('Paz y Salvo');
            $pdf->SetSubject('Paz y Salvo');
            $pdf->SetKeywords('Paz y Salvo, Empleado');

            // Agregar una página
            $pdf->AddPage();


            
               // Obtener la fecha actual
                $fecha_generacion = date("Y-m-d");

            // Agregar la imagen al PDF
            $image_file = '../../imagen/beyon2.jpg'; // Ruta de la imagen en tu servidor
            $pdf->Image($image_file, 20, 20, 50, 0, 'JPG'); // Parámetros: nombre del archivo, coordenadas x e y, ancho, altura, formato
            


                // Configurar el contenido del PDF
                $contenido = "Fecha de Generación: " . $fecha_generacion . "\n\n\n\n\n";
                $contenido .= "\n\n\n\n\n";
                $contenido .= "Reciba un cordial saludo\n\n\n";
                $contenido .= "\n\n\n\n";
                


                $contenido .= "Por medio de la presente, nuestra empresa Be Yonder Colombia SAS con Nit 901263518-1\n Informa que el Señor@ " . $empleado['Nombre']  ." ".  $empleado['Apellido'] . " Identificado con el numero de documento " . $empleado['DocumentoIdentidad'] . "\n" ;
                $contenido .= "Se encuentra a Paz Y Salvo por todo concepto con nuestra organización.\n\n\n\n";
                
                $contenido .= "Datos del Empleado Solicitante:\n";
                $contenido .= "Nombre: " . $empleado['Nombre'] . "\n";
                $contenido .= "Apellido: " . $empleado['Apellido'] . "\n";
                $contenido .= "Documento de Identidad: " . $empleado['DocumentoIdentidad'] . "\n";
                $contenido .= "Correo Electrónico: " . $empleado['CorreoElectronico'] . "\n\n";
                $contenido .= "El empleado está a paz y salvo con la empresa.\n\n\n\n";
                $contenido .= "Cordialmente.\n\n\n\n";
                $contenido .= "\n\n\n";
                $contenido .= "FIRMA REPRESENTANTE LEGAL\n\n";
                $contenido .= "Jacsson Muñoz Cuervo\n\n";
                $contenido .= "Representate Legal";

                // Agregar el contenido al PDF
                    $pdf->MultiCell(0, 10, $contenido, 0, 'L', 0, 1, '', '', true);

            // Salida del PDF (descarga o visualización)
            $pdf->Output('paz_y_salvo.pdf', 'D'); // 'D' para descargar, 'I' para visualizar en el navegador
        } else {
            // Si el estado del Paz y Salvo no es 'Aprobado', mostrar un mensaje emergente
            echo "<script>alert('Error: El estado del Paz y Salvo no está aprobado.'); </script>";
        }
    } else {
        echo "<script>alert('Error: No se encontró el estado del Paz y Salvo.');</script>";
    }

} else {
    // Manejar el caso si no se encuentra el empleado
    echo "<script>alert('Error: No se encontraron datos del empleado asociado al usuario.');</script>";
}

// Cerrar la conexión
$conn->close();
?>
