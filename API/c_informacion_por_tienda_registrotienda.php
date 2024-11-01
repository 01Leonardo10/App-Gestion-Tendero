<?php
include 'conexion.php';
$conexion->set_charset('utf8');

$json = array();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET["p_idTienda"])) {
        $p_idTienda = $_GET['p_idTienda'];

        if ($stmt = $conexion->prepare("CALL sp_c_informacion_por_tienda_registrotienda(?)")) {
            $stmt->bind_param('s', $p_idTienda); // Cambia 's' a 'i' si p_idTienda es un entero

            if ($stmt->execute()) {
                $resultado = $stmt->get_result();
                while ($request = $resultado->fetch_assoc()) {
                    $result = array(
                        "tieNombre" => $request['tieNombre'],
                        "tieImagenurl" => $request['tieImagen'],
                        "tieURLWeb" => $request['tieURLWeb'],
                        "tieDescripcion" => $request['tieDescripcion'],
                        "tieCorreo" => $request['tieCorreo'],
                        "tieTelefono" => $request['tieTelefono'],
                        "tieDireccion" => $request['tieDireccion'],
                        "tieCiudad" => $request['tieCiudad'],
                        "tieEstado" => $request['tieEstado'],
                        "tieVentasMensuales" => $request['tieVentasMensuales'],
                        "tieInventarioEstimado" => $request['tieInventarioEstimado'],
                        "tieLatitud" => $request['tieLatitud'],
                        "tieLongitud" => $request['tieLongitud'],
                        "idRubroTienda" => $request['idRubroTienda']
                    );
                    $json['tiendas_info'][] = $result;
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
