<?php
include('banco_e_functions_gerais.php');




$message = ""; // Variável para armazenar a mensagem de sucesso ou erro

// Cadastrar novo usuário
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cadastro'])) {
    $usuario = $_POST['username'];
    $usuario_nome = $_POST['username'];
    $senha = $_POST['senha'];
    $nivel = $_POST['nivel_permissao'];
    $idioma = $_POST['idioma'];
    $nome_empresa = $_POST['nome_empresa'];
    $email = $_POST['email'];

    // Prevenir SQL Injection
    $usuario = $conn->real_escape_string($usuario);
    $senha = password_hash($conn->real_escape_string($senha), PASSWORD_DEFAULT);
    $nivel = (int) $nivel; // Garantir que o nível seja um número inteiro válido

    // Verificar se o nome de usuário já existe
    $sql_check = "SELECT * FROM usuarios WHERE username = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $usuario);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $message = "Nome de usuário já existe!";
    } else {
        // Inserir o novo usuário
        $sql = "INSERT INTO usuarios (username, senha, nivel_permissao, idioma, nome_empresa, email) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssisss", $usuario, $senha, $nivel, $idioma, $nome_empresa, $email);

        if ($stmt->execute()) {
            $message = "Usuário cadastrado com sucesso!";
        } else {
            $message = "Erro ao cadastrar usuário: " . $stmt->error;
        }

        // Gravar histórico
        $current_usuario = $_SESSION['usuario'];
        $current_id_usuario = $_SESSION['id_usuario'];
        $acao = "Usuário $current_usuario cadastrou o usuário $usuario_nome com nível $nivel.";
        $stmt = $conn->prepare("INSERT INTO historico (usuario_id, acao) VALUES (?, ?)");
        $stmt->bind_param("is", $current_id_usuario, $acao);
        $stmt->execute();
        $stmt->close();
    }
}

// Editar usuário
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar'])) {
    $id = $_POST['id'];
    $usuario = $_POST['username'];
    $usuario_nome = $_POST['username'];
    $nivel = $_POST['nivel_permissao'];
    $idioma = $_POST['idioma'];
    $nome_empresa = $_POST['nome_empresa'];
    $email = $_POST['email'];
    //echo " idiom $idioma";
    // Prevenir SQL Injection
    $usuario = $conn->real_escape_string($usuario);
    $nivel = (int) $nivel;

    // Atualizar usuário
    $sql = "UPDATE usuarios SET idioma = ?, nivel_permissao = ?, nome_empresa = ?, email= ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sissi", $idioma, $nivel, $nome_empresa, $email, $id);

    if ($stmt->execute()) {
        $message = "Usuário atualizado com sucesso!";
    } else {
        $message = "Erro ao atualizar usuário: " . $stmt->error;
    }



    if ($nivel == '0'){
        // Caminho para o diretório de sessões
        $sessionDir = '/var/lib/php/sessions/';
        $sessionFiles = glob($sessionDir . 'sess_*');
        // Verifica se encontrou arquivos de sessão
        if ($sessionFiles !== false && !empty($sessionFiles)) {
            // Percorre todos os arquivos de sessão
            foreach ($sessionFiles as $file) {
                // Lê o conteúdo do arquivo de sessão
                $conteudo = file_get_contents($file);

                // Verifica se o nome de usuário está presente no conteúdo do arquivo
                if (strpos($conteudo, $usuario) !== false) {
                    // Exclui o arquivo de sessão físico
                    unlink($file);

                    echo "A sessão do usuário $usuario_nome foi finalizada com sucesso!<br>";
                }
            }
        } else {
            echo "Nenhum arquivo de sessão encontrado.";
        }
    }



    // Gravar histórico
    $current_usuario = $_SESSION['usuario'];
    $current_id_usuario = $_SESSION['id_usuario'];
    $acao = "Usuário $current_usuario atualizou usuário $usuario_nome para nível $nivel.";
    $stmt = $conn->prepare("INSERT INTO historico (usuario_id, acao) VALUES (?, ?)");
    $stmt->bind_param("is", $current_id_usuario, $acao);
    $stmt->execute();
    $stmt->close();
}

// Buscar todos os usuários
$sql_users = "SELECT * FROM usuarios";
$result_users = $conn->query($sql_users);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Usuários</title>
    <style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    color: #e0e0e0; /* Texto em cinza claro */
}

.container {
    max-width: 1200px;
    margin: 50px auto;
    padding: 20px;
    background-color: #2c2c2c; /* Fundo cinza escuro */
    border-radius: 0px; /* Remover bordas arredondadas */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); /* Sombra mais escura */
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table, th, td {
    border: 1px solid #444; /* Borda cinza escuro */
}

h1 {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 20px;
    color: #e0e0e0; /* Texto em cinza claro */
}

th, td {
    padding: 10px;
    text-align: left;
}

th {
    background-color: #333; /* Fundo cinza escuro */
    color: #fff; /* Texto branco */
}

input[type="text"], input[type="password"], select {
    width: 100%; /* Garantir que o select tenha 100% da largura */
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #555; /* Borda cinza médio */
    border-radius: 0px; /* Remover bordas arredondadas */
    font-size: 16px;
    background-color: #2c2c2c; /* Fundo cinza escuro */
    color: #fff; /* Texto branco */
    box-sizing: border-box; /* Garantir que padding e borda não aumentem a largura */
}

select {
    padding-right: 20px; /* Ajuste para evitar overflow do conteúdo */
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
}

button {
    width: auto;
    padding: 10px;
    font-size: 16px;
    background-color: #333; /* Preto fosco */
    color: white;
    border: 1px solid #555; /* Borda cinza escuro */
    border-radius: 0px; /* Remover bordas arredondadas */
    cursor: pointer;
    transition: background-color 0.3s ease, border 0.3s ease;
}

button:hover {
    background-color: #555; /* Cinza escuro */
    border: 1px solid #444; /* Borda cinza escuro */
}

h2 {
    text-align: center;
    color: #e0e0e0; /* Texto em cinza claro */
}

.toast {
    visibility: hidden;
    max-width: 50%;
    margin: 10px auto;
    background-color: #333; /* Fundo cinza escuro */
    color: #fff;
    text-align: center;
    border-radius: 0px; /* Remover bordas arredondadas */
    padding: 10px;
    position: fixed;
    top: 10px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 9999;
}

.toast.show {
    visibility: visible;
    animation: fadeInOut 4s forwards;
}

@keyframes fadeInOut {
    0% { opacity: 0; top: 0; }
    20% { opacity: 1; top: 10px; }
    80% { opacity: 1; top: 10px; }
    100% { opacity: 0; top: 0; }
}

.formulario {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    width: 100%;
}

.coluna {
    width: 30%;
}

input, select, button {
    width: 100%;
    box-sizing: border-box; /* Garantir que padding e borda não aumentem a largura */
    margin-bottom: 10px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 0px; /* Remover bordas arredondadas */
}

select {
    padding-right: 10px; /* Ajuste para evitar overflow do conteúdo */
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
}





    </style>

</head>
<body>

<div class="container">
    <h2><?php echo texto_idioma($idioma, 'cad_user'); ?></h2>
    <form action="" method="POST" class="formulario">
    <div class="coluna">
        <input type="text" name="username" placeholder="<?php echo texto_idioma($idioma, 'user_name'); ?>" required>
        <input type="text" name="nome_empresa" placeholder="Nome da Empresa" required>

    </div>
    <div class="coluna">
        <input type="password" name="senha" placeholder="<?php echo texto_idioma($idioma, 'senha'); ?>" required>
        <select name="idioma">
            <option value="portugues">Idioma/Language</option>
            <option value="portugues">Português</option>
            <option value="ingles">English</option>
            <option value="espanhol">Español</option>
        </select>
    </div>
    <div class="coluna">
    <input type="text" name="email" placeholder="E-Mail" required>
        <select name="nivel_permissao">
            <option value="1"><?php echo texto_idioma($idioma, 'nivel'); ?> 1 Cliente</option>
            <option value="3"><?php echo texto_idioma($idioma, 'nivel'); ?> 3 Técnico</option>
            <option value="5"><?php echo texto_idioma($idioma, 'nivel'); ?> 5 Full</option>
        </select>
        <button type="submit" name="cadastro"><?php echo texto_idioma($idioma, 'cadastrar'); ?></button>
    </div>
</form>


    <!-- Tabela de Usuários -->
    <h2><?php echo texto_idioma($idioma, 'list_users'); ?></h2>
    <table>
        <thead>
            <tr>
                <th><?php echo texto_idioma($idioma, 'user_nivel_perm'); ?></th>
                <th><?php echo texto_idioma($idioma, 'acoes'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result_users->num_rows > 0) {
                while ($row = $result_users->fetch_assoc()) {
                    echo "<tr>
                            <td>
                                <form action='' method='POST' style='display:flex; gap: 10px; align-items: center;'>
                                    <input type='hidden' name='id' value='{$row['id']}'>
                                    <input type='text' name='username' value='{$row['username']}' readonly>
                                    <input type='text' name='nome_empresa' value='{$row['nome_empresa']}' >
                                    <input type='text' name='email' value='{$row['email']}' >
                                    <select name='nivel_permissao' style='width: 140px;'>
                                        <option value='0' ".($row['nivel_permissao'] == 0 ? 'selected' : '').">".texto_idioma($idioma, 'nivel')." 0 Desativado</option>
                                        <option value='1' ".($row['nivel_permissao'] == 1 ? 'selected' : '').">".texto_idioma($idioma, 'nivel')." 1 Cliente</option>
                                        <option value='3' ".($row['nivel_permissao'] == 3 ? 'selected' : '').">".texto_idioma($idioma, 'nivel')." 3 Técnico</option>
                                        <option value='5' ".($row['nivel_permissao'] == 5 ? 'selected' : '').">".texto_idioma($idioma, 'nivel')." 5 Full</option>
                                    </select>
                                    <select name='idioma' style='width: 140px;'>
                                        <option value='portugues' ".($row['idioma'] == 'portugues' ? 'selected' : '').">Português</option>
                                        <option value='ingles' ".($row['idioma'] == 'ingles' ? 'selected' : '').">English</option>
                                        <option value='espanhol' ".($row['idioma'] == 'espanhol' ? 'selected' : '').">Español</option>
                                    </select>
                                    <th>
                                    <button type='submit' name='editar'>".texto_idioma($idioma, 'atualizar')."</button>
                                    </th>
                                </form>
                            </td>
                        </tr>";
                }
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Toast de mensagem -->
<div id="toast" class="toast">
    <?php echo isset($message) ? $message : ''; ?>
</div>

<script>
// Função para mostrar o toast
function showToast(message) {
    var toast = document.getElementById("toast");
    toast.textContent = message; // Define o conteúdo do toast
    toast.classList.add("show"); // Adiciona a classe para mostrar o toast

    // Remove a classe 'show' após o tempo da animação
    setTimeout(function() {
        toast.classList.remove("show");
    }, 4000); // A duração da animação do toast
}

// Verifica se há uma mensagem de sucesso ou erro para exibir o toast
<?php if (isset($message) && !empty($message)): ?>
    window.onload = function() {
        showToast('<?php echo addslashes($message); ?>');
    };
<?php endif; ?>
</script>

</body>
</html>
<?php
if (isset($conn)) {
    $conn->close();
    $conn = null;
}
?>