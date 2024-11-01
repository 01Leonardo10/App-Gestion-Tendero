<?php
include 'conexion.php';
$conexion->set_charset('utf8');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['idOrden'])) {
        $idOrden = $_GET['idOrden'];
        
        if ($stmt = $conexion->prepare("CALL sp_m_estado_orden_encamino_a_entregado(?)")) {
            $stmt->bind_param('s', $idOrden);
            
            if ($stmt->execute()) {
                $resultado = $stmt->get_result();
                
                if ($fila = $resultado->fetch_assoc()) {
                    echo json_encode(['odEstado' => $fila['odEstado']]);
                } else {
                    echo json_encode(['error' => 'No se encontr� la orden.']);
                }
            } else {
                echo json_encode(['error' => 'Error en la ejecuci�n de la consulta: ' . $stmt->error]);
            }
            $stmt->close();
        } else {
            echo json_encode(['error' => 'Error al preparar la consulta: ' . $conexion->error]);
        }
    } else {
        echo json_encode(['error' => 'No se proporcion� el ID de orden requerido.']);
    }
} else {
    echo json_encode(['error' => 'M�todo no permitido. Se requiere una solicitud GET.']);
}

$conexion->close();
?>