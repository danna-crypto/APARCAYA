<?php
/**
 * Funciones utilitarias para el sistema ParkEasy
 */

// Obtiene todos los parqueaderos registrados
function obtenerParqueaderos($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM parqueaderos ORDER BY nombre ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error en obtenerParqueaderos: " . $e->getMessage());
        return [];
    }
}

// Envía emails usando PHPMailer
function enviarEmail($destinatario, $asunto, $mensaje) {
    try {
        require_once 'libs/phpmailer/PHPMailerAutoload.php';
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = 'smtp.tudominio.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'tucorreo@dominio.com';
        $mail->Password = 'tucontraseña';
        $mail->setFrom('no-reply@parkeasy.com', 'ParkEasy');
        $mail->addAddress($destinatario);
        $mail->Subject = $asunto;
        $mail->Body = $mensaje;
        return $mail->send();
    } catch (Exception $e) {
        error_log("Error al enviar email: " . $e->getMessage());
        return false;
    }
}

// Obtiene el número de reservas para el día actual
function obtenerReservasHoy($pdo) {
    try {
        $stmt = $pdo->prepare("
            SELECT COUNT(*) 
            FROM reservas 
            WHERE DATE(fecha_entrada) = CURDATE()
        ");
        $stmt->execute();
        return $stmt->fetchColumn() ?: 0;
    } catch (PDOException $e) {
        error_log("Error en obtenerReservasHoy: " . $e->getMessage());
        return 0;
    }
}

// Calcula los ingresos mensuales
function obtenerIngresos($pdo) {
    try {
        $stmt = $pdo->prepare("
            SELECT SUM(total) 
            FROM facturas 
            WHERE MONTH(fecha) = MONTH(CURRENT_DATE())
            AND YEAR(fecha) = YEAR(CURRENT_DATE())
        ");
        $stmt->execute();
        return $stmt->fetchColumn() ?: 0;
    } catch (PDOException $e) {
        error_log("Error en obtenerIngresos: " . $e->getMessage());
        return 0;
    }
}

// Obtiene reservas por usuario (para clientes)
function obtenerReservasUsuario($pdo, $usuario_id) {
    try {
        $stmt = $pdo->prepare("
            SELECT r.*, p.nombre as parqueadero
            FROM reservas r
            JOIN parqueaderos p ON r.parqueadero_id = p.id
            WHERE r.usuario_id = ?
            ORDER BY r.fecha_entrada DESC
        ");
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error en obtenerReservasUsuario: " . $e->getMessage());
        return [];
    }
}
?>