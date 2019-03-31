<?php 
require_once "conexion.php";
class ModeloClientes{
    	/*=============================================
	Mostrar clientes
	=============================================*/
    static public function ModelMostrarClientes($tabla, $item, $valor){
        if($item != null){

			$consultla = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");

			$consultla -> bindParam(":".$item, $valor, PDO::PARAM_STR);

			$consultla -> execute();

			return $consultla -> fetch();

		}else{

			$consultla = Conexion::conectar()->prepare("SELECT * FROM $tabla");

			$consultla -> execute();

			return $consultla -> fetchAll();

		}

		$consultla -> close();

		$consultla = null;

    }   
    	/*=============================================
	INTRESAR clientes
	=============================================*/
    static public function ModelIngresarClientes($tabla, $datos)
    {
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(nombre, documento, email, 
        telefono, direccion, fecha_nacimiento) VALUES (:nombre, :documento, :email, :telefono, :direccion, :fecha_nacimiento)");

		$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt->bindParam(":documento", $datos["documento"], PDO::PARAM_INT);
		$stmt->bindParam(":email", $datos["email"], PDO::PARAM_STR);
		$stmt->bindParam(":telefono", $datos["telefono"], PDO::PARAM_STR);
		$stmt->bindParam(":direccion", $datos["direccion"], PDO::PARAM_STR);
		$stmt->bindParam(":fecha_nacimiento", $datos["fecha_nacimiento"], PDO::PARAM_STR);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$stmt->close();
		$stmt = null;
    }   
    	/*=============================================
	EDITAR clientes
	=============================================*/
    static public function ModelEditarClientes($tabla, $datos){
        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET nombre = :nombre, documento = :documento, email = :email, telefono = :telefono, direccion = :direccion, fecha_nacimiento = :fecha_nacimiento WHERE id = :id");

		$stmt->bindParam(":id", $datos["id"], PDO::PARAM_INT);
		$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt->bindParam(":documento", $datos["documento"], PDO::PARAM_INT);
		$stmt->bindParam(":email", $datos["email"], PDO::PARAM_STR);
		$stmt->bindParam(":telefono", $datos["telefono"], PDO::PARAM_STR);
		$stmt->bindParam(":direccion", $datos["direccion"], PDO::PARAM_STR);
		$stmt->bindParam(":fecha_nacimiento", $datos["fecha_nacimiento"], PDO::PARAM_STR);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$stmt->close();
		$stmt = null;

    }   
    	/*=============================================
	ELIMINAR clientes
	=============================================*/
    static public function ModelEliminarClientes($tabla,$datos){
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
    
    static public function mdlActualizarCliente($tabla, $item1, $valor1, $valor){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET $item1 = :$item1 WHERE id = :id");

		$stmt -> bindParam(":".$item1, $valor1, PDO::PARAM_STR);
		$stmt -> bindParam(":id", $valor, PDO::PARAM_STR);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt -> close();

		$stmt = null;

	}
}