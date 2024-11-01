<?php
include 'conexion.php';
$conexion->set_charset('utf8');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['sp_idTienda'])) {
        $sp_idTienda = $_GET['sp_idTienda'];
        
        if ($stmt = $conexion->prepare("CALL sp_c_lista_repartidor_orden_mis_ordenes(?)")) {
            $stmt->bind_param('i', $sp_idTienda);
            
            if ($stmt->execute()) {
                $resultado = $stmt->get_result();
                $json = array();
                
                while ($registro = $resultado->fetch_assoc()) {
                    $result["idRepartidor"] = $registro['idRepartidor'];
                    $result["perNombreCompleto"] = $registro['perNombreCompleto'];
                    $json['sp_c_lista_repartidor_orden'][] = $result;
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
        echo json_encode(['error' => 'No se proporcionó el parámetro requerido sp_idTienda.']);
    }
} else {
    echo json_encode(['error' => 'Método no permitido. Se requiere una solicitud GET.']);
}

$conexion->close();
?>
