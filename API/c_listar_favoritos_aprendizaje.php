<?php
include 'conexion.php';
$conexion->set_charset('utf8');

$json = array();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id_DocumentoPersona'])) {
        $c_id_DocumentoPersona = $_GET['id_DocumentoPersona'];

        if ($stmt = $conexion->prepare("CALL sp_c_listar_favoritos_aprendizaje(?)")) {
            $stmt->bind_param('s', $c_id_DocumentoPersona);

            if ($stmt->execute()) {
                $resultado = $stmt->get_result();
                while ($registro = $resultado->fetch_assoc()) {
                    $result = array(
                        "perapEstado" => $registro['perapEstado'],
                        "perapFechaInscripcion" => $registro['perapFechaInscripcion'],
                        "idAprendizaje" => $registro['idAprendizaje'],
                        "idDocumentoPersona" => $registro['idDocumentoPersona'],
                        "apreTituloRecurso" => $registro['apreTituloRecurso'],
                        "apreContenido" => $registro['apreContenido']
                    );
                    $json['consulta'][] = $result;   
                }
            } else {
                $json['error'] = 'Error en la ejecución de la consulta: ' . $stmt->error;
            }
            $stmt->close();
        } else {
            $json['error'] = 'Error al preparar la consulta: ' . $conexion->error;
        }
    } else {
        $json['error'] = 'Faltan parámetros requeridos.';
    }
} else {
    $json['error'] = 'Método no permitido. Se requiere una solicitud GET.';
}

$conexion->close();
echo json_encode($json);
?>
