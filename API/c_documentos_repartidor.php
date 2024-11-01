<?php
include 'conexion.php';
$conexion->set_charset('utf8');

$json = array();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id_DocumentoPersona'])) {
        $c_id_DocumentoPersona = $_GET['id_DocumentoPersona'];

        if ($stmt = $conexion->prepare("CALL sp_c_documentos_repartidor(?)")) {
            $stmt->bind_param('s', $c_id_DocumentoPersona); // Cambia 's' a 'i' si id_DocumentoPersona es un entero

            if ($stmt->execute()) {
                $resultado = $stmt->get_result();
                
                while ($registro = $resultado->fetch_assoc()) {
                    $result = array(
                        "idDocumentoPersona" => $registro['idDocumentoPersona'],
                        "repEstado" => $registro['repEstado'],
                        "repTipoVehiculo" => $registro['repTipoVehiculo'],
                        "repPlaca" => $registro['repPlaca'],
                        "repAntecedentes" => $registro['repAntecedentes'],
                        "repAntecedentesEstado" => $registro['repAntecedentesEstado'],
                        "repLicencia" => $registro['repLicencia'],
                        "repLicenciaEstado" => $registro['repLicenciaEstado'],
                        "repDocumentoVehiculo" => $registro['repDocumentoVehiculo'],
                        "repDocumentoVehiculoEstado" => $registro['repDocumentoVehiculoEstado'],
                        "repTarjetaPropiedad" => $registro['repTarjetaPropiedad'],
                        "repTarjetaPropiedadEstado" => $registro['repTarjetaPropiedadEstado'],
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

mysqli_close($conexion);
echo json_encode($json);
?>
