<?php
include 'banco_e_functions_gerais.php';

// Obtém o nível do usuário
$nivel_usuario = $_SESSION['nivel_usuario'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo texto_idioma($idioma, 'proj_maps'); ?></title>
    <style>
body {
    font-family: Arial, sans-serif;
    background-color: #121212; /* Fundo escuro */
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    color: #e0e0e0; /* Texto em cinza claro */
}

h1 {
    color: #e0e0e0; /* Texto em cinza claro */
    margin: 20px 0;
    text-align: center;
    width: 100%;
}

.container {
    display: flex;
    flex-direction: row;
    gap: 20px;
    max-width: 1900px;
    width: 100%;
    padding: 0 20px;
}

.file-list-container {
    flex: 0 0 30%;
    max-width: 30%;
    background-color: #2c2c2c; /* Cinza escuro */
    padding: 10px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    overflow-y: auto;
    max-height: 80vh;
}

.file-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    border-bottom: 1px solid #444; /* Borda cinza escuro */
}

.file-item:last-child {
    border-bottom: none;
}

.file-name {
    color: #e0e0e0;  /* Texto claro */
    text-decoration: none;
    cursor: pointer;
}

.file-name:hover {
    text-decoration: underline;
}

.action-btns button {
    margin-left: 10px;
    background-color: #333; /* Preto fosco */
    color: white;
    border: none;
    padding: 5px 10px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.action-btns .delete-btn {
    background-color:rgb(40, 22, 20); /* Vermelho escuro */
}

.action-btns .delete-btn:hover {
    background-color:rgb(17, 7, 6); /* Vermelho mais escuro */
}

.file-viewer-container {
    flex: 1;
    background-color: #2c2c2c; /* Cinza escuro */
    padding: 10px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    min-height: 400px;
    height: 80vh;
}

#fileViewer, #imageViewer {
    width: 100%;
    height: 100%;
    border: none;
    border-radius: 8px;
    display: none;
}

img {
    max-width: 100%;
    max-height: 100%;
    display: none;
}

.upload-container {
    margin-bottom: 20px;
}

.upload-container input[type="file"] {
    display: none;
}

.upload-container label {
    background-color: #333; /* Preto fosco */
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
}

.upload-container label:hover {
    background-color: #555; /* Cinza escuro */
}
.header-container {
    display: flex;
    align-items: center;
    gap: 10px; /* Espaço entre o h1 e o select */
}

.select {
    background-color: #222; /* Cinza escuro */
    color: white;
    padding: 5px 10px;
    border: none;
    font-size: 16px;
    cursor: pointer;
}


    </style>
</head>
<body>
<div class="header-container">
    <?php if ($nivel_usuario != 1 and $nivel_usuario != 2): ?>

            <label for="combo_users">Usuário:</label>

        <select class="select" id='combo_users' onchange="enviarUsuario(this.value)">
            <option value="<?php echo $_SESSION['usuario']; ?>"><?php echo $_SESSION['usuario']; ?></option>
            <?php
            if ($nivel_usuario == 5)
                $sql = "SELECT username FROM usuarios";
            else 
                $sql = "SELECT username FROM usuarios where nivel_permissao != 5";

            $result = mysqli_query($conn, $sql);

            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<option value="' . htmlspecialchars($row['username']) . '">' . htmlspecialchars($row['username']) . '</option>';
                }
            }
            ?>
        </select>
    <?php endif; ?>
    <?php if ($nivel_usuario == 1 or $nivel_usuario == 2): ?>
        <input type="text" class="select" id="combo_users" value="<?php echo $_SESSION['usuario']; ?>" style="visibility: hidden;" readonly>
    <?php endif; ?>

    <h1><?php echo texto_idioma($idioma, 'proj_maps'); ?> </h1>

</div>

<div class="container">


    <!-- Listagem de Arquivos -->
    <div class="file-list-container">
        <div class="upload-container">

            <label for="fileUpload"><?php echo texto_idioma($idioma, 'add_pdf_img'); ?></label>

            <input type="file" id="fileUpload" accept=".pdf,image/*" onchange="uploadFile()">

            <!-- Adicione o campo de pesquisa -->
            <input
                type="text"
                id="searchInput"
                placeholder="<?php echo texto_idioma($idioma, 'pesq_arq'); ?>..."
                onkeyup="filterFiles()"
                style="width: 40%; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 5px;"
            />
        </div>
        <ul id="fileList"></ul>
    </div>
    
    <!-- Visualizador de Arquivos -->
    <div class="file-viewer-container">
        <iframe id="fileViewer"></iframe>
        <img id="imageViewer" hidden />
    </div>
</div>


    <script>
        // Variável global para armazenar a lista de arquivos completa
    let allFiles = [];

// Função para carregar os arquivos do servidor
function loadFiles() {
    let current_usuario;

    const comboUsers = document.getElementById('combo_users');
    if (comboUsers.value === "") {
        current_usuario = "<?php echo $_SESSION['usuario']; ?>";
    } else {
        current_usuario = comboUsers.value;
    }
    fetch(`projetos-mapeados-back.php?action=list&usuario=${encodeURIComponent(current_usuario)}`)
        .then(response => response.json())
        .then(data => {
            allFiles = data; // Armazena todos os arquivos
            displayFiles(allFiles); // Exibe os arquivos
        })
        .catch(error => console.error('Erro ao carregar os arquivos:', error));
}

// Função para exibir os arquivos na lista
function displayFiles(files) {
    let current_usuario;

    const comboUsers = document.getElementById('combo_users');
    if (comboUsers.value === "") {
        current_usuario = "<?php echo $_SESSION['usuario']; ?>";
    } else {
        current_usuario = comboUsers.value;
    }

    const fileList = document.getElementById('fileList');
    fileList.innerHTML = ''; // Limpa a lista antes de exibir os arquivos

    files.forEach(file => {
        const listItem = document.createElement('li');
        listItem.classList.add('file-item');

        const link = document.createElement('span');
        link.textContent = file;
        link.classList.add('file-name');

        // Evento de clique para visualizar o arquivo
        link.addEventListener('click', function () {
            const fileViewer = document.getElementById('fileViewer');
            const imageViewer = document.getElementById('imageViewer');
            if (file.endsWith('.pdf')) {
                fileViewer.src = `../projetos-mapeados-agenda/${current_usuario}/${file}`;
                fileViewer.style.display = 'block';
                imageViewer.style.display = 'none';
            } else {
                imageViewer.src = `../projetos-mapeados-agenda/${current_usuario}/${file}`;
                imageViewer.style.display = 'block';
                fileViewer.style.display = 'none';
            }
        });

        const actionBtns = document.createElement('div');
        actionBtns.classList.add('action-btns');

        // Botão Visualizar
        const viewBtn = document.createElement('button');
        viewBtn.textContent = '<?php echo texto_idioma($idioma, 'visualizar'); ?>';
        viewBtn.addEventListener('click', function () {
            link.click();
        });
        actionBtns.appendChild(viewBtn);

            // Botão Excluir
            const deleteBtn = document.createElement('button');
            deleteBtn.textContent = '<?php echo texto_idioma($idioma, 'excluir'); ?>';
            deleteBtn.classList.add('delete-btn');
            deleteBtn.addEventListener('click', function () {
                deleteFile(file);
            });
            actionBtns.appendChild(deleteBtn);

        listItem.appendChild(link);
        listItem.appendChild(actionBtns);
        fileList.appendChild(listItem);
    });
}
function enviarUsuario(usuario) {
    if (usuario) {
        fetch(`projetos-mapeados-back.php?action=list&usuario=${encodeURIComponent(usuario)}`)
            .then(response => response.json())
            .then(data => {
                console.log("Resposta do servidor:", data);
                
                // Chama a função displayFiles com os dados recebidos
                displayFiles(data);  // Exibe os dados usando a função existente
            })
            .catch(error => {
                console.error("Erro ao enviar requisição:", error);
            });
    }
}



// Função para filtrar arquivos com base no texto digitado
function filterFiles() {
    const searchText = document.getElementById('searchInput').value.toLowerCase();
    const filteredFiles = allFiles.filter(file =>
        file.toLowerCase().includes(searchText) // Verifica se o nome do arquivo contém o texto pesquisado
    );
    displayFiles(filteredFiles); // Exibe apenas os arquivos filtrados
}

// Carrega os arquivos na inicialização
window.onload = loadFiles;

// Função para mostrar um toast
function showToast(message, isSuccess = true) {
    const toast = document.createElement('div');
    toast.textContent = message;
    toast.style.position = 'fixed';
    toast.style.top = '50%'; // Centraliza verticalmente
    toast.style.left = '50%'; // Centraliza horizontalmente
    toast.style.transform = 'translate(-50%, -50%)'; // Ajusta o centro para o elemento
    toast.style.padding = '15px 30px';
    toast.style.color = '#fff';
    toast.style.borderRadius = '8px';
    toast.style.boxShadow = '0 2px 5px rgba(0, 0, 0, 0.2)';
    toast.style.fontSize = '18px';
    toast.style.zIndex = '1000';
    toast.style.backgroundColor = isSuccess ? '#28a745' : '#dc3545'; // Verde para sucesso, vermelho para erro
    toast.style.opacity = '1';
    toast.style.transition = 'opacity 0.5s ease, transform 0.5s ease';

    document.body.appendChild(toast);

    // Remove o toast após 3 segundos
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translate(-50%, -60%)'; // Move um pouco para cima ao desaparecer
        setTimeout(() => toast.remove(), 500); // Aguarda a transição antes de remover
    }, 3000);
}

// Função para fazer upload do arquivo
function uploadFile() {
    let current_usuario;

    const comboUsers = document.getElementById('combo_users');
    if (comboUsers.value === "") {
        current_usuario = "<?php echo $_SESSION['usuario']; ?>";
    } else {
        current_usuario = comboUsers.value;
    }
    const fileInput = document.getElementById('fileUpload');
    const file = fileInput.files[0];
    if (!file) {
        showToast('Selecione um arquivo antes de enviar.', false);
        return;
    }

    const formData = new FormData();
    formData.append('file', file);

    fetch(`projetos-mapeados-back.php?action=upload&usuario=${encodeURIComponent(current_usuario)}`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadFiles(); // Recarrega a lista de arquivos
            showToast('Arquivo enviado com sucesso!'); // Toast para sucesso
        } else {
            showToast('Erro ao enviar o arquivo: ' + (data.message || 'Erro desconhecido'), false);
        }
        fileInput.value = ''; // Limpa o input após o upload
    })
    .catch(error => {
        console.error('Erro ao enviar o arquivo:', error);
        showToast('Erro ao enviar o arquivo.', false); // Toast para erro
    });
}


 // Função para excluir arquivos
function deleteFile(fileName) {
    let current_usuario;

    const comboUsers = document.getElementById('combo_users');
    if (comboUsers.value === "") {
        current_usuario = "<?php echo $_SESSION['usuario']; ?>";
    } else {
        current_usuario = comboUsers.value;
    }
    // Exibe a confirmação de exclusão
    if (!confirm(`Tem certeza de que deseja excluir o arquivo "${fileName}"?`)) {
        return;
    }

    // Requisição de exclusão
    fetch(`projetos-mapeados-back.php?action=delete&file=${encodeURIComponent(fileName)}&usuario=${encodeURIComponent(current_usuario)}`, {
        method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Arquivo excluído com sucesso!'); // Toast de sucesso
            loadFiles(); // Recarregar a lista de arquivos
        } else {
            showToast('Erro ao excluir o arquivo.', false); // Toast de erro
        }
    })
    .catch(error => {
        console.error('Erro ao excluir o arquivo:', error);
        showToast('Ocorreu um erro ao tentar excluir o arquivo.', false); // Toast de erro
    });
}

// Função para exibir um toast
function showToast(message, isSuccess = true) {
    const toast = document.createElement('div');
    toast.textContent = message;
    toast.style.position = 'fixed';
    toast.style.top = '50%'; // Centraliza verticalmente
    toast.style.left = '50%'; // Centraliza horizontalmente
    toast.style.transform = 'translate(-50%, -50%)'; // Ajusta para centralizar exatamente
    toast.style.padding = '15px 30px';
    toast.style.color = '#fff';
    toast.style.borderRadius = '8px';
    toast.style.boxShadow = '0 2px 5px rgba(0, 0, 0, 0.2)';
    toast.style.fontSize = '18px';
    toast.style.zIndex = '1000';
    toast.style.backgroundColor = isSuccess ? '#28a745' : '#dc3545'; // Verde para sucesso, vermelho para erro
    toast.style.opacity = '1';
    toast.style.transition = 'opacity 0.5s ease, transform 0.5s ease';

    document.body.appendChild(toast);

    // Remove o toast após 3 segundos
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translate(-50%, -60%)'; // Move um pouco para cima ao desaparecer
        setTimeout(() => toast.remove(), 500); // Aguarda a transição antes de remover
    }, 3000);
}

    </script>
</body>
</html>
