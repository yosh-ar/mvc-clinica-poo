<?php

require_once "../controladores/usuarios.controlador.php";
require_once "../modelos/usuarios.modelo.php";

class AjaxUsuarios{

	/*=============================================
	EDITAR USUARIO
	=============================================*/	

	public $idUsuario;

	public function ajaxEditarUsuario(){

		$item = "id"; //envio el id
		$valor = $this->idUsuario; //evaluo si es igual

		$respuesta = ControladorUsuarios::ctrMostrarUsuarios($item, $valor);

		echo json_encode($respuesta);// comvierdo los datos en json

	}

	/*=============================================
	ACTIVAR USUARIO
	=============================================*/	

	public $activarUsuario;
	public $activarId;

	/*metodo para acctivar usuario */
	public function ajaxActivarUsuario(){

		$tabla = "usuarios";

		$item1 = "estado";//solicito que actualice el estado
		$valor1 = $this->activarUsuario; //con este valor

		$item2 = "id";//que coincida con el id
		$valor2 = $this->activarId;//con el valor del id
		//solicitamos una respuesta al modelo 

		$respuesta = ModeloUsuarios::mdlActualizarUsuario($tabla, $item1, $valor1, $item2, $valor2);

	}

	/*=============================================
	VALIDAR NO REPETIR USUARIO
	=============================================*/	

	public $validarUsuario; //variable publica

	public function ajaxValidarUsuario(){
		//metodo
		$item = "usuario"; //nos muestre el usuario
		$valor = $this->validarUsuario;//que viene guardado con el usuario que viene por el metodo post

		$respuesta = ControladorUsuarios::ctrMostrarUsuarios($item, $valor);
		//nuestra las coincidencias
		echo json_encode($respuesta);

	}
}

/*=============================================
EDITAR USUARIO
=============================================*/
if(isset($_POST["idUsuario"])){
	//en caso de que venga un id
	$editar = new AjaxUsuarios(); //nueva clase
	$editar -> idUsuario = $_POST["idUsuario"];  //evaluo si es igual al metodo post
	$editar -> ajaxEditarUsuario(); //ejecuto el metodo

}

/*=============================================
ACTIVAR USUARIO
=============================================*/	

if(isset($_POST["activarUsuario"])){

	$activarUsuario = new AjaxUsuarios();
	$activarUsuario -> activarUsuario = $_POST["activarUsuario"];
	$activarUsuario -> activarId = $_POST["activarId"];
	$activarUsuario -> ajaxActivarUsuario();

}

/*=============================================
VALIDAR NO REPETIR USUARIO
=============================================*/
//si viene una variable post en validar usuario
if(isset( $_POST["validarUsuario"])){

	$valUsuario = new AjaxUsuarios();
	$valUsuario -> validarUsuario = $_POST["validarUsuario"];
	$valUsuario -> ajaxValidarUsuario();//ejecutamos el metodo

}