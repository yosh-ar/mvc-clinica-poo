<?php
class ControladorCategorias{
   

     /*=============================================
	MOSTRAR Categorias
	=============================================*/
    static public function ControllerMostrarCategorias($item, $valor){
        $tabla = "categorias";
		$respuesta = ModeloCategorias::ModelMostrarCategorias($tabla, $item, $valor);
		return $respuesta;
    }
    /*=============================================
	INSERTAR Categorias
    =============================================*/
    static public function ControllerIngresarCategorias(){
        if(isset($_POST["nuevaCategoria"])){//si vienen datos
            //solo datos permitidos 
			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevaCategoria"])){

				$tabla = "categorias";//a la tabla categorias

				$datos = $_POST["nuevaCategoria"];//enviamos la nueva categoria

				$respuesta = ModeloCategorias::ModelIngresarCategorias($tabla, $datos);

				if($respuesta == "ok"){

					echo'<script>

					swal({
						  type: "success",
						  title: "La categoría ha sido guardada correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {

									window.location = "categorias";

									}
								})

					</script>';

				}


			}else{
                //en caso de que vaya vacia o caracteres especiales
                echo'<script>

					swal({
						  type: "error",
						  title: "¡La categoría no puede ir vacía o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {

							window.location = "categorias";

							}
						})

			  	</script>';

			}

		}

    }
        /*=============================================
	EDITAR Categorias
    =============================================*/
    static public function ControllerEditarCategoria(){
        //evaluamos igual si viene una categoria
        if(isset($_POST["editarCategoria"])){
            //validaciones
			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarCategoria"])){
                //tabla y arreglos
				$tabla = "categorias";

				$datos = array("categoria"=>$_POST["editarCategoria"],
							   "id"=>$_POST["idCategoria"]);
                //lo enviamos al modelo
				$respuesta = ModeloCategorias::ModeloEditarCategoria($tabla, $datos);
                //si edita que envie una respuesta
				if($respuesta == "ok"){

					echo'<script>

					swal({
						  type: "success",
						  title: "La categoría ha sido cambiada correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {

									window.location = "categorias";

									}
								})

					</script>';

				}


			}else{
                // en caso contrarios
				echo'<script>

					swal({
						  type: "error",
						  title: "¡La categoría no puede ir vacía o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {

							window.location = "categorias";

							}
						})

			  	</script>';

			}

		}

    }
    /*=============================================
	BORRAR CATEGORIA
	=============================================*/

	static public function ControllerBorrarCategoria(){

		if(isset($_GET["idCategoria"])){
            //recibimos la variable get
			$tabla ="Categorias";//enviamos la tabla
			$datos = $_GET["idCategoria"];//el id que viene desde get

			$respuesta = ModeloCategorias::ModelBorrarCategoria($tabla, $datos);

			if($respuesta == "ok"){

				echo'<script>

					swal({
						  type: "success",
						  title: "La categoría ha sido borrada correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {

									window.location = "categorias";

									}
								})

					</script>';
			}
		}
		
	}
}
