<?php
include 'conexion.php';
$conexion->set_charset('utf8');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['p_idDocumentoPersona'])) {
        $p_idDocumentoPersona = $_GET['p_idDocumentoPersona'];
        
        if ($stmt = $conexion->prepare("CALL sp_c_tiendas_por_persona_registrotienda(?)")) {
            $stmt->bind_param('s', $p_idDocumentoPersona);
            
            if ($stmt->execute()) {
                $resultado = $stmt->get_result();
                $json = array();
                $json['tiendas_personas'] = array();
                
                while ($request = $resultado->fetch_assoc()) {
                    $result = array(
                        "idTienda" => $request['idTienda'],
                        "tieNombre" => $request['tieNombre']
                    );
                    $json['tiendas_personas'][] = $result;
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
        echo json_encode(['error' => 'No se proporcionó el parámetro requerido p_idDocumentoPersona.']);
    }
} else {
    echo json_encode(['error' => 'Método no permitido. Se requiere una solicitud GET.']);
}

$conexion->close();
?>
