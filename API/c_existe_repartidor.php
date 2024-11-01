<?php
include 'conexion.php';
$conexion->set_charset('utf8');

$json = array();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET["documento"])) {
        $documento = $_GET['documento'];//revisar si es el valor correcto

        if ($stmt = $conexion->prepare("CALL sp_c_existe_repartidor(?)")) {
            $stmt->bind_param('s', $documento); // Cambia 's' a 'i' si documento es un entero

            if ($stmt->execute()) {
                $resultado = $stmt->get_result();
                if ($fila = $resultado->fetch_assoc()) {
                    $json['EXISTE'] = $fila['EXISTE'];
                } else {
                    $json['EXISTE'] = 0; // O una respuesta por defecto si no hay resultados
                }
            } else {
                $json['error'] = 'Error en la ejecución de la consulta: ' . $stmt->error;
            }
            $stmt->close();
        } else {
            $json['error'] = 'Error al preparar la consulta: ' . $conexion->error;
        }
    } else {
        $json['error'] = 'Faltan parámetros requeridos.';
    }
} else {
    $json['error'] = 'Método no permitido. Se requiere una solicitud GET.';
}

$conexion->close();
echo json_encode($json);
?>
