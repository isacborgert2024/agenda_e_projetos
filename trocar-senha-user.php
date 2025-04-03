<?php
include 'banco_e_functions_gerais.php';

$usuarioLogado = $_SESSION['usuario'];
$sqlPermissao = "SELECT nivel_permissao FROM usuarios WHERE username = ?";
$stmtPermissao = $conn->prepare($sqlPermissao);
$stmtPermissao->bind_param("s", $usuarioLogado);
$stmtPermissao->execute();
$resultPermissao = $stmtPermissao->get_result();
$nivelPermissao = $resultPermissao->fetch_assoc()['nivel_permissao'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $senhaAntiga = $_POST['senha_antiga'];
    $senhaNova = $_POST['senha_nova'];
    $senhaConfirmacao = $_POST['senha_confirmacao'];
    $usuarioAlvo = ($nivelPermissao == 5 or $nivelPermissao == 3) ? $_POST['usuario_alvo'] : $usuarioLogado;

    // Prevenir SQL Injection
    $senhaAntiga = $conn->real_escape_string($senhaAntiga);
    $senhaNova = $conn->real_escape_string($senhaNova);
    $senhaConfirmacao = $conn->real_escape_string($senhaConfirmacao);
    $usuarioAlvo = $conn->real_escape_string($usuarioAlvo);

    // Validação da senha nova para garantir que tenha pelo menos uma letra maiúscula, uma minúscula e um número
    if (!preg_match('/[A-Z]/', $senhaNova) || !preg_match('/[a-z]/', $senhaNova) || !preg_match('/\d/', $senhaNova)) {
        $erro = "A nova senha deve conter pelo menos uma letra maiúscula, uma letra minúscula e um número.";
    } else {
        if ($nivelPermissao != 5 and $nivelPermissao != 3) {
            // Verifica se a senha antiga corresponde à senha armazenada no banco de dados
            $sql = "SELECT senha FROM usuarios WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $usuarioAlvo);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $usuarioData = $result->fetch_assoc();
                if (!password_verify($senhaAntiga, $usuarioData['senha'])) {
                    $erro = "Senha antiga incorreta.";
                }
            } else {
                $erro = "Usuário não encontrado.";
            }
        }

        if (!isset($erro)) {
            if ($senhaNova === $senhaConfirmacao) {
                $senhaNovaHash = password_hash($senhaNova, PASSWORD_DEFAULT);
                $sqlUpdate = "UPDATE usuarios SET senha = ? WHERE username = ?";
                $stmtUpdate = $conn->prepare($sqlUpdate);
                $stmtUpdate->bind_param("ss", $senhaNovaHash, $usuarioAlvo);

                if ($stmtUpdate->execute()) {
                    $sucesso = ($nivelPermissao == 5 or $nivelPermissao == 3) ? 
                        "Senha do usuário '$usuarioAlvo' alterada com sucesso!" : 
                        "Senha alterada com sucesso!";

                                            ///////////////////////////////////////////////// Gravar histórico ///////////////////////////////////////////////
                                            // Obter as informações do usuário atual
                                            $current_usuario = $_SESSION['usuario'];
                                            $current_id_usuario = $_SESSION['id_usuario'];
                                            // Ação que foi realizada
                                            $acao = "Usuário $current_usuario trocou a senha do $usuarioAlvo.";
                                            // Preparar o SQL com placeholders para evitar SQL Injection
                                            $sql_historico = "INSERT INTO historico (usuario_id, acao) VALUES (?, ?)";
                                            $stmt_historico = $conn->prepare($sql_historico);
                                            $stmt_historico->bind_param("is", $current_id_usuario, $acao);
                                            $stmt_historico->execute();
                                            $stmt_historico->close();
                                            ///////////////////////////////////////////////// Gravar histórico ///////////////////////////////////////////////



                } else {
                    $erro = "Erro ao atualizar a senha.";
                }
            } else {
                $erro = "As novas senhas não coincidem.";
            }
        }
    }
}


?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Senha</title>
    <style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    color: #e0e0e0; /* Texto cinza claro */
}

.container {
    max-width: 400px;
    margin: 50px auto;
    padding: 20px;
    background-color: #2c2c2c; /* Fundo cinza escuro */
    border-radius: 0px; /* Remover bordas arredondadas */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); /* Sombra mais forte */
}

input[type="password"], select {
    width: 96%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #555; /* Borda cinza médio */
    border-radius: 0px; /* Remover bordas arredondadas */
    font-size: 16px;
    background-color: #333; /* Fundo escuro para os inputs */
    color: #fff; /* Texto branco nos inputs */
}

button {
    width: 100%;
    padding: 10px;
    font-size: 16px;
    background-color: #333; /* Preto fosco */
    color: white;
    border: none;
    border-radius: 0px; /* Remover bordas arredondadas */
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #555; /* Cinza escuro */
}

h2 {
    text-align: center;
    color: #e0e0e0; /* Texto cinza claro */
}

.error {
    color: #e74c3c; /* Vermelho para erros */
    text-align: center;
}

.success {
    color: #2ecc71; /* Verde para sucesso */
    text-align: center;
}


    </style>
</head>
<body>
<div class="container">
    <h2><?php echo texto_idioma($idioma, 'alterar'); ?> <?php echo texto_idioma($idioma, 'senha'); ?></h2>
    <?php
    if (isset($erro)) {
        echo "<p class='error'>$erro</p>";
    }
    if (isset($sucesso)) {
        echo "<p class='success'>$sucesso</p>";
    }
    ?>
    <form method="POST" action="">
        <?php if ($nivelPermissao == 5): ?>
            <label for="usuario_alvo"><?php echo texto_idioma($idioma, 'usuario'); ?> <?php echo texto_idioma($idioma, 'alvo'); ?>:</label>
            <select name="usuario_alvo" required>
                <option value=""><?php echo texto_idioma($idioma, 'select_user'); ?></option>
                <?php
                $sqlUsuarios = "SELECT username FROM usuarios";
                $resultUsuarios = $conn->query($sqlUsuarios);
                while ($row = $resultUsuarios->fetch_assoc()) {
                    echo "<option value='" . $row['username'] . "'>" . $row['username'] . "</option>";
                }
                ?>
            </select><br><br>
        <?php endif; ?>
        <?php if ($nivelPermissao == 3): ?>
            <label for="usuario_alvo"><?php echo texto_idioma($idioma, 'usuario'); ?> <?php echo texto_idioma($idioma, 'alvo'); ?>:</label>
            <select name="usuario_alvo" required>
                <option value=""><?php echo texto_idioma($idioma, 'select_user'); ?></option>
                <?php
                $sqlUsuarios = "SELECT * FROM usuarios where nivel_permissao != 5";
                $resultUsuarios = $conn->query($sqlUsuarios);
                while ($row = $resultUsuarios->fetch_assoc()) {
                    echo "<option value='" . $row['username'] . "'>" . $row['username'] . "</option>";
                }
                ?>
            </select><br><br>
        <?php endif; ?>
        <?php if ($nivelPermissao != 5 and $nivelPermissao != 3): ?>
            <label for="senha_antiga"><?php echo texto_idioma($idioma, 'senha_antiga'); ?>:</label>
            <input type="password" name="senha_antiga" required><br><br>
        <?php endif; ?>
        <label for="senha_nova"><?php echo texto_idioma($idioma, 'nova_senha'); ?>:</label>
        <input type="password" name="senha_nova" required><br><br>
        <label for="senha_confirmacao"><?php echo texto_idioma($idioma, 'confirm_senha'); ?>:</label>
        <input type="password" name="senha_confirmacao" required><br><br>
        <button type="submit"><?php echo texto_idioma($idioma, 'enviar'); ?></button>
    </form>
</div>
</body>
</html>
<?php $conn->close(); ?>

