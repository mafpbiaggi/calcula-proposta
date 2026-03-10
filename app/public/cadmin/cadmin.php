<?php

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if(empty($dados['dataInicial']) || empty($dados['dataFinal'])){
        $retorna = ['status' => false, 'msg' => "<p class='alert alert-danger' style='margin-top: 50px;'>Por favor, preencha todos os campos!</p>"];

} elseif ($dados['dataFinal'] < $dados['dataInicial']) {
    $retorna = ['status' => false, 'msg' => "<p class='alert alert-danger' style='margin-top: 50px;'>Por favor, verifique os campos de data (Data Final < Data Inicial)!</p>"];

} else {

    //Coleta dados da variável de ambiente para a conexão com o banco
    $host = getenv('MYSQL_HOST');
    $user = getenv('MYSQL_USER');
    $pass = getenv('MYSQL_PASSWORD');
    $dbname = getenv('MYSQL_DATABASE');
    $port = getenv('MYSQL_PORT');

    try {
        $conn = new PDO("mysql:host=$host;port=$port;dbname=" . $dbname, $user, $pass);

    } catch (PDOException $err) {
        error_log($err->getMessage());
        $retorna = ['status' => false, 'msg' => "<p class='alert alert-danger' style='margin-top: 50px;'>Conexão com o banco não realizada.</p>"];
        goto end;
    }

    $dataInicial = $dados['dataInicial'];
    $dataFinal = $dados['dataFinal'];

    //Prepara query com os dados do formulário
    $sql = "SELECT * FROM registros WHERE created > '$dataInicial' AND created < '$dataFinal' + INTERVAL '1' DAY";
 
    //Cria um array vazio para armazenar o resultado da consulta
    $rows = [];
    $countRows = 0;
    foreach ($conn->query($sql) as $row) {
        $rows[] = [
            'nome' => $row['nome'],
            'documento' => $row['documento'],
            'valorBem' => $row['valorBem'],
            'percPago' => $row['percPago'],
            'encrGrupo' => date("d/m/Y", strtotime($row['encrGrupo'])),
            'resultado' => $row['resultado'],
            'created' => date("d/m/Y H:i:s", strtotime($row['created']))
        ];
        $countRows += 1;
    }
    
    //Verifica a quantidade de resultados
    if ($countRows >= 1) {
        $retorna = ['status' => true, 'msg' => $rows];

    } else {
        $retorna = ['status' => false, 'msg' => "<p class='alert alert-info' style='margin-top: 50px;'>Não há resultados para a sua consulta.</p>"];
    }
}

end:
echo json_encode($retorna);
