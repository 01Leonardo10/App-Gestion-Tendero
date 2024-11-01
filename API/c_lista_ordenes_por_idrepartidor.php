<?php
include 'conexion.php';
$conexion->set_charset('utf8');

$json = array();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['idrepartidor'])) {
        $idrepartidor = $_GET['idrepartidor'];
        
        if ($stmt = $conexion->prepare("CALL sp_c_lista_ordenes_por_idrepartidor(?)")) {
            $stmt->bind_param('i', $idrepartidor);
            
            if ($stmt->execute()) {
                $resultado = $stmt->get_result();
                while ($fila = $resultado->fetch_assoc()) {
                    $json['Consulta'][] = $fila;
                }
            } else {
                $json['error'] = 'Error en la ejecución de la consulta: ' . $stmt->error;
            }
            $stmt->close();
        } else {
            $json['error'] = 'Error al preparar la consulta: ' . $conexion->error;
        }
    } else {
        $json['error'] = 'No se proporcionó el parámetro requerido idrepartidor.';
    }
} else {
    $json['error'] = 'Método no permitido. Se requiere una solicitud GET.';
}

$conexion->close();
echo json_encode($json);
?>
