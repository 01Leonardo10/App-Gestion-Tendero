<?php
include 'conexion.php';
$conexion->set_charset('utf8');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id_DocumentoPersona'])) {
        $c_id_DocumentoPersona = $_GET['id_DocumentoPersona'];
        
        if ($stmt = $conexion->prepare("CALL sp_c_perfil_usuario(?)")) {
            $stmt->bind_param('s', $c_id_DocumentoPersona);
            
            if ($stmt->execute()) {
                $resultado = $stmt->get_result();
                $json = array();
                $json['consulta'] = array();
                
                while ($registro = $resultado->fetch_assoc()) {
                    $result = array(
                        "idDocumentoPersona" => $registro['idDocumentoPersona'],
                        "perNombres" => $registro['perNombres'],
                        "perApellidos" => $registro['perApellidos'],
                        "perNumeroCelular" => $registro['perNumeroCelular'],
                        "perUbiLatitud" => $registro['perUbiLatitud'],
                        "perUbiLongitud" => $registro['perUbiLongitud'],
                        "repEstado" => $registro['repEstado'],
                        "repTipoVehiculo" => $registro['repTipoVehiculo'],
                        "repNombreVehiculo" => $registro['repNombreVehiculo'],
                        "repPlaca" => $registro['repPlaca']
                    );
                    $json['consulta'][] = $result;
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
        echo json_encode(['error' => 'No se proporcionó el parámetro requerido id_DocumentoPersona.']);
    }
} else {
    echo json_encode(['error' => 'Método no permitido. Se requiere una solicitud GET.']);
}

$conexion->close();
?>
