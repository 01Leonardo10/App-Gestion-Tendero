<?php
include 'conexion.php';  

$conexion->set_charset('utf8');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['idPersona']) && isset($_GET['usuImagen'])) {
        $c_idPersona = $_GET['idPersona'];
        $c_usuImagen = $_GET['usuImagen'];

        if ($stmt = $conexion->prepare("CALL sp_m_foto_usuario(?, ?)")) {
            $stmt->bind_param('ss', $c_idPersona, $c_usuImagen);
            
            if ($stmt->execute()) {
                $stmt->store_result();  
                if ($stmt->affected_rows > 0) {
                    echo json_encode(['success' => 'Imagen de usuario actualizada correctamente.']);
                } else {
                    echo json_encode(['error' => 'No hubo cambios a la imagen.']);
                }
            } else {
                echo json_encode(['error' => 'Error en la ejecucion de la consulta: ' . $stmt->error]);
            }

            $stmt->close();
        } else {
            echo json_encode(['error' => 'Error al preparar la consulta: ' . $conexion->error]);
        }
    } else {
        echo json_encode(['error' => 'No se proporcionaron todos los parametros requeridos.']);
    }
} else {
    echo json_encode(['error' => 'Metodo no permitido. Se requiere una solicitud GET.']);
}

$conexion->close();
?>