<?php
// =============================================================================
// enviar_email.php — Envia a notificação de novo contato por e-mail (SMTP)
// -----------------------------------------------------------------------------
// Responsabilidade única: pegar os dados de um contato e enviar um e-mail
// avisando que chegou. Não mexe no banco, não decide sucesso/erro do form —
// isso quem decide é o processa.php, que já salvou no banco antes de chamar isto.
// =============================================================================

if (!defined('ENTRY_POINT')) {
    http_response_code(403);
    exit('Acesso negado.');
}

require_once __DIR__ . '/libs/PHPMailer/Exception.php';
require_once __DIR__ . '/libs/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/libs/PHPMailer/SMTP.php';
require_once __DIR__ . '/email_config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

/**
 * Envia o e-mail de notificação de um novo contato recebido pelo site.
 *
 * Retorna true/false em vez de lançar exceção para fora: o envio de e-mail
 * é "best effort" — se falhar, o contato já está salvo no banco (fonte da
 * verdade) e o formulário não deve quebrar por causa disso.
 */
function notificar_novo_contato(string $nome, string $email, string $mensagem): bool
{
    global $smtp_host, $smtp_porta, $smtp_seguro, $smtp_user, $smtp_pass,
           $smtp_remetente_nome, $email_destino, $email_destino_nome;

    $mail = new PHPMailer(true);

    try {
        // ---- Configuração do transporte SMTP ----
        $mail->isSMTP();
        $mail->Host       = $smtp_host;
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtp_user;
        $mail->Password   = $smtp_pass;
        $mail->SMTPSecure = $smtp_seguro === 'tls' ? PHPMailer::ENCRYPTION_STARTTLS : PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = $smtp_porta;
        $mail->CharSet    = 'UTF-8';
        $mail->Timeout    = 10; // segundos — evita travar a página se o SMTP não responder

        // ---- Remetente e destinatário ----
        $mail->setFrom($smtp_user, $smtp_remetente_nome);
        $mail->addAddress($email_destino, $email_destino_nome);

        // Responder direto pra quem preencheu o formulário, sem precisar copiar o e-mail
        $mail->addReplyTo($email, $nome);

        // ---- Conteúdo ----
        $mail->isHTML(true);
        $mail->Subject = "Novo contato pelo site — {$nome}";

        $data_hora = date('d/m/Y \à\s H:i');
        $mensagem_html = nl2br(htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8'));

        $mail->Body = <<<HTML
            <div style="font-family: Arial, sans-serif; max-width: 560px; margin: 0 auto;">
                <h2 style="color:#2c3e50; margin-bottom: 4px;">Novo contato recebido pelo site</h2>
                <p style="color:#718096; font-size: 13px; margin-top: 0;">{$data_hora}</p>

                <table style="width:100%; border-collapse: collapse; margin: 16px 0;">
                    <tr>
                        <td style="padding:8px 0; color:#2c3e50; font-weight:bold; width:90px;">Nome</td>
                        <td style="padding:8px 0; color:#4a5568;">{$nome}</td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0; color:#2c3e50; font-weight:bold;">E-mail</td>
                        <td style="padding:8px 0; color:#4a5568;">{$email}</td>
                    </tr>
                </table>

                <div style="background:#f8fafc; border-left:4px solid #3498db; padding:16px 20px; border-radius: 0 8px 8px 0;">
                    <p style="margin:0; color:#4a5568; line-height:1.6; white-space:pre-wrap;">{$mensagem_html}</p>
                </div>

                <p style="color:#a0aec0; font-size:12px; margin-top:24px;">
                    Esta mensagem já está salva no banco de dados (tabela contatos).
                    Este e-mail é só um aviso — responda direto para {$email}.
                </p>
            </div>
        HTML;

        $mail->AltBody = "Novo contato de {$nome} ({$email}) em {$data_hora}:\n\n{$mensagem}";

        $mail->send();
        return true;

    } catch (PHPMailerException $e) {
        // Nunca deixa o erro de e-mail derrubar o fluxo do formulário.
        // Fica registrado no log do servidor para você investigar depois.
        error_log('[ERRO EMAIL CONTATO] ' . $mail->ErrorInfo);
        return false;
    }
}
