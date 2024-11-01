<?php
include 'conexion.php';
$conexion->set_charset('utf8');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['p_proDescripcion']) && isset($_GET['p_idTienda'])) {
        $p_proDescripcion = $_GET['p_proDescripcion'];
        $p_idTienda = $_GET['p_idTienda'];

        // Valor predeterminado para p_proDescripcion si está vacío
        if (empty($p_proDescripcion)) {
            $p_proDescripcion = " ";
        }

        if ($stmt = $conexion->prepare("CALL sp_c_productos_globales_mitienda(?, ?)")) {
            $stmt->bind_param('ss', $p_proDescripcion, $p_idTienda);
            
            if ($stmt->execute()) {
                $resultado = $stmt->get_result();
                $json = array();
                $json['lista_productos'] = array();
                
                while ($request = $resultado->fetch_assoc()) {
                    $result = array(
                        "idProducto" => $request['idProducto'],
                        "proImagen" => $request['proImagen'],
                        "proDescripcion" => $request['proDescripcion'],
                        "proPrecioCostoPromedio" => $request['proPrecioCostoPromedio'],
                        "proPrecioVentaRecomendado" => $request['proPrecioVentaRecomendado'],
                        "proUnidadMedida" => $request['proUnidadMedida'],
                        "cpNombre" => $request['cpNombre']
                    );
                    $json['lista_productos'][] = $result;
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
        echo json_encode(['error' => 'No se proporcionaron los parámetros requeridos p_proDescripcion o p_idTienda.']);
    }
} else {
    echo json_encode(['error' => 'Método no permitido. Se requiere una solicitud GET.']);
}

$conexion->close();
?>
