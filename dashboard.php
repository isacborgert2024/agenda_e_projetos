<?php

include 'banco_e_functions_gerais.php';


$nivel_usuario = $_SESSION['nivel_usuario'];
$nome_usuario = $_SESSION['usuario']; // Accessing username

// Lógica de logout
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

if ($nivel_usuario === 5 || $nivel_usuario === 3 || $nivel_usuario === 4) {
    $iframe_url = 'home_tecnico.php';
    $tabhome = 'tab22';
} else {
    $iframe_url = 'home.php';
    $tabhome = 'tab1';
}


?>

<!DOCTYPE html>
<html lang="pt">
<head>

<title>Agenda</title>
    <meta charset="UTF-8">
    <!--title>Eng4OLT</title-->
    <link rel="icon" href="favicon-16x16.png" type="image/x-icon">
    <style>
        /* Configuração do fundo e layout */
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    width: 100vw;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: url('fibra-plano-de-fundo.jpeg') no-repeat center center fixed;
    background-size: cover;
}





/* Estilos principais do container */
.container {
    width: 100%;
    height: 100%;
    background-color: rgba(30, 30, 30, 0.85); /* Preto fosco translúcido */
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
}

/* Estilos das guias e botões de navegação */
.tab-buttons {
    display: flex;
    justify-content: space-around;
    background-color: #222; /* Cinza escuro */
}

.tablinks {
    flex: 1;
    padding: 15px;
    cursor: pointer;
    background-color: #222; /* Cinza escuro */
    color: white;
    border: none;
    outline: none;
    text-align: center;
    font-size: 16px;
}

.tablinks:hover {
    background-color: #555; /* Cinza médio */
}

.tablinks.active {
    background-color: #222; /* Preto mais intenso */
}

/* Estilos do conteúdo das guias */
.tab-content {
    display: none;
    padding: 5px;
    height: calc(100vh - 80px);
}

.tab-content.active {
    display: block;
}

/* Estilos do iframe */
iframe {
    width: 100%;
    height: 100%;
    border: none;
}

/* Botão de logout */
.logout-button {
    position: fixed;
    bottom: 10px; /* Move para o canto inferior */
    right: 10px; /* Mantém no lado direito */
    background-color: #222; /* Cinza escuro */
    color: white;
    padding: 10px 20px;
    text-align: center;
    text-decoration: none;
    font-size: 16px;
    cursor: pointer;
    z-index: 1000;
    border: none;
}

.logout-button:hover {
    background-color: #444; /* Preto intenso */
}


/* Estilo para menus dropdown */
.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: #222;
    min-width: 260px;
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
    z-index: 1;
}

.dropdown-content a {
    color: white;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
}

.dropdown-content a:hover {
    background-color: #444;
}

.dropdown:hover .dropdown-content {
    display: block;
}

.dropdown:hover .tablinks {
    background-color: #333;
}

.tablinks, .dropdown-content a {
    cursor: pointer;
}

/* Tooltip */
.tooltip {
    position: relative;
    display: inline-block;
    cursor: pointer;
}

.tooltip:hover .tooltip-text {
    visibility: visible;
    opacity: 1;
}

.tooltip-text {
    visibility: hidden;
    width: 200px;
    background-color: #222;
    color: #fff;
    text-align: center;
    padding: 10px;
    position: absolute;
    z-index: 1;
    bottom: 125%;
    left: 50%;
    transform: translateX(-50%);
    opacity: 0;
    transition: opacity 0.3s;
}

.tooltip-text::after {
    content: "";
    position: absolute;
    top: 100%;
    left: 50%;
    margin-left: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: #222 transparent transparent transparent;
}



    </style>

    <script>
    
function openTab(event, tabId, iframeSrc) {
    var i, tabs, tablinks;
    tabs = document.getElementsByClassName("tab-content");
    for (i = 0; i < tabs.length; i++) {
        tabs[i].classList.remove("active");
    }

    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].classList.remove("active");
    }

    document.getElementById(tabId).classList.add("active");
    event.currentTarget.classList.add("active");

    /*var iframe = document.getElementById(tabId).getElementsByTagName('iframe')[0];
    if (!iframe.src) {
        iframe.src = iframeSrc;
    }*/
    var iframe = document.getElementById(tabId).getElementsByTagName('iframe')[0];
    iframe.src = iframeSrc;
}
const urlParams = new URLSearchParams(window.location.search);
const currentInterface = urlParams.get('interface');
const currentId = urlParams.get('id');
const currentSerial = urlParams.get('serial');
const currenttab = urlParams.get('tab');

window.onload = function () {
    if (currentInterface) {
        // Se os parâmetros estão presentes, abre a tela correspondente
        abrirTelaComParametros(currentInterface, currentId);
    } else {
        // Caso contrário, executa a função padrão
        carregarTabSinal();
    }
};

function abrirTelaComParametros(interfaceParam, idParam) {

    // Remove a classe ativa de todas as abas
    var allTabs = document.querySelectorAll('.tab-content');
    allTabs.forEach(function(tab) {
        tab.classList.remove('active');
    });
    // Verifica se a URL já contém os parâmetros corretos
    if (currentInterface === interfaceParam && currentId === idParam) {
        console.log('A URL já está correta. Verificando e ativando a aba.');
        console.log(`tab: ${currenttab}`);
        // Tenta ativar a aba e carregar o iframe
        const tabDiv = document.getElementById(`tab${currenttab}`);
        if (tabDiv) {
            const iframe = tabDiv.querySelector('iframe');
            const expectedUrl = `${interfaceParam}?serial=${currentSerial}&id=${idParam}`;

            if (iframe) {
                // Verifica se o iframe já está carregado com a URL correta
                if (iframe.src === expectedUrl) {
                    console.log('O iframe já está carregado corretamente. Nenhuma ação adicional necessária.');
                    return; // Evita recarregar o iframe
                }

                // Atualiza o src do iframe para a URL correta
                iframe.src = expectedUrl;
                console.log(`Iframe atualizado para: ${expectedUrl}`);
            } else {
                console.error('Iframe não encontrado dentro da aba alvo.');
            }

            // Ativa apenas a aba correta
            const allTabs = document.querySelectorAll('.tab');
            allTabs.forEach(tab => tab.classList.remove("active")); // Remove a classe 'active' de todas as abas
            tabDiv.classList.add("active"); // Ativa a aba correta
        } else {
            console.error('Div da aba alvo não encontrada.');
        }

        return; // Sai da função para evitar redirecionamento desnecessário
    }

    // Se os parâmetros não estão corretos, redireciona para a nova URL
    const expectedNewUrl = `${interfaceParam}?serial=${currentSerial}&id=${idParam}`;
    console.log(`Redirecionando para: ${expectedNewUrl}`);
    window.location.href = expectedNewUrl; // Redireciona para a nova página
}


if (window.self !== window.top) {
    console.log('A página foi carregada dentro de um iframe. Redirecionando para fora.');
    window.top.location.href = window.location.href; // Força o carregamento no contexto principal
}

function carregarTabSinal() {
    // Remove a classe ativa de todas as abas
    var allTabs = document.querySelectorAll('.tab-content');
    allTabs.forEach(function(tab) {
        tab.classList.remove('active');
    });

    // Busca a aba correspondente
    var sinalTab = document.getElementById('<?php echo $tabhome; ?>');
    console.log('<?php echo 'tab ='.$tabhome.$iframe_url; ?>');

    if (sinalTab) {
        console.log('Elemento sinalTab encontrado:', sinalTab);

        // Adiciona a classe ativa à aba
        sinalTab.classList.add("active");

        // Busca o iframe dentro da aba
        var iframe = sinalTab.querySelector('iframe');
        if (iframe) {
            console.log('Iframe encontrado dentro de sinalTab:', iframe);

            // Verifica e define o src do iframe
            if (!iframe.getAttribute('src')) {
                iframe.setAttribute('src', '<?php echo $iframe_url; ?>');
                console.log('Src do iframe definido como:', iframe.src);
            } else {
                console.log('Src do iframe já está definido:', iframe.src);
            }
        } else {
            console.warn('Nenhum iframe encontrado dentro de sinalTab.');
        }
    } else {
        console.error('Elemento correspondente à aba "Sinal" não encontrado. ID:', '<?php echo $tabhome; ?>');
    }
}



    </script>
</head>
<body>
    <div class="container">
        <!--h2>Eng4OLT</h2-->
        
        <div class="tab-buttons">
            <?php if ($nivel_usuario == 1 or $nivel_usuario == 2): ?>
                <button class="tablinks" onclick="openTab(event, 'tab1', 'home.php')">Agendas</button>

                <button class="tablinks" onclick="openTab(event, 'tab2', 'agenda_front.php')">Adicionar Agenda</button>
                <button class="tablinks" onclick="openTab(event, 'tab2', 'historico_de_agenda_cliente.php')">Histórico de Agenda</button>
                <button class="tablinks" onclick="openTab(event, 'tab3', 'projetos-mapeados-front.php')">Projetos</button>
                <button class="tablinks" onclick="openTab(event, 'tab3', 'trocar-senha-user.php')">Senha</button>
            <?php endif; ?>

            <?php if ($nivel_usuario != 1 and $nivel_usuario != 2): ?>
                <div class="dropdown">
                    <button class="tablinks">Tarefas</button>
                    <div class="dropdown-content">
                        <a onclick="openTab(event, 'tab16', 'home_tecnico.php')">Minhas Tarefas</a>
                        <?php if ($nivel_usuario == 5): ?>
                            <a onclick="openTab(event, 'tab16', 'tarefas_geral.php')">Tarefas Geral</a>
                        <?php endif; ?>
                        <a onclick="openTab(event, 'tab16', 'tarefas_historico.php')">Meu Histórico de Tarefas</a>

                        
                    </div>
                </div>

                <div class="dropdown">
                    <button class="tablinks">Agenda</button>
                    <div class="dropdown-content">
                        
                        <a onclick="openTab(event, 'tab16', 'agenda_front.php')">Adicionar Agenda</a>
                        <a onclick="openTab(event, 'tab16', 'home.php')">Minhas Agendas</a>
                        <a onclick="openTab(event, 'tab16', 'historico_de_agenda_cliente.php')">Meu Histórico de Agenda</a>
                        
                    </div>
                </div>
                <?php if ($nivel_usuario == 5): ?>
                    <div class="dropdown">
                        <button class="tablinks" onclick="openTab(event, 'tab22', 'bloqueio_de_datas.php')">Bloqueio de data</button>
                    </div>
                <?php endif; ?>
                <?php if ($nivel_usuario == 5): ?>
                    <div class="dropdown">
                        <button class="tablinks" onclick="openTab(event, 'tab3', 'historico_de_agenda_geral.php')">Histórico Geral</button>
                    </div>
                <?php endif; ?>

                <?php if ($nivel_usuario == 5): ?>
                    <div class="dropdown">
                        <button class="tablinks">Usuários</button>
                        <div class="dropdown-content">
                            <a onclick="openTab(event, 'tab16', 'cadastrar_usuarios.php')">Gestão de Usuários</a>
                            <a onclick="openTab(event, 'tab16', 'historico.php')">Histórico de ações</a>
                            <a onclick="openTab(event, 'tab2', 'trocar-senha-user.php')">Senhas de acesso</a>
                            
                        </div>
                    </div>
                <?php endif; ?>
                <?php if ($nivel_usuario == 3): ?>
                    <div class="dropdown">
                        <button class="tablinks" onclick="openTab(event, 'tab3', 'trocar-senha-user.php')">Senhas de acesso</button>
                    </div>
                <?php endif; ?>
                <div class="dropdown">
                    <button class="tablinks" onclick="openTab(event, 'tab3', 'projetos-mapeados-front.php')">Projetos</button>
                </div>
            <?php endif; ?>

        </div>

        <div id="tab1" class="tab-content active">
            <iframe id="iframe-tab1"></iframe>
        </div>
        <div id="tab2" class="tab-content">
            <iframe id="iframe-tab2"></iframe>
        </div>
        <div id="tab3" class="tab-content">
            <iframe id="iframe-tab3"></iframe>
        </div>
        <div id="tab4" class="tab-content">
            <iframe id="iframe-tab4"></iframe>
        </div>
        <div id="tab5" class="tab-content">
            <iframe id="iframe-tab5"></iframe>
        </div>
        <div id="tab6" class="tab-content">
            <iframe id="iframe-tab6"></iframe>
        </div>
        <div id="tab7" class="tab-content">
            <iframe id="iframe-tab7"></iframe>
        </div>
        <div id="tab8" class="tab-content">
            <iframe id="iframe-tab8"></iframe>
        </div>
        <div id="tab9" class="tab-content">
            <iframe id="iframe-tab9"></iframe>
        </div>
        <div id="tab10" class="tab-content">
            <iframe id="iframe-tab10"></iframe>
        </div>
        <div id="tab11" class="tab-content">
            <iframe id="iframe-tab11"></iframe>
        </div>
        <div id="tab12" class="tab-content">
            <iframe id="iframe-tab12"></iframe>
        </div>
        <div id="tab13" class="tab-content">
            <iframe id="iframe-tab13"></iframe>
        </div>
        <div id="tab14" class="tab-content">
            <iframe id="iframe-tab14"></iframe>
        </div>
        <div id="tab15" class="tab-content">
            <iframe id="iframe-tab15"></iframe>
        </div>
        <div id="tab16" class="tab-content">
            <iframe id="iframe-tab16"></iframe>
        </div>
        <div id="tab17" class="tab-content">
            <iframe id="iframe-tab17"></iframe>
        </div>
        <div id="tab18" class="tab-content">
            <iframe id="iframe-tab18"></iframe>
        </div>
        <div id="tab19" class="tab-content">
            <iframe id="iframe-tab19"></iframe>
        </div>
        <div id="tab20" class="tab-content">
            <iframe id="iframe-tab20"></iframe>
        </div>
        <div id="tab21" class="tab-content">
            <iframe id="iframe-tab21"></iframe>
        </div>
        <div id="tab22" class="tab-content"><!--aqui é a tela home-->
            <iframe id="iframe-tab22"></iframe>
        </div>
        <div id="tab23" class="tab-content">
            <iframe id="iframe-tab23"></iframe>
        </div>
        <?php
// Exibir o combo somente se $_SESSION['nivel_usuario'] for 5 ou $_SESSION['nivel_usuario_real'] for 5
if ($_SESSION['nivel_usuario'] == 5 || $_SESSION['nivel_usuario_real'] == 5) {
?>
    <form method="post">
        <select name="nivel_usuario" id="nivel_usuario" onchange="alterarNivel()">
            <option value="1" <?= $nivel_usuario == 1 ? 'selected' : '' ?>>User level 1</option>
            <option value="3" <?= $nivel_usuario == 3 ? 'selected' : '' ?>>User level 3</option>
            <option value="5" <?= $nivel_usuario == 5 ? 'selected' : '' ?>>User level 5</option>
        </select>
    </form>
<?php
}
?>
        <a href="?logout=true" class="logout-button"><?php echo "$nome_usuario";?> - Logout</a>
    </div>
    


    <script>
        function alterarNivel() {
            let nivel = document.getElementById('nivel_usuario').value;

            fetch('alterar_sessao.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'nivel_usuario=' + nivel
            })
            .then(response => response.text())
            .then(data => {
                console.log(data); // Exibe a resposta no console
                location.reload(); // Recarrega a página após a alteração
            });
        }
    </script>
</body>
</html>
