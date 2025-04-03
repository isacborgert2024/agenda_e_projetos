document.querySelectorAll('.acoes-agenda').forEach(div => {
    let id = div.getAttribute('data-id');

    div.querySelector('.btn-editar').addEventListener('click', function() {
        window.location.href = "agenda_front.php?id=" + id;
    });

    div.querySelector('.btn-excluir').addEventListener('click', function() {
        if (confirm("Tem certeza que deseja excluir esta agenda?")) {
            // Exibir o carregamento
            document.getElementById("loading-container").style.display = "flex";

            fetch("agenda_back.php?action=excluir&id=" + id)
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert(data.error);
                    }
                })
                .catch(error => console.error("Erro ao excluir:", error))
                .finally(() => {
                    // Esconder o carregamento após a resposta
                    document.getElementById("loading-container").style.display = "none";
                });
        }
    });

});



document.addEventListener("DOMContentLoaded", function () {
    const agora = new Date();
    const amanha = new Date();
    amanha.setDate(amanha.getDate() + 1); // Adiciona 1 dia para pegar a data de amanhã
    
    document.querySelectorAll("td:first-child").forEach(td => {
        const texto = td.textContent.trim();

        // Se tiver "Aprovação Pendente", fica laranja fosco
        if (texto.includes("Aprovação Pendente")) {
            td.style.backgroundColor = "#e5a04c"; // Laranja fosco
            td.style.color = "#333"; // Contraste suave
        }

        // Se tiver "Aprovada", fica verde fosco
        if (texto.includes("Aprovada")) {
            td.style.backgroundColor = "#6b9e6b"; // Verde fosco
            td.style.color = "#222"; // Melhor contraste
        }
        if (texto.includes("Em Execução")) {
            td.style.backgroundColor = "#5A7690"; /* Azul fosco */

            td.style.color = "#222"; // Melhor contraste
        }

        // Se tiver "Rejeitada", fica vermelho fosco (igual ao da data)
        if (texto.includes("Rejeitada")) {
            td.style.backgroundColor = "#c46b6b"; // Vermelho fosco
            td.style.color = "#222"; // Melhor contraste
        }
    });

    document.querySelectorAll("tr").forEach(tr => {
        const td = tr.querySelectorAll("td")[3]; // Quarta coluna (índice 3, pois começa do 0)
        if (!td) return;

        const texto = td.textContent.trim();

        // Expressão regular para capturar a data e a hora
        const regexDataHora = /(\d{2})\/(\d{2})\/(\d{4}) (\d{2}):(\d{2})/;
        const match = texto.match(regexDataHora);

        if (match) {
            const [_, dia, mes, ano, hora, minuto] = match.map(Number);
            const dataCelula = new Date(ano, mes - 1, dia, hora, minuto);

            // Se a data for hoje e a hora for maior que a atual -> Vermelho fosco
            if (dataCelula.toDateString() === agora.toDateString() && dataCelula > agora) {
                td.style.backgroundColor = "#c46b6b"; // Vermelho fosco
                td.style.color = "#222"; // Texto com melhor contraste
            }

            // Se a data for de amanhã -> Laranja fosco
            if (dataCelula.toDateString() === amanha.toDateString()) {
                td.style.backgroundColor = "#e5a04c"; // Laranja fosco
                td.style.color = "#333"; // Melhor contraste
            }
        }
    });
});

function filtrarTabela(coluna) {
    let input = document.querySelectorAll("th input")[coluna].value.toLowerCase();
    let tabela = document.querySelector("table");
    let linhas = tabela.getElementsByTagName("tr");

    for (let i = 1; i < linhas.length; i++) { // Começa em 1 para ignorar os cabeçalhos
        let celula = linhas[i].getElementsByTagName("td")[coluna];
        if (celula) {
            let texto = celula.textContent.toLowerCase();
            linhas[i].style.display = texto.includes(input) ? "" : "none";
        }
    }
}
