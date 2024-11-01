<?php
include 'conexion.php';  
$conexion->set_charset('utf8');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['idOrden'])) {
        $idOrden = $_GET['idOrden'];
        
        if ($stmt = $conexion->prepare("CALL sp_m_estado_orden_preparado_a_encamino(?)")) {
            $stmt->bind_param('s', $idOrden);
            
            if ($stmt->execute()) {
                $resultado = $stmt->get_result();
                
                if ($resultado->num_rows > 0) {
                    $fila = $resultado->fetch_assoc();
                    echo json_encode(['odEstado' => $fila['odEstado']]);
                } else {
                    echo json_encode(['error' => 'No se encontró la orden o no hubo cambios']);
                }
                $resultado->close();
            } else {
                echo json_encode(['error' => 'Error en la ejecución de la consulta: ' . $stmt->error]);
            }
            $stmt->close();
        } else {
            echo json_encode(['error' => 'Error al preparar la consulta: ' . $conexion->error]);
        }
    } else {
        echo json_encode(['error' => 'No se proporcionó el ID de orden requerido']);
    }
} else {
    echo json_encode(['error' => 'Método no permitido. Se requiere una solicitud GET']);
}

$conexion->close();
?>