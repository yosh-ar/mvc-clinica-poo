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
$("#nuevoPrecioCompra").change(function(){

    if($(".porcentaje").prop("checked")){

        var valorPorcentaje = $(".nuevoPorcentaje").val();

        var porcentaje =  Number(($("#nuevoPrecioCompra").val()*valorPorcentaje/100))+Number($("#nuevoPrecioCompra").val());
        
        $("#nuevoPrecioVenta").val(porcentaje);
        $("#nuevoPrecioVenta").prop("readonly", true);
        
    }

})

/*=============================================
Cambio de porcentaje
=============================================*/
$(".nuevoPorcentaje").change(function(){

    if($(".porcentaje").prop("checked")){

        var valorPorcentaje = $(".nuevoPorcentaje").val();

        var porcentaje =  Number(($("#nuevoPrecioCompra").val()*valorPorcentaje/100))+Number($("#nuevoPrecioCompra").val());
        
        $("#nuevoPrecioVenta").val(porcentaje);
        $("#nuevoPrecioVenta").prop("readonly", true);
        
    }
})

$(".porcentaje").on("ifUnchecked", function(){

    $("#nuevoPrecioVenta").prop("readonly", false);
    $("#editarPrecioVenta").prop("readonly",false);
    console.log("esta chingadera no funciona");

})

$(".porcentaje").on("ifChecked", function(){

    $("#nuevoPrecioVenta").prop("readonly", true);
    $("#editarPrecioVenta").prop("readonly",true);
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