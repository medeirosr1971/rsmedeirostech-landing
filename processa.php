<?php
// =============================================================================
// processa.php — Processador do Formulário de Contato
// -----------------------------------------------------------------------------
// Responsabilidade única: receber POST, validar, salvar no banco,
// notificar por e-mail e redirecionar.
// Este arquivo não exibe HTML. Só processa dados e redireciona.
// Padrão: PRG (Post/Redirect/Get) — evita reenvio de formulário ao atualizar a página
// =============================================================================

// Define a constante que libera o acesso ao config.php
// Sem isso, config.php bloqueia com "Acesso negado"
define('ENTRY_POINT', true);

require_once 'config.php';
require_once 'enviar_email.php';

// =============================================================================
// BARREIRA 1: Só aceita requisições POST
// Qualquer outra tentativa de acesso (navegador direto, GET) vai para a home
// =============================================================================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// =============================================================================
// BARREIRA 2: Honeypot anti-bot
// Campo invisível no HTML. Humanos não preenchem. Bots preenchem tudo.
// Se vier preenchido, fingimos que deu certo (não revelamos que detectamos o bot)
// =============================================================================
if (!empty($_POST['url_check'])) {
    header('Location: index.php?envio=sucesso#contato');
    exit;
}

// =============================================================================
// VALIDAÇÃO E SANITIZAÇÃO DOS DADOS
// Diferença importante:
//   Sanitizar  = limpar caracteres perigosos do valor
//   Validar    = verificar se o valor faz sentido (email válido, campo não vazio)
// Fazemos os dois, nessa ordem
// =============================================================================

// filter_input(): função nativa do PHP para capturar e tratar dados externos
// Nunca use $_POST['campo'] diretamente sem tratar — porta aberta para ataques XSS

// FILTER_SANITIZE_SPECIAL_CHARS: converte <, >, &, aspas em entidades HTML seguras
$nome     = filter_input(INPUT_POST, 'nome',     FILTER_SANITIZE_SPECIAL_CHARS);
$mensagem = filter_input(INPUT_POST, 'mensagem', FILTER_SANITIZE_SPECIAL_CHARS);

// FILTER_VALIDATE_EMAIL: além de limpar, valida o formato. Retorna false se inválido.
$email    = filter_input(INPUT_POST, 'email',    FILTER_VALIDATE_EMAIL);

// Validação de campos obrigatórios
// trim() remove espaços em branco do início e fim (evita campos com só espaços)
$campos_validos = !empty(trim($nome))
               && !empty(trim($mensagem))
               && $email !== false;   // false = email inválido pelo filter_input

if (!$campos_validos) {
    // Redireciona de volta com flag de erro — o index.php lê essa flag e exibe alerta
    header('Location: index.php?envio=invalido#contato');
    exit;
}

// =============================================================================
// PERSISTÊNCIA NO BANCO DE DADOS
// Prepared Statement: separação entre SQL e dados → imune a SQL Injection
// Funcionamento:
//   1. prepare()     → envia a estrutura SQL com placeholders (:nome, :email...)
//   2. bindValue()   → amarra cada placeholder ao valor real, já escapado
//   3. execute()     → executa com segurança
// =============================================================================
try {
    // Nomenclatura da tabela: substantivo no plural, snake_case → contatos
    // Nomenclatura das colunas: snake_case → nome, email, mensagem, criado_em
    $sql = $pdo->prepare("
        INSERT INTO contatos (nome, email, mensagem)
        VALUES (:nome, :email, :mensagem)
    ");

    // bindValue() vs bindParam():
    // bindValue() copia o valor agora (mais seguro em loops)
    // bindParam() passa a referência (valor lido só no execute)
    // Para formulários simples, bindValue() é a escolha certa
    $sql->bindValue(':nome',     $nome);
    $sql->bindValue(':email',    $email);
    $sql->bindValue(':mensagem', $mensagem);

    $sql->execute();

    // =========================================================================
    // NOTIFICAÇÃO POR E-MAIL
    // O banco é a fonte da verdade — a mensagem já está salva no ponto acima.
    // O e-mail é só um aviso "best effort": se falhar (SMTP fora do ar, senha
    // errada etc.), não desfazemos o salvamento nem mostramos erro ao visitante.
    // O erro real fica registrado no log do servidor via error_log().
    // =========================================================================
    notificar_novo_contato($nome, $email, $mensagem);

    // Sucesso: redireciona com flag positiva
    header('Location: index.php?envio=sucesso#contato');
    exit;

} catch (PDOException $e) {
    // Registra o erro real no log do servidor (para o desenvolvedor analisar)
    error_log('[ERRO CONTATO] ' . $e->getMessage());

    // Redireciona com flag de erro genérico (o usuário não vê detalhes técnicos)
    header('Location: index.php?envio=erro#contato');
    exit;
}
