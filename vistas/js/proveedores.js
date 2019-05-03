/*=====================================
EDITAR PROVEEDOR
======================================*/
$(".btnEditarProveedor").click(function(){

    var idProveedor = $(this).attr("idProveedor");

    var datos = new FormData();
    datos.append("idProveedor", idProveedor);

    $.ajax({

        url:"ajax/proveedores.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType:"json",
        success:function(respuesta){
        
            //console.log("respuesta",respuesta);
            $("#idProveedor").val(respuesta["id"]);
            $("#editarProveedor").val(respuesta["nombre"]);
            $("#editarTelefono").val(respuesta["telefono"]);
            $("#editarDireccion").val(respuesta["direccion"]);
            
        }

    })

})
/*=====================================
ELIMINAR PROVEEDOR
======================================*/
$(".tablas").on("click", ".btnEliminarProveedor", function(){

    var idProveedor = $(this).attr("idProveedor");
    //console.log(idProveedor);

    swal({
        title: '¿Está seguro de borrar el proveedor',
        text: "¡Si no lo está puede cancelar la acción!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, borrar cliente!'
      }).then((result) => {
        if (result.value) {
          
            window.location = "index.php?ruta=proveedores&idProveedor="+idProveedor;
        }

  })

})
