<?php  


require 'config.php';
session_start();
ob_start();


$query_movimentos = "SELECT cpf, hora, data, armario FROM movimentos";


$result_movimentos = $pdo->prepare($query_movimentos); 



$result_movimentos->execute();


if(($result_movimentos) and ($result_movimentos->rowCount() != 0)) {
header('Content-Type: text/csv; charset-UTF-8');

header('Content-Disposition: attachment; filename=relatorio.csv');

$resultado = fopen("php://output",'w'); 

$cabecalho = ['Cpf','hora','data','armario'];

fputcsv($resultado, $cabecalho, ';');

while($row_movimentos = $result_movimentos->fetch(PDO::FETCH_ASSOC)){
	//extract($row_movimentos);
	fputcsv($resultado, $row_movimentos, ';');
}

fclose($resultado);

}else{

	//$_SESSION['msg'] = "<p>Nenhum movimento encontrado</p>";
	    print '<script>alert("ERRO: Nenhum registro encontrado");</script>'; 
    echo '<!DOCTYPE html>
<html>
<head> 
  <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
  <title>Erro</title>
</head>
    <div class="container">
    <div class="jumbotron"> 
    <body> 
  <h1 class="display-4">ERRO FATAL</h1>
  <p class="lead">Este erro foi provocado pois não há nenhum registro dentro da tabela.</p>
  <hr class="my-4">
  <p>É necessário que haja conteúdo, ou seja, movimentações para que seja possível transcrever esses dados para dentro de uma planilha, para mais informações entrar em contato com o desenvolvedor.</p>
  <p class="lead">
    <a class="btn btn-primary btn-lg" href="index.php" role="button">Voltar</a>
  </p>
</div> 
</div>
<script type="text/javascript" src="./js/bootstrap.bundle.min.js"></script>
</body>';
}












?>