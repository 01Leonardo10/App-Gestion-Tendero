<?php
include 'conexion.php';
$conexion->set_charset('utf8');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['idRepartidor'])) {
        $idRepartidor = $_GET['idRepartidor'];
        
        if ($stmt = $conexion->prepare("CALL sp_c_orden_actual_por_id(?)")) {
            $stmt->bind_param('s', $idRepartidor);
            
            if ($stmt->execute()) {
                $resultado = $stmt->get_result();
                $json = array();
                
                if ($fila = $resultado->fetch_assoc()) {
                    $json['odEstado'] = $fila['odEstado'];
                    $json['idOrden'] = $fila['idOrden'];
                }

                echo json_encode($json);
            } else {
                echo json_encode(['error' => 'Error en la ejecución de la consulta: ' . $stmt->error]);
            }
            $stmt->close();
        } else {
            echo json_encode(['error' => 'Error al preparar la consulta: ' . $conexion->error]);
        }
    } else {
        echo json_encode(['error' => 'No se proporcionó el parámetro requerido idRepartidor.']);
    }
} else {
    echo json_encode(['error' => 'Método no permitido. Se requiere una solicitud GET.']);
}

$conexion->close();
?>
