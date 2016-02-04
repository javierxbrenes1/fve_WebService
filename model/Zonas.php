<?php
if(!class_exists('AD')){ 
    include 'AD.php'; 
}

class Zonas {
    function __construct(){}
    
    public function ObtenerZonas()
    {
        try {
            //Crea instancia de Acceso a datos
            $AccesoDatos = new AD();
            //Crea el script a ejecutar
            $vlcScript = "SELECT * FROM fve_zon";
            //retornar datos
            return $AccesoDatos->RetornarResultado($vlcScript);
        } catch (Exception $exc) {
            //Lanza la excepcion
            echo $exc->getTraceAsString();
        }
    }
    
    public function ObtenerDescripcionZona($pvnId)
    {
        try {
            //Crea instancia de acceso a datos
            $vloAD = new AD();
            //Script de consulta
            $vlcScript = 'SELECT zon_nom FROM fve_zon WHERE zon_id = '.$pvnId.';';
            //Crea variable de retorno
            $vlcZonaNombre = '';
            
            $vloResultado = mysql_query($vlcScript);
            while($vloFila = mysql_fetch_array($vloResultado))
            {
                $vlcZonaNombre = $vloFila['zon_nom'];
            }
            return $vlcZonaNombre;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    
    }
}
