<?php
class Conexion {
    private static $conexion;

    public static function getConexion() {
        if (self::$conexion == null) {
            // Datos de conexión a la base de datos
            $servername = "bjgxtiqfs78pgiy7qzux-mysql.services.clever-cloud.com";
            $username = "udb0mb339gpdtxkh";
            $password = "0PRRJnHNJEdZdU9pCHYR";
            $dbname = "bjgxtiqfs78pgiy7qzux";

            try {
                self::$conexion = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                self::$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "Error de conexión: " . $e->getMessage();
            }
        }

        return self::$conexion;
    }
}
