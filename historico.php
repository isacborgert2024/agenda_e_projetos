<?php

include('banco_e_functions_gerais.php'); 


$current_usuario = $_SESSION['usuario'];



// Filtro por data e busca por palavra-chave
$filtro_inicio = $_GET['data_inicio'] ?? '';
$filtro_fim = $_GET['data_fim'] ?? '';
$pesquisa_acao = $_GET['pesquisa_acao'] ?? '';

// Verifica se estão vazios e define a data atual
if (empty($filtro_inicio)) {
    $filtro_inicio = date('Y-m-d'); // Formato de data padrão (AAAA-MM-DD)
}

if (empty($filtro_fim)) {
    $filtro_fim = date('Y-m-d');
}

// Conecta ao banco
$conn = conectar_banco();

// Consulta SQL com filtro por data e palavra-chave
$sql = "SELECT * FROM historico";
$params = [];

if (!empty($filtro_inicio) && !empty($filtro_fim)) {
    $sql .= " WHERE data_hora BETWEEN ? AND ?";
    $params[] = $filtro_inicio . " 00:00:00";  // Adiciona a hora inicial
    $params[] = $filtro_fim . " 23:59:59";     // Adiciona a hora final
}

if (!empty($pesquisa_acao)) {
    $sql .= !empty($params) ? " AND" : " WHERE";
    $sql .= " acao LIKE ?";
    $params[] = "%" . $pesquisa_acao . "%";  // Adiciona a pesquisa com LIKE
}

$sql .= " ORDER BY data_hora DESC";  // Ordena pela data de forma decrescente

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param(str_repeat("s", count($params)), ...$params);  // Vincula os parâmetros
}
$stmt->execute();
$result = $stmt->get_result();
$historico = $result->fetch_all(MYSQLI_ASSOC);


?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Ações</title>
    <style>
       /* Reset básico */
       * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Arial', sans-serif;
    color: #e0e0e0; /* Texto em cinza claro */
    padding: 20px;
}

h1 {
    text-align: center;
    color: #e0e0e0; /* Texto em cinza claro */
    margin-bottom: 20px;
}

/* Estilo do formulário de filtro */
form {
    max-width: 600px;
    margin: 0 auto 20px;
    padding: 20px;
    border-radius: 0px; /* Remover bordas arredondadas */
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3); /* Sombra mais escura */
    background-color: rgb(44, 43, 43); /* Cinza escuro */
}

form label {
    font-size: 14px;
    color: #ccc; /* Texto cinza claro */
    margin-right: 10px;
    width: 48%; /* Largura do rótulo */
}

form input[type="date"],
form input[type="text"] {
    background-color: #333; /* Fundo cinza escuro */
    padding: 10px;
    margin: 10px 0;
    width: 100%;
    border-radius: 0px;
    border: 1px solid #555;
    font-size: 14px;
    color: #fff; /* Texto branco */
    text-align: right;
}


form button {
    padding: 10px 15px;
    background-color: #333; /* Preto fosco */
    color: white;
    border: 1px solid #555; /* Borda cinza escuro */
    border-radius: 0px; /* Remover bordas arredondadas */
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.3s ease, border 0.3s ease;
}

form button:hover {
    background-color: #555; /* Cinza escuro */
    border: 1px solid #444; /* Borda cinza mais escuro */
}

/* Div com flexbox */
form div {
    display: flex;
    justify-content: flex-start; /* Alinha o rótulo à esquerda */
    align-items: center; /* Alinha verticalmente */
    margin-bottom: 10px;
}

form div input[type="date"],
form div input[type="text"] {
    margin-left: 10px; /* Espaço entre o rótulo e o campo de input */
    width: 50%; /* Ajuste a largura conforme necessário */
}

/* Estilo da tabela */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table, th, td {
    border: 1px solid #444; /* Borda cinza escuro */
}

th, td {
    padding: 12px;
    text-align: left;
    font-size: 14px;
    color: #e0e0e0; /* Texto em cinza claro */
}

th {
    background-color: #333; /* Preto fosco */
    color: white;
    font-weight: bold;
}

td {
    background-color: #2c2c2c; /* Cinza escuro para células */
}

tr:nth-child(even) td {
    background-color: #444; /* Cinza médio para linhas pares */
}

/* Mensagem quando não há registros */
.no-records {
    text-align: center;
    font-size: 16px;
    color: #e74c3c;
    padding: 20px;
}



    </style>
</head>
<body>
    <h2><?php echo texto_idioma($idioma, 'hist_act_geral'); ?></h2>

    <!-- Formulário de filtro por data e busca por palavra-chave -->
    <form method="GET" action="historico.php">
        <label for="data_inicio"><?php echo texto_idioma($idioma, 'inicio'); ?>:</label>
        <input type="date" id="data_inicio" name="data_inicio" value="<?= htmlspecialchars($filtro_inicio) ?>">
        <br>
        <label for="data_fim"><?php echo texto_idioma($idioma, 'final'); ?>:</label>
        <input type="date" id="data_fim" name="data_fim" value="<?= htmlspecialchars($filtro_fim) ?>">
        <br>
        <label for="pesquisa_acao"><?php echo texto_idioma($idioma, 'acao'); ?>:</label>
        <input type="text" id="pesquisa_acao" name="pesquisa_acao" placeholder="<?php echo texto_idioma($idioma, 'keyword'); ?>" value="<?= htmlspecialchars($pesquisa_acao) ?>">
        <br>
        <button type="submit"><?php echo texto_idioma($idioma, 'filtrar'); ?></button>
    </form>

    <!-- Tabela de histórico -->
    <?php if (empty($historico)): ?>
        <div class="no-records"><?php echo texto_idioma($idioma, 'no_regist_found'); ?></div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th><?php echo texto_idioma($idioma, 'usuario'); ?> ID</th>
                    <th><?php echo texto_idioma($idioma, 'acao'); ?></th>
                    <th><?php echo texto_idioma($idioma, 'data_e_hora'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($historico as $registro): ?>
                    <tr>
                        <td><?= htmlspecialchars($registro['id']) ?></td>
                        <td><?= htmlspecialchars($registro['usuario_id']) ?></td>
                        <td><?= htmlspecialchars($registro['acao']) ?></td>
                        <td><?= htmlspecialchars($registro['data_hora']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
<?php
// Fecha a conexão com o banco
$stmt->close();
$conn->close();
$conn = null;
?>