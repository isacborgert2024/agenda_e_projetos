<?php

include('banco_e_functions_gerais.php');

if(isset($_GET['usuario']) && !empty($_GET['usuario']))
    $current_usuario = $_GET['usuario'];
elseif(isset($_POST['usuario']) && !empty($_POST['usuario']))
    $current_usuario = $_POST['usuario'];
else 
    $current_usuario = $_SESSION['usuario'];

$directory = "/var/www/html/projetos-mapeados-agenda/$current_usuario/";

// Verifica se o diretório existe
if (!is_dir($directory)) {
    mkdir($directory, 0777, true); // Cria o diretório com permissões totais
}

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    if ($action != 'list') {

                        ///////////////////////////////////////////////// Gravar histórico ///////////////////////////////////////////////
                    $filename = basename($_GET['file']);

                    $filename2 = basename($_FILES['file']['name']);
                    // Obter as informações do usuário atual
                    $current_usuario = $_SESSION['usuario'];
                    $current_id_usuario = $_SESSION['id_usuario'];
                    // Ação que foi realizada
                    $acao = "Usuário $current_usuario ação $action $filename$filename2 na tela projetos mapeados";
                    // Preparar o SQL com placeholders para evitar SQL Injection
                    $sql_historico = "INSERT INTO historico (usuario_id, acao) VALUES (?, ?)";
                    $stmt_historico = $conn->prepare($sql_historico);
                    $stmt_historico->bind_param("is", $current_id_usuario, $acao);
                    $stmt_historico->execute();
                    $stmt_historico->close();
                    ///////////////////////////////////////////////// Gravar histórico ///////////////////////////////////////////////
    }

    if ($action === 'list') {
        // Listar PDFs e Imagens
        $files = [];
        if (is_dir($directory)) {
            if ($handle = opendir($directory)) {
                while (false !== ($file = readdir($handle))) {
                    $extension = pathinfo($file, PATHINFO_EXTENSION);
                    if (in_array(strtolower($extension), ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff', 'svg'])) {
                        $files[] = $file;
                    }
                }
                closedir($handle);
            }
        }

    // Ordenar os arquivos em ordem alfabética
    sort($files);
    
    echo json_encode($files);
    } elseif ($action === 'upload' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($_FILES['file']['error'] === UPLOAD_ERR_INI_SIZE) {
            echo json_encode(['success' => false, 'message' => 'Arquivo excede o tamanho máximo permitido.']);
            exit;
        }
        
        // Fazer upload de arquivo
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $uploadFile = $directory . basename($_FILES['file']['name']);
            
            // Verifica se o arquivo já existe
            if (file_exists($uploadFile)) {
                echo json_encode(['success' => false, 'message' => 'Arquivo já existe.']);
            } else {
                if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Erro ao mover o arquivo.']);
                }
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Nenhum arquivo enviado ou erro no upload.']);
        }
    } elseif ($action === 'delete' && isset($_GET['file'])) {
        // Recebe a senha do corpo da requisição
        $requestBody = file_get_contents('php://input');
        $data = json_decode($requestBody, true);
    
        $fileToDelete = $directory . basename($_GET['file']);
        if (file_exists($fileToDelete) && unlink($fileToDelete)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    }
}
?>
