<?php

require_once "../controladores/categorias.controlador.php";
require_once "../modelos/categorias.modelo.php";

class AjaxCategorias{

	/*=============================================
	EDITAR CATEGORÍA
	=============================================*/	

	public $idCategoria;//declaramos una variable publica

	public function ajaxEditarCategoria(){//hacemos un metodo

		$item = "id";//mandamos el id
		$valor = $this->idCategoria;//evaluamos si es el mismo

		$respuesta = ControladorCategorias::ControllerMostrarCategorias($item, $valor);

		echo json_encode($respuesta);//enviamos la respuesta en json

	}
}

/*=============================================
EDITAR CATEGORÍA
=============================================*/	
if(isset($_POST["idCategoria"])){//evaluamos si viene un id

	$categoria = new AjaxCategorias();//hacemos una instancia
	$categoria -> idCategoria = $_POST["idCategoria"];//igualamos la variable publica a lo que viene por post
	$categoria -> ajaxEditarCategoria();//ejecutamos el metodo
}
