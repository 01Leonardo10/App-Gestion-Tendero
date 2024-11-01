<?php

include 'conexion.php';
$conexion->set_charset('utf8');

$json = array();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET["sp_usuCorreo"])) {
        $sp_usuCorreo = $_GET["sp_usuCorreo"];
        
        if ($stmt = $conexion->prepare("CALL sp_c_existencia_usuario(?)")) {
            $stmt->bind_param('s', $sp_usuCorreo); // Cambia 's' a 'i' si sp_usuCorreo es un entero

            if ($stmt->execute()) {
                $resultado = $stmt->get_result();
                
                while ($registro = $resultado->fetch_assoc()) {
                    $result["idUsuario"] = $registro['idUsuario'];
                    $result["idDocumentoPersona"] = $registro['idDocumentoPersona'];
                    $json['usuario'][] = $result;
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
