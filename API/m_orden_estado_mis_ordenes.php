<?php
include 'conexion.php';  
$conexion->set_charset('utf8');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['sp_idOrden']) && isset($_GET['sp_odEstado'])) {
        $sp_idOrden = $_GET['sp_idOrden'];
        $sp_odEstado = $_GET['sp_odEstado'];
        
        if ($stmt = $conexion->prepare("CALL sp_m_orden_estado_mis_ordenes(?, ?)")) {
            $stmt->bind_param('is', $sp_idOrden, $sp_odEstado);
            
            if ($stmt->execute()) {
                $resultado = $stmt->get_result();
                $json = array();
                
                if ($resultado->num_rows > 0) {
                    while ($registro = $resultado->fetch_assoc()) {
                        $result = array(
                            'esorEstado' => $registro['esorEstado'],
                            'esorFecha' => $registro['esorFecha'],//NO SE SI AÑADIR ESTO EN LA BASE DE DATOS O BORRARLA DE AQUI
                            'esorHora' => $registro['esorHora']
                        );
                        $json['sp_m_repartidor_por_orden_mis_ordenes'][] = $result;
                    }
                    echo json_encode($json);
                } else {
                    echo json_encode(['error' => 'No se encontraron registros']);
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
        echo json_encode(['error' => 'No se proporcionaron todos los parámetros requeridos']);
    }
} else {
    echo json_encode(['error' => 'Método no permitido. Se requiere una solicitud GET']);
}

$conexion->close();
?>