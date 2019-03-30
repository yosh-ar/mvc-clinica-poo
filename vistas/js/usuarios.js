/*=============================================
SUBIENDO LA FOTO DEL USUARIO
=============================================*/

$(".nuevaFoto").change(function(){

	var imagen = this.files[0]; //capturo el indice cero que es lo qunico que necesito
	 //console.log(imagen);// evaluo por consola lo que me trae la imagen que captura el files
	/*=============================================
  	VALIDAMOS EL FORMATO DE LA IMAGEN SEA JPG O PNG
  	=============================================*/

  	if(imagen["type"] != "image/jpeg" && imagen["type"] != "image/png"){
        /*evaluo si la imagen es jpg o png, en caso de que no cumpla  */
  		$(".nuevaFoto").val("");//mandamos a la clase un valor vacio
        /*y posteriormente lanzamos una alerta que nos idique que no se ingreso
        una imagen */
  		 swal({
		      title: "Error al subir la imagen",
		      text: "¡La imagen debe estar en formato JPG o PNG!",
		      type: "error",
		      confirmButtonText: "¡Cerrar!"
		    });
        /*tambien se evalua en caso que la imagen sea mayor a 200 mb */
  	}else if(imagen["size"] > 2000000){
    
  		$(".nuevaFoto").val("");
        //en caso de ser mayor lanzo una alerta  y mandamos el valor vacio
  		 swal({
		      title: "Error al subir la imagen",
		      text: "¡La imagen no debe pesar más de 2MB!",
		      type: "error",
		      confirmButtonText: "¡Cerrar!"
		    });

  	}else{
        //de lo contrario guardamos la imagen 
  		var datosImagen = new FileReader; //filereader clase de javaScrip
  		datosImagen.readAsDataURL(imagen);//leemos la imagen como dato url

  		$(datosImagen).on("load", function(event){
				
  			var rutaImagen = event.target.result;

  			$(".previsualizar").attr("src", rutaImagen);

  		})

  	}
});

/*=============================================
EDITAR USUARIO
=============================================*/
$(document).on("click", ".btnEditarUsuario", function(){

	var idUsuario = $(this).attr("idUsuario"); //capturamos lo que trae el boton en su atributo idUsuario
	 console.log(idUsuario);  
	var datos = new FormData(); //instanciamos la clase fordata de javaScrip
	datos.append("idUsuario", idUsuario);
	//console.log(datos);

	$.ajax({
	
		url:"ajax/usuarios.ajax.php",
		method: "POST",
		data: datos,
		cache: false,
		contentType: false,
		processData: false,
		dataType: "json",
		success: function(respuesta){
			//sacamos la respuesta de la base de datos
			console.log("respuesta",respuesta);
				$("#editarNombre").val(respuesta["nombre"]);//accedemos al id de la vista 
			//luego imprimimos la respuesta con su valor nombre y asi sucesibamente
			$("#editarUsuario").val(respuesta["usuario"]);
			$("#editarPerfil").html(respuesta["perfil"]); //en caso de que los datos no se vaya  a modificar mantenemos los mismos
			$("#editarPerfil").val(respuesta["perfil"]);//de esa manera tenemos el val
			$("#fotoActual").val(respuesta["foto"]);

			$("#passwordActual").val(respuesta["password"]);

			if(respuesta["foto"] != ""){//en caso de que la foto este vacia 

				$(".previsualizar").attr("src", respuesta["foto"]);//se muestra la foto que ya tiene

			}
		}
	});

})

/*=============================================
ACTIVAR USUARIO
=============================================*/
$(".tablas").on("click", ".btnActivar", function(){

	var idUsuario = $(this).attr("idUsuario");
	var estadoUsuario = $(this).attr("estadoUsuario");

	var datos = new FormData();
 	datos.append("activarId", idUsuario);
  	datos.append("activarUsuario", estadoUsuario);

  	$.ajax({

	  url:"ajax/usuarios.ajax.php",
	  method: "POST",
	  data: datos,
	  cache: false,
      contentType: false,
      processData: false,
      success: function(respuesta){

      		if(window.matchMedia("(max-width:767px)").matches){

	      		 swal({
			      title: "El usuario ha sido actualizado",
			      type: "success",
			      confirmButtonText: "¡Cerrar!"
			    }).then(function(result) {
			        if (result.value) {

			        	window.location = "usuarios";

			        }


				});

	      	}

      }

  	})

  	if(estadoUsuario == 0){

  		$(this).removeClass('btn-success');
  		$(this).addClass('btn-danger');
  		$(this).html('Desactivado');
  		$(this).attr('estadoUsuario',1);

  	}else{

  		$(this).addClass('btn-success');
  		$(this).removeClass('btn-danger');
  		$(this).html('Activado');
  		$(this).attr('estadoUsuario',0);

  	}

})
/*=============================================
REVISAR SI EL USUARIO YA ESTÁ REGISTRADO
=============================================*/

$("#nuevoUsuario").change(function(){
//en caso de que el imput cambie lo capturamos
	$(".alert").remove(); //cuando cambie el input que los mensajes de alerta desaparezcan 

	var usuario = $(this).val();//capturamos su value o nombre de usuario

	var datos = new FormData();
	datos.append("validarUsuario", usuario); //solicitamos a ajax si encuentra el mismo usuario

	 $.ajax({
	    url:"ajax/usuarios.ajax.php",
	    method:"POST",
	    data: datos,
	    cache: false,
	    contentType: false,
	    processData: false,
	    dataType: "json",
	    success:function(respuesta){
	    	//console.log("respuesta", respuesta);
	    	if(respuesta){
					//en caso de que la respuesta sea verdadero
					//parent para sacar el mensaje 
	    		$("#nuevoUsuario").parent().after('<div class="alert alert-warning">Este usuario ya existe en la base de datos</div>');
						//inmediatamente limpiamos el value para que no continue ccon ese dato
	    		$("#nuevoUsuario").val("");

	    	}

	    }

	})
})

/*=============================================
ELIMINAR USUARIO
=============================================*/
$(".tablas").on("click", ".btnEliminarUsuario", function(){

  var idUsuario = $(this).attr("idUsuario");
  var fotoUsuario = $(this).attr("fotoUsuario");
  var usuario = $(this).attr("usuario");

  swal({
    title: '¿Está seguro de borrar el usuario?',
    text: "¡Si no lo está puede cancelar la accíón!",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      cancelButtonText: 'Cancelar',
      confirmButtonText: 'Si, borrar usuario!'
  }).then(function(result){

    if(result.value){

      window.location = "index.php?ruta=usuarios&idUsuario="+idUsuario+"&usuario="+usuario+"&fotoUsuario="+fotoUsuario;

    }

  })

})

 

