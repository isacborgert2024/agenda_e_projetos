<?php
ini_set('date.timezone', 'America/Sao_Paulo');


if (session_status() == PHP_SESSION_NONE) {
    /*ini_set('session.gc_maxlifetime', 54000); // 15 horas
    ini_set('session.cookie_lifetime', 54000); // 15 horas.*/

    //o tempo fica em /var/www/html# nano .htaccess
    // e tambem em nano /etc/php/8.3/apache2/php.ini  em cookie_lifetime e gc_maxlifetime
    // reiniciar o apache para aplicar
        // abra o info.php pelo navegador pra ver se ficou ok o tempo


    session_start();
        //sleep(4); // Espera 14 segundos
    //session_destroy(); // Destroi a sessão

    
}

//session_destroy(); // Destroi a sessão


if (!isset($_SESSION['autenticado'])) {
    // Obtém a URI do script em execução
    $uriParts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

    // Obtém o primeiro diretório após o domínio/IP
    $baseDir = '/' . $uriParts[0] . '/';

    // Constrói a URL base correta
    $baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . $baseDir;

    // Redireciona para a página de login na raiz do projeto
    echo "<script>window.top.location.href = '" . $baseUrl . "login.php';</script>";
    exit();
}


$nivel_usuario = $_SESSION['nivel_usuario'];
$usuario_id = $_SESSION['id_usuario'] ?? null;
$nome_usuario = $_SESSION['usuario'] ?? null;
$idioma = $_SESSION['idioma'];
$current_usuario = $_SESSION['usuario'];

// Função para conexão com o banco de dados
function conectar_banco() {
    $host = "localhost";
    $username = "agenda";
    $password = "suasenhaaqui";
    $dbname = "agenda_db";
    

    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Erro de conexão: " . $conn->connect_error);
    }

    return $conn;
}

$conn = conectar_banco();


function texto_idioma($idioma, $nome) {
    global $conn; // Para usar a conexão com o banco de dados já existente

    // Define a coluna a ser consultada conforme o idioma
    switch ($idioma) {
        case 'portugues':
            $coluna = 'texto_portugues';
            break;
        case 'espanhol':
            $coluna = 'texto_espanhol';
            break;
        case 'ingles':
            $coluna = 'texto_ingles';
            break;
        default:
            return "Idioma inválido!";
    }

    // Prepara a consulta utilizando a coluna definida e a tabela unificada
    $sql = "SELECT $coluna FROM textos_de_idiomas WHERE nome = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nome);
    $stmt->execute();
    $stmt->bind_result($texto);
    $stmt->fetch();

    // Retorna o texto, ou uma mensagem caso o nome não seja encontrado
    return $texto ? $texto : "Texto não encontrado para o nome: $nome";
}



//// Registra o historico de ações do usuário
//$log = "Usuário $nome_usuario excluiu a OLT id $id";
//registrarLog($conn, $log);
function registrarLog($conn, $mensagem) {
    if (!$conn) {
        return false; // Retorna falso se a conexão não estiver disponível
    }

    $usuario_id = $_SESSION['id_usuario'] ?? null;
    if (!$usuario_id) {
        return false; // Retorna falso se o usuário não estiver logado
    }

    $stmt = $conn->prepare("INSERT INTO historico (usuario_id, acao) VALUES (?, ?)");
    $stmt->bind_param("is", $usuario_id, $mensagem);
    return $stmt->execute();
}



?>
