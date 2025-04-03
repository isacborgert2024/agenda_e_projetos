<?php
// Incluindo o arquivo de conexão
include 'banco_e_functions_gerais.php'; // A conexão com o banco de dados

// Conectar ao banco de dados
$conn = conectar_banco();

// Adicionar data
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["adicionar"])) {
    $data = $_POST["data_bloqueada"];
    
    // Verifica se a data foi fornecida e não está vazia
    if (!empty($data)) {
        $query = "INSERT INTO datas_bloqueadas (data_bloqueada) VALUES (?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $data); // "s" para string
        
        $log = "Usuário $nome_usuario adicionou o bloqueio de data $data";
        registrarLog($conn, $log);

        if ($stmt->execute()) {
            echo "<script>window.onload = function() { showToast('Data adicionada com sucesso!'); }</script>";

        } else {
            echo "<script>alert('Erro ao adicionar data: " . $conn->error . "');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('A data não pode ser vazia!');</script>";
    }
}

// Editar data
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["editar"])) {
    $id = $_POST["id"];
    $data = $_POST["data_bloqueada"];
    
    // Verifica se o ID e a nova data não estão vazios
    if (!empty($id) && !empty($data)) {
        $query = "UPDATE datas_bloqueadas SET data_bloqueada = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $data, $id); // "si" para string e inteiro
        
        $log = "Usuário $nome_usuario editou o bloqueio de data $data";
        registrarLog($conn, $log);

        if ($stmt->execute()) {
            echo "<script>window.onload = function() { showToast('Data editada com sucesso!'); }</script>";

        } else {
            echo "<script>alert('Erro ao editar data: " . $conn->error . "');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('ID ou Data não podem ser vazios!');</script>";
    }
}

// Excluir data
if (isset($_GET["excluir"])) {
    $id = $_GET["excluir"];
    
    // Verifica se o ID não está vazio
    if (!empty($id)) {
        $query = "DELETE FROM datas_bloqueadas WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id); // "i" para inteiro
        
        $log = "Usuário $nome_usuario excluiu o bloqueio de data $data";
        registrarLog($conn, $log);

        if ($stmt->execute()) {
            echo "<script>window.onload = function() { showToast('Data excluída com sucesso!'); }</script>";

        } else {
            echo "<script>alert('Erro ao excluir data: " . $conn->error . "');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('ID inválido para exclusão!');</script>";
    }
}

// Listar todas as datas bloqueadas
$query = "SELECT * FROM datas_bloqueadas ORDER BY data_bloqueada";
$result = $conn->query($query);
$datas_bloqueadas = [];
if ($result->num_rows > 0) {
    while ($data = $result->fetch_assoc()) {
        $datas_bloqueadas[] = $data;
    }
}

// Fechar a conexão com o banco de dados
$conn->close();
?>




<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            font-family: Arial, sans-serif;
            
            color: #fff;
            padding: 20px;
        }
        .container {
            background-color: #333;
            width: 800px; /* Tamanho fixo do contêiner */
            max-width: 80%; /* Garante que o contêiner nunca ultrapasse 100% da largura da tela */
            margin: 0 auto; /* Centraliza o contêiner na tela */
            padding: 20px; /* Adiciona um pouco de espaçamento interno */
        }

        h1, h2 {
            color: #f8f8f8;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #555;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        input[type="date"], input[type="submit"], button {
            padding: 8px;
            margin: 5px;
        }
        .btn {
            background-color: #444;
            border: 1px solid #666;
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #555;
        }
        .btn:focus {
            outline: none;
        }
        #formEditar {
            background-color: #444;
            padding: 20px;
            border-radius: 5px;
            display: none;
            margin-top: 20px;
        }
        .form-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .form-container form {
            flex: 1;
            max-width: 300px;
        }
        /* Estilo do Toast */
        .toast {
            visibility: hidden;
            min-width: 250px;
            max-width: 500px; /* Máximo para a largura */
            max-height: 50px; /* Máximo para a altura */
            margin-left: -125px; /* Para centralizar horizontalmente */
            background-color: rgba(52, 58, 52, 0.6); /* Verde fosco com opacidade */
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

    
    </style>
</head>
<body>


<div class="container">


    <!-- Formulário para adicionar uma nova data -->
    <h2>Adicionar Data Bloqueada</h2>
    <form method="POST">
        <div class="form-container">
            <label for="data_bloqueada">Data:</label>
            <input type="date" id="data_bloqueada" name="data_bloqueada" required>
            <input type="submit" name="adicionar" value="Adicionar" class="btn">
        </div>
    </form>

    <!-- Listagem de datas bloqueadas -->
        <h2>Datas Bloqueadas</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Data Bloqueada</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($datas_bloqueadas as $data): ?>
            <tr>
                <td><?php echo $data['id']; ?></td>
                <td><?php echo $data['data_bloqueada']; ?></td>
                <td>
                    <!-- Botão para editar -->
                    <button onclick="editarData(<?php echo $data['id']; ?>, '<?php echo $data['data_bloqueada']; ?>')" class="btn">Editar</button>
                    <!-- Botão para excluir -->
                    <a href="?excluir=<?php echo $data['id']; ?>" class="btn">Excluir</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Formulário para editar uma data -->
    <div id="formEditar">
        <h2>Editar Data Bloqueada</h2>
        <form method="POST">
            <input type="hidden" id="id" name="id">
            <div class="form-container">
                <label for="data_bloqueada_edit">Nova Data:</label>
                <input type="date" id="data_bloqueada_edit" name="data_bloqueada" required>
                <input type="submit" name="editar" value="Salvar Alterações" class="btn">
            </div>
        </form>
    </div>
</div>


<script>

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
 



// Função para preencher o formulário de edição
function editarData(id, data) {
    document.getElementById("id").value = id;
    document.getElementById("data_bloqueada_edit").value = data;
    document.getElementById("formEditar").style.display = "block";
}
</script>

</body>
</html>
