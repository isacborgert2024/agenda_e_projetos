<?php
include 'banco_e_functions_gerais.php';
include 'envia_email_gmail.php';

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'salvar':
        salvarAgenda();
        break;
    case 'excluir':
        excluirAgenda();
        break;
    case 'listar_tecnicos':
        listarTecnicos();
        break;
    default:
        echo json_encode(["error" => "Ação inválida"]);
}


function salvarAgenda() {
    global $conn, $usuario_id, $nome_usuario, $action;
    
    // Recebendo dados do POST
    $id = $_POST['id_agenda'] ?? '';
    $id_tecnico = $_POST['id_usuario_tecnico'] ?? '';
    $dados = $_POST['dados'] ?? '';
    $data_agenda = $_POST['data_agenda'] ?? '';
    $hora_agenda = $_POST['hora_agenda'] ?? ''; // Exemplo: '08:00'
    $data_agenda = $data_agenda . ' ' . $hora_agenda . ':00';
    $materiais_necessarios = $_POST['materiais_necessarios'] ?? '';
    $responsavel_local = $_POST['responsavel_local'] ?? '';
    $fone_responsavel_local = $_POST['fone_responsavel_local'] ?? '';
    $horas_execucao = $_POST['horas_execucao'] ?? 1; // Valor padrão 1
    $data_agenda = date('Y-m-d H:i:s', strtotime($data_agenda));
    $conclusao = $_POST['conclusao'] ?? '';

    // Consulta para pegar o e-mail do usuário com id = 1
    $sql = "SELECT email FROM usuarios WHERE id = $id_tecnico";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Pega o e-mail do registro
        $row = $result->fetch_assoc();
        $email_tecnico = $row['email']; // Armazena o e-mail na variável
    } else {
        $email_tecnico = ''; 
    }

    $data_atual = new DateTime();
    $data_3_dias = $data_atual->modify('+3 days');
 
    // Verifique se a data_agenda é maior que a data de 3 dias (posterior)
    $data_agenda_obj = new DateTime($data_agenda); // Certifique-se de que $agenda['data_agenda'] está no formato adequado

    if ($data_agenda_obj > $data_3_dias) {
        // Obter a hora atual

        $hora_agenda_obj = new DateTime($hora_agenda);
        $hora_agenda = (int) $hora_agenda_obj->format('H');  // Extrai apenas a hora
        // Verificar se a hora está entre 8h e 18h
        if ($hora_agenda >= 8 && $hora_agenda <= 18) {
            $aprovada = 'Aprovada';
        } else {
            $aprovada = 'Aprovação Pendente'; // Se a hora não estiver no intervalo
        }
    } else {
        $aprovada = 'Aprovação Pendente'; // Se a data não for daqui a 2 dias
    }

    $data_agenda_formatada = $data_agenda_obj->format('Y-m-d'); // Formato de data para comparar com o banco

    // Consulta para verificar se a data está na tabela datas_bloqueadas
    $query = "SELECT * FROM datas_bloqueadas WHERE data_bloqueada = '$data_agenda_formatada'";
    $result = mysqli_query($conn, $query);

    // Verifica se a data existe na tabela
    if (mysqli_num_rows($result) > 0) {
        // A data está bloqueada
        $aprovada = 'Aprovação Pendente';
    } 

    $diaSemana = $data_agenda_obj->format('N'); // Obtém o dia da semana (1 = Segunda, 7 = Domingo)

    // Se for sábado (6) ou domingo (7), define como "Aprovação Pendente"
    if ($diaSemana == 6 || $diaSemana == 7) {
        $aprovada = 'Aprovação Pendente';
    }


    $nivel_usuario = $_SESSION['nivel_usuario'];
    if ($nivel_usuario == 5 or $nivel_usuario == 3){
        $aprovada = $_POST['aprovacao'] ?? '';
    }
    // Verificando se os dados obrigatórios estão presentes
    if (empty($id_tecnico) || empty($dados) || empty($data_agenda) || empty($responsavel_local) || empty($fone_responsavel_local)) {
        echo json_encode(["error" => "Todos os campos obrigatórios devem ser preenchidos."]);
        return;
    }
   
    // Atualização ou inserção
    if ($id) {
        //// Registra o historico de ações do usuário
        $log = "Usuário $nome_usuario editou a agenda id $id, id do técnico é $id_tecnico Dados do serviço: $dados  Materiais: $materiais_necessarios  Data de execução: $data_agenda";
        registrarLog($conn, $log);


        // Atualiza a agenda existente
        $stmt = $conn->prepare("UPDATE agenda SET 
                                id_usuario_tecnico=?, dados=?, data_agenda=?, materiais_necessarios=?, responsavel_local=?, fone_responsavel_local=?, horas_execucao=?, aprovada=?, conclusao=?
                                WHERE id=?");
        $stmt->execute([$id_tecnico, $dados, $data_agenda, $materiais_necessarios, $responsavel_local, $fone_responsavel_local, $horas_execucao, $aprovada, $conclusao, $id]);

        if($id_tecnico == $usuario_id){
            $sql = "SELECT id_usuario_cliente FROM agenda WHERE id = $id";
            $result = $conn->query($sql);
        
            if ($result->num_rows > 0) {
                // Pega o e-mail do registro
                $row = $result->fetch_assoc();
                $id_cliente = $row['id_usuario_cliente']; // Armazena o e-mail na variável
                $sql = "SELECT email FROM usuarios WHERE id = $id_cliente";
                $result = $conn->query($sql);
            
                if ($result->num_rows > 0) {
                    // Pega o e-mail do registro
                    $row = $result->fetch_assoc();
                    $email_cliente = $row['email']; // Armazena o e-mail na variável
                } else {
                    $email_cliente = ''; 
                }

            } else {
                $email_cliente = ''; 
            }
        }


        if($nome_usuario === 'isac.borgert' or $nome_usuario === 'usuario_de_teste_L1' or $nome_usuario === 'usuario_de_teste_L3'){
            if($email_tecnico != 'isacborgert@gmail.com')
                $email_tecnico = "$email_tecnico, isacborgert@gmail.com";
        }else{
            if($email_tecnico != 'leandro.debetio@gmail.com')
                $email_tecnico = "$email_tecnico, leandro.debetio@gmail.com";
        }



        if($id_tecnico == $usuario_id)
            enviarEmail("$email_cliente", "User $nome_usuario, atualizou a agenda id $id", "Dados do serviço: $dados <br> Materiais: $materiais_necessarios <br> Data de execução: $data_agenda");
        else
            enviarEmail("$email_tecnico", "User $nome_usuario, atualizou a agenda id $id", "Dados do serviço: $dados <br> Materiais: $materiais_necessarios <br> Data de execução: $data_agenda");
        
        echo json_encode(["message" => "Agenda atualizada com sucesso"]);
    } else {
        //// Registra o historico de ações do usuário
        $log = "Usuário $nome_usuario criou uma nova agenda, id do técnico é $id_tecnico Dados do serviço: $dados  Materiais: $materiais_necessarios  Data de execução: $data_agenda";
        registrarLog($conn, $log);
        // Insere uma nova agenda
        $stmt = $conn->prepare("INSERT INTO agenda 
                                (id_usuario_cliente, id_usuario_tecnico, dados, data_agenda, materiais_necessarios, responsavel_local, fone_responsavel_local, horas_execucao, aprovada, conclusao) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$usuario_id, $id_tecnico, $dados, $data_agenda, $materiais_necessarios, $responsavel_local, $fone_responsavel_local, $horas_execucao, $aprovada, $conclusao]);
        $_GET['action'] = $id;
        //,leandro.debetio@gmail.com
        if($nome_usuario === 'isac.borgert' or $nome_usuario === 'usuario_de_teste_L1' or $nome_usuario === 'usuario_de_teste_L3'){
            if($email_tecnico != 'isacborgert@gmail.com')
                $email_tecnico = "$email_tecnico, isacborgert@gmail.com";
        }else{
            if($email_tecnico != 'leandro.debetio@gmail.com')
                $email_tecnico = "$email_tecnico, leandro.debetio@gmail.com";
        }

        enviarEmail("$email_tecnico", "User $nome_usuario, criou uma nova agenda", "Dados do serviço: $dados <br> Materiais: $materiais_necessarios <br> Data de execução: $data_agenda");
        echo json_encode(["message" => "Agenda cadastrada com sucesso"]);
    }
}

function excluirAgenda() {
    global $conn, $nome_usuario, $action;

    $id = $_GET['id'] ?? '';
    if ($id) {
        $log = "Usuário $nome_usuario excluiu a agenda id $id Dados do serviço: $dados  Materiais: $materiais_necessarios  Data de execução: $data_agenda";
        registrarLog($conn, $log);


        $sql = "SELECT * FROM agenda WHERE id = $id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Pega o e-mail do registro
            $row = $result->fetch_assoc();
            $id_cliente = $row['id_usuario_cliente']; // Armazena o e-mail na variável
            $dados = $row['dados'] ?? '';
            $data_agenda = $row['data_agenda'] ?? '';
            $hora_agenda = $row['hora_agenda'] ?? ''; // Exemplo: '08:00'
            $data_agenda = $data_agenda . ' ' . $hora_agenda . ':00';
            $materiais_necessarios = $row['materiais_necessarios'] ?? '';

            
            $sql = "SELECT email FROM usuarios WHERE id = $id_cliente";
            $result = $conn->query($sql);
        
            if ($result->num_rows > 0) {
                // Pega o e-mail do registro
                $row2 = $result->fetch_assoc();
                $email_cliente = $row2['email']; // Armazena o e-mail na variável
            } else {
                $email_cliente = ''; 
            }

            $id_tecnico = $row['id_usuario_tecnico']; // Armazena o e-mail na variável
            $sql = "SELECT email FROM usuarios WHERE id = $id_tecnico";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Pega o e-mail do registro
                $row = $result->fetch_assoc();
                $email_tecnico = $row['email']; // Armazena o e-mail na variável
            } else {
                $email_tecnico = ''; 
            }

        } else {
            $email_cliente = ''; 
            $email_tecnico = ''; 
        }


        $stmt = $conn->prepare("DELETE FROM agenda WHERE id=?");
        $stmt->execute([$id]);
        

        if($nome_usuario === 'isac.borgert' or $nome_usuario === 'usuario_de_teste_L1' or $nome_usuario === 'usuario_de_teste_L3'){
            if($email_tecnico != 'isacborgert@gmail.com')
                $email_tecnico = "$email_tecnico, $email_cliente, isacborgert@gmail.com";
            else 
                $email_tecnico = "$email_tecnico, $email_cliente";
        }else{
            if($email_tecnico != 'leandro.debetio@gmail.com')
                $email_tecnico = "$email_tecnico, $email_cliente, leandro.debetio@gmail.com";
            else 
                $email_tecnico = "$email_tecnico, $email_cliente";
        }

        enviarEmail($email_tecnico, "User $nome_usuario, excluiu a agenda id: $id", "Dados do serviço: $dados <br> Materiais: $materiais_necessarios <br> Data de execução: $data_agenda");
        echo json_encode(["message" => "Agenda excluída com sucesso"]);
    } else {
        echo json_encode(["error" => "ID da agenda não informado"]);
    }
}

function listarTecnicos() {
    global $conn;
    $result = $conn->query("
    SELECT id, username 
    FROM usuarios 
    WHERE nivel_permissao = 5 OR nivel_permissao = 3 
    ORDER BY (username = 'leandro.pacheco') DESC, username ASC
");

    
    $tecnicos = [];
    while ($row = $result->fetch_assoc()) {
        $tecnicos[] = $row;
    }

    echo json_encode($tecnicos);
}
?>
