<?php
class Conexion {
    private static $conexion;

    public static function getConexion() {
        if (self::$conexion == null) {
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "paz_y_salvo2";

            try {
                self::$conexion = new PDO("mysql:host=$servidor;dbname=$baseDatos", $usuario, $contrasena);
                self::$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "Error de conexión: " . $e->getMessage();
            }
        }

        return self::$conexion;
    }
}