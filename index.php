<!DOCTYPE html>
<html>
<head>
<?php  
require 'config.php'; 

session_start();
date_default_timezone_set('America/Sao_Paulo');

$lista = [];
$lista1 = [];
$lista2 = [];
$lista3 = [];

if(isset($_SESSION['msg'])){
	echo $_SESSION['msg'];
	unset($_SESSION['msg']); 

}

$sql3 = $pdo->query("SELECT *FROM promotores");
if($sql3->rowCount() > 0){
    $lista3 = $sql3->fetchAll(PDO::FETCH_ASSOC);
} 

$sql = $pdo->query("SELECT *FROM movimentos ORDER BY id DESC");
if($sql->rowCount() > 0){
    $lista = $sql->fetchAll(PDO::FETCH_ASSOC);
}  

$sql1 = $pdo->query("SELECT *FROM estoque ORDER BY id DESC");
if($sql1->rowCount() > 0){
    $lista1 = $sql1->fetchAll(PDO::FETCH_ASSOC);
} 

$sql2 = $pdo->query("SELECT *FROM armarios ORDER BY id DESC");
if($sql2->rowCount() > 0){
    $lista2 = $sql2->fetchAll(PDO::FETCH_ASSOC);
}


$horario = date("H:i");
$data = date("d/m/Y"); 



$cpf = filter_input(INPUT_POST, 'cpf');
$armario = $_POST['armarios'];

if(isset($_POST['retirar'])){ 
$query_flag = "SELECT id, cpf FROM promotores WHERE cpf = :cpf LIMIT 1"; 
$result_flag = $pdo->prepare($query_flag); 
$result_flag->bindParam(':cpf',$cpf, PDO::PARAM_STR); 
$result_flag->execute();

if(($result_flag) and ($result_flag->rowCount() != 0)){

$query_armario = "SELECT id, acao FROM estoque WHERE acao = :acao LIMIT 1"; 
$result_armario = $pdo->prepare($query_armario); 
$result_armario->bindParam(':acao',$armario, PDO::PARAM_STR); 
$result_armario->execute(); 

$query_cpf = "SELECT id, cpf FROM estoque WHERE cpf = :cpf LIMIT 1"; 
$result_cpf = $pdo->prepare($query_cpf); 
$result_cpf->bindParam(':cpf',$cpf, PDO::PARAM_STR); 
$result_cpf->execute();

if(($result_armario) and ($result_armario->rowCount() != 0)){
  print '<script>alert("Armário ocupado escolha outro por favor !");</script>';
}else {
  if(($result_cpf) and ($result_cpf->rowCount() != 0)){
  print '<script>alert("CPF já ocupando armário ");</script>';}
  else{


  

    /*$sql = $pdo->prepare("UPDATE setores SET setor = :setor");
    $sql->bindValue(":setor",$setor);
    $sql->execute(); 
    header('Location: index.php');*/
 


if($cpf && $armario){ 

  $sql1 = $pdo->prepare("INSERT INTO estoque (cpf,hora,datas,acao) VALUES (:cpf, :hora, :datas, :acao)");
  //$sql1->bindValue(":flag",$flag);
  $sql1->bindValue(":cpf",$cpf);
  $sql1->bindValue(":hora",$horario); 
  $sql1->bindValue(":datas",$data); 
  $sql1->bindValue(":acao",$armario);
  $sql1->execute(); 
  

	$sql = $pdo->prepare("INSERT INTO movimentos (cpf,hora,data,armario) VALUES (:cpf, :hora, :data, :armario)");
	//$sql1->bindValue(":flag",$flag);
	$sql->bindValue(":cpf",$cpf);
	$sql->bindValue(":hora",$horario); 
	$sql->bindValue(":data",$data); 
  $sql->bindValue(":armario","Pegou ".$armario);
	$sql->execute(); 
  header('Location: index.php'); 

$sql3 = $pdo->prepare("DELETE FROM armarios WHERE armario = :armario");
$sql3->bindValue(":armario",$armario);
$sql3->execute();

    /*$sql = $pdo->prepare("UPDATE setores SET setor = :setor");
    $sql->bindValue(":setor",$setor);
    $sql->execute(); 
    header('Location: index.php');*/
 
} 

} 
} 
}else{
  print '<script>alert("Promotor desconhecido ");</script>';
  
}  

}


if(isset($_POST['devolver'])){
$query_flag1 = "SELECT id, cpf FROM promotores WHERE cpf = :cpf LIMIT 1"; 
$result_flag1 = $pdo->prepare($query_flag1); 
$result_flag1->bindParam(':cpf',$cpf, PDO::PARAM_STR); 
$result_flag1->execute();

if(($result_flag1) and ($result_flag1->rowCount() != 0)){

$query_armario = "SELECT id, acao FROM estoque WHERE acao = :acao LIMIT 1"; 
$result_armario = $pdo->prepare($query_armario); 
$result_armario->bindParam(':acao',$armario, PDO::PARAM_STR); 
$result_armario->execute(); 

$query_cpf = "SELECT id, cpf FROM estoque WHERE cpf = :cpf LIMIT 1"; 
$result_cpf = $pdo->prepare($query_cpf); 
$result_cpf->bindParam(':cpf',$cpf, PDO::PARAM_STR); 
$result_cpf->execute(); 


$armariov = "SELECT acao FROM estoque WHERE cpf = :cpf LIMIT 1"; 
$armariove = $pdo->prepare($armariov); 
$armariove->bindParam(':cpf',$cpf, PDO::PARAM_STR); 
$armariove->execute();
 






if(($result_armario) and ($result_armario->rowCount() == 0)){
  print '<script>alert("Armário não está ocupado !");</script>';
}else {
  if(($result_cpf) and ($result_cpf->rowCount() == 0)){
  print '<script>alert("CPF não está ocupando nenhum armário ");</script>';}
  else{
    $row_armario = $armariove->fetch(PDO::FETCH_ASSOC) ;  
    extract($row_armario);
    $armario_o = $acao;
    if($armario_o != $armario){
  print '<script>alert("Usuário usando outro armário !");</script>';
  
}else{


$sql1 = $pdo->prepare("DELETE FROM estoque WHERE acao = :acao");
 $sql1->bindValue(":acao",$armario);
  $sql1->execute();

  $sql = $pdo->prepare("INSERT INTO movimentos (cpf,hora,data,armario) VALUES (:cpf, :hora, :data, :armario)");
  //$sql1->bindValue(":flag",$flag);
  $sql->bindValue(":cpf",$cpf);
  $sql->bindValue(":hora",$horario); 
  $sql->bindValue(":data",$data); 
  $sql->bindValue(":armario","Desocupou o ".$armario);
  $sql->execute(); 
  header('Location: index.php'); 


  $sql2 = $pdo->prepare("INSERT INTO armarios (armario) VALUES (:armario)");
  //$sql1->bindValue(":flag",$flag);
  //$sql2->bindValue(":cpf",$cpf);
  //$sql2->bindValue(":hora",$horario); 
  //$sql2->bindValue(":data",$data); 
  $sql2->bindValue(":armario",$armario);
  $sql2->execute();
}
}
}
}else{
  print '<script>alert("Promotor desconhecido ");</script>';
} 
}


?>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
	<title>Controlador</title>
</head>
<body style="background-image: linear-gradient(to right, rgba(255,0,0,0), orange);"> 
<nav class="navbar navbar-dark bg-dark fixed-top ">
  <div class="container-fluid" >
    <a class="navbar-brand" href="https://israel-alves.netlify.app" target="_blank">Informática Israel</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
      
        
        
      <div class="offcanvas-body text-center">
        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
          
          <li class="nav-item">
            <a class="nav-link " aria-current="page" href="portal_adm.php">Painel administrador</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="gerar_excel.php">Gerar relatório</a>
          </li> 

             
        <h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel">Armários disponíveis</h5>
 <table class="table table-success table-hover">
     
<?php foreach($lista2 as $armarios) : ?>
     <tr>
      <td><?=$armarios['armario'];?></td>
     </tr>  
     
<?php endforeach; ?>
</table>

          
        </ul>
      </div>
    </div>
  </div>
</nav>
<br>
 <div class="container-fluid">
   <img src="./img/logo_novo.png" class="img-fluid">
 </div>
 <div class="container mt-5 " > 

    <h1 class="display-2 text-center">Controle de Armários</h1>
    <form method="POST" action="" class="text-center" >
    	<input class="form-control" type="text" placeholder="CPF" aria-label="default input example" name="cpf" required> 
        <br>
    
  <label for="armarios">Armários</label> 

  <select class="form-select" aria-label="Default select example" id="armarios" name="armarios" required>
    
  <option value="armário 050">Armário 050</option>
  <option value="armário 051">Armário 051</option>
  <option value="armário 052">Armário 052</option>
  <option value="armário 053">Armário 053</option>
  <option value="armário 054">Armário 054</option>
  <option value="armário 055">Armário 055</option>
  <option value="armário 056">Armário 056</option>
  <option value="armário 057">Armário 057</option>
  <option value="armário 058">Armário 058</option>
  <option value="armário 059">Armário 059</option>
  <option value="armário 060">Armário 060</option>
  <option value="armário 061">Armário 061</option>
  <option value="armário 062">Armário 062</option>
  <option value="armário 063">Armário 063</option>
  <option value="armário 064">Armário 064</option>
  <option value="armário 065">Armário 065</option>
  <option value="armário 066">Armário 066</option>
  <option value="armário 067">Armário 067</option>
  <option value="armário 068">Armário 068</option>
  <option value="armário 069">Armário 069</option>
  <option value="armário 070">Armário 070</option>
  <option value="armário 071">Armário 071</option>
  <option value="armário 072">Armário 072</option>
  <option value="armário 073">Armário 073</option>
  <option value="armário 074">Armário 074</option>
  <option value="armário 075">Armário 075</option>
  <option value="armário 076">Armário 076</option>
  <option value="armário 077">Armário 077</option>
  <option value="armário 078">Armário 078</option>
  <option value="armário 079">Armário 079</option>
  <option value="armário 080">Armário 080</option>
  <option value="armário 081">Armário 081</option>
  <option value="armário 082">Armário 082</option>
  <option value="armário 083">Armário 083</option>
  <option value="armário 084">Armário 084</option>
  <option value="armário 085">Armário 085</option>
  <option value="armário 086">Armário 086</option>
  <option value="armário 087">Armário 087</option>
  <option value="armário 088">Armário 088</option>
  <option value="armário 089">Armário 089</option>
  <option value="armário 090">Armário 090</option>
  <option value="armário 091">Armário 091</option>
  <option value="armário 092">Armário 092</option>
  <option value="armário 093">Armário 093</option>
  <option value="armário 094">Armário 094</option>
  <option value="armário 095">Armário 095</option>
  <option value="armário 096">Armário 096</option>
  <option value="armário 097">Armário 097</option>
  <option value="armário 098">Armário 098</option>
  <option value="armário 099">Armário 099</option>
  <option value="armário 100">Armário 100</option>
  <option value="armário 101">Armário 101</option>
  <option value="armário 102">Armário 102</option>
  <option value="armário 103">Armário 103</option>
  <option value="armário 104">Armário 104</option>
  <option value="armário 105">Armário 105</option>
  <option value="armário 106">Armário 106</option>
  <option value="armário 107">Armário 107</option>
  <option value="armário 108">Armário 108</option>
  <option value="armário 109">Armário 109</option>
  <option value="armário 110">Armário 110</option>
  <option value="armário 111">Armário 111</option>
  <option value="armário 112">Armário 112</option>
  <option value="armário 113">Armário 113</option>
  <option value="armário 114">Armário 114</option>
  <option value="armário 115">Armário 115</option>
  <option value="armário 116">Armário 116</option>
  <option value="armário 117">Armário 117</option>
  <option value="armário 118">Armário 118</option>
  <option value="armário 119">Armário 119</option>
  <option value="armário 120">Armário 120</option>
  <option value="armário 121">Armário 121</option>
  <option value="armário 122">Armário 122</option>
  <option value="armário 123">Armário 123</option>
  <option value="armário 124">Armário 124</option>
  <option value="armário 125">Armário 125</option>
  <option value="armário 126">Armário 126</option>
  <option value="armário 127">Armário 127</option>
  <option value="armário 128">Armário 128</option>
  <option value="armário 129">Armário 129</option>



</select>
    	<br>
      <button class="btn btn-success " type="submit" name="devolver">Devolva</button> 
      <button class="btn btn-success " type="submit" name="retirar">Pegue</button>

    </form> 


 	</div>  


<br><br><br>

<div class="container text-center"> 

  <div class="accordion" id="accordionExample">
  <div class="accordion-item">
    <h2 class="accordion-header" id="headingOne">
      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
        <strong>Relatório de usuários que ocuparam ou desocuparam armários</strong>
      </button>
    </h2>
    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
      <div class="accordion-body">
            
    
     <table class="table table-dark table-hover">

     <?php if(!$lista) :?>
     <tr>
     <td>Nenhum registro</td>
     </tr>
     <?php endif ;?>

     <?php foreach($lista as $movimentos) : ?>
     <tr>
      <td><?=$movimentos['cpf'];?></td>
      <td><?=$movimentos['armario'];?></td>
      <td><?=$movimentos['data'];?></td>
      <td><?=$movimentos['hora'];?></td>
     </tr>  
     
     <?php endforeach; ?>
     </table>
    
      </div>
    </div>
  </div>

  <div class="row">


    <div class="col">
      <h1 class="mt-5"> Armários Ocupados </h1>
      <table class="table table-dark table-hover">

      <?php if(!$lista1) :?>
      <tr>
      <td>Nenhum registro</td>
      </tr>
      <?php endif ;?>



      <?php foreach($lista1 as $estoque) : ?>
     <tr>

      <td><?=$estoque['cpf'];?></td>
      <td><?=$estoque['acao'];?></td>
      <td><?=$estoque['datas'];?></td>
      <td><?=$estoque['hora'];?></td>
     </tr>  
     
     <?php endforeach; ?>

      </table>

   </div> 

   <div class="col">
      <h1 class="mt-5"> Usuários cadastrados </h1>
       <table class="table table-dark table-hover">
       <tr>
      
       <th scope="col">Nome</th>
       <th scope="col">CPF</th> 
       <th scope="col">Empresa</th>
     
      </tr>
     <?php foreach($lista3 as $promotores) : ?> 
    
      <tr>
       <td><?=$promotores['nome'];?></td>
       <td><?=$promotores['cpf'];?></td>
       <td><?=$promotores['empresa'];?></td>
      </tr>
     <?php endforeach; ?> 
   </div>  
   
 

 </div>
</div>


<script type="text/javascript" src="./js/bootstrap.bundle.min.js"></script>
</body>
</html>