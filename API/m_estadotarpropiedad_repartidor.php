<?php
include 'conexion.php';
$conexion->set_charset('utf8');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id_DocumentoPersona']) && isset($_GET['rep_TarjetaPropiedadEstado'])) {
        $c_id_DocumentoPersona = $_GET['id_DocumentoPersona'];
        $c_rep_TarjetaPropiedadEstado = $_GET['rep_TarjetaPropiedadEstado'];
        
        if ($stmt = $conexion->prepare("CALL sp_m_estadotarpropiedad_repartidor(?, ?)")) {
            $stmt->bind_param('ss', $c_id_DocumentoPersona, $c_rep_TarjetaPropiedadEstado);
            
            if ($stmt->execute()) {
                $stmt->store_result();
                
                if ($stmt->affected_rows > 0) {
                    echo json_encode(['success' => 'Estado de tarjeta de propiedad actualizado correctamente.']);
                } else {
                    echo json_encode(['error' => 'No se encontró el documento de la persona o no hubo cambios.']);
                }
            } else {
                echo json_encode(['error' => 'Error en la ejecución de la consulta: ' . $stmt->error]);
            }
            $stmt->close();
        } else {
            echo json_encode(['error' => 'Error al preparar la consulta: ' . $conexion->error]);
        }
    } else {
        echo json_encode(['error' => 'No se proporcionaron todos los parámetros requeridos.']);
    }
} else {
    echo json_encode(['error' => 'Método no permitido. Se requiere una solicitud GET.']);
}

$conexion->close();
?>
