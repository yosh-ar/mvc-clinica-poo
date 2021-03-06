<?php

require_once "../controladores/productos.controlador.php";
require_once "../modelos/productos.modelo.php";


    class TablaProductos{
 
        /*=============================================
        Mostrar la tabla de productos
        =============================================*/

        public function mostrarTablaProductos(){

            $item = null;
            $valor = null;
            $orden = "id";

            $productos = ControladorProductos::ctrMostrarProductos($item,$valor, $orden);

            

            $datosJson = '{
                "data": [';

                for ($i = 0; $i <count($productos); $i++){
                
                     /*=============================================
                     traemos la imagen
                    =============================================*/

                    $imagen = "<img src ='".$productos[$i]["imagen"]."' width='40px'>";
                

                     /*=============================================
                    stock
                    =============================================*/

                    if($productos[$i]["stock"] <= 10){
                        
                        $stock = "<button class='btn btn-danger'>".$productos[$i]["stock"]."</button>";

                    }else if($productos[$i]["stock"] > 11 && $productos[$i]["stock"] <= 15){

                        $stock = "<button class='btn btn-warning'>".$productos[$i]["stock"]."</button>";
                        
                    }else{

                    $stock = "<button class='btn btn-success'>".$productos[$i]["stock"]."</button>";
                    
                    }
                     /*=============================================
                    traemos los botones
                    =============================================*/
									//  $botones= "<div class='btn-group'><button class='
									//  btn btn-primary'>
									//  agregar</button</div>";

									$botones = "<div class='btn-group'><button class='btn btn-primary agregarProducto recuperarBoton' idProducto='".$productos[$i]["id"]."'>Agregar</button></div>";

                    $datosJson .='[
                        "'.($i+1).'",
												"'.$imagen.'",
                        "'.$productos[$i]["codigo"].'",
                        "'.$productos[$i]["descripcion"].'",
                        "'.$stock.'",
                       	"'.$botones.'"
                    ],';
                }
        
                $datosJson = substr($datosJson, 0, -1);
                
                $datosJson .= '] 
                }';
            
            
            
            echo $datosJson; 

        }
    }
/*=============================================
Activar tabla de productos
=============================================*/
$activarProductos = new TablaProductos();
$activarProductos -> mostrarTablaProductos();