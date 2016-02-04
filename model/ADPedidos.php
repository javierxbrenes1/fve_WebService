<?php

if(!class_exists('AD')){ 
    include 'AD.php'; 
}
class ADPedidos {
   
    function __construct(){}
    //Funciones
    public function InicializarPedido($pvcNombre,$pvcEmail,$pvcTelPrin,$pvcTelSec,
                                      $pvcDireccion,$pvnZona,$pvnTotProd,$pvnMontTotal)
    {
        $vlcScript = '';
        $vloAD;
        $vloCodigoPedido='';
        $vloResultado;
        try {
            //Crea el script a ejecutar
            $vlcScript = "CALL spInicializaDatosPedido('".$pvcNombre."','".$pvcEmail."','".$pvcTelPrin."','".$pvcTelSec."','".$pvcDireccion."',".$pvnZona.",".$pvnTotProd.",".$pvnMontTotal.")";
            
            //crea instancia de AD
            $vloAD = new AD();
            $vloResultado = $vloAD->RetornarResultado($vlcScript);
            //Obtiene el codigo del pedido 
            while($vloFila = mysql_fetch_array($vloResultado))
            {
                //Almacena el el id del pedido 
                $vloCodigoPedido = $vloFila['ped_enc_id'];
            }
            //Retorna id del pedido
            return $vloCodigoPedido;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
            
    }

    public function GuardarDetalle($pvcIDEncPedido, $pvoListaProductos)
    {
        
        $vlcScript = '';
        $vlnIDDet = 1;
        $vlnSubTotal = 0;
        try {
             $vloAD = new AD();
             $sqlArray = array();
            //Por cada producto manda a crea una instancia
            foreach($pvoListaProductos as $vloProducto)
            {
                //Calcula el subtotal
                $vlnSubTotal = $vloProducto['cantidad']*$vloProducto['precio'];
                //Crea arreglo de articulos a almacenar
                $sqlArray[] = "('".$pvcIDEncPedido."',".$vlnIDDet.",'".$vloProducto['id']."',".$vloProducto['cantidad'].",".$vlnSubTotal.")";
                //aumenta el id del detalle del pedido 
                $vlnIDDet++;
            
            }
           $vlcScript = " INSERT INTO fve_ped_det(ped_enc_id,ped_det_id,prod_id,ped_det_can_prod,ped_det_mont) "
                        . "VALUES ".  implode(',', $sqlArray);
           
            $vloAD->EjecutarComando($vlcScript);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        }
}
