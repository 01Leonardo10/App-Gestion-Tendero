<?php
include 'conexion.php';  
$conexion->set_charset('utf8');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id_DocumentoPersona']) && isset($_GET['per_NumeroCelular'])) {
        $c_id_DocumentoPersona = $_GET['id_DocumentoPersona'];
        $c_per_NumeroCelular = $_GET['per_NumeroCelular'];
        
        if ($stmt = $conexion->prepare("CALL sp_m_numerocelular_persona(?, ?)")) {
            $stmt->bind_param('ss', $c_id_DocumentoPersona, $c_per_NumeroCelular);
            
            if ($stmt->execute()) {
                $stmt->store_result();  
                if ($stmt->affected_rows > 0) {
                    echo json_encode(['success' => 'N�mero de celular actualizado correctamente']);
                } else {
                    echo json_encode(['error' => 'No se encontr� la persona o no hubo cambios']);
                }
            } else {
                echo json_encode(['error' => 'Error en la ejecuci�n de la consulta: ' . $stmt->error]);
            }
            $stmt->close();
        } else {
            echo json_encode(['error' => 'Error al preparar la consulta: ' . $conexion->error]);
        }
    } else {
        echo json_encode(['error' => 'No se proporcionaron todos los par�metros requeridos']);
    }
} else {
    echo json_encode(['error' => 'M�todo no permitido. Se requiere una solicitud GET']);
}

$conexion->close();
?>