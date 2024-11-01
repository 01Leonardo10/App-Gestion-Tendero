<?php
include 'conexion.php';  

$conexion->set_charset('utf8');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['idPersona']) && isset($_GET['perNumeroCelular'])) {
        $c_idPersona = $_GET['idPersona'];
        $c_per_NumeroCelular = $_GET['perNumeroCelular'];

        if ($stmt = $conexion->prepare("CALL sp_m_numerocelular_persona(?, ?)")) {
            $stmt->bind_param('ss', $c_idPersona, $c_per_NumeroCelular);
            
            if ($stmt->execute()) {
                $stmt->store_result();  
                if ($stmt->affected_rows > 0) {
                    echo json_encode(['success' => 'Numero de celular actualizado correctamente.']);
                } else {
                    echo json_encode(['error' => 'No se encontro la persona o no hubo cambios.']);
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