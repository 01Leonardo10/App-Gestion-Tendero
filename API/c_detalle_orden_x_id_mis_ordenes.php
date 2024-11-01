<?php
include 'conexion.php';
$conexion->set_charset('utf8');

$json = array();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id_orden'])) {
        $c_id_orden = $_GET['id_orden'];

        if ($stmt = $conexion->prepare("CALL sp_c_detalle_orden_x_id_mis_ordenes(?)")) {
            $stmt->bind_param('i', $c_id_orden); // Cambia 'i' a 's' si id_orden es un string

            if ($stmt->execute()) {
                $resultado = $stmt->get_result();
                
                while ($registro = $resultado->fetch_assoc()) {
                    $result = array(
                        "idListadoProductoTienda" => $registro['idListadoProductoTienda'],
                        "proDescripcion" => $registro['lptDescripcionProductoTienda'],
                        "doPrecioVenta" => $registro['doPrecioVenta'],
                        "lptImagen1" => $registro['lptImagen1'],
                        "proUnidadMedida" => $registro['lptUnidadMedida'],
                        "doCantidad" => $registro['doCantidad'],
                        "doSubTotal" => $registro['doSubTotal'],
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
