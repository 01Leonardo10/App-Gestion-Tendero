<?php
include 'conexion.php';
$conexion->set_charset('utf8');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['sp_idTienda'], $_GET['sp_idOrden'], $_GET['sp_odEstado'], $_GET['sp_odFechaPedido'])) {
        $sp_idTienda = $_GET['sp_idTienda'];
        $sp_idOrden = $_GET['sp_idOrden'];
        $sp_odEstado = $_GET['sp_odEstado'];
        $sp_odFechaPedido = $_GET['sp_odFechaPedido'];

        if ($stmt = $conexion->prepare("CALL sp_c_lista_ordenes_mis_ordenes(?, ?, ?, ?)")) {
            $stmt->bind_param('iiss', $sp_idTienda, $sp_idOrden, $sp_odEstado, $sp_odFechaPedido);

            if ($stmt->execute()) {
                $resultado = $stmt->get_result();
                while ($registro = $resultado->fetch_assoc()) {
                    $result["idOrden"] = $registro['idOrden'];
                    $result["odFechaPedido"] = $registro['odFechaPedido'];
                    $result["odHoraPedido"] = $registro['odHoraPedido'];
                    $result["perNombreCompleto"] = $registro['perNombreCompleto'];
                    $result["odEstado"] = $registro['odEstado'];
                    $result["idRepartidor"] = $registro['idRepartidor'];
                    $result["repNombres"] = $registro['repNombres'];
                    $json['lista_ordenes'][] = $result;
                }
            } else {
                $json['error'] = 'Error en la ejecuci�n de la consulta: ' . $stmt->error;
            }
            $stmt->close();
        } else {
            $json['error'] = 'Error al preparar la consulta: ' . $conexion->error;
        }
    } else {
        $json['error'] = 'Faltan par�metros requeridos.';
    }
} else {
    $json['error'] = 'M�todo no permitido. Se requiere una solicitud GET.';
}

$conexion->close();
?>
