<?php
	include_once "./lib/db.php";
	class Respuesta {
		// Declaración explícita de propiedades
		private $respuesta;
		private $pregunta;
		private $encuesta;
		private $encuestado;
	
		// Constructor opcional para inicializar propiedades
		public function __construct($respuesta = null, $pregunta = null, $encuesta = null, $encuestado = null) {
			$this->respuesta = $respuesta;
			$this->pregunta = $pregunta;
			$this->encuesta = $encuesta;
			$this->encuestado = $encuestado;
		}
	
		// Métodos setters
		public function set_respuesta($respuesta) {
			$this->respuesta = $respuesta;
		}
	
		public function set_pregunta($pregunta) {
			$this->pregunta = $pregunta;
		}
	
		public function set_encuesta($encuesta) {
			$this->encuesta = $encuesta;
		}
	
		public function set_encuestado($encuestado) {
			$this->encuestado = $encuestado;
		}
	
		// Métodos getters (opcional, si necesitas acceder a estas propiedades)
		public function get_respuesta() {
			return $this->respuesta;
		}
	
		public function get_pregunta() {
			return $this->pregunta;
		}
	
		public function get_encuesta() {
			return $this->encuesta;
		}
	
		public function get_encuestado() {
			return $this->encuestado;
		}
	
		
		
		public function read($id){
			$sql = "SELECT * FROM respuestas WHERE id = ". $id;
			//echo $sql;
			$res = mysqli_query($this->con, $sql);
			
			if ($res != FALSE){
				$tupla = mysqli_fetch_assoc($res);
				$this->id = $tupla['id'];
				$this->respuesta = $tupla['respuesta'];
				$this->fecha = $tupla['fecha'];
				$this->pregunta = $tupla['pregunta'];
				$this->encuesta = $tupla['encuesta'];
				$res = TRUE;
			}

			return $res;
		}
		
		// public function add(){
		// 	$sql = "INSERT INTO `respuestas` (`respuesta`, `pregunta`, `encuesta`, `encuestado`) VALUES ('";
		// 	$sql .= $this->respuesta . "', '";
		// 	$sql .= $this->pregunta . "', '";
		// 	$sql .= $this->encuesta . "', '";
		// 	$sql .= $this->encuestado . "')";
			
		// 	//echo $sql;
		// 	$res = mysqli_query($this->con, $sql);
		// 	if($res){
		// 		return true;
		// 	}else{
		// 		return false;
		// 	}

		// 	return $res;
		// }

		public function enviarRespuestaAPIDesdeObjeto() {
			// Accede directamente a las propiedades del objeto actual
			$data = array(
				'respuesta' => $this->respuesta,
				'pregunta' => $this->pregunta,
				'encuesta' => $this->encuesta,
				'encuestado' => $this->encuestado
			);
		
			// Convierte los datos a JSON
			$jsonData = json_encode($data);
		
			// Inicializa cURL
			$ch = curl_init();
		
			// Configuración de cURL para enviar la solicitud
			curl_setopt($ch, CURLOPT_URL, 'http://localhost:3000/respuestas');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Content-Length: ' . strlen($jsonData)
			));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
		
			// Ejecuta la solicitud y obtiene la respuesta
			$response = curl_exec($ch);
			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		
			// Manejo de errores
			if (curl_errno($ch)) {
				echo 'Error en la solicitud: ' . curl_error($ch);
			} else if ($httpCode === 201) {
				echo "✅ Respuesta guardada con éxito: " . $response;
			} else {
				echo "❌ Error del servidor API (HTTP $httpCode): " . $response;
			}
		
			// Cierra la conexión cURL
			curl_close($ch);
		}
		
		public function respuestas_docente($docente){
			$sql = "SELECT * FROM respuestas, encuestas WHERE respuestas.encuesta = encuesta.id AND encuesta.docente = ". $docente;
			//echo $sql;
			$res = mysqli_query($this->con, $sql);
			
			if($res){
				while($row = mysqli_fetch_array($res)){
					$rows[] = $row;
				}
			}else
				$rows=FALSE;

			return $rows;
		}
		
		public function respuestas_espacio_curricular($espacio_curricular){
			$sql = "SELECT * FROM respuestas, encuestas WHERE respuestas.encuesta = encuesta.id AND encuesta.espacio_curricular = ". $espacio_curricular;
			//echo $sql;
			$res = mysqli_query($this->con, $sql);
			
			if($res){
				while($row = mysqli_fetch_array($res)){
					$rows[] = $row;
				}
			}else
				$rows=FALSE;

			return $rows;
		}
	}
?>