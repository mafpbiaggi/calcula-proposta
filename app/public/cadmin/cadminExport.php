<?php

    //Coleta dados da variável de ambiente para a conexão com o banco
    $host = getenv('MYSQL_HOST');
    $user = getenv('MYSQL_USER');
    $pass = getenv('MYSQL_PASSWORD');
    $dbname = getenv('MYSQL_DATABASE');
    $port = getenv('MYSQL_PORT');

try {
    $conn = new PDO("mysql:host=$host;port=$port;dbname=" . $dbname, $user, $pass);

} catch (PDOException $err) {
    echo "<p class='alert alert-danger' style='margin-top: 50px;'>Conexão com o banco não realizada.<br>".$err->getMessage()."</p>"; 
}

$dataInicial = $_POST['dataInicialEx'];
$dataFinal = $_POST['dataFinalEx'];

//Prepara query com os dados do formulário
$query = $conn->query("SELECT * FROM registros WHERE created > '$dataInicial' AND created < '$dataFinal' + INTERVAL '1' DAY");

if($query->rowCount() > 0){
    $delimiter = ","; 
    $filename = "consulta-calc_" . date('d-m-Y-His') . ".csv";

    //Cria arquivo ponteiro
    $f = fopen('php://memory', 'w'); 
     
    //Define o cabeçalho
    $fields = array('Nome', 'Documento', 'Valor do Bem', '% Pago', 'Encer. Grupo', 'Valor Simulado', 'Simulado em');
    fputcsv($f, $fields, $delimiter);

    //Extrai os dados das linhas, formata as linhas como csv e escreve o ponteiro
    while($row = $query->fetch()){ 
        $lineData = array($row['nome'], $row['documento'], $row['percPago'], $row['valorBem'], $row['encrGrupo'], $row['resultado'], $row['created']); 
        $teste = fputcsv($f, $lineData, $delimiter); 
    }
        
    //Volta ao início do arquivo
    fseek($f, 0); 
    
    //Define header para fazer o download do arquivo
    header('Content-Type: text/csv'); 
    header('Content-Disposition: attachment; filename="' . $filename . '";'); 
     
    //Exibe o dado restaste no ponteiro
    fpassthru($f);

} else {
    echo "<p class='alert alert-danger' style='margin-top: 50px;'>Falha ao exportar arquivo.</p>";
}
