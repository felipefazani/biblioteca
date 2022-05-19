<?php
	$arq0="senhas.php";
	$arq1="login.html";
	$arq2 = "emprestarLivro.php";
	$arq3 = "fazTudo.php";
	$arq4="senhas_admin.php";
	$tab="livros";
	$arqEmprestimo = "registraEmprDevol.php";
	$admin = 0;
	$funcionario = 1;
	$bib = 2;

	//require_once("$arq0");
	require_once("$arq4");

	$_POST['usuarioDigitado'] = $_POST['usuario'];
	$_POST['senhaDigitada'] = $_POST['senha'];
	$usuarioDigitado = $_POST['usuario'];
	$senhaDigitada = $_POST['senha'];
	$servidor = 'localhost';
	$bd = 'fatec';

	if ($_POST['usuarioDigitado'] == "" or $_POST['senhaDigitada']== ""){
		$_POST['$erroNoLogin'] = 'Usuário ou Senha Inválida';
		header("Location: $arq1");
	}else{
		$conexão = new mysqli($servidor, $usuario, $senha, $bd);
		if ($conexão->connect_error) 
			die($conexão->connect_error);
		$consulta = "SELECT * FROM usuarios WHERE login='$usuarioDigitado'  AND senha = '$senhaDigitada' LIMIT 1";
		$resultado = $conexão->query($consulta);
		if (!$resultado) 
			die ("Erro de acesso à base de dados: " . $conexão->error);
		if (empty($resultado->data_seek(0)))
			header("Location: $arq1");
		else{
			$nivel = $resultado->fetch_assoc()['nivel'];
			
			if ($nivel == $admin)
				header("Location: $arq3");
			else if ($nivel == $funcionario){
				$arq = $arq2;	
				mostraLivros($tab, $arq2, $conexão);
			} else if($nivel == $bib){
				header("Location: $arqEmprestimo");
			}
			else 
				header("Location: $arq1");
		}
	}
	$resultado->close();
	$conexão->close();
		
	function mostraLivros($tab, $arq, $conexão){
		//  ************* Mostrar os livros existentes *************
		$query= "SELECT * FROM $tab";
		$resultado = $conexão->query($query);
		if (!$resultado) 
			die ("Erro de acesso à base de dados: " . $conexão->error);
		
		$linhas = $resultado->num_rows;
		echo "<br>";
		echo "Lista de livros:";
		echo "<br>";
		$_novoId=0;
		for ($j = 0 ; $j < $linhas ; ++$j){
			$resultado->data_seek($j);
			$linha = $resultado->fetch_array(MYSQLI_NUM);
			echo <<<_TEXTO
			<pre>

			Autor	$linha[0]
			Título	$linha[1]
			Área	$linha[2]
			Ano	$linha[3]
			Tombo	$linha[4]
			</pre>

			<form name = "emprestar" action="$arq" method="post">
			<input type="hidden" name="Tombo" value="$linha[4]">
			<input type="submit" value="Emprestar"></form>
_TEXTO;
		}
			
	header("Location: $arq");

	}

?>