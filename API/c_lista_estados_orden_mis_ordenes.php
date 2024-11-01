<?php
include 'conexion.php';
$conexion->set_charset('utf8');

$json = array();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['sp_idOrden'])) {
        $sp_idOrden = $_GET['sp_idOrden'];

        if ($stmt = $conexion->prepare("CALL sp_c_lista_estados_orden_mis_ordenes(?)")) {
            $stmt->bind_param('i', $sp_idOrden);

            if ($stmt->execute()) {
                $resultado = $stmt->get_result();
                while ($registro = $resultado->fetch_assoc()) {
                    $result["esorEstado"] = $registro['esorEstado'];
                    $result["esorFecha"] = $registro['esorFecha'];//revisar si hay que añadirla a la base de datos
                    $result["esorHora"] = $registro['esorHora'];
                    $json['sp_c_lista_estados_orden'][] = $result;
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
