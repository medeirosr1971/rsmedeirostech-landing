<?php
// =============================================================================
// config.example.php — Modelo de Configuração Central do Sistema
// -----------------------------------------------------------------------------
// Copie este arquivo para config.php e preencha com os valores reais.
// config.php nunca é versionado (está no .gitignore).
//
// REGRA DE OURO: config.php NUNCA deve ficar acessível pelo navegador.
// No seu hosting, mova-o para FORA da pasta public_html. Se não puder,
// o guard abaixo bloqueia o acesso direto.
// =============================================================================

if (!defined('ENTRY_POINT')) {
    http_response_code(403);
    exit('Acesso negado.');
}

// =============================================================================
// CONFIGURAÇÕES DO BANCO DE DADOS
// =============================================================================
$db_host    = '127.0.0.1';
$db_name    = 'COLOQUE_O_NOME_DO_BANCO_AQUI';
$db_user    = 'COLOQUE_O_USUARIO_AQUI';
$db_pass    = 'COLOQUE_A_SENHA_AQUI';
$db_charset = 'utf8mb4';

// =============================================================================
// CONEXÃO COM O BANCO — PDO (PHP Data Objects)
// =============================================================================
$dsn = "mysql:host={$db_host};dbname={$db_name};charset={$db_charset}";

$opcoes_pdo = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $db_user, $db_pass, $opcoes_pdo);

} catch (PDOException $e) {
    error_log('[ERRO DB] ' . $e->getMessage() . ' | Arquivo: ' . __FILE__);
    die('Sistema temporariamente indisponível. Tente novamente em instantes.');
}
