<?php 
	include_once 'lib/nusoap.php';
	include_once 'model/Productos.php';
    //define el namespace
    
	//crear objeto de servicio
	$servicio = new soap_server();
	$ns = "urn:WebServiceFVEwsdl";
	$servicio->configureWSDL("FVEWebService",$ns);
	$servicio->schemaTargetNamespace = $ns;

  //********************************************* Registro las estructuras de datos a usar ***************************************************// 
	
	//Estructura de productos
	$servicio->wsdl->addComplexType('Productos',
							        'complexType',
							        'struct',
							        'all',
							        '',
							        array('prod_id' => Array('name' => 'prod_id','type' =>'xsd:int'),
									      'tip_prod_id' => Array('name' => 'tip_prod_id', 'type' => 'xsd:int'),
										  'prod_nom' => Array('name' => 'prod_nom', 'type' => 'xsd:string'),
										  'prod_desc' => Array('name' => 'prod_desc', 'type' => 'xsd:string'),
										  'prod_prc_act' => Array('name' => 'prod_prc_act', 'type' => 'xsd:float'),
										  'prod_fec_cmb_prc' => Array('name' => 'prod_fec_cmb_prc' , 'type' => 'xsd:string'),
										  'prod_sts' => Array('name' => 'prod_sts' , 'type' => 'xsd:int'),
										  'prod_prm' => Array('name' => 'prod_prm' , 'type' => 'xsd:int'),
										  'prod_rut_img' => Array('name' => 'prod_' , 'type' => 'xsd:string'),
										  'prod_unit_med' => Array('name' => 'prod_unit_med' , 'type' => 'xsd:string')
										  )
							        );
									
	//Estructura para arreglo de productos
	$servicio->wsdl->addComplexType('arrProductos',
							        'complexType',
									'array',
									'',
									'SOAP-ENC:Array',
									array(),
									array(
										array('ref' => 'SOAP-ENC:arrayType',
										      'wsdl:arrayType' => 'tns:Productos[]'
											  )
									)
	                              );
	
	
	//Estructura de tipos de productos
	$servicio->wsdl->addComplexType('TipoProductos',
	                                'complexType',
									'struct',
									'all',
									'',
									array('tip_prod_id' => array('name'=>'tip_prod_id','type'=>'xsd:int'),
									      'tip_prod_nom' => array('name' => 'tip_prod_nom','type'=>'xsd:string')
										 )
									);
	
	/************************* Registro la funcion ********************************************/
	
    $servicio->register("foObtenerProductos",array(),array('return'=>'xsd:string'),$ns); 
    $servicio->register("foObtenerTiposProducto",array(),array('return' => 'xsd:string'),$ns);
	$servicio->register("foBuscarProducto",array('prod_id' => 'xsd:int'), array('vloProducto' => 'tns:Productos') , $ns);
	$servicio->register("foActualizarProducto",array('Producto' => 'tns:Productos'),array('return' => 'xsd:string') , $ns );
	$servicio->register("foAgregarProducto",array('Producto' => 'tns:Productos'),array('return' => 'xsd:string') , $ns );
	
	/************************** METODOS PROPIOS DEL WEB SERVICE *********************************/
	
	//Agrega un nuevo producto al catalogo
	function foAgregarProducto($Producto){
		//Define el string de respuesta
		$vloRespuesta = "";
		try{
			//crea instancia del producto
			$vloProducto = new Productos();
			//Manda a actualizar el articulo
			$vloResultado = $vloProducto->AgregarProducto($Producto);
			//Verifica la respuesta
			 if($vloResultado)
			 {
				 $vloRespuesta = ":-) Producto Agregado Satisfactoriamente";
			 }else{
				 $vloRespuesta = ":-( El producto no se ha podido Agregar. Es posible que el producto ya exista o que haya omitido algún valor importante,
				                  intentelo mas tarde. De no ser posible la actualización comuniquese con el encargado del sistema.";
				 
			 }
			 
		}catch(Exception $ex){
			$vloRespuesta = "Error: ".$ex->getMessage();
		}
		return $vloRespuesta;
	}
	//Funcion que actualiza un producto
	function foActualizarProducto($Producto)
	{
		//Define el string de respuesta
		$vloRespuesta = "";
		try{
			//crea instancia del producto
			$vloProducto = new Productos();
			//Manda a actualizar el articulo
			$vloResultado = $vloProducto->ActualizarProducto($Producto);
			//Verifica la respuesta
			 if($vloResultado)
			 {
				 $vloRespuesta = ":-) Producto Actualizado Satisfactoriamente";
			 }else{
				 $vloRespuesta = ":-( El producto no se ha podido actualizar. Es posible que el producto no exista o que haya omitido algún valor importante,
				                  intentelo mas tarde. De no ser posible la actualización comuniquese con el encargado del sistema.";
				 
			 }
			 
		}catch(Exception $ex){
			$vloRespuesta = "Error: ".$ex->getMessage();
		}
		return $vloRespuesta;
	}
	 
	//funcion que obtiene los tipos de productos
	function foObtenerTiposProducto()
	{
		//Crea variable de retorno
		$vloResultado = "";
		try{
			//Crea instancias de productos 
			$vloProducto = new Productos();
			//Obtiene los tipos de productos
			$vloRespuesta = $vloProducto->ObtenerCategorias();
			//Crea ciclo 
			while($vloCat = mysql_fetch_row($vloRespuesta)){
				//Concatena todos los tipos de productos
				$vloResultado = $vloResultado.$vloCat[0]."|".$vloCat[1].";";
			}
		}catch(Exception $ex){
			//Limpia la variable de retorno en caso de que ya tuviera datos gravados
			$vloResultado = "";
		}
		return $vloResultado;
	}
	
	//Funcion que retorna los datos de un articulo en especifico.
	function foBuscarProducto($pvnProd_Id)
	{
		//Creo el arreglo a retornar
		$vloProductoEncontrado = array();
		try{
			//Creo instancia de productos
			$vloProductos = new Productos();
			//Obtengo los productos
			$vloResultado = $vloProductos->BuscarProducto($pvnProd_Id);
			//Recorre lo retornado por la BD
			while($vloFila = mysql_fetch_array($vloResultado))
			{
				//Obtiene el producto encontrado
				$vloProductoEncontrado = array ( 'prod_id' => intval($vloFila['prod_id']),
									      'tip_prod_id' => intval($vloFila['tip_prod_id']),
										  'prod_nom' => $vloFila['prod_nom'],
										  'prod_desc' => $vloFila['prod_desc'],
										  'prod_prc_act' => floatval($vloFila['prod_prc_act']),
										  'prod_fec_cmb_prc' => (string)$vloFila['prod_fec_cmb_prc'],
										  'prod_sts' => intval($vloFila['prod_sts']),
										  'prod_prm' => intval($vloFila['prod_prm']),
										  'prod_rut_img' => $vloFila['prod_rut_img'],
										  'prod_unit_med' => $vloFila['prod_unit_med']);
			}
		}catch(Exception $ex){
			//SI ocurre una excepcion no hace nada
		}
		//Retorna el arreglo
		return $vloProductoEncontrado;
	}
	
	//Obtiene el nombre codigo y tipo de producto para la carga inicial
	function foObtenerProductos(){
		//Define las variables de retorno
		$vloarrProductos = "";
		try{
			//Crea instancia de productos
			$vloProductos = new Productos();
			//Obtiene el catalogo
			$vloResultado = $vloProductos->Catalogo();
			//Por cada fila de productos
			while($vloFila = mysql_fetch_array($vloResultado)){
				//Lo agrega al arreglo de productos
				$vloarrProductos = $vloarrProductos.
				                   $vloFila['prod_id']."|".
				                   $vloFila['prod_nom']."|".
								   $vloFila['tip_prod_id'].";";
			}
		}catch(Exception $ex)
		{
			//Limpia la variable de retorno
			$vloarrProductos="";
		}
		//returna los productos
		return  $vloarrProductos;
	}		

	
	$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
	$servicio->service($HTTP_RAW_POST_DATA);
?>