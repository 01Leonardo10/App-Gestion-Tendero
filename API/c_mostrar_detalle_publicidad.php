<?php
include 'conexion.php';
$conexion->set_charset('utf8');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id_Tienda'])) {
        $c_id_Tienda = $_GET['id_Tienda'];
        
        if ($stmt = $conexion->prepare("CALL sp_c_mostrar_detalle_publicidad(?)")) {
            $stmt->bind_param('i', $c_id_Tienda);
            
            if ($stmt->execute()) {
                $resultado = $stmt->get_result();
                $json = array();
                
                while ($registro = $resultado->fetch_assoc()) {
                    $result["pubImagen"] = $registro['pubImagen'];
                    $result["pubTitulo"] = $registro['pubTitulo'];
                    $result["pubDescripcion"] = $registro['pubDescripcion'];
                    $result["pubFechaInicio"] = $registro['pubFechaInicio'];
                    $result["fecha_Dias"] = $registro['fecha_Dias'];
                    $result["factpubMontoTotal"] = $registro['factpubMontoTotal'];
                    $result["tasa"] = $registro['tasa'];
                    $result["pubCantVistas"] = $registro['pubCantVistas'];
                    
                    $json['D_publicidad'][] = $result;
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
        echo json_encode(['error' => 'No se proporcionó el parámetro requerido id_Tienda.']);
    }
} else {
    echo json_encode(['error' => 'Método no permitido. Se requiere una solicitud GET.']);
}

$conexion->close();
?>
