<?php
if (session_status() == PHP_SESSION_NONE) {
    /*ini_set('session.gc_maxlifetime', 54000); // 15 horas
    ini_set('session.cookie_lifetime', 54000); // 15 horas.*/

    //o tempo fica em /var/www/html# nano .htaccess
    // e tambem em nano /etc/php/8.3/apache2/php.ini  em cookie_lifetime e gc_maxlifetime
        // reiniciar o apache para aplicar

        // abra o info.php pelo navegador pra ver se ficou ok o tempo




    session_start();


    //sleep(14); // Espera 14 segundos
    //session_destroy(); // Destroi a sessão
}
if (isset($_SESSION['usuario'])) {
    // Se a sessão estiver ativa, redireciona para o dashboard
    header('Location: dashboard.php');
    exit();
}

// Configuração do banco de dados
$host = "localhost";
$username = "agenda";
$password = "#htmKJHg786##";
$dbname = "agenda_db";

// Conexão com o banco
$conn = new mysqli($host, $username, $password, $dbname);

// Verifica se a conexão foi bem-sucedida
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recebe os dados do formulário
    $usuarioInput = $_POST['usuario'];
    $senhaInput = $_POST['senha'];
 

    // Prevenir SQL Injection usando prepared statements
    $usuarioInput = $conn->real_escape_string($usuarioInput);

    // Consulta ao banco para verificar o usuário
    $sql = "SELECT * FROM usuarios WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuarioInput);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifica se o usuário existe
    if ($result->num_rows > 0) {
        // Recupera os dados do usuário
        $usuario = $result->fetch_assoc();
    
        // Verifica se o usuário está ativo
        if ($usuario['nivel_permissao'] == 0) {
            $erro = "User deactivated. Contact the administrator.";
        } else {
            // Verifica se a senha informada corresponde à senha hash no banco de dados
            if (password_verify($senhaInput, $usuario['senha'])) {
                // Se as credenciais estiverem corretas, armazena na sessão
                $_SESSION['autenticado'] = true;
                $_SESSION['usuario'] = $usuario['username']; // Salva o nome de usuário na sessão
                $_SESSION['nivel_usuario'] = $usuario['nivel_permissao']; // Salva o nível do usuário na sessão
                $_SESSION['id_usuario'] = $usuario['id']; // Salva o id do usuário na sessão
                $conn->prepare("UPDATE usuarios SET ultimo_login = NOW() WHERE username = ?")->execute([$usuarioInput]);

                $_SESSION['idioma'] = $usuario['idioma'];
                // Redireciona para o dashboard ou página principal
                header("Location: dashboard.php");
                exit();
            } else {
                // Caso a senha esteja incorreta
                $erro = "Incorrect username or password.";
            }
        }
    } else {
        // Caso o usuário não seja encontrado
        $erro = "Incorrect username or password.";
    }
    
}

$conn->close(); // Fecha a conexão com o banco
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Agenda</title>
    <link rel="icon" href="favicon-16x16.png" type="image/x-icon">
    <style>
        
        body {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background: url('fibra-plano-de-fundo.jpeg') no-repeat center center fixed;
    background-size: cover;
    font-family: Arial, sans-serif;
    margin: 0;
    color: #d1d1d1;
}

.overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5); /* Camada semitransparente preta */
}

.login-container {
    position: relative;
    width: 300px;
    padding: 20px;
    background-color: rgba(42, 42, 42, 0.85); /* Fundo preto semi-transparente */
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
    text-align: center;
}

h1, h2 {
    color: #d1d1d1;
    margin-bottom: 20px;
}

form {
    display: flex;
    flex-direction: column;
    align-items: center;
}

label {
    font-size: 14px;
    color: #d1d1d1;
    align-self: flex-start;
    margin-bottom: 5px;
}

input[type="text"], input[type="password"], select {
    width: 100%;
    padding: 10px;
    margin: 8px 0;
    border: 1px solid #444;
    background: #222;
    color: #d1d1d1;
    box-sizing: border-box;
    text-align: center;
}

button {
    width: 100%;
    padding: 10px;
    background-color: #555;
    color: white;
    border: none;
    cursor: pointer;
    font-size: 16px;
    margin-top: 10px;
}

button:hover {
    background-color: #777;
}

.error {
    color: red;
    margin-top: 10px;
}

    </style>
</head>
<body>
    <div class="overlay"></div> <!-- Camada semitransparente sobre a imagem de fundo -->
    <div class="login-container">
        <h1>
            <span style="display: inline-block; width: 25px; height: 30px; background-image: url('favicon-16x16.png'); background-size: contain; background-repeat: no-repeat; vertical-align: middle;"></span>Agenda
        </h1>
        <h2>Login</h2>
        <form method="POST" action="">
            <label for="usuario">Username:</label>
            <input type="text" id="usuario" name="usuario" required>
            <label for="senha">Password:</label>
            <input type="password" id="senha" name="senha" required>
            <button type="submit">Login</button>
        </form>
        <?php if (isset($erro)): ?>
            <p class="error"><?php echo $erro; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
