<?php

require_once "conexion.php";

class ModeloUsuarios{

	/*=============================================
	MOSTRAR USUARIOS
	=============================================*/

	static public function mdlMostrarUsuarios($tabla, $item, $valor){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();

		}else{

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");

			$stmt -> execute();

			return $stmt -> fetchAll();

		}
		

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	REGISTRO DE USUARIO
	=============================================*/

	static public function mdlIngresarUsuario($tabla, $datos){

		//ejecuto la consulta llamando ala clase conexion y al metodo conectar 
        /*al colocar :nombre le indico a los valores que se comviertan en parametros para posterirormente utilizarlos  */
        $consulta = Conexion::conectar()->prepare("INSERT INTO $tabla(nombre, usuario, password, perfil, foto) VALUES (:nombre, :usuario, :password, :perfil, :foto)");
        /*inserto los datos en su arreglo correspondientes  */
		$consulta->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$consulta->bindParam(":usuario", $datos["usuario"], PDO::PARAM_STR);
		$consulta->bindParam(":password", $datos["password"], PDO::PARAM_STR);
		$consulta->bindParam(":perfil", $datos["perfil"], PDO::PARAM_STR);
		$consulta->bindParam(":foto", $datos["foto"], PDO::PARAM_STR);

		if($consulta->execute()){//si la consulta se ejectua que retorne un ok

			return "ok";

		}else{//en caso de que los datos no hayan sido guardados que me devuelba un error 

			return "error";
		
		}

		$consulta->close();//cierro la connexion
		
		$consulta = null; // la hago null para que solo se utilice una vez
	

	}

	/*=============================================
	EDITAR USUARIO
	=============================================*/

	static public function mdlEditarUsuario($tabla, $datos){
	
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET nombre = :nombre, password = :password, perfil = :perfil, foto = :foto WHERE usuario = :usuario");

		$stmt -> bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt -> bindParam(":password", $datos["password"], PDO::PARAM_STR);
		$stmt -> bindParam(":perfil", $datos["perfil"], PDO::PARAM_STR);
		$stmt -> bindParam(":foto", $datos["foto"], PDO::PARAM_STR);
		$stmt -> bindParam(":usuario", $datos["usuario"], PDO::PARAM_STR);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	ACTUALIZAR USUARIO
	=============================================*/

	static public function mdlActualizarUsuario($tabla, $item1, $valor1, $item2, $valor2){
		//generamos un update el set seria el item y el where seria el item2

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET $item1 = :$item1 WHERE $item2 = :$item2");

		$stmt -> bindParam(":".$item1, $valor1, PDO::PARAM_STR);
		$stmt -> bindParam(":".$item2, $valor2, PDO::PARAM_STR);

		if($stmt -> execute()){

			return "ok"; // si se ejectua que me retorne un ok
		
		}else{
			//en caso contrario
			return "error";	

		}

		$stmt -> close();
		//limpiamos y desconectamos
		$stmt = null;

	}

	/*=============================================
	BORRAR USUARIO
	=============================================*/

	static public function mdlBorrarUsuario($tabla, $datos){
		//borre donde la tabla coincida con el id de lo que viene en datos
		$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id = :id");

		$stmt -> bindParam(":id", $datos, PDO::PARAM_INT);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt -> close();

		$stmt = null;


	}

}