<?php
include 'conexion.php';  
$conexion->set_charset('utf8');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['p_idListadoProductoTienda'])) {
        $p_idListadoProductoTienda = $_GET['p_idListadoProductoTienda'];
        
        if ($stmt = $conexion->prepare("CALL sp_m_producto_inactivo_registroproducto(?)")) {
            $stmt->bind_param('s', $p_idListadoProductoTienda);
            
            if ($stmt->execute()) {
                $stmt->store_result();  
                if ($stmt->affected_rows > 0) {
                    echo json_encode(['success' => 'Producto INACTIVO']);
                } else {
                    echo json_encode(['error' => 'No se encontró el producto o no hubo cambios']);
                }
            } else {
                echo json_encode(['error' => 'Error en la ejecución de la consulta: ' . $stmt->error]);
            }
            $stmt->close();
        } else {
            echo json_encode(['error' => 'Error al preparar la consulta: ' . $conexion->error]);
        }
    } else {
        echo json_encode(['error' => 'No se proporcionó el ID del producto requerido']);
    }
} else {
    echo json_encode(['error' => 'Método no permitido. Se requiere una solicitud GET']);
}

$conexion->close();
?>