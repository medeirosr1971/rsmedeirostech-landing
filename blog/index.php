<?php
define('ENTRY_POINT', true);
require_once 'config.php';

$meus_servicos = [];

try {
    $sql = $pdo->query("SELECT titulo, descricao FROM servicos ORDER BY id DESC");
    $meus_servicos = $sql->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $meus_servicos = [];
}

/**
 * ==========================================================
 * 🔥 NOVO: PORTFÓLIO BASEADO NO WORKERP (SEU SISTEMA REAL)
 * ==========================================================
 */
$stack_tecnico = [
    "Arquitetura própria, sem depender de plataforma SaaS de terceiros",
    "Arquitetura MVC, pensada para crescer sem virar bagunça",
    "Camada de persistência sólida (PDO)",
    "Regras fiscais reais aplicadas (CFOP, CST/CSOSN por regime)",
    "Preparado para virar produto (SaaS) no futuro",
];

$modulos_sistema = [
    "Dados Mestres — entidades, produtos, categorias e colaboradores",
    "Estoque — posição, ajuste e transferência entre depósitos",
    "Compras — notas de entrada e pedidos de compra",
    "Comercial — pedidos de venda, ordens de serviço, notas de saída e contas a receber",
    "Financeiro — contas a pagar e contas a receber",
    "Fiscal — emissão de NF-e/NFC-e, com CFOP, CST/CSOSN e regras da Reforma Tributária",
];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roberto Medeiros | Dev & Blog</title>
    <meta name="description" content="WorkErp: ERP próprio em PHP e MySQL, em produção — estoque, compras, vendas, financeiro e NF-e numa única plataforma.">
    <link rel="canonical" href="https://www.rsmedeirostech.com.br/">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Roberto Medeiros | Dev">
    <meta property="og:title" content="WorkErp — um ERP de verdade, rodando em produção">
    <meta property="og:description" content="Sistema de gestão próprio em PHP e MySQL — estoque, compras, comercial, financeiro e fiscal (NF-e), já em uso real.">
    <meta property="og:url" content="https://www.rsmedeirostech.com.br/">
    <meta property="og:locale" content="pt_BR">
    <meta name="twitter:card" content="summary">

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>

<header>
    <div class="header-content">

        <div class="brand">
            <h1>Roberto Medeiros</h1>
            <p>Soluções em Desenvolvimento Web</p>
            <p style="font-size: 0.85rem; opacity: 0.8; margin-top: 4px;">Mais de 35 anos de carreira profissional — em TI (redes, suporte a sistemas) desde 1997.</p>
        </div>

        <nav id="menu" class="menu">
            <ul>
                <li><a href="#servicos">Serviços</a></li>
                <li><a href="#sistema">Sistema</a></li>
                <li><a href="#experiencia">Experiência</a></li>
                <li><a href="#contato">Contato</a></li>
            </ul>
        </nav>

        <button class="menu-toggle" onclick="toggleMenu()" aria-label="Abrir menu">☰</button>
    </div>
</header>

<main>

<!-- HERO -->
<section class="hero">
    <span class="hero-badge"><span class="ponto"></span> Em produção</span>
    <h2>Um ERP de verdade, rodando em produção</h2>
    <p>
        Não é no-code nem template pronto — é código próprio, pensado pra crescer junto com o negócio:
        estoque, compras, vendas, financeiro e nota fiscal eletrônica num só lugar.
    </p>
    <a href="https://workerp.rsmedeirostech.com.br/" target="_blank" class="btn-primary" style="margin-top: 18px;">
        <i class="fas fa-arrow-up-right-from-square"></i> Acessar o WorkErp
    </a>
</section>

<!-- Sistema em produção: convite ao WorkErp -->
<section id="sistema">

    <h2><i class="fas fa-cogs"></i> Conheça o WorkErp</h2>

    <p style="color: var(--texto-secundario); margin-bottom: 20px; line-height: 1.7;">
        Nenhum módulo aqui é enfeite — é o que roda o dia a dia de negócio de verdade, construído
        por quem viveu administração, faturamento, estoque, fiscal, financeiro e custos na prática,
        não só em teoria de curso:
    </p>

    <div class="cards">

        <?php foreach ($modulos_sistema as $modulo): ?>
            <article class="card">
                <h3><?= htmlspecialchars($modulo) ?></h3>
            </article>
        <?php endforeach; ?>

        <article class="card">
            <h3><i class="fas fa-door-open"></i> Quer ver por dentro?</h3>
            <p>Posso liberar um acesso de teste para você acompanhar o sistema em uso real.</p>
        </article>

    </div>

    <h2 style="margin-top:30px;"><i class="fas fa-layer-group"></i> Como foi construído</h2>

    <div class="cards">

        <?php foreach ($stack_tecnico as $stack): ?>
            <article class="card">
                <p><?= htmlspecialchars($stack) ?></p>
            </article>
        <?php endforeach; ?>

    </div>

    <p style="color: var(--texto-secundario); margin-top: 20px; line-height: 1.7;">
        Fora do WorkErp, também venho construindo um sistema de gestão para igrejas em
        <strong>Laravel</strong> — arquitetura e regras de negócio definidas por mim, aprendendo
        o framework no processo.
    </p>

</section>

<!-- SERVIÇOS EXISTENTES -->
<section id="servicos">

    <h2><i class="fas fa-code"></i> Serviços</h2>

    <div class="cards">

        <?php if (!empty($meus_servicos)): ?>

            <?php foreach ($meus_servicos as $item): ?>
                <article class="card">
                    <h3><?= htmlspecialchars($item['titulo']) ?></h3>
                    <p><?= htmlspecialchars($item['descricao']) ?></p>
                </article>
            <?php endforeach; ?>

        <?php else: ?>
            <p>Nenhum serviço cadastrado.</p>
        <?php endif; ?>

    </div>

</section>

<!-- EXPERIÊNCIA -->
<section id="experiencia">

    <h2><i class="fas fa-history"></i> Experiência</h2>

    <div class="timeline">

        <article class="timeline-item">
            <time class="date">2026</time>
            <h3>WorkErp em produção</h3>
            <p>Sistema completo (MVC, estoque, compras, vendas, serviços e fiscal) rodando e em uso real.</p>
        </article>

        <article class="timeline-item">
            <time class="date">2025</time>
            <h3>Infraestrutura Linux</h3>
            <p>Administração de servidores e redes locais.</p>
        </article>

        <article class="timeline-item">
            <time class="date">2020</time>
            <h3>Início do desenvolvimento do WorkErp</h3>
            <p>Estudo de PHP orientado a objetos por conta própria, evoluindo o código ao longo
            dos anos até amadurecer o sistema com apoio de IA.</p>
        </article>

        <article class="timeline-item">
            <time class="date">2014</time>
            <h3>Tecnólogo em Sistemas para Internet</h3>
            <p>Faculdade Educacional de Ponta Grossa (União/Unopar).</p>
        </article>

        <article class="timeline-item">
            <time class="date">1997</time>
            <h3>Entrada em TI</h3>
            <p>Suporte a redes, estações de trabalho (Windows desde o 95) e sistemas ERP corporativos.</p>
        </article>

        <article class="timeline-item">
            <time class="date">1990</time>
            <h3>Início de carreira profissional</h3>
            <p>Base prática em administração, faturamento, estoque, fiscal, financeiro e custos.</p>
        </article>

    </div>

</section>

<!-- CONTATO -->
<section id="contato" class="contact">

    <h2><i class="fas fa-envelope"></i> Vamos Conversar?</h2>

    <p style="color: var(--texto-secundario); margin-bottom: 20px;">
        Quer testar o WorkErp, sugerir uma funcionalidade ou conversar sobre um sistema para o seu negócio? Me chama.
    </p>

    <?php if (isset($_GET['envio'])): ?>
        <p class="msg <?= $_GET['envio'] === 'sucesso' ? 'sucesso' : 'erro' ?>">
            <?= $_GET['envio'] === 'sucesso'
                ? 'Mensagem enviada com sucesso!'
                : 'Erro ao enviar mensagem.'; ?>
        </p>
    <?php endif; ?>

    <form class="contact-form" action="processa.php" method="POST">

        <input type="text" name="url_check" style="display:none">

        <label>Nome:</label>
        <input type="text" name="nome" required>

        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Mensagem:</label>
        <textarea name="mensagem" rows="4" required></textarea>

        <button type="submit">Enviar</button>

    </form>

</section>

</main>

<a href="https://wa.me/5542991656130" class="whatsapp-btn" target="_blank" aria-label="Falar no WhatsApp">
    <i class="fab fa-whatsapp"></i>
</a>

<footer>
    <p>&copy; <?= date('Y') ?> - Roberto Medeiros</p>
</footer>

<script>
function toggleMenu() {
    document.getElementById('menu').classList.toggle('open');
}
</script>

</body>
</html>
