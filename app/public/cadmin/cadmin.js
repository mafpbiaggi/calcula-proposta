//Recarrega janela quando o botão limpar é acionado
function limpaTela() {
    window.location.reload();
}

//Declara e coleta dados do formulário "formConsulta"
const formConsulta = document.getElementById("formConsulta");
if (formConsulta) {
    formConsulta.addEventListener("submit", async (e) => {
        e.preventDefault();

        // Recebe os dados do formulário
        const dadosForm = new FormData(formConsulta);

        // Envia os dados para o arquivo "/cadmin/cadmin.php"
        const dados = await fetch("/cadmin/cadmin.php", {
            method: "POST",
            body: dadosForm
        });

        //Retorna as mensagens do php.
        const resposta = await dados.json();

        //Limpa resultados anteriores
        const resultHead = document.getElementById('resultHead');
        const resultExport = document.getElementById('resultExport');
        const resultBody = document.getElementById('resultBody');
        resultHead.innerHTML = '';
        resultBody.innerHTML = '';
        resultExport.innerHTML = '';


        if (resposta['status']) {
            document.getElementById("msgAlerta").innerHTML = "";

            resultHead.innerHTML += `
                <tr>
                    <th>Nome</th>
                    <th>Documento</th>
                    <th>Valor do Bem</th>
                    <th>% Pago</th>
                    <th>Encer. Grupo</th>
                    <th>Valor Simulado</th>
                    <th>Simulado em</th>
                </tr>
            `;

            resposta['msg'].forEach((element) => {
                resultBody.innerHTML += `
                    <tr>
                        <td>${element.nome}</td>
                        <td>${element.documento}</td>
                        <td>${element.valorBem}</td>
                        <td>${element.percPago}</td>
                        <td>${element.encrGrupo}</td>
                        <td>${element.resultado}</td>
                        <td>${element.created}</td>
                    </tr>
                `;
            });

            resultExport.innerHTML += `
                <form method="POST" id="formExporta" action="/cadmin/cadminExport.php" target="_blank">
                    <input type="hidden" name="dataInicialEx" id="dataInicialEx" type="date" value="` + document.getElementById("dataInicial").value + `"/>
                    <input type="hidden" name="dataFinalEx" id="dataFinalEx" type="date" value="` + document.getElementById("dataFinal").value + `"/>
                    <button type="submit" class="btn btn-sm btn-success">Exportar</button>
                </form>
            `;

        } else {
            document.getElementById("msgAlerta").innerHTML = resposta['msg'];
        }
    });
}