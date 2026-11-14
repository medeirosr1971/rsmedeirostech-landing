<?php
// =============================================================================
// email_config.example.php — Modelo de configuração do envio de e-mail
// -----------------------------------------------------------------------------
// Copie este arquivo para email_config.php e preencha com os valores reais.
// email_config.php nunca é versionado (está no .gitignore).
// =============================================================================

if (!defined('ENTRY_POINT')) {
    http_response_code(403);
    exit('Acesso negado.');
}

$smtp_host   = 'smtp.hostinger.com';
$smtp_porta  = 465;                 // 465 = SSL (recomendado) | 587 = TLS
$smtp_seguro = 'ssl';               // 'ssl' para porta 465 | 'tls' para porta 587
$smtp_user   = 'COLOQUE_O_EMAIL_AQUI@rsmedeirostech.com.br';
$smtp_pass   = 'COLOQUE_A_SENHA_AQUI'; // NUNCA a do banco

$smtp_remetente_nome = 'WorkErp — Formulário de Contato';

$email_destino       = 'COLOQUE_O_EMAIL_DE_DESTINO_AQUI';
$email_destino_nome  = 'Roberto Medeiros';
