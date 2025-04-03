<?php
include 'banco_e_functions_gerais.php';



$query = "
    SELECT a.*, u.username AS tecnico_username
    FROM agenda a
    LEFT JOIN usuarios u ON a.id_usuario_tecnico = u.id
    WHERE a.id_usuario_cliente = $usuario_id
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
    <style>
        /* Definições gerais */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif; /* Usando apenas uma fonte */
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh; /* Alterado para evitar cortes em telas menores */
            padding: 20px;
        }

        .container {
            width: 100%;
            background-color: #1e1e1e;
            padding: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
        }


        h1 {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #fff;
            letter-spacing: 1.5px;
        }
        h2 {
            text-align: center;
            font-size: 1.8rem; /* Tamanho menor */
            margin-bottom: 20px;
            color: orange; /* Cor laranja corrigida */
            letter-spacing: 1.5px;
        }


        /* Tabela */
        .agenda-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .agenda-table th, .agenda-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #333;
            font-size: 1rem;
        }

        .agenda-table th {
            background-color: #2a2a2a;
            font-weight: 600;
        }

        .agenda-table td {
            background-color: #1f1f1f;
        }

        /* Efeito de hover nas linhas da tabela */
        .agenda-table tr {
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .agenda-table tr:hover {
            background-color: #444;
            transform: scale(1.02);
        }

        .agenda-table tr:nth-child(odd) {
            background-color: #2a2a2a;
        }

        .agenda-table tr:nth-child(even) {
            background-color: #1f1f1f;
        }

        /* Mensagem caso não haja agendas */
        .no-agendas {
            text-align: center;
            color: #f44336;
            font-size: 1.2rem;
            margin-top: 20px;
        }
/* Definir tamanho mínimo para evitar colunas muito estreitas (1 a 9) */
.agenda-table th:nth-child(-n+9),
.agenda-table td:nth-child(-n+9) {
    min-width: 140px; /* Ajuste conforme necessário */
    word-wrap: break-word; /* Permite quebra de linha */
    white-space: normal; /* Garante que o texto quebre quando necessário */
}


        /* Estiliza o tooltip */
.tooltip {
    position: relative;
    cursor: help;
}

.tooltip-text {
    visibility: hidden;
    width: 180px;
    background-color: #333;
    color: #fff;
    text-align: center;
    padding: 6px;
    border-radius: 6px;
    position: absolute;
    bottom: 125%;
    left: 50%;
    transform: translateX(-50%);
    opacity: 0;
    transition: opacity 0.3s;
    white-space: normal; /* Permite a quebra de linha */
    word-wrap: break-word; /* Garante que o texto não ultrapasse os limites */
}

/* Exibe o tooltip ao passar o mouse */
.tooltip:hover .tooltip-text {
    visibility: visible;
    opacity: 1;
}
.btn-editar {
    background-color:rgb(27, 29, 30);
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.9rem;
    transition: background 0.3s;
}

.btn-editar:hover {
    background-color:rgb(33, 37, 41);
}

th input {
    width: 100%;
    padding: 5px;
    background: #333; /* Preto fosco */
    color: #ddd; /* Cinza claro para contraste */
    border: 1px solid #444; /* Borda cinza escuro */
    outline: none;
    font-size: 14px;
    text-align: center;
    margin-bottom: 10px; /* Espaço entre o input e o label */
}

th input::placeholder {
    color: #666; /* Cinza médio para o placeholder */
}

th input:focus {
    background: #222; /* Cinza escuro ao focar */
    border-color: #888; /* Cinza mais claro para destacar */
}

    </style>
</head>
<body>
    <div class="container">
        <h1>Meu Histórico de Agenda</h1>
        
        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <table class="agenda-table">
                <thead>
                    <tr>
                    <th>
                            <input type="text" placeholder="Filtrar..." onkeyup="filtrarTabela(0)">
                            Executor
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
                            <td><?php echo htmlspecialchars($row['tecnico_username']).' - '.$row['aprovada']; ?></td>
                            <td><?php echo htmlspecialchars($row['dados']); ?></td>
                            <td><?php echo htmlspecialchars($data_criacao); ?></td>
                            <td><?php echo htmlspecialchars($data_agenda); ?></td>
                            <td><?php echo htmlspecialchars($row['horas_execucao']); ?> Hora(s)</td>
                            <td><?php echo htmlspecialchars($row['materiais_necessarios']); ?></td>
                            <td><?php echo htmlspecialchars($row['responsavel_local']).' - '.$row['fone_responsavel_local']; ?></td>
                            <td><?php echo htmlspecialchars($row['conclusao']); ?></td>


                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-agendas">Você não tem nenhuma agenda registrada.</p>
        <?php endif; ?>
    </div>
    <script>
document.addEventListener("DOMContentLoaded", function () {
    const agora = new Date();
    const amanha = new Date();
    amanha.setDate(amanha.getDate() + 1); // Adiciona 1 dia para pegar a data de amanhã
    
    document.querySelectorAll("td:first-child").forEach(td => {
        const texto = td.textContent.trim();

        // Se tiver "Aprovação Pendente", fica laranja fosco
        if (texto.includes("Aprovação Pendente")) {
            td.style.backgroundColor = "#e5a04c"; // Laranja fosco
            td.style.color = "#333"; // Contraste suave
        }

        // Se tiver "Aprovada", fica verde fosco
        if (texto.includes("Aprovada")) {
            td.style.backgroundColor = "#6b9e6b"; // Verde fosco
            td.style.color = "#222"; // Melhor contraste
        }

        // Se tiver "Rejeitada", fica vermelho fosco (igual ao da data)
        if (texto.includes("Rejeitada")) {
            td.style.backgroundColor = "#c46b6b"; // Vermelho fosco
            td.style.color = "#222"; // Melhor contraste
        }
    });

    document.querySelectorAll("tr").forEach(tr => {
        const td = tr.querySelectorAll("td")[3]; // Quarta coluna (índice 3, pois começa do 0)
        if (!td) return;

        const texto = td.textContent.trim();

        // Expressão regular para capturar a data e a hora
        const regexDataHora = /(\d{2})\/(\d{2})\/(\d{4}) (\d{2}):(\d{2})/;
        const match = texto.match(regexDataHora);

        if (match) {
            const [_, dia, mes, ano, hora, minuto] = match.map(Number);
            const dataCelula = new Date(ano, mes - 1, dia, hora, minuto);

            // Se a data for hoje e a hora for maior que a atual -> Vermelho fosco
            if (dataCelula.toDateString() === agora.toDateString() && dataCelula > agora) {
                td.style.backgroundColor = "#c46b6b"; // Vermelho fosco
                td.style.color = "#222"; // Texto com melhor contraste
            }

            // Se a data for de amanhã -> Laranja fosco
            if (dataCelula.toDateString() === amanha.toDateString()) {
                td.style.backgroundColor = "#e5a04c"; // Laranja fosco
                td.style.color = "#333"; // Melhor contraste
            }
        }
    });
});


function filtrarTabela(coluna) {
    let input = document.querySelectorAll("th input")[coluna].value.toLowerCase();
    let tabela = document.querySelector("table");
    let linhas = tabela.getElementsByTagName("tr");

    for (let i = 1; i < linhas.length; i++) { // Começa em 1 para ignorar os cabeçalhos
        let celula = linhas[i].getElementsByTagName("td")[coluna];
        if (celula) {
            let texto = celula.textContent.toLowerCase();
            linhas[i].style.display = texto.includes(input) ? "" : "none";
        }
    }
}


</script>

</body>
</html>
