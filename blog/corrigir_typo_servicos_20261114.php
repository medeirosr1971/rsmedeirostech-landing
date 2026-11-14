<?php
// Script de uso único — corrige o typo "Sistemas Simpes" -> "Sistemas Simples"
// na tabela `servicos`. Rodar UMA VEZ pela URL, depois apagar este arquivo.

define('ENTRY_POINT', true);
require_once __DIR__ . '/config.php';

header('Content-Type: text/plain; charset=UTF-8');

try {
    $stmt = $pdo->prepare("UPDATE servicos SET titulo = :novo WHERE titulo = :antigo");
    $stmt->execute([':novo' => 'Sistemas Simples', ':antigo' => 'Sistemas Simpes']);

    echo "Linhas corrigidas: " . $stmt->rowCount() . PHP_EOL;

    $check = $pdo->query("SELECT id, titulo FROM servicos ORDER BY id DESC")->fetchAll();
    echo "Estado atual da tabela servicos:" . PHP_EOL;
    foreach ($check as $row) {
        echo "  #{$row['id']} - {$row['titulo']}" . PHP_EOL;
    }
} catch (PDOException $e) {
    echo "ERRO: " . $e->getMessage() . PHP_EOL;
}
