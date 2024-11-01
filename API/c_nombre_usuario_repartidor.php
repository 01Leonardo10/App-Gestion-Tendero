<?php
include 'conexion.php';
$conexion->set_charset('utf8');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['iDocumentoPersona'])) {
        $iDocumentoPersona = $_GET['iDocumentoPersona'];
        
        if ($stmt = $conexion->prepare("CALL sp_c_nombre_por_dni_repartidor(?)")) {
            $stmt->bind_param('s', $iDocumentoPersona);
            
            if ($stmt->execute()) {
                $resultado = $stmt->get_result();
                $json = array();
                
                if ($fila = $resultado->fetch_assoc()) {
                    $json['Nombre'] = "{$fila['perApellidos']} {$fila['perNombres']}";
                    $json['Imagen'] = $fila['usuImagen'];
                    $json['idRepartidor'] = $fila['idRepartidor'];
                    $json['idUsuario'] = $fila['idUsuario'];
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
        echo json_encode(['error' => 'No se proporcionó el parámetro requerido iDocumentoPersona.']);
    }
} else {
    echo json_encode(['error' => 'Método no permitido. Se requiere una solicitud GET.']);
}

$conexion->close();
?>
