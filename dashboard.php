<?php  

require 'config.php';
$lista1 = [];
$lista2 = [];
session_start(); 
ob_start();
if((!isset($_SESSION['id'])) AND (!isset($_SESSION['usuario']))){
header("Location: portal_adm.php"); 
$_SESSION['msg'] = "<p> Erro: Necessário realizar o login para acessar essa página</p>";

} 

$sql = $pdo->query("SELECT *FROM promotores");
if($sql->rowCount() > 0){
    $lista = $sql->fetchAll(PDO::FETCH_ASSOC);
}  

$sql2 = $pdo->query("SELECT *FROM movimentos ORDER BY id DESC");
if($sql2->rowCount() > 0){
    $lista2 = $sql2->fetchAll(PDO::FETCH_ASSOC);
}

$cpf = filter_input(INPUT_POST,'cpf');
$nome= filter_input(INPUT_POST,'nome');
$empresa = filter_input(INPUT_POST,'empresa');

if(isset($_POST['resetar'])){
$sql3 = $pdo->prepare("DELETE FROM movimentos");
$sql3->execute(); 
header("Location: index.php"); 

}

if(isset($_POST['cadastrar'])){
	$sql = $pdo->prepare("INSERT INTO promotores (cpf,nome,empresa) VALUES (:cpf,:nome,:empresa)");
	$sql->bindValue(":cpf",$cpf); 
  $sql->bindValue(":nome",$nome);
  $sql->bindValue(":empresa",$empresa);
	$sql->execute();
header("Location: dashboard.php");
} 

$quantidade = filter_input(INPUT_POST,'estoque');
if(isset($_POST['editar'])){
	$sql = $pdo->prepare("UPDATE cartoes SET quantidade = :quantidade");
	$sql->bindValue(":quantidade",$quantidade);
	$sql->execute();
header("Location: dashboard.php");
} 


?> 

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
	<title>Administrador</title>
</head>
<body>  

	<nav class="navbar bg-dark ">
  <div class="container-fluid">
    <a class="navbar-brand" href="https://israel-alves.netlify.app" style="color:white;" target="_blank">Criado por Israel Alves</a> 

    <a href="sair.php" class="btn btn-warning">Sair</a>
  </div>
</nav>
	<br><br>
	<div class="container">
	  <form method="POST" action="" class="text-center" >
      <h1 class="mt-5"> Cadastrar usuários </h1>
      <input type="number" name="cpf" placeholder="CPF"  class = "form-control" required>
      <br>
      <input type="text" name="nome" placeholder="Nome completo"  class = "form-control" required>
      <br> 
      <input type="text" name="empresa" placeholder="Empresa"  class = "form-control" required>
      <br>
      <button class="btn btn-success " type="submit" name="cadastrar">Submeter</button>
     
      </form>  
    <br><br>
    <!--<form method="POST" class="text-center"> 
    <input type="number" name="estoque" placeholder="Quantidade" class = "form-control" required>
    <br>
    <button class="btn btn-success " type="submit" name="editar">Definir estoque</button>
    </form>-->  

     <br><br><br>
     

       <div class="container text-center">
 <h1 class="mt-5"> Movimentos </h1>
 <table class="table table-dark table-hover">
<?php if(!$lista2) :?>
<tr>
<td>Nenhum registro</td>
</tr>
<?php endif ;?>
     <!--<tr>
      
      <th scope="col">CPF</th>
      <th scope="col">Armários</th>
      <th scope="col">Datas</th>
      <th scope="col">Horário</th>
     
    </tr>-->
<?php foreach($lista2 as $movimentos) : ?>
     <tr>
      <td><?=$movimentos['cpf'];?></td>
      <td><?=$movimentos['armario'];?></td>
      <td><?=$movimentos['data'];?></td>
      <td><?=$movimentos['hora'];?></td>
     </tr>  
     
<?php endforeach; ?>
</table>
  <form method="POST" class="text-center">
   <button class="btn btn-danger " type="submit" name="resetar">Excluir relatório</button>
    </form>
  </div>

    </div> 
    
    <br><br><br>
     
    <div class="container text-center">
      <h1 class="mt-5"> Usuários cadastrados </h1>
    	 <table class="table table-dark table-hover">
     <tr>
      
      <th scope="col">Nome</th>
      <th scope="col">CPF</th> 
      <th scope="col">Empresa</th>
      <th scope="col">Ação</th>
     
    </tr>
    <?php foreach($lista as $promotores) : ?> 
    
     <tr>
      <td><?=$promotores['nome'];?></td>
      <td><?=$promotores['cpf'];?></td>
      <td><?=$promotores['empresa'];?></td>
        <td>
       <a href="excluir.php?id=<?=$promotores['id'];?>" class="btn btn-danger">Excluir</a>

      </td>
     </tr>
    <?php endforeach; ?>



    </div> 


  





 
<script type="text/javascript" src="./js/bootstrap.bundle.min.js"></script>
</body>
</html>