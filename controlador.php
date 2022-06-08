<?php


	if(isset($_POST["autenticar"]) && $_POST["autenticar"] == true){
		$email = $_POST["email"];
		$senha = $_POST["senha"];
	
		$usuario = autenticar($email, $senha);
		if($usuario->id == NULL){
			echo false;
		}else{
			session_start();
			$_SESSION["id"] = $usuario->id;
			$_SESSION["email"] = $usuario->email;
			$_SESSION["nome"] = $usuario->nome;
			echo true;
		}
	}
	
	if(isset($_POST["qrcode"])){
		include_once("./classes/Code.php");
		$code = new Code();
		$code = $code->busca();
		$code = $code->codigo;
		$imagem = base64_encode($code);
		echo $imagem;
		//var_dump($code);
	}
	
	
	
	if(isset($_POST["cria_sessao"])){
		$nome = $_POST["nome"];
		$valor = $_POST["valor"];
		
		session_start();
		$_SESSION[$nome] = $valor;
	}
	
	if(isset($_POST["hiddenRedirecionamento"])){
		$nomeCode = $_POST["nomeCode"];
		$codigo = $_POST["code"];
		$endereco = $_POST["endereco"];
		
		$codigo = explode(',', $codigo);
		$codigo = base64_decode($codigo[1]);
		$filepath = "image.png";
		file_put_contents($filepath,$codigo);

		session_start();
		$id_usuario = $_SESSION["id"];

		if(strlen($nomeCode) < 3){
			echo json_msg(0, "Nome inválido", "Verifique o valor inserido no nome do QR Code e tente novamente.");
			return;
		}
		if(strlen($codigo) == NULL){
			echo json_msg(0, "Erro ao gerar QR Code", "Ocorreu um erro ao gerar seu QR Code. Tente novamente mais tarde.");
			return;
		}
		if(strlen($endereco) < 7){
			echo json_msg(0, "Endereço inválido", "Verifique o valor inserido no campo Endereço e tente novamente.");
			return;
		}


		//echo $nomeCode, $id_usuario, $endereco;
		
		if($id_usuario == NULL){
			echo json_msg(0, "Usuário não autenticado", "Tente sair e conectar-se novamente.");
			return;
		}
		
		include_once("./classes/Code.php");
		$code = new Code();
		$resposta = $code->inserir_redirecionamento($id_usuario, $codigo, $nomeCode, $endereco);

		if(!$resposta){
			echo json_msg(0, "Erro ao cadastrar QR Code", "Ocorreu um problema ao cadastrar seu QR Code. Tente novamente mais tarde.");
			return;
		}
		
		echo json_code_sucesso($resposta);
		
	}

	function autenticar($email, $senha){
		include_once("./classes/Usuario.php");
		$usuario = new Usuario();
		$usuario = $usuario->autenticar($email, $senha);
		return $usuario;
	}

	function json_msg($status, $titulo, $mensagem){
		if($status == 0){
			$status = "erro";
		}
		if($status == 1){
			$status = "sucesso";
		}
		
		$json = json_encode(array('status'=>$status, 'titulo'=>$titulo, 'mensagem'=>$mensagem));
		
		return $json;
	}

	function json_code_sucesso($code){
		$json = json_encode(array('status'=>1, 'id'=>$code->id, 'nome'=>$code->nome, 'url'=>$code->url, 'codigo'=>base64_encode($code->codigo), ));
		return $json;
	}



	
?>