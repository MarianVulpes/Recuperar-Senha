<?php
require('../config/connect.php');
require '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

if (isset($_GET["email"])) {
    $email = urldecode($_GET["email"]);

    $sql = "SELECT reset_token FROM cadastros WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $tokenResult = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($tokenResult) {
        $token = $tokenResult['reset_token'];

        $mail = new PHPMailer;

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->Port       = 587;
            $mail->SMTPAuth   = true;
            $mail->SMTPSecure = 'tls';

            $mail->Username   = 'Email utilizado';
            $mail->Password   = 'Senha de app do Email';

            $mail->setFrom('Remetente (email utilizado)', 'Password Reset');
            $mail->addAddress($email);

            $mail->Subject = 'Recuperar Senha';
            $mail->Body    = "Olá,\r\n\r\nVocê solicitou a recuperação de senha. Aqui está seu token:\r\n$token\r\n\r\nEste token é válido por 30 minutos.\r\n\r\nSe você não solicitou a recuperação de senha, por favor, ignore este e-mail.\r\n";

            $mail->send();
            header("Location: ../../newpass.php?email=" . urlencode($email));
        } catch (Exception $e) {
            header("Location: ../../recuperacao.php?msg=Erro ao enviar e-mail! " . $mail->ErrorInfo);
        }
    } else {
        header("Location: ../../recuperacao.php?msg=E-mail não encontrado!");
    }
} else {
    header("Location: ../../recuperacao.php?msg=Email não fornecido!");
}
?>
