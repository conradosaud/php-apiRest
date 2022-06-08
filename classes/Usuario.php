<?php

	include_once("Dao.php");

	class Usuario{
		public $id;
		public $nome;
		public $email;
		public $senha;
		public $imagem;
		public $status;
		public $data_criacao;
		
		private $db;
		private $usuario;
		
		public function __construct(){
			$db = new Dao();
			$this->db = $db->instance();
		}
		
		public function busca_email($email){
			
			try{
            
				$sql = "
					SELECT email FROM usuarios
					WHERE email = :email AND status != 'inativo';
					";
				
				$query = $this->db->prepare($sql);
				$query->bindValue(":email", $email, PDO::PARAM_STR);
				$query->execute();
				$dados = $query->fetchAll(PDO::FETCH_ASSOC);
				
				$usuario = new Usuario();
				
				foreach($dados as $listado){
					$usuario->email = $listado["email"];
				}
				
				return $usuario;
				
			} catch (Exception $ex) {
				echo "erro ".$ex->getMessage();
				return NULL;
			}
			
		}
		
		public function autenticar($email, $senha){
			
			try{
            
				$sql = "
					SELECT * FROM usuarios
					WHERE email = :email AND senha = :senha AND status != 'inativo';
					";
				
				$query = $this->db->prepare($sql);
				$query->bindValue(":email", $email, PDO::PARAM_STR);
				$query->bindValue(":senha", $senha, PDO::PARAM_STR);
				$query->execute();
				$dados = $query->fetchAll(PDO::FETCH_ASSOC);
				
				$usuario = $this->carrega_dados($dados);
				
				if($usuario != NULL){
					$usuario = $usuario[0];
				}
				
				return $usuario;
				
			} catch (Exception $ex) {
				echo "erro ".$ex->getMessage();
				return NULL;
			}
			
		}
		
		public function inserir($nome, $email, $senha) {
			
			$usuario = $this->busca_email($email);
			if($usuario->email != null){
				return "email existente";
			}
			
			try{

				$sql = "
					INSERT INTO usuarios
					(nome, email, senha, data_criacao)
					VALUES
					(:nome, :email, :senha, :data_criacao);
				";

				$query = $this->db->prepare($sql);
				$query->bindValue(":nome", $nome, PDO::PARAM_STR);
				$query->bindValue(":email", $email, PDO::PARAM_STR);
				$query->bindValue(":senha", $senha, PDO::PARAM_STR);
				$query->bindValue(":data_criacao", $this->data_agora(), PDO::PARAM_STR);
				$query->execute();
				return true;

			} catch (Exception $ex) {
				echo "erro ".$ex;
				return false;
			}
		}
		
		private function carrega_dados($dados){
			$classes = array();
			foreach($dados as $listado){
				$classe = new Usuario();
				$this->classe->id = $listado["id"];
				$this->classe->nome = $listado["nome"];
				$this->classe->email = $listado["email"];
				$this->classe->senha = $listado["senha"];
				$this->classe->imagem = $listado["imagem"];
				$this->classe->status = $listado["status"];
				$this->classe->data_criacao = $listado["data_criacao"];
				$classes[] = $classe;
			}
			
			return $classes;
		}
		
		private function data_agora(){
			date_default_timezone_set('America/Sao_Paulo');
			return date('Y/m/d');
		}
		
	}