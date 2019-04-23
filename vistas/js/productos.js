/*=============================================
CARGAR LA TABLA DINAMICA PRODUCTOS
=============================================*/

// $.ajax({
    
//     url: "ajax/datatable-productos.ajax.php",
//     success:function(respuesta){

//         console.log("respuesta", respuesta);

//     }
// })

$('.tablaProductos').DataTable({
    "ajax": "ajax/datatable-productos.ajax.php",
    "deferRender": true,
    "retrieve": true,
    "processing": true
});


/*=============================================
capturar la categoria para asignar codigo
=============================================*/

$("#nuevaCategoria").change(function(){
    
    var idCategoria = $(this).val();

    var datos = new FormData();
    datos.append("idCategoria", idCategoria);

    $.ajax({

        url: "ajax/productos.ajax.php",
        method: "POST",
        data : datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success:function(respuesta){

            //console.log("respuesta", respuesta);

            // var nuevoCodigo = respuesta["codigo"];
            // console.log("nuevoCodigo", nuevoCodigo);
            if(!respuesta){
                var nuevoCodigo = idCategoria+"01";
                $("#nuevoCodigo").val(nuevoCodigo);
                //console.log("nuevoCodigo", nuevoCodigo);
            }

        //     // var nuevoCodigo = respuesta["codigo"];
        //     // console.log("nuevoCodigo", nuevoCodigo);
            else{
                var nuevoCodigo = Number(respuesta["codigo"])+1;
                $("#nuevoCodigo").val(nuevoCodigo);
            }
                
         }



    })

})

/*=============================================
Agregando precio de venta
=============================================*/
$("#nuevoPrecioCompra, #editarPrecioCompra").change(function(){

    if($(".porcentaje").prop("checked")){

        var valorPorcentaje = $(".nuevoPorcentaje").val();

        var porcentaje =  Number(($("#nuevoPrecioCompra").val()*valorPorcentaje/100))+Number($("#nuevoPrecioCompra").val());
        var editarPorcentaje = Number(($("#editarPrecioCompra").val()*valorPorcentaje/100))+Number($("#editarPrecioCompra").val());

        $("#nuevoPrecioVenta").val(porcentaje);
        $("#nuevoPrecioVenta").prop("readonly", true);
//cuandoo cambie el valor en editar precio compra  que tome los valores de editar precio compra

        $("#editarPrecioVenta").val(editarPorcentaje);
		$("#editarPrecioVenta").prop("readonly",true)
        
    }

})

/*=============================================
Cambio de porcentaje
=============================================*/
$(".nuevoPorcentaje").change(function(){

    if($(".porcentaje").prop("checked")){

        var valorPorcentaje = $(this).val();

        var porcentaje =  Number(($("#nuevoPrecioCompra").val()*valorPorcentaje/100))+Number($("#nuevoPrecioCompra").val());
        var editarPorcentaje = Number(($("#editarPrecioCompra").val()*valorPorcentaje/100))+Number($("#editarPrecioCompra").val());

        $("#nuevoPrecioVenta").val(porcentaje);
        $("#nuevoPrecioVenta").prop("readonly", true);
        $("#editarPrecioVenta").val(editarPorcentaje);
		$("#editarPrecioVenta").prop("readonly",true)
    }
})

$(".porcentaje").on("ifUnchecked", function(){

    $("#nuevoPrecioVenta").prop("readonly", false);
    $("#editarPrecioVenta").prop("readonly",false);
    console.log("esta chingadera no funciona");

})

$(".porcentaje").on("ifChecked", function(){

    $("#nuevoPrecioVenta").prop("readonly", true);
    $("#editarPrecioVenta").prop("readonly",true); //que el campo aparezca bloqueado
    console.log("esta chingadera no funciona");

})  


/*=============================================
SUBIENDO LA FOTO DEL PRODUCTO
=============================================*/

$(".nuevaImagen").change(function(){

	var imagen = this.files[0];
	
	/*=============================================
  	VALIDAMOS EL FORMATO DE LA IMAGEN SEA JPG O PNG
  	=============================================*/

  	if(imagen["type"] != "image/jpeg" && imagen["type"] != "image/png"){

  		$(".nuevaImagen").val("");

  		 swal({
		      title: "Error al subir la imagen",
		      text: "¡La imagen debe estar en formato JPG o PNG!",
		      type: "error",
		      confirmButtonText: "¡Cerrar!"
		    });

  	}else if(imagen["size"] > 2000000){

  		$(".nuevaImagen").val("");

  		 swal({
		      title: "Error al subir la imagen",
		      text: "¡La imagen no debe pesar más de 2MB!",
		      type: "error",
		      confirmButtonText: "¡Cerrar!"
		    });

  	}else{

  		var datosImagen = new FileReader;
  		datosImagen.readAsDataURL(imagen);

  		$(datosImagen).on("load", function(event){

  			var rutaImagen = event.target.result;

  			$(".previsualizar").attr("src", rutaImagen);

  		})

  	}
})

$(".tablaProductos tbody").on("click", "button.btnEditarProducto", function(){

	var idProducto = $(this).attr("idProducto");
	
	var datos = new FormData();
    datos.append("idProducto", idProducto);

     $.ajax({

      url:"ajax/productos.ajax.php",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType:"json",
      success:function(respuesta){
        var datosCategoria = new FormData();
        datosCategoria.append("idCategoria",respuesta["id_categoria"]);

         $.ajax({

            url:"ajax/categorias.ajax.php",
            method: "POST",
            data: datosCategoria,
            cache: false,
            contentType: false,
            processData: false,
            dataType:"json",
            success:function(respuesta){
                
                $("#editarCategoria").val(respuesta["categoria"]);
                $("#editarCategoria").html(respuesta["categoria"]);
            }

        })
        $("#editarCodigo").val(respuesta["codigo"]);

           $("#editarDescripcion").val(respuesta["descripcion"]);

           $("#editarStock").val(respuesta["stock"]);

           $("#editarPrecioCompra").val(respuesta["precio_compra"]);

           $("#editarPrecioVenta").val(respuesta["precio_venta"]);

           if(respuesta["imagen"] != ""){

           	$("#imagenActual").val(respuesta["imagen"]);

           	$(".previsualizar").attr("src",  respuesta["imagen"]);

           }
      }
    })
});


/*=============================================
ELIMINAR PRODUCTO
=============================================*/

$(".tablaProductos tbody").on("click", "button.btnEliminarProducto", function(){

	var idProducto = $(this).attr("idProducto");
	var codigo = $(this).attr("codigo");
	var imagen = $(this).attr("imagen");
	
	swal({

		title: '¿Está seguro de borrar el producto?',
		text: "¡Si no lo está puede cancelar la accíón!",
		type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, borrar producto!'
        }).then((result) => {
        if (result.value) {

        	window.location = "index.php?ruta=productos&idProducto="+idProducto+"&imagen="+imagen+"&codigo="+codigo;

        }


	})

})
	