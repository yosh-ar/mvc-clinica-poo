<?php 
require_once "conexion.php";
   /*=============================================
	MOSTRAR Categorias
	=============================================*/
class ModeloCategorias{
    static public function ModelMostrarCategorias($tabla, $item,$valor){
        if($item != null){

			$consulta = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");

			$consulta -> bindParam(":".$item, $valor, PDO::PARAM_STR);

			$consulta -> execute();

			return $consulta -> fetch();

		}else{

			$consulta = Conexion::conectar()->prepare("SELECT * FROM $tabla");

			$consulta -> execute();

			return $consulta -> fetchAll();

		}
		

		$consulta -> close();

		$consulta = null;
    }
       /*=============================================
	Ingresar Categorias Categorias
    =============================================*/  
    static public function ModelIngresarCategorias($tabla, $datos){
        //recibimos la tabla y los datos 

        $consulta = Conexion::conectar()->prepare("INSERT INTO $tabla(categoria) VALUES (:categoria)");

		$consulta->bindParam(":categoria", $datos, PDO::PARAM_STR);

		if($consulta->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$consulta->close();
		$consulta = null;
    } 
      /*=============================================
	EDITAR CATEGORIA
	=============================================*/

	static public function ModeloEditarCategoria($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET categoria = :categoria WHERE id = :id");

		$stmt -> bindParam(":categoria", $datos["categoria"], PDO::PARAM_STR);
		$stmt -> bindParam(":id", $datos["id"], PDO::PARAM_INT);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$stmt->close();
		$stmt = null;

	}

	/*=============================================
	BORRAR CATEGORIA
	=============================================*/

	static public function ModelBorrarCategoria($tabla, $datos){
        //recibimos la tabla y el id
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