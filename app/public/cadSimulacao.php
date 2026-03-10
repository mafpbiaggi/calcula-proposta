<?php

//Função de finalização de cálculo
function endCalc($r, $n, $d, $v, $p, $e) {
    $fmt = numfmt_create( 'pt_BR', NumberFormatter::CURRENCY );
    $r = numfmt_format_currency($fmt, $r, "BRL");
    cadSimulacao($n, $d, $v, $p, $e, $r);

    return ['status' => true, 'msg' => "<p style='color: green;'>Resultado: <b>" . $r . "</b></p>" ];
}

//Função cadastra simulação no BD
function cadSimulacao($nome, $documento, $valorBem, $percPago, $encrGrupo, $resultado) {

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
        return ['status' => false, 'msg' => "<p style='color: #f00;'>Conexão com o banco não realizada.</p>"];
        
    }

    $querySimulacao = "INSERT INTO registros (nome, documento, valorBem, percPago, encrGrupo, resultado, created) VALUES (:nome, :documento, :valorBem, :percPago, :encrGrupo, :resultado, (SELECT now()))";
    $cadSimulacao = $conn->prepare($querySimulacao);
    $cadSimulacao->bindParam(':nome', $nome);
    $cadSimulacao->bindParam(':documento', $documento);
    $cadSimulacao->bindParam(':valorBem', $valorBem);
    $cadSimulacao->bindParam(':percPago', $percPago);
    $cadSimulacao->bindParam(':encrGrupo', $encrGrupo);
    $cadSimulacao->bindParam(':resultado', $resultado);
    $cadSimulacao->execute();
}

//Recebe os dados enviado pelo JavaScript
$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//Valida o preencimento dos campos
if(empty($dados['nome']) || empty($dados['documento']) || empty($dados['valorBem']) || empty($dados['percPago']) || empty($dados['encrGrupo']) ){
    $retorna = ['status' => false, 'msg' => "<p style='color: #f00;'>Erro: Por favor, preencha todos os campos!</p>"];

//Valida o preenchimento do campo percentual pago, deve ser menor do que 100%.
}elseif($dados['percPago'] > 99 ) {
    $retorna = ['status' => false, 'msg' => "<p style='color: #f00;'>Erro: Percentual pago maior/igual a 100%.</p>"];
    
}else{

    //Valida subtotal para poder continuar. Deve ser maior do que 7000
    $subTotal = $dados['valorBem'] * ($dados['percPago'] / 100);

    if ($subTotal < 7000) {
        $retorna = ['status' => false, 'msg' => "<p style='color: #f00;'>Percentual pago fora dos parâmentros de proposta.</p>"];

    }else {

        //Inicia calculos baseados nos encerramentos de grupo
        if (strtotime($dados['encrGrupo']) < strtotime('2025-07-01')) {
            $resultProp = $subTotal * 0.4;
            $retorna = endCalc($resultProp, $dados['nome'], $dados['documento'] ,$dados['valorBem'], $dados['percPago'], $dados['encrGrupo']);
            goto end;
        }

        if (strtotime($dados['encrGrupo']) > strtotime('2025-06-30') && strtotime($dados['encrGrupo']) < strtotime('2026-07-01')) {
            $resultProp = $subTotal * 0.3333;
            $retorna = endCalc($resultProp, $dados['nome'], $dados['documento'] ,$dados['valorBem'], $dados['percPago'], $dados['encrGrupo']);
            goto end;
        }

        if (strtotime($dados['encrGrupo']) > strtotime('2026-06-30') && strtotime($dados['encrGrupo']) < strtotime('2027-07-01')) {
            $resultProp = $subTotal * 0.3;
            $retorna = endCalc($resultProp, $dados['nome'], $dados['documento'] ,$dados['valorBem'], $dados['percPago'], $dados['encrGrupo']);
            goto end;
        }

        if (strtotime($dados['encrGrupo']) > strtotime('2027-06-30') && strtotime($dados['encrGrupo']) < strtotime('2028-07-01')) {
            $resultProp = $subTotal * 0.27;
            $retorna = endCalc($resultProp, $dados['nome'], $dados['documento'] ,$dados['valorBem'], $dados['percPago'], $dados['encrGrupo']);
            goto end;
        }

        if (strtotime($dados['encrGrupo']) > strtotime('2028-06-30') && strtotime($dados['encrGrupo']) < strtotime('2029-07-01')) {
            $resultProp = $subTotal * 0.24;
            $retorna = endCalc($resultProp, $dados['nome'], $dados['documento'] ,$dados['valorBem'], $dados['percPago'], $dados['encrGrupo']);
            goto end;
        }

        if (strtotime($dados['encrGrupo']) > strtotime('2029-06-30') && strtotime($dados['encrGrupo']) < strtotime('2031-07-01')) {
            $resultProp = $subTotal * 0.20;
            $retorna = endCalc($resultProp, $dados['nome'], $dados['documento'] ,$dados['valorBem'], $dados['percPago'], $dados['encrGrupo']);
            goto end;
        }

        if (strtotime($dados['encrGrupo']) > strtotime('2031-06-30')) {
            $resultProp = $subTotal * 0.13;
            $retorna = endCalc($resultProp, $dados['nome'], $dados['documento'] ,$dados['valorBem'], $dados['percPago'], $dados['encrGrupo']);
            goto end;
        }
    }
}

end:
echo json_encode($retorna);
