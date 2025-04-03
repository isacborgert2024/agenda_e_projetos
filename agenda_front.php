<?php
include 'banco_e_functions_gerais.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : null;
$agenda = null;

if ($id) {
    $query = "SELECT * FROM agenda WHERE id = $id";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $agenda = mysqli_fetch_assoc($result);
    }
}
?>


<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Agenda</title>

    <style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 20px;
    color: #ddd;

}

.container {
    width: 80%;
    margin: auto;
    background-color: rgba(30, 30, 30, 0.7);
    padding: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    margin-bottom: 20px;
}

h1, h2 {
    text-align: center;
}

form {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

label {
    display: flex;
    flex-direction: column;
    font-weight: bold;
}

input, textarea, select {
    padding: 8px;
    border: 1px solid #444;
    background-color: #222;
    color: #ddd;
    width: 100%;
    box-sizing: border-box;
}
textarea {
    height: 150px; /* Ajuste o valor conforme a necessidade */
}
button {
    padding: 10px;
    background: #333;
    color: white;
    border: none;
    cursor: pointer;
    font-size: 16px;
    width: 100%;
}

button:hover {
    background: #555;
}
.toast {
visibility: hidden;
min-width: 250px;
max-width: 500px; /* Máximo para a largura */
max-height: 50px; /* Máximo para a altura */
margin-left: -125px; /* Para centralizar horizontalmente */
background-color: rgba(99, 107, 99, 0.6); /* Verde fosco com opacidade */
color: #fff;
text-align: center;
border-radius: 5px;
padding: 10px;
position: fixed;
z-index: 9999;
left: 50%;
top: 50%; /* Centraliza verticalmente */
transform: translate(-50%, -50%); /* Ajusta a posição exata do centro */
font-size: 16px;
opacity: 0;
transition: opacity 0.5s, bottom 0.5s ease-in-out;
overflow: hidden; /* Evita que o conteúdo ultrapasse os limites do toast */
}

.toast.show {
visibility: visible;
opacity: 1;
bottom: 50px;
}

#loading-container {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    background-color: rgba(0, 0, 0, 0.5);
    padding: 20px;
    border-radius: 8px;
    color: #fff;
    z-index: 9999;
}

#loading-circle {
    border: 4px solid #f3f3f3;
    border-top: 4px solid #3498db;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    animation: spin 2s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}



    </style>
</head>
<?php
if($nivel_usuario == 3)
    $data_minima = date('Y-m-d', strtotime('-3 days'));
elseif($nivel_usuario == 5)
    $data_minima = date('Y-m-d', strtotime('-30 days')); // Permite até 2 dias atrás
else 
    $data_minima = date('Y-m-d');

?>
<body>
    <!-- Adicione isso ao seu HTML -->
<div id="loading-container" style="display:none;">
    <div id="loading-circle"></div>
    <span>Enviando e-mails, aguarde...</span>
</div>


    <div class="container">
        <h1>Cadastro de Agenda</h1>
        <form id="agenda-form">
            <input type="hidden" id="id_agenda" name="id_agenda" value="<?php echo $id; ?>"> 

            <label>Executor: 
                <select id="id_usuario_tecnico" name="id_usuario_tecnico" required>
                    <!-- As opções serão preenchidas dinamicamente -->
                </select>
            </label>

            <div style="display: flex; gap: 10px;">
                <label>Data da Agenda:
                    <input type="date" id="data_agenda" name="data_agenda" 
                        value="<?php echo $agenda ? date('Y-m-d', strtotime($agenda['data_agenda'])) : ''; ?>" 
                        min="<?php echo $data_minima; ?>" required>
                </label>

                <label>Horário:
                    <select id="hora_agenda" name="hora_agenda" required title="Horas em vermelho já existe agenda">
                        <?php
                        // Preenche o campo de hora_agenda
                        $horaAgenda = $agenda ? date('H', strtotime($agenda['data_agenda'])) : '';
                        for ($i = 0; $i < 24; $i++) {
                            $hora = str_pad($i, 2, "0", STR_PAD_LEFT) . ":00";
                            echo "<option value='$hora' " . ($horaAgenda == $i ? 'selected' : '') . ">$hora</option>";
                        }
                        ?>
                    </select>
                </label>

                <label>Tempo de Execução:
                    <select id="horas_execucao" name="horas_execucao" required>
                        <?php
                        // Preenche o campo de horas_execucao
                        $tempoExecucao = $agenda ? $agenda['horas_execucao'] : '';
                        for ($i = 1; $i <= 24; $i++) {
                            echo "<option value='$i' " . ($tempoExecucao == $i ? 'selected' : '') . ">$i</option>";
                        }
                        ?>
                    </select>
                </label>
                <?php if ($nivel_usuario != 1 and $nivel_usuario != 2): ?>
                    <label>Aprovação: 
                        <select id="aprovacao" name="aprovacao" required>
                            <option value="Aprovação Pendente" <?php echo ($agenda['aprovada'] == 'Aprovação Pendente') ? 'selected' : ''; ?>>Aprovação Pendente</option>
                            <option value="Aprovada" <?php echo ($agenda['aprovada'] == 'Aprovada') ? 'selected' : ''; ?>>Aprovada</option>
                            <option value="Rejeitada" <?php echo ($agenda['aprovada'] == 'Rejeitada') ? 'selected' : ''; ?>>Rejeitada</option>
                            <option value="Em Execução" <?php echo ($agenda['aprovada'] == 'Em Execução') ? 'selected' : ''; ?>>Em Execução</option>
                            <option value="Concluída" <?php echo ($agenda['aprovada'] == 'Concluída') ? 'selected' : ''; ?>>Concluída</option>
                        </select>
                    </label>
                <?php endif; ?>
            </div>
           
            <label>Dados do Serviço: 
                <textarea id="dados" name="dados"><?php echo $agenda ? htmlspecialchars($agenda['dados']) : ''; ?></textarea>
            </label>

            <label>Materiais Necessários: 
                <textarea id="materiais_necessarios" name="materiais_necessarios"><?php echo $agenda ? htmlspecialchars($agenda['materiais_necessarios']) : ''; ?></textarea>
            </label>

            <label>Técnico Local: 
                <input type="text" id="responsavel_local" name="responsavel_local" 
                    value="<?php echo $agenda ? htmlspecialchars($agenda['responsavel_local']) : ''; ?>"
                    placeholder="Pessoa que estará no local, caso o executor precise." required>
                Fone:
                <input type="text" id="fone_responsavel_local" name="fone_responsavel_local" 
                    value="<?php echo $agenda ? htmlspecialchars($agenda['fone_responsavel_local']) : ''; ?>"
                    placeholder="Telefone do técnico." required>
            </label>
            <?php if ($nivel_usuario != 1 and $nivel_usuario != 2): ?>
                <label>Conclusão: 
                    <textarea id="conclusao" name="conclusao" placeholder="Aqui o executor do serviço fará suas considerações finais."><?php echo $agenda ? htmlspecialchars($agenda['conclusao']) : ''; ?></textarea>
                </label>
            <?php endif; ?>
            <?php if ($nivel_usuario == 1 or $nivel_usuario == 2): ?>
                <label>Conclusão: 
                    <textarea id="conclusao" name="conclusao" placeholder="Aqui o executor do serviço fará suas considerações finais." readonly><?php echo $agenda ? htmlspecialchars($agenda['conclusao']) : ''; ?></textarea>
                </label>
            <?php endif; ?>
            <button type="submit" id="submit-btn"><?php echo $agenda ? 'Atualizar' : 'Cadastrar'; ?></button>
            <button type="button" onclick="cancelar()">Cancelar</button>
        </form>


    </div>

<script>
    // Função para carregar os técnicos (comentada por enquanto)
    
function carregarTecnicos() {
    fetch("agenda_back.php?action=listar_tecnicos")
        .then(response => response.json())
        .then(data => {
            const tecnicoSelect = document.getElementById("id_usuario_tecnico");

            // Preenche as opções dinamicamente
            data.forEach(tecnico => {
                const option = document.createElement("option");
                option.value = tecnico.id;
                option.textContent = tecnico.username;

                // Se estiver editando, seleciona o técnico que já foi atribuído
                <?php if (isset($agenda['id_usuario_tecnico'])): ?>
                    if (tecnico.id == <?php echo $agenda['id_usuario_tecnico']; ?>) {
                        option.selected = true;
                    }
                <?php endif; ?>

                tecnicoSelect.appendChild(option);
            });
        })
        .catch(error => console.error("Erro ao carregar técnicos:", error));
    }

    // Carregar técnicos quando a página carregar
    window.onload = function() {
        carregarTecnicos();
};

// Submissão do formulário
document.getElementById("agenda-form").addEventListener("submit", function(event) {
    event.preventDefault();
    salvarAgenda();
});

// Função para salvar a agenda
function salvarAgenda() {
    const formData = new FormData(document.getElementById("agenda-form"));

    // Exibir o carregamento
    document.getElementById("loading-container").style.display = "flex";

    fetch("agenda_back.php?action=salvar", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        showToast(data.message);
        setTimeout(() => {
            console.log("Pausa concluída!");
            location.reload(); // Recarrega a página após salvar com sucesso
        }, 2000);
    })
    .catch(error => console.error("Erro ao salvar agenda:", error))
    .finally(() => {
        // Esconde o carregamento quando a resposta for recebida
        document.getElementById("loading-container").style.display = "none";
    });
}



// Função para cancelar
function cancelar() {
    window.location.href = "agenda_front.php"; // Redireciona para a tela de cadastro (sem ID)
}

function atualizarHorariosOcupados() {
    let idTecnico = document.getElementById("id_usuario_tecnico").value;
    let dataSelecionada = document.getElementById("data_agenda").value; // YYYY-MM-DD
    let idAgenda = document.getElementById("id_agenda").value; // Pega o valor do id_agenda

    // Só busca os horários se ambos estiverem preenchidos
    if (!idTecnico || !dataSelecionada) return;

    // Formar a URL com id_agenda, se estiver presente
    let url = `buscar_horarios_ocupados.php?id_usuario_tecnico=${idTecnico}&data_agenda=${dataSelecionada}`;
    if (idAgenda) {
        url += `&id_agenda=${idAgenda}`;  // Adiciona o id_agenda à URL se ele estiver presente
    }

    fetch(url)
        .then(response => response.json())
        .then(horariosOcupados => {
            let selectHora = document.getElementById("hora_agenda");
            let options = selectHora.options;

            for (let i = 0; i < options.length; i++) {
                let hora = options[i].value;
                if (horariosOcupados.includes(hora)) {
                    options[i].disabled = true; // Desativa horário ocupado
                    options[i].style.color = "red"; // Opcional: Destaca horário bloqueado
                } else {
                    options[i].disabled = false; // Habilita horário disponível
                    options[i].style.color = "white"; // Reseta a cor
                }
            }
        })
        .catch(error => console.error("Erro ao buscar horários:", error));
}


// Monitorar mudanças no técnico e na data
document.getElementById("id_usuario_tecnico").addEventListener("change", () => {
    if (document.getElementById("data_agenda").value) {
        atualizarHorariosOcupados();
    }
});

document.getElementById("data_agenda").addEventListener("change", () => {
    // Zerar os valores dos campos hora_agenda e horas_execucao
    document.getElementById("hora_agenda").value = '';
    document.getElementById("horas_execucao").value = '';

    if (document.getElementById("id_usuario_tecnico").value) {
        atualizarHorariosOcupados();
    }
});



document.getElementById('hora_agenda').addEventListener('change', function() {
    var horaAgenda = this.value;
    ajustarTempoExecucaoEdicaochamda(horaAgenda, 0);
});



var edicao = 0;
function ajustarTempoExecucaoEdicaochamda(horaAgenda, edicao) {
    var dataAgenda = document.getElementById('data_agenda').value;
    var tecnicoId = document.getElementById('id_usuario_tecnico').value;
    let idAgenda = document.getElementById("id_agenda").value; // Pega o valor do id_agenda

    console.log("Hora Selecionada:", horaAgenda);
    console.log("Data da Agenda:", dataAgenda);
    console.log("ID do Técnico:", tecnicoId);

    if (horaAgenda && dataAgenda && tecnicoId) {
        // Fazer requisição AJAX para buscar os horários ocupados
        let url = `buscar_horarios_ocupados.php?id_usuario_tecnico=${tecnicoId}&data_agenda=${dataAgenda}`;
        if (idAgenda) {
            url += `&id_agenda=${idAgenda}`;  // Adiciona o id_agenda à URL se ele estiver presente
        }
        fetch(url)
            .then(response => response.json())
            .then(horariosOcupados => {
                console.log("Horários Ocupados Recebidos:", horariosOcupados);
                ajustarTempoExecucaoEdicao(horariosOcupados, horaAgenda, edicao);
            })
            .catch(error => {
                console.error('Erro ao buscar horários ocupados:', error);
            });
    }
}

function ajustarTempoExecucaoEdicao(horariosOcupados, horaAgenda, edicao) {

    const tempoExecucaoSelect = document.getElementById('horas_execucao');
    console.log("se for 1 é edicao:", edicao);
    console.log("Ajustando Tempo de Execução...");
    var selectedValue;
    if(edicao == 1){
        console.log("estrou no primeiro if de edicao:", edicao);
        let selectedIndex = tempoExecucaoSelect.selectedIndex;
        var selectedValue = tempoExecucaoSelect.options[selectedIndex].value;
    }
    // Remove todas as opções
    while (tempoExecucaoSelect.options.length > 0) {
        tempoExecucaoSelect.remove(0);
    }
    if(edicao == 1){
        console.log("estrou no segundo if de edicao:", edicao);
        // Re-adiciona a opção selecionada
        let option = new Option(selectedValue, selectedValue, true, true);
        tempoExecucaoSelect.add(option);
    }
    const horaSelecionada = parseInt(horaAgenda.split(':')[0]); // Hora escolhida pelo usuário
    console.log("Hora Selecionada Convertida para Número:", horaSelecionada);
    
    let maxTempoExecucao = 24 - horaSelecionada;

    // Ordena os horários ocupados (extrai apenas a hora e ordena)
    horariosOcupados = horariosOcupados.map(h => parseInt(h.split(':')[0])); // Converte "H:00" em número inteiro
    horariosOcupados.sort((a, b) => a - b); // Ordena em ordem crescente

    console.log("Horários Ocupados Processados:", horariosOcupados);

    // Verifica a quantidade de tempo disponível até o próximo horário ocupado
    for (let ocupadoHora of horariosOcupados) {
        if (ocupadoHora > horaSelecionada) {
            maxTempoExecucao = ocupadoHora - horaSelecionada; // Limita até o próximo horário ocupado
            console.log("Próximo horário ocupado:", ocupadoHora, "Máximo permitido:", maxTempoExecucao);
            break;
        }
    }

    // Adiciona as opções no campo 'horas_execucao' com base no intervalo disponível
    for (let i = 1; i <= maxTempoExecucao; i++) {
        let option = document.createElement("option");
        option.value = i;
        option.textContent = i;
        tempoExecucaoSelect.appendChild(option);
    }

    console.log("Opções de Tempo de Execução Atualizadas. Máximo disponível:", maxTempoExecucao);
}

document.addEventListener('DOMContentLoaded', function() {
    // Espera 500ms para garantir que o campo id_agenda esteja preenchido antes de verificar
    setTimeout(function() {
        var idAgenda = document.getElementById('id_agenda').value;  // Pega o valor do campo id_agenda
        
        // Verifica se o id_agenda não está vazio
        if (idAgenda) {
            console.log('id_agenda não está vazio, atualizando horários...');
            
            // Chama a função de atualizar os horários ocupados
            atualizarHorariosOcupados();
            
            // Espera mais 500ms antes de chamar a função ajustarTempoExecucaoEdicaochamda
            setTimeout(function() {
                var horaAgenda = document.getElementById('hora_agenda').value;  // Pega a hora selecionada
                
                if (horaAgenda) {
                    console.log('Hora selecionada:', horaAgenda);

                    // Chama a função ajustarTempoExecucaoEdicaochamda diretamente
                    ajustarTempoExecucaoEdicaochamda(horaAgenda, 1);
                }
            }, 500); // Espera 500ms após a atualização dos horários para chamar a função
        } else {
            console.log('id_agenda está vazio');
        }
    }, 500); // Espera 500ms antes de verificar o valor de id_agenda
});


function showToast(message) {
    var toast = document.createElement("div");
    toast.classList.add("toast");
    toast.textContent = message;
    document.body.appendChild(toast);

    // Adiciona a classe para mostrar o toast
    setTimeout(function () {
        toast.classList.add("show");
    }, 100);

    // Esconde o toast após 3 segundos
    setTimeout(function () {
        toast.classList.remove("show");
        setTimeout(function () {
            toast.remove();
        }, 500);
    }, 3000);
}

    </script>
</body>
</html>
