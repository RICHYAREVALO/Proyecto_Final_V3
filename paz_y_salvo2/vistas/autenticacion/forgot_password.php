<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Olvido Contraseña</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tu archivo de estilos personalizado -->
    <link rel="stylesheet" href="olvido_cont.css">
    <link rel="stylesheet" href="../login/styles.css">
</head>
<body>
    <div class="container form-container">
        <h2>Olvido Contraseña</h2>
        <form action="olvido_contraseña.php" method="post">
            <div class="form-group">
                <label for="nombre_usuario">Nombre de Usuario:</label>
                <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" required>
            </div>
            <div class="form-group">
                <label for="nueva_contraseña">Nueva Contraseña:</label>
                <input type="password" class="form-control" id="nueva_contraseña" name="nueva_contraseña" required>
            </div>
            <div class="form-group">
                <label for="confirmar_contraseña">Confirmar Contraseña:</label>
                <input type="password" class="form-control" id="confirmar_contraseña" name="confirmar_contraseña" required>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Contraseña</button>
        </form>
    </div>

    <!-- Bootstrap JS y jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
