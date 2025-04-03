<?php
// Inclui o autoload do PHPMailer
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Função para enviar e-mail
function enviarEmail($destinatarios, $assunto, $corpo) {
    // Criação do objeto PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configurações do servidor
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'seu email@gmail.com'; // Seu e-mail
        $mail->Password = 'senha app aqui'; // A senha do aplicativo
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587; // Porta para TLS

        // Não há necessidade de enviar para o remetente, então, removemos o setFrom() ou apenas definimos
        $mail->setFrom('seu email@gmail.com', 'Nome');

        // Adiciona múltiplos destinatários
        $emails = explode(',', $destinatarios); // Divide os e-mails separados por vírgula
        foreach ($emails as $email) {
            $mail->addAddress(trim($email)); // Adiciona cada e-mail à lista de destinatários
        }

        // Conteúdo do e-mail
        $mail->isHTML(true);
        $mail->Subject = $assunto; // Assunto passado como parâmetro
        $mail->Body    = $corpo;   // Corpo da mensagem passado como parâmetro

        // Enviar o e-mail
        $mail->send();
        return 'Mensagem enviada com sucesso';
    } catch (Exception $e) {
        return "A mensagem não pôde ser enviada. Erro: {$mail->ErrorInfo}";
    }
}

// Exemplo de chamada da função com múltiplos destinatários
//enviarEmail('isacborgert@gmail.com,leandro.debetio@gmail.com', 'Teste 4 telecom agenda', 'Este é o corpo do e-mail em <b>HTML</b>');
?>
