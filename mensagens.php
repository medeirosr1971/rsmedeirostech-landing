<?php
// =============================================================================
// admin_mensagens.php — Painel de Mensagens Recebidas
// -----------------------------------------------------------------------------
// Responsabilidade: listar as mensagens da tabela contatos para o administrador
// Futuramente: adicionar autenticação antes deste painel (login obrigatório)
// =============================================================================

define('ENTRY_POINT', true);
require_once 'config.php';

// =============================================================================
// BUSCA DOS DADOS
// Aqui usamos query() e não prepare() porque não há dados do usuário na query.
// query() é para SQLs fixos e seguros. prepare() é para SQLs com parâmetros externos.
// ORDER BY id DESC = os mais recentes aparecem primeiro (id maior = inserido depois)
// =============================================================================
try {
    $stmt      = $pdo->query("SELECT * FROM contatos ORDER BY id DESC");
    $mensagens = $stmt->fetchAll(); // fetchAll() retorna todas as linhas de uma vez
} catch (PDOException $e) {
    error_log('[ERRO MENSAGENS] ' . $e->getMessage());
    $mensagens = []; // Em caso de erro, exibimos lista vazia em vez de quebrar a página
}

// =============================================================================
// HELPER: formata a data no padrão brasileiro
// Criamos uma função para não repetir a lógica em vários lugares (DRY)
// DRY = Don't Repeat Yourself — princípio fundamental de código limpo
// =============================================================================
function formatar_data(string $data_raw): string
{
    // strtotime() converte string de data para timestamp Unix (número inteiro)
    // date() formata o timestamp no padrão desejado
    // Espaço entre Y e H:i é obrigatório: 'd/m/Y H:i' → "10/06/2026 14:30"
    return date('d/m/Y H:i', strtotime($data_raw));
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensagens | Painel Admin</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* =====================================================================
           Estilos específicos deste painel — complementam o style.css global
           Nomenclatura: BEM simplificado → bloco-elemento (sem modificador por ora)
           ===================================================================== */

        /* Wrapper centralizado do painel */
        .painel-admin {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
        }

        /* Cabeçalho do painel com título e link de volta */
        .painel-topo {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid #3498db; /* Padrão WorkerP */
        }

        .painel-topo h2 {
            color: #2c3e50;
            font-size: 1.5rem;
            margin: 0;
        }

        .link-voltar {
            text-decoration: none;
            color: #3498db;
            font-weight: 600;
            font-size: 14px;
            transition: color 0.2s;
        }

        .link-voltar:hover {
            color: #2980b9;
        }

        /* Card de cada mensagem — padrão .info-bloco do WorkerP */
        .msg-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #2c3e50; /* Acento navy no lado esquerdo */
            border-radius: 0 8px 8px 0;
            padding: 20px 24px;
            margin-bottom: 16px;
            transition: border-left-color 0.2s, box-shadow 0.2s;
        }

        .msg-card:hover {
            border-left-color: #3498db; /* Muda para azul royal no hover */
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.1);
        }

        /* Linha de metadados: nome + email à esquerda, data à direita */
        .msg-meta {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
            flex-wrap: wrap; /* Quebra em telas menores */
            gap: 8px;
        }

        .msg-remetente {
            font-weight: 700;
            color: #2c3e50;
            font-size: 1rem;
        }

        /* Email ao lado do nome, estilo secundário */
        .msg-email {
            font-weight: 400;
            color: #718096;
            font-size: 0.85rem;
            margin-left: 8px;
        }

        .msg-data {
            font-size: 12px;
            color: #a0aec0;
            white-space: nowrap; /* Impede quebra da data */
        }

        /* Corpo da mensagem */
        .msg-texto {
            color: #4a5568;
            font-size: 14px;
            line-height: 1.7;
            white-space: pre-wrap; /* Respeita quebras de linha digitadas pelo usuário */
        }

        /* Estado vazio — quando não há mensagens */
        .estado-vazio {
            text-align: center;
            padding: 60px 20px;
            color: #718096;
            background: #f8fafc;
            border: 1px dashed #e2e8f0;
            border-radius: 8px;
        }

        .estado-vazio p {
            font-size: 15px;
            margin-top: 8px;
        }

        /* Contador de mensagens */
        .badge-contagem {
            background: #3498db;
            color: #fff;
            font-size: 12px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
            margin-left: 10px;
        }
    </style>
</head>
<body style="background: #f4f7f9;">

<div class="painel-admin">

    <!-- Cabeçalho do painel -->
    <div class="painel-topo">
        <h2>
            Mensagens Recebidas
            <?php if (!empty($mensagens)): ?>
                <!-- Exibe quantas mensagens existem — informação útil para o admin -->
                <span class="badge-contagem"><?php echo count($mensagens); ?></span>
            <?php endif; ?>
        </h2>
        <a href="index.php" class="link-voltar">← Voltar ao Site</a>
    </div>

    <!-- =====================================================================
         LISTAGEM DAS MENSAGENS
         Padrão: verificamos se o array tem itens antes de iterar
         Isso evita erros e permite exibir um estado vazio amigável
         ===================================================================== -->
    <?php if (!empty($mensagens)): ?>

        <?php foreach ($mensagens as $msg): ?>
            <div class="msg-card">

                <div class="msg-meta">
                    <div>
                        <!-- htmlspecialchars(): converte caracteres especiais em entidades HTML
                             Proteção XSS na exibição: evita que <script> do banco execute no browser
                             ENT_QUOTES: trata aspas simples e duplas
                             'UTF-8': especifica o charset para evitar problemas de encoding -->
                        <span class="msg-remetente">
                            <?php echo htmlspecialchars($msg['nome'], ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                        <span class="msg-email">
                            (<?php echo htmlspecialchars($msg['email'], ENT_QUOTES, 'UTF-8'); ?>)
                        </span>
                    </div>
                    <span class="msg-data">
                        <!-- Chama a função helper que criamos no topo do arquivo -->
                        <?php echo formatar_data($msg['criado_em']); ?>
                    </span>
                </div>

                <div class="msg-texto">
                    <!-- nl2br(): converte \n (quebra de linha) em <br> HTML
                         Combinado com white-space: pre-wrap no CSS, preserva a formatação -->
                    <?php echo nl2br(htmlspecialchars($msg['mensagem'], ENT_QUOTES, 'UTF-8')); ?>
                </div>

            </div>
        <?php endforeach; ?>

    <?php else: ?>
        <!-- Estado vazio: sempre melhor que uma página em branco -->
        <div class="estado-vazio">
            <p>Nenhuma mensagem recebida ainda.</p>
            <p style="font-size: 13px; margin-top: 4px;">As mensagens do formulário de contato aparecerão aqui.</p>
        </div>
    <?php endif; ?>

</div>

</body>
</html>
