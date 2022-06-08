
<?php

	//header('Content-Type: application/json; charset=utf-8');
	header('Access-Control-Allow-Origin: *');

	// Valida o token enviado
	function valida_token($_token){
		
		// *************************************************
		
		$validar_token = false; // Habilitar como FALSE para APIs que não usam Token
		
		// *************************************************
		
		if(!$validar_token){
			return true;
		}
		
		if($_token == null){
			return false;
		}
		
		include_once("./classes/Token.php");
		
		$token = new Token();
		$token = $token->get($_token);
		if($token){
			return $token;
		}else{
			return false;
		}
		
	}
	
	// Restringir IP
	function ip(){

		$remote_addr          = $_SERVER['REMOTE_ADDR'];

		return $remote_addr;
		
	}

	// Importa todas as classes da API
	function importa_classes(){
		include_once("classes/Usuario.php");
		include_once("classes/Code.php");
	}
	
	function request($classe, $metodo, $parametros){
		$classe = ucfirst($classe);
		
		if($parametros == NULL){
			$parametros = array();
		}
		
		if($classe == null || $metodo == null){
			return array('status'=>'erro', 'dados'=>'Classe ou método vazio');
			die;
		}else{
			importa_classes();
		}
		
		if(class_exists($classe)){
			// Verifica se o método existe
			if(method_exists($classe, $metodo)){
				$resposta = call_user_func_array(array(new $classe, $metodo), $parametros);
				return array('status'=>'sucesso', 'dados'=>$resposta);
			}else{
				return array('status'=>'erro', 'dados'=>'Metodo ['.$metodo.'] da classe ['.$classe.'] não existe');
			}
		}else{
			return array('status'=>'erro', 'dados'=>'Classe inexistente');
		}
		
	}

	// Exemplo de requisição pela URL:
	// req=classe&m=metodo&p=parametros/parametros
	// req=usuario&m=autenticar&p=email/senha
	if(isset($_REQUEST['req'])){
		
		// Valida Token
		if(!valida_token(null)){
			echo json_encode(array('status'=>'erro', 'dados'=>'Token inválido'));
		}
		
		// Parâmetro req : armazena a classe
		$classe = $_REQUEST['req'];
		$classe = ucfirst($classe);
		
		// Parâmetro m : armazena o método
		$metodo = $_REQUEST['m'];
		
		// Parâmetro p : armazena o parâmetro do método
		$parametros = array();
		if(isset($_REQUEST['p'])){
			$parametros = $_REQUEST['p'];
			$parametros = explode('///', $parametros);
		}
		
		// Verifica se foi passada uma classe e um método
		if($classe == null || $metodo == null){
			echo json_encode(array('status'=>'erro', 'dados'=>'Classe ou método vazio'));
			die;
		}else{
			importa_classes();
		}
		
		// Verifica se a classe existe
		if(class_exists($classe)){
			// Verifica se o método existe
			if(method_exists($classe, $metodo)){
				$resposta = call_user_func_array(array(new $classe, $metodo), $parametros);
				echo json_encode(array('status'=>'sucesso', 'dados'=>$resposta));
			}else{
				echo json_encode(array('status'=>'erro', 'dados'=>'Metodo ['.$metodo.'] da classe ['.$classe.'] não existe'));
			}
		}else{
			echo json_encode(array('status'=>'erro', 'dados'=>'Classe inexistente'));
		}
		
	}
	
	if(isset($_REQUEST['ip'])){
		$resposta = ip();
		echo json_encode(array('status'=>'sucesso', 'dados'=>$resposta));
	}