<?php
include 'conexion.php';
$conexion->set_charset('utf8');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['p_categoria']) && isset($_GET['p_idTienda'])) {
        $p_categoria = $_GET['p_categoria'];
        $p_idTienda = $_GET['p_idTienda'];

        // Valor predeterminado para p_categoria si está vacío
        if (empty($p_categoria)) {
            $p_categoria = "1";
        }

        if ($stmt = $conexion->prepare("CALL sp_c_productos_por_tienda_mitienda(?, ?)")) {
            $stmt->bind_param('ss', $p_categoria, $p_idTienda);
            
            if ($stmt->execute()) {
                $resultado = $stmt->get_result();
                $json = array();
                $json['productos_tienda'] = array();
                
                while ($request = $resultado->fetch_assoc()) {
                    $result = array(
                        "idListadoProductoTienda" => $request['idListadoProductoTienda'],
                        "lptDescripcionProductoTienda" => $request['lptDescripcionProductoTienda'],
                        "lptStock" => $request['lptStock'],
                        "lptUnidadMedida" => $request['lptUnidadMedida'],
                        "lptStockMinimo" => $request['lptStockMinimo'],
                        "lptImagen1" => $request['lptImagen1'],
                        "lptImagen2" => $request['lptImagen2'],
                        "lptImagen3" => $request['lptImagen3'],
                        "lptPrecioCompra" => $request['lptPrecioCompra'],
                        "lptPrecioVenta" => $request['lptPrecioVenta'],
                        "idProducto" => $request['idProducto'],
                        "idCategoriaProducto" => $request['idCategoriaProducto'],
                        "cpNombre" => $request['cpNombre']
                    );
                    $json['productos_tienda'][] = $result;
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
        echo json_encode(['error' => 'No se proporcionaron los parámetros requeridos p_categoria o p_idTienda.']);
    }
} else {
    echo json_encode(['error' => 'Método no permitido. Se requiere una solicitud GET.']);
}

$conexion->close();
?>
