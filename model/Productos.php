<?php
//Archivos a incluir
include 'AD.php';

    class Productos
    {
        var $ERROR_MESSAGE   = "Ocurrio un error, lo lamentamos.!,favor noticarlo al siguiente correo electronico: soporte@verfrutaexpress.com";
        //Constructor
        function __construct(){}
        
        //Obtiene el catalogo del producto 
        public function Catalogo()
        {
            
            try{
                $AccesoAdatos = new AD();
                $vlcScript = "SELECT * "
                        . "     FROM fve_prod";
               
                return $AccesoAdatos->RetornarResultado($vlcScript) ;
            } catch (Exception $ex) {
            
                echo $ERROR_MESSAGE;      
                  
            }
            
        }
        
        //Busca el producto por ID
        public function BuscarProducto($pvcbuscar)
        {
            try {
               
               
                $AD = new AD();
                $vlcScript = "SELECT * "
                        . "     FROM fve_prod "
                        . "    WHERE prod_id = '".$pvcbuscar."';";
                return $AD->RetornarResultado($vlcScript);
                
            } catch (Exception $exc) {
                echo $ERROR_MESSAGE;
            }
        }
  
        //Obtiene las categorias
        public function ObtenerCategorias()
        {
            try{
                $AccesoAdatos = new AD();
                $vlcScript = "SELECT * "
                        . "     FROM fve_tip_prod ";
                 return $AccesoAdatos->RetornarResultado($vlcScript) ;
            } catch (Exception $ex) {
                 echo $ERROR_MESSAGE;  
            }
        }
        
        //Inserta un nuevo producto
        public function AgregarProducto($vloProducto){
            //Variable de respuesta
            $vloRes = false;
            try{
                //Si el parametro es un arreglo 
                if(is_array($vloProducto)){
                        //Crea acceso a datos
                        $AccesoDatos = new AD();
                        //Define el script 
                        $query = sprintf("INSERT INTO fve_prod (prod_id, tip_prod_id, prod_nom, prod_desc, prod_prc_act, prod_fec_cmb_prc, prod_sts, prod_prm, prod_rut_img, prod_unit_med ) 
                                                               Values(%d, %d,'%s','%s', %g, Now(), %d, %d, '%s','%s')",
                                            mysql_real_escape_string($vloProducto['prod_id']),
                                            mysql_real_escape_string($vloProducto['tip_prod_id']),
                                            mysql_real_escape_string($vloProducto['prod_nom']),
                                            mysql_real_escape_string($vloProducto['prod_desc']),
                                            mysql_real_escape_string($vloProducto['prod_prc_act']),
                                            mysql_real_escape_string($vloProducto['prod_sts']),
                                            mysql_real_escape_string($vloProducto['prod_prm']),
                                            mysql_real_escape_string($vloProducto['prod_rut_img']),
                                            mysql_real_escape_string($vloProducto['prod_unit_med']));                 
                        //Ejecuta el comando 
                        $vloResult =  $AccesoDatos->RetornarResultado($query); 
                        //Cuento el total de registros afectados
                        $TotalRegAfectados = mysql_affected_rows();
                        //Asigno el resultado de la consulta
                        $vloRes = ($TotalRegAfectados > 0);
                }
            }catch(Exception $ex)
            {
                 $vloRes = false;
            }
            return $vloRes;
  
        }
        
        //Actualiza un producto 
        public function ActualizarProducto($vloProducto)
        {
            //Variable de respuesta
            $vloRes = false;
            try{
                //Si el parametro es un arreglo 
                if(is_array($vloProducto)){
                        //Crea acceso a datos
                        $AccesoDatos = new AD();
                        //Define el script 
                        $query = sprintf("UPDATE fve_prod 
                                                SET tip_prod_id = %d,
                                                    prod_nom = '%s',
                                                    prod_desc = '%s',
                                                    prod_prc_act = %g,
                                                    prod_fec_cmb_prc = Now(),
                                                    prod_sts = %d,
                                                    prod_prm = %d,
                                                    prod_rut_img = '%s',
                                                    prod_unit_med = '%s' 
                                            WHERE prod_id = %d ",
                                            mysql_real_escape_string($vloProducto['tip_prod_id']),
                                            mysql_real_escape_string($vloProducto['prod_nom']),
                                            mysql_real_escape_string($vloProducto['prod_desc']),
                                            mysql_real_escape_string($vloProducto['prod_prc_act']),
                                            mysql_real_escape_string($vloProducto['prod_sts']),
                                            mysql_real_escape_string($vloProducto['prod_prm']),
                                            mysql_real_escape_string($vloProducto['prod_rut_img']),
                                            mysql_real_escape_string($vloProducto['prod_unit_med']),
                                            mysql_real_escape_string($vloProducto['prod_id']));                 
                        //Ejecuta el comando 
                        $vloResult =  $AccesoDatos->RetornarResultado($query); 
                        //Cuento el total de registros afectados
                        $TotalRegAfectados = mysql_affected_rows();
                        //Asigno el resultado de la consulta
                        $vloRes = ($TotalRegAfectados > 0);
                }
            }catch(Exception $ex)
            {
                 $vloRes = false;
            }
            return $vloRes;
        }
    }
