//Cria um formatador para a moeda
const formatter = new Intl.NumberFormat('pt-BR', {
    style: 'currency',
    currency: 'BRL',

});

//Declara e coleta dados do formulário "formCalc"
const formCalcProp = document.getElementById("formCalc");
if (formCalcProp) {
    formCalcProp.addEventListener("submit", async(e) => {
        e.preventDefault();
        // Recebe os dados do formulário
        const dadosForm = new FormData(formCalcProp);

        // Envia os dados para o arquivo "cadSimulacao.php"
        const dados = await fetch("cadSimulacao.php", {
            method: "POST",
            body: dadosForm
        });

        //Retorna as mensagens do php.
        const resposta = await dados.json();
        if (resposta['status']) {
            document.getElementById("msgAlerta").innerHTML = resposta['msg'];
            formCalcProp.reset();

        } else {
            document.getElementById("msgAlerta").innerHTML = resposta['msg'];
        }
    });
}
