<?php
include 'banco_e_functions_gerais.php';



$query = "
    SELECT a.*, u.username AS cliente_username, t.username AS tecnico_username
    FROM agenda a
    LEFT JOIN usuarios t ON a.id_usuario_tecnico = t.id
    LEFT JOIN usuarios u ON a.id_usuario_cliente = u.id
    WHERE a.id_usuario_tecnico = $usuario_id
    AND (a.data_agenda < CURDATE() or a.aprovada = 'Concluída')
    ORDER BY a.data_agenda
";


$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Agendas</title>

    <link rel="stylesheet" href="css_fontes/para_listagem_de_agendas.css?<?php echo time(); ?>">


</head>
<body>
<div id="loading-container" style="display:none;">
    <div id="loading-circle"></div>
    <span>Carregando...</span>
</div>
    <div class="container">
        <h1>Histórico de Tarefas</h1>
        
        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <table class="agenda-table">
                <thead>
                    <tr>
                    <th>
                            <input type="text" placeholder="Filtrar..." onkeyup="filtrarTabela(0)">
                            Solicitante/Executor
                        </th>
                        <th class="tooltip">
                            <input type="text" placeholder="Filtrar..." onkeyup="filtrarTabela(1)">
                            Dados do serviço
                            <span class="tooltip-text">Informações do serviço a ser realizado</span>
                        </th>
                        <th>
                            <input type="text" placeholder="Filtrar..." onkeyup="filtrarTabela(2)">
                            Criada dia:
                        </th>
                        <th class="tooltip">
                            <input type="text" placeholder="Filtrar..." onkeyup="filtrarTabela(3)">
                            Realizada dia:
                            <span class="tooltip-text">Data e hora que será realizado o serviço</span>
                        </th>
                        <th class="tooltip">
                            <input type="text" placeholder="Filtrar..." onkeyup="filtrarTabela(4)">
                            Duração
                            <span class="tooltip-text">Duração estimada para a execução do serviço</span>
                        </th>
                        <th class="tooltip">
                            <input type="text" placeholder="Filtrar..." onkeyup="filtrarTabela(5)">
                            Materiais
                            <span class="tooltip-text">Materiais necessários para a execução do serviço</span>
                        </th>
                        <th class="tooltip">
                            <input type="text" placeholder="Filtrar..." onkeyup="filtrarTabela(6)">
                            Técnico local
                            <span class="tooltip-text">Pessoa que estará no local, caso o executor precise</span>
                        </th>
                        <th class="tooltip">
                            <input type="text" placeholder="Filtrar..." onkeyup="filtrarTabela(7)">
                            Conclusão
                            <span class="tooltip-text">Aqui o executor do serviço fará suas considerações finais.</span>
                        </th>
                        <th>
                            Ações
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                        <?php 
                            $data_criacao = new DateTime($row['data_criacao']);
                            $data_criacao = $data_criacao->format('d/m/Y H:i');
                            $data_agenda = new DateTime($row['data_agenda']);
                            $data_agenda = $data_agenda->format('d/m/Y H:i');
                        ?>
                            <td><?php echo htmlspecialchars($row['cliente_username']).' - '.$row['tecnico_username'].' - '.$row['aprovada']; ?></td>
                            <td><?php echo htmlspecialchars($row['dados']); ?></td>
                            <td><?php echo htmlspecialchars($data_criacao); ?></td>
                            <td><?php echo htmlspecialchars($data_agenda); ?></td>
                            <td><?php echo htmlspecialchars($row['horas_execucao']); ?> Hora(s)</td>
                            <td><?php echo htmlspecialchars($row['materiais_necessarios']); ?></td>
                            <td><?php echo htmlspecialchars($row['responsavel_local']).' - '.$row['fone_responsavel_local']; ?></td>
                            <td><?php echo htmlspecialchars($row['conclusao']); ?></td>
                            <td>
                                <div class="acoes-agenda" data-id="<?php echo $row['id']; ?>">
                                    <button class="btn-editar">Editar</button>
                                    <button class="btn-excluir">Excluir</button>
                                </div>
                            </td>

                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-agendas">Você não tem nenhuma agenda registrada.</p>
        <?php endif; ?>
    </div>

<script src="js_fontes/para_listagem_de_agendas.js?<?php echo time(); ?>"></script>



</body>
</html>
