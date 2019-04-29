<?php

class ControladorUsuarios{

	/*=============================================
	INGRESO DE USUARIO
	=============================================*/

	static public function ctrIngresoUsuario(){

		if(isset($_POST["ingUsuario"])){
			  // comprobamos si viene del name ingUsuario
            /*realizamos una validacion para solo permitir 
            letras mayusculas minusculas y numeros del 0-9 
            para evitar ataques sql Inyection */
				if(preg_match('/^[a-zA-Z0-9]+$/', $_POST["ingUsuario"]) &&
			   preg_match('/^[a-zA-Z0-9]+$/', $_POST["ingPassword"])){

			   	$encriptar = crypt($_POST["ingPassword"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
			//	$encriptar = sha1($_POST["ingPassword"]);
			//	$encriptar = md5($_POST["ingPassword"]);
			//$encriptar = "hola";

				$tabla = "usuarios";//tabla de la base de datos

				$item = "usuario";//variable que consulta el valor de la tabla
				$valor = $_POST["ingUsuario"];

				$respuesta = ModeloUsuarios::MdlMostrarUsuarios($tabla, $item, $valor);
			//	var_dump ($respuesta["password"]);
			//	$encriptar = password_verify($_POST['ingPassword'], $respuesta["password"]);
				          //el item que evaluara el atributo usuario y el valor
			//	var_dump($respuesta["password"]);
				//si la respuesta en la columna usuario es igual como la contraseña entonces accedemos
			
				if($respuesta["usuario"] == $_POST["ingUsuario"] && $respuesta["password"] == $encriptar){

					if($respuesta["estado"] == 1){
						//en caso de que su estado esta activo que inicie sin problema
							/*variables de sesion */
						$_SESSION["iniciarSesion"] = "ok";
						$_SESSION["id"] = $respuesta["id"];
						$_SESSION["nombre"] = $respuesta["nombre"];
						$_SESSION["usuario"] = $respuesta["usuario"];
						$_SESSION["foto"] = $respuesta["foto"];
						$_SESSION["perfil"] = $respuesta["perfil"];

						/*=============================================
						REGISTRAR FECHA PARA SABER EL ÚLTIMO LOGIN
						=============================================*/
						/*capturamos la fecha y hora para saber el ultimo login */
						date_default_timezone_set('America/Guatemala');//definimos zona horaria

						$fecha = date('Y-m-d');
						$hora = date('H:i:s');

						$fechaActual = $fecha.' '.$hora;

						$item1 = "ultimo_login"; //actulizamos el ultimo login
						$valor1 = $fechaActual;	//con el valor de la fecha actual

						$item2 = "id"; //el id
						$valor2 = $respuesta["id"]; //con el valor de la respuesta del id
						//lo mandamos al modelo
						$ultimoLogin = ModeloUsuarios::mdlActualizarUsuario($tabla, $item1, $valor1, $item2, $valor2);

						if($ultimoLogin == "ok"){

							echo '<script>

								window.location = "inicio";

							</script>';

						}				
						
					}else{

						echo '<br>
							<div class="alert alert-danger">El usuario aún no está activado</div>';

					}		

				}else{

					echo '<br><div class="alert alert-danger">Error al ingresar, vuelve a intentarlo</div>';

				}

			}	

		}

	}

	/*=============================================
	REGISTRO DE USUARIO
	=============================================*/

	static public function ctrCrearUsuario(){
		//evaluo si recibo datos  por el metodo post
		if(isset($_POST["nuevoUsuario"])){
					//le asigno una exprecion regular para solo permitir ciertos caracteres 
			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoNombre"]) &&
			   preg_match('/^[a-zA-Z0-9]+$/', $_POST["nuevoUsuario"]) &&
			   preg_match('/^[a-zA-Z0-9]+$/', $_POST["nuevoPassword"])){

			   	/*=============================================
				VALIDAR IMAGEN
				=============================================*/

				$ruta = "";//en caso de que el usuario no ingrese una foto que mande la ruta vacia


				if(isset($_FILES["nuevaFoto"]["tmp_name"])){
		/*preguntamos si viene vacio el archivo o no con el archivo temporal  */
					list($ancho, $alto) = getimagesize($_FILES["nuevaFoto"]["tmp_name"]);
						/*list nos permite crear un nuevo array con los indices que le agreguemos */
					$nuevoAncho = 500;
					$nuevoAlto = 500;
					//le asigno un nuevo valor al ancho y alto
					/*=============================================
					CREAMOS EL DIRECTORIO DONDE VAMOS A GUARDAR LA FOTO DEL USUARIO
					=============================================*/

					$directorio = "vistas/img/usuarios/".$_POST["nuevoUsuario"];

					mkdir($directorio, 0755);//le asignamos los permisos de lectura y escritura

					/*=============================================
					DE ACUERDO AL TIPO DE IMAGEN APLICAMOS LAS FUNCIONES POR DEFECTO DE PHP
					=============================================*/

					if($_FILES["nuevaFoto"]["type"] == "image/jpeg"){

						/*=============================================
						GUARDAMOS LA IMAGEN EN EL DIRECTORIO
						=============================================*/


						$aleatorio = mt_rand(100,999); //asigno un numero aleatoreo a la imge 


						$ruta = "vistas/img/usuarios/".$_POST["nuevoUsuario"]."/".$aleatorio.".jpg";

						$origen = imagecreatefromjpeg($_FILES["nuevaFoto"]["tmp_name"]);						

						$destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

						imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
								//ajusta la imagen a los margenes de 500 por 500
						imagejpeg($destino, $ruta);

					}

					if($_FILES["nuevaFoto"]["type"] == "image/png"){

						/*=============================================
						GUARDAMOS LA IMAGEN EN EL DIRECTORIO
						=============================================*/

						$aleatorio = mt_rand(100,999);

						$ruta = "vistas/img/usuarios/".$_POST["nuevoUsuario"]."/".$aleatorio.".png";

						$origen = imagecreatefrompng($_FILES["nuevaFoto"]["tmp_name"]);						

						$destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

						imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

						imagepng($destino, $ruta);

					}

				}

				$tabla = "usuarios";//Accedo a la tabla Usuarios 

			//	$encriptar = crypt($_POST["nuevoPassword"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
			//	$encriptar = sha1($_POST["nuevoPassword"]);
				//	$encriptar = password_hash($_POST["nuevoPassword"], PASSWORD_BCRYPT);
			//encriptamos la contraseña
			$encriptar = md5($_POST["nuevoPassword"]);
				$datos = array("nombre" => $_POST["nuevoNombre"], /*colo en un array los datos de viene por el metodo post */
				"usuario" => $_POST["nuevoUsuario"], //le asigno los valores de nombre con lo que viene en el post
			    "password" => $encriptar, //aun no trabaje la encriptacion
			   "perfil" => $_POST["nuevoPerfil"],
				"foto"=>$ruta);

				$respuesta = ModeloUsuarios::mdlIngresarUsuario($tabla, $datos);
			//accedo al modelo y le envio los parametros 
				/*que es la tabala que quiero enviar junto con los datos */
				if($respuesta == "ok"){
					//en caso que los datos hayan sido guardados correctamente
					//envio una alerta indicando que los datos fueron guardados correctamente
			
					echo '<script>

					swal({

						type: "success",
						title: "¡El usuario ha sido guardado correctamente!",
						showConfirmButton: true,
						confirmButtonText: "Cerrar",
						closeOnConfirm: false

					}).then((result)=>{

						if(result.value){
						
							window.location = "usuarios";

						}

					});
				

					</script>';


				}	


			}else{
	//utilizamos el plugin sweetalert y lanzamos una alerta en caso de no cumplir con los parametros
			
				echo '<script>

					swal({

						type: "error",
						title: "¡El usuario no puede ir vacío o llevar caracteres especiales!",
						showConfirmButton: true,
						confirmButtonText: "Cerrar",
						closeOnConfirm: false

					}).then((result)=>{

						if(result.value){
						
							window.location = "usuarios";

						}

					});
				

				</script>';

			}


		}


	}

	/*=============================================
	MOSTRAR USUARIO
	=============================================*/

	static public function ctrMostrarUsuarios($item, $valor){

		$tabla = "usuarios";

		$respuesta = ModeloUsuarios::MdlMostrarUsuarios($tabla, $item, $valor);

		return $respuesta;
	}

	/*=============================================
	EDITAR USUARIO
	=============================================*/

	static public function ctrEditarUsuario(){

		if(isset($_POST["editarUsuario"])){
					//no permitimos editar el nickname para no tener conflicto en las carpetas
			//debido a las imagenes 
			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarNombre"])){

				/*=============================================
				VALIDAR IMAGEN
				=============================================*/

				$ruta = $_POST["fotoActual"];
				//en caso de que no se quiera modificar la foto traemos la foto actual
				//en caso de que cambie la foto
				if(isset($_FILES["editarFoto"]["tmp_name"]) && !empty($_FILES["editarFoto"]["tmp_name"])){

					list($ancho, $alto) = getimagesize($_FILES["editarFoto"]["tmp_name"]);

					$nuevoAncho = 500;
					$nuevoAlto = 500;

					/*=============================================
					CREAMOS EL DIRECTORIO DONDE VAMOS A GUARDAR LA FOTO DEL USUARIO
					=============================================*/

					$directorio = "vistas/img/usuarios/".$_POST["editarUsuario"];

					/*=============================================
					PRIMERO PREGUNTAMOS SI EXISTE OTRA IMAGEN EN LA BD
					=============================================*/

					if(!empty($_POST["fotoActual"])){
								//primero preguntamos si existe una foto 
						unlink($_POST["fotoActual"]);//borramos el archivo que esta en la ruta


					}else{
							//en caso de no tener ninguna que mantenga el directori y la guarde
						mkdir($directorio, 0755);

					}	

					/*=============================================
					DE ACUERDO AL TIPO DE IMAGEN APLICAMOS LAS FUNCIONES POR DEFECTO DE PHP
					=============================================*/

					if($_FILES["editarFoto"]["type"] == "image/jpeg"){

						/*=============================================
						GUARDAMOS LA IMAGEN EN EL DIRECTORIO
						=============================================*/

						$aleatorio = mt_rand(100,999);

						$ruta = "vistas/img/usuarios/".$_POST["editarUsuario"]."/".$aleatorio.".jpg";

						$origen = imagecreatefromjpeg($_FILES["editarFoto"]["tmp_name"]);						

						$destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

						imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

						imagejpeg($destino, $ruta);

					}

					if($_FILES["editarFoto"]["type"] == "image/png"){

						/*=============================================
						GUARDAMOS LA IMAGEN EN EL DIRECTORIO
						=============================================*/

						$aleatorio = mt_rand(100,999);

						$ruta = "vistas/img/usuarios/".$_POST["editarUsuario"]."/".$aleatorio.".png";

						$origen = imagecreatefrompng($_FILES["editarFoto"]["tmp_name"]);						

						$destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

						imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

						imagepng($destino, $ruta);

					}

				}

				$tabla = "usuarios";//tabla usuarios

				if($_POST["editarPassword"] != ""){

					if(preg_match('/^[a-zA-Z0-9]+$/', $_POST["editarPassword"])){
				//cambiar contraseña
					//se evalua la contraseña que viene por el metodo post
								//y se guarda la contraseña	
					$encriptar = crypt($_POST["editarPassword"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
				//	$encriptar = sha1($_POST["editarPassword"]);
			//	$encriptar = password_hash($_POST["nuevoPassword"], PASSWORD_BCRYPT);
					}else{

						echo'<script>

								swal({
									  type: "error",
									  title: "¡La contraseña no puede ir vacía o llevar caracteres especiales!",
									  showConfirmButton: true,
									  confirmButtonText: "Cerrar",
									  closeOnConfirm: false
									  }).then((result) => {
										if (result.value) {

										window.location = "usuarios";

										}
									})

						  	</script>';

					}

				}else{
					
					//en caso de que no se envie ninguna contraseña 
					//se mantiene la contraseña que el usuario tenia antes
					$encriptar = $passwordActual;

				}
				
				//guardamos los datos en un array y los mandamos al controlador
				$datos = array("nombre" => $_POST["editarNombre"],
							   "usuario" => $_POST["editarUsuario"],
							   "password" => $encriptar,
							   "perfil" => $_POST["editarPerfil"],
							   "foto" => $ruta);

				$respuesta = ModeloUsuarios::mdlEditarUsuario($tabla, $datos);
					//en caso de que los datos se guarden correctamente
				if($respuesta == "ok"){

					echo'<script>

					swal({
						  type: "success",
						  title: "El usuario ha sido editado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar",
						  closeOnConfirm: false
						  }).then((result) => {
									if (result.value) {

									window.location = "usuarios";

									}
								})

					</script>';

				}


			}else{
						//en caso de que no cumpla con las condiciones
				echo'<script>

					swal({
						  type: "error",
						  title: "¡El nombre no puede ir vacío o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar",
						  closeOnConfirm: false
						  }).then((result) => {
							if (result.value) {

							window.location = "usuarios";

							}
						})

			  	</script>';

			}

		}

	}

	/*=============================================
	BORRAR USUARIO
	=============================================*/

	static public function ctrBorrarUsuario(){
		//recibo los valores por una peticion get 
		if(isset($_GET["idUsuario"])){
			//con el valor id usuarios
			$tabla ="usuarios";	//tabla usuarios
			$datos = $_GET["idUsuario"];//con el id de usuarios

			if($_GET["fotoUsuario"] != ""){
				//en caso de que la variable de foto venga diferente a vacaio
				//signifca que hay una foto para eliminar
				unlink($_GET["fotoUsuario"]);//eliminamos la foto 
				rmdir('vistas/img/usuarios/'.$_GET["usuario"]);//eliminamos la carpeta que tiene el usuario

			}

			$respuesta = ModeloUsuarios::mdlBorrarUsuario($tabla, $datos);
			//solicitamos la respuesta
			if($respuesta == "ok"){
				//y enviamos una alerta
				echo'<script>

				swal({
					  type: "success",
					  title: "El usuario ha sido borrado correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar",
					  closeOnConfirm: false
					  }).then((result) => {
								if (result.value) {

								window.location = "usuarios";

								}
							})

				</script>';

			}		

		}

	}


}
	


