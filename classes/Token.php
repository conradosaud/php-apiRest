<?php

	class Token{
		
		private $token;
		private $inicio;
		private $fim;
		private $status;
		
		
		/* CRUD */
		function get($token){
			
		}
		
		function insert(){
			
		}
		
		function update($token, $new){
			
		}
		
		function del($token){
			
		}


		/* GET */
		public getToken(){
			return $this->token;
		}
		public getInicio(){
			return $this->token;
		}
		public getFim(){
			return $this->token;
		}
		public getStatus(){
			return $this->token;
		}
		
		/* SET */
		public setToken($token){
			$this->token = $token;
		}
		public setToken($inicio){
			$this->inicio = $inicio;
		}
		public setToken($fim){
			$this->fim = $fim;
		}
		public setToken($status){
			$this->status = $status;
		}
	}