<?php
function trd_formulario_uif_J($link, $row, $rsDt, $url, $implant, $fac, $reason = '') {
	$ingreso_mensual = $link->monthly_income[$row['tipo_cliente']];
	if($row['fecha_emision']!=='0000-00-00'){
		$vec = explode('-',$row['fecha_emision']);
		$dia = $vec[2];
		$mes = $vec[1];
		$anio = $vec[0];
	}else{
		$vec = explode('-',$row['fecha_creacion']);
		$dia = $vec[2];
		$mes = $vec[1];
		$anio = $vec[0];
	}
	
	$sucursal = $row['u_depto'];
	$razon_social = $row['cl_razon_social'];
	$nit = $row['cl_ci'];
	$phpArray = json_decode($row['data_jur'], true);
	$ts_comercial = $phpArray['type_company'];	
	$nr_fundaempresa = $phpArray['registration_number'];
	$nl_funcionamiento = $phpArray['license_number'];
	$nr_vifpe = $phpArray['number_vifpe'];
	$actividad = $row['actividad'];
	$antiguedad_pj = $phpArray['antiquity'];
	$ci_representante = $phpArray['executive_ci'];//preg_replace('/[a-zA-Z]/', '', $phpArray['executive_ci']);
	$extension = $phpArray['executive_ext'];//preg_replace('/[0-9]/', '', $phpArray['executive_ci']);
	$vecfn = explode('-',$phpArray['executive_birth']);
	$diafn = $vecfn[2];
	$mesfn = $vecfn[1];
	$aniofn = $vecfn[0];
	$prof_representante = $phpArray['executive_profession'];
	$direccion_oficina = $row['cl_direccion_laboral'];
	$telefono = $row['cl_tel_oficina'];
	$representante_legal = $row['ejecutivo'];
	$cargo = $row['cl_cargo'];
	$nacionalidad = $row['cl_pais'];
	ob_start();
?>
  <div id="container-c" style="width: 785px; height: auto; 
    border: 0px solid #0081C2; padding: 5px;">
	  <div id="main-c" style="width: 775px; font-weight: normal; font-size: 12px; 
      font-family: Arial, Helvetica, sans-serif; color: #000000; border-left: 1px solid #000;
      border-top: 1px solid #000; border-right: 1px solid #000; border-bottom: 1px solid #000;">

        <div style="width: 775px; border: 0px solid #000; text-align:center;">
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-family: Arial; font-size:80%;">
                <tr>
                  <td style="width:60%; text-align:left;">
                     <img src="<?=$url;?>img/logo-sud.jpg" width="200"/>
                  </td>
                  <td style="width:40%; padding-right:10px;" align="right">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 100%;">
                        <tr>
                          <td style="width:40%; text-align:center; border-top: 0px solid #000;
                           border-left: 1px solid #000; border-bottom: 1px solid #000; height:10px;
                           background:#d8d8d8; vertical-align:middle;">Sucursal</td>
                          <td style="width:20%; text-align:center; border-top: 0px solid #000;
                           border-left: 1px solid #000; border-bottom: 1px solid #000; height:10px;
                           background:#d8d8d8; vertical-align:middle;">Día</td>
                          <td style="width:20%; text-align:center; border-top: 0px solid #000;
                           border-left: 1px solid #000; border-bottom: 1px solid #000; height:10px;
                           background:#d8d8d8; vertical-align:middle;">Mes</td>
                          <td style="width:20%; text-align:center; border-top: 0px solid #000;
                           border-left: 1px solid #000; border-bottom: 1px solid #000; height:10px;
                           background:#d8d8d8; vertical-align:middle; border-right: 1px solid #000;">Año</td>
                        </tr>
                        <tr>
                          <td style="width:40%; text-align:center; border-left: 1px solid #000;
                           border-bottom: 1px solid #000; height:25px;"><?=$sucursal;?></td>
                          <td style="width:20%; text-align:center; border-left: 1px solid #000;
                           border-bottom: 1px solid #000; height:25px;"><?=$dia;?></td>
                          <td style="width:20%; text-align:center; border-left: 1px solid #000;
                           border-bottom: 1px solid #000; height:25px;"><?=$mes;?></td>
                          <td style="width:20%; text-align:center; border-left: 1px solid #000;
                           border-bottom: 1px solid #000; border-right: 1px solid #000; 
                           height:25px;"><?=$anio?></td>
                        </tr>
                     </table>
                  </td> 
                </tr>
                <tr>
                  <td style="width:100%; font-weight:bold; text-align:center; padding-top:15px;" colspan="2">
                     FORMULARIO DE IDENTIFICACION DE CLIENTE -  PERSONAS JURIDICAS 
                     <br> Politica Conozca su Cliente ART 26 D.S.24771
                  </td> 
                </tr>
            </table>     
        </div>
        
        <div style="width: 775px; border: 0px solid #FFFF00;">
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 65%; font-family: Arial; 
                padding-top:4px; padding-bottom:3px;">
                <tr> 
                  <td style="width:100%; height:10px; text-align:left;
                    border-top: 1px solid #000; border-bottom: 1px solid #000; background:#d8d8d8;
                    vertical-align:middle; font-weight:bold;">
                     DATOS GENERALES DE LA EMPRESA
                  </td>      
                </tr>
                <tr> 
                  <td style="width:100%; height:20px; text-align:left; border-bottom: 1px solid #000;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                         <tr>
                          <td style="width:60%; text-align:left; vertical-align:middle; 
                            border-bottom: 1px solid #000; height:20px;">
                            <b>RAZON SOCIAL:</b>&nbsp;<?=$razon_social;?>
                          </td>
                          <td style="width:40%; text-align:left; border-left: 1px solid #000; 
                            border-bottom: 1px solid #000; height:20px; vertical-align:middle;">
                            <b>N° DE NIT:</b>&nbsp;<?=$nit;?> 
                          </td> 
                         </tr>
                         <tr>
                          <td style="width:60%; text-align:left; vertical-align:middle; height:20px; 
                             border-bottom: 1px solid #000;">
                             <b>TIPO DE SOCIEDAD COMERCIAL : </b>&nbsp;<?=$ts_comercial;?>
                          </td>
                          <td style="width:40%; text-align:left; border-left: 1px solid #000; height:20px;
                             border-bottom: 1px solid #000; vertical-align:middle;">
                             <b>N° DE REGISTRO EN FUNDEMPRESA:</b>&nbsp;<?=$nr_fundaempresa;?> 
                          </td> 
                         </tr> 
                         <tr>
                          <td style="width:60%; text-align:left; vertical-align:middle; height:20px;
                             border-bottom: 1px solid #000;">
                             <b>NUMERO DE LICENCIA DE FUNCIONAMIENTO GAM: </b>&nbsp;<?=$nl_funcionamiento;?>
                          </td>
                          <td style="width:40%; text-align:left; border-left: 1px solid #000; height:20px;
                             border-bottom: 1px solid #000; vertical-align:middle;">
                             <b>N°DE REGISTRO DEL VIFPE (SOLO PARA ORG. SIN FINES DE LUCRO):</b>&nbsp;<?=$nr_vifpe;?> 
                          </td> 
                         </tr>
                         <tr>
                          <td style="width:60%; text-align:left; vertical-align:middle; height:20px;
                             border-bottom: 1px solid #000;">
                             <b>ACTIVIDAD PRINCIPAL: </b>&nbsp;<?=$actividad;?>
                          </td>
                          <td style="width:40%; text-align:left; border-left: 1px solid #000; height:20px;
                             vertical-align:middle; border-bottom: 1px solid #000;">
                             <b>ANTIGÜEDAD DE LA PERSONA JURIDICA: </b>&nbsp;<?=$antiguedad_pj;?> 
                          </td> 
                         </tr>
                         <tr>
                          <td style="width:100%; text-align:left; border-bottom: 1px solid #000; height:20px;
                             vertical-align:middle;" colspan="2">
                             <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                               <tr>
                                <td style="width:75%; text-align:left; vertical-align:middle; 
                                  border-bottom: 0px solid #000;">
                                  <b>DIRECCION DE OFICINA PRINCIPAL:</b>&nbsp;<?=$direccion_oficina;?>
                                </td>
                                <td style="width:25%; text-align:left; border-left: 1px solid #000; 
                                  border-bottom: 0px solid #000; height:20px; vertical-align:middle;">
                                  <b>TELFEFONO:</b>&nbsp;<?=$telefono;?> 
                                </td> 
                               </tr>
                             </table>
                          </td>
                         </tr>
                         <tr>
                          <td style="width:100%; text-align:left; border-bottom: 1px solid #000; height:20px;
                             vertical-align:middle;" colspan="2">
                             <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                               <tr>
                                 <td style="width:20%; height:20px; text-align:left; 
                                    background:#d8d8d8; vertical-align:middle;">
                                    <b>INGRESOS MENSUALES</b>
                                 </td>
<?php
                            foreach($ingreso_mensual as $key => $value){
?>                                 
                                 <td style="width:16%; height:20px; border-left: 1px solid #000;
                                    vertical-align:middle;">
                                    <table cellspacing="0" cellpadding="0" border="0" style="width:100%;
                                       font-size:100%; border: 0px solid #000;">
                                       <tr>
                                          <td style="width:30%; padding-left:8px;">
                                            <div style="width: 15px; height: 12px; border: 1px solid #000; 
                                               text-align:center;">
<?php
												 if($row['ingreso_mensual']==$key){
													echo 'X';					
												 }else{
													echo '';	 
												 }
?>
                                             </div> 
                                          </td>
                                          <td style="width:70%; text-align:center;">
                                              <?=$value;?>
                                          </td>
                                       </tr>
                                    </table>    
                                 </td>
<?php
							}
?>                                 
                               </tr>  
                             </table> 
                          </td>
                         </tr>
                         <tr>
                          <td style="width:100%; text-align:left; border-bottom: 1px solid #000; height:10px;
                             vertical-align:middle; background:#d8d8d8;" colspan="2">
                             INFORMACION DE LOS REPRESENTANTES LEGALES 
                          </td>
                         </tr> 
                         <tr>
                          <td style="width:100%; text-align:center; border-bottom: 1px solid #000; height:10px;
                             vertical-align:middle; background:#d8d8d8;" colspan="2">
                             REPRESENTANTE LEGAL 1
                          </td>
                         </tr>
                         <tr>
                          <td style="width:100%; text-align:center; border-bottom: 1px solid #000; height:20px;
                             vertical-align:middle;" colspan="2">
                             <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                               <tr>
                                <td style="width:60%; text-align:left; vertical-align:middle; 
                                  border-bottom: 0px solid #000;">
                                  <b>NOMBRES Y APELLIDOS:</b>&nbsp;<?=$representante_legal;?>
                                </td>
                                <td style="width:32%; text-align:left; border-left: 1px solid #000; 
                                  border-bottom: 0px solid #000; height:20px; vertical-align:middle;">
                                  <b>N° DE DOC IDENTIDAD:</b>&nbsp;<?=$ci_representante;?> 
                                </td>
                                <td style="width:8%; text-align:left; border-left: 1px solid #000; 
                                  border-bottom: 0px solid #000; height:20px; vertical-align:middle;">
                                  <b>EXT.</b>&nbsp;<?=$extension;?> 
                                </td> 
                               </tr>
                             </table>
                          </td>
                         </tr>
                         <tr>
                          <td style="width:100%; text-align:center; border-bottom: 1px solid #000; height:20px;
                             vertical-align:middle;" colspan="2">
                             <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                               <tr>
                                <td style="width:15%; text-align:left; vertical-align:middle; 
                                  border-bottom: 0px solid #000; height:20px;">
                                  <b>FECHA DE NACIMIENTO</b>
                                </td>
                                <td style="width:5%; text-align:center; border-left: 1px solid #000; 
                                  border-bottom: 0px solid #000; height:20px; vertical-align:middle;
                                  background:#d8d8d8;">
                                  <?=$diafn;?> 
                                </td>
                                <td style="width:2%; text-align:left; border-left: 1px solid #000; 
                                  border-bottom: 0px solid #000; height:20px; vertical-align:middle;">&nbsp;
                                  
                                </td>
                                <td style="width:5%; text-align:center; border-left: 1px solid #000; 
                                  border-bottom: 0px solid #000; height:20px; vertical-align:middle;
                                  background:#d8d8d8;">
                                  <?=$mesfn;?>
                                </td>
                                <td style="width:2%; text-align:left; border-left: 1px solid #000; 
                                  border-bottom: 0px solid #000; height:20px; vertical-align:middle;">&nbsp;
                                   
                                </td>
                                <td style="width:5%; text-align:center; border-left: 1px solid #000; 
                                  border-bottom: 0px solid #000; height:20px; vertical-align:middle;
                                  background:#d8d8d8;">
                                  <?=$aniofn;?>
                                </td>
                                <td style="width:22%; text-align:left; border-left: 1px solid #000; 
                                  border-bottom: 0px solid #000; height:20px; vertical-align:middle;">
                                  <b>NACIONALIDAD:</b>&nbsp;<?=$nacionalidad;?> 
                                </td>
                                <td style="width:22%; text-align:left; border-left: 1px solid #000; 
                                  border-bottom: 0px solid #000; height:20px; vertical-align:middle;">
                                  <b>PROFESION:</b>&nbsp;<?=$prof_representante;?> 
                                </td>
                                <td style="width:22%; text-align:left; border-left: 1px solid #000; 
                                  border-bottom: 0px solid #000; height:20px; vertical-align:middle;">
                                  <b>CARGO:</b>&nbsp;<?=$cargo;?> 
                                </td> 
                               </tr>
                             </table>
                          </td>
                         </tr>
                         <tr>
                          <td style="width:100%; text-align:center; border-bottom: 1px solid #000; height:10px;
                             vertical-align:middle; background:#d8d8d8;" colspan="2">
                             REPRESENTANTE LEGAL 2
                          </td>
                         </tr>
                         <tr>
                          <td style="width:100%; text-align:center; border-bottom: 1px solid #000; height:20px;
                             vertical-align:middle;" colspan="2">
                             <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                               <tr>
                                <td style="width:60%; text-align:left; vertical-align:middle; 
                                  border-bottom: 0px solid #000;">
                                  <b>NOMBRES Y APELLIDOS:</b>
                                </td>
                                <td style="width:32%; text-align:left; border-left: 1px solid #000; 
                                  border-bottom: 0px solid #000; height:20px; vertical-align:middle;">
                                  <b>N° DE DOC IDENTIDAD:</b> 
                                </td>
                                <td style="width:8%; text-align:left; border-left: 1px solid #000; 
                                  border-bottom: 0px solid #000; height:20px; vertical-align:middle;">
                                  <b>EXT.</b> 
                                </td> 
                               </tr>
                             </table>
                          </td>
                         </tr>
                         <tr>
                          <td style="width:100%; text-align:center; border-bottom: 1px solid #000; height:20px;
                             vertical-align:middle;" colspan="2">
                             <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                               <tr>
                                <td style="width:15%; text-align:left; vertical-align:middle; 
                                  border-bottom: 0px solid #000;">
                                  <b>FECHA DE NACIMIENTO</b>
                                </td>
                                <td style="width:5%; text-align:center; border-left: 1px solid #000; 
                                  border-bottom: 0px solid #000; height:20px; vertical-align:middle;
                                  background:#d8d8d8;">
                                  DIA 
                                </td>
                                <td style="width:2%; text-align:left; border-left: 1px solid #000; 
                                  border-bottom: 0px solid #000; height:20px; vertical-align:middle;">&nbsp;
                                  
                                </td>
                                <td style="width:5%; text-align:center; border-left: 1px solid #000; 
                                  border-bottom: 0px solid #000; height:20px; vertical-align:middle;
                                  background:#d8d8d8;">
                                  MES
                                </td>
                                <td style="width:2%; text-align:left; border-left: 1px solid #000; 
                                  border-bottom: 0px solid #000; height:20px; vertical-align:middle;">&nbsp;
                                   
                                </td>
                                <td style="width:5%; text-align:center; border-left: 1px solid #000; 
                                  border-bottom: 0px solid #000; height:20px; vertical-align:middle;
                                  background:#d8d8d8;">
                                  AÑO
                                </td>
                                <td style="width:22%; text-align:left; border-left: 1px solid #000; 
                                  border-bottom: 0px solid #000; height:20px; vertical-align:middle;">
                                  <b>NACIONALIDAD:</b> 
                                </td>
                                <td style="width:22%; text-align:left; border-left: 1px solid #000; 
                                  border-bottom: 0px solid #000; height:20px; vertical-align:middle;">
                                  <b>PROFESION:</b> 
                                </td>
                                <td style="width:22%; text-align:left; border-left: 1px solid #000; 
                                  border-bottom: 0px solid #000; height:20px; vertical-align:middle;">
                                  <b>CARGO:</b> 
                                </td> 
                               </tr>
                             </table>
                          </td>
                         </tr>
                         <tr>
                          <td style="width:100%; text-align:center; border-bottom: 1px solid #000; height:10px;
                             vertical-align:middle; background:#d8d8d8;" colspan="2">
                             REPRESENTANTE LEGAL 3
                          </td>
                         </tr>
                         <tr>
                          <td style="width:100%; text-align:center; border-bottom: 1px solid #000; height:20px;
                             vertical-align:middle;" colspan="2">
                             <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                               <tr>
                                <td style="width:60%; text-align:left; vertical-align:middle; 
                                  border-bottom: 0px solid #000;">
                                  <b>NOMBRES Y APELLIDOS:</b>
                                </td>
                                <td style="width:32%; text-align:left; border-left: 1px solid #000; 
                                  border-bottom: 0px solid #000; height:20px; vertical-align:middle;">
                                  <b>N° DE DOC IDENTIDAD:</b> 
                                </td>
                                <td style="width:8%; text-align:left; border-left: 1px solid #000; 
                                  border-bottom: 0px solid #000; height:20px; vertical-align:middle;">
                                  <b>EXT.</b> 
                                </td> 
                               </tr>
                             </table>
                          </td>
                         </tr>
                         <tr>
                          <td style="width:100%; text-align:center; border-bottom: 1px solid #000; height:20px;
                             vertical-align:middle;" colspan="2">
                             <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                               <tr>
                                <td style="width:15%; text-align:left; vertical-align:middle; 
                                  border-bottom: 0px solid #000;">
                                  <b>FECHA DE NACIMIENTO</b>
                                </td>
                                <td style="width:5%; text-align:center; border-left: 1px solid #000; 
                                  border-bottom: 0px solid #000; height:20px; vertical-align:middle;
                                  background:#d8d8d8;">
                                  DIA 
                                </td>
                                <td style="width:2%; text-align:left; border-left: 1px solid #000; 
                                  border-bottom: 0px solid #000; height:20px; vertical-align:middle;">&nbsp;
                                  
                                </td>
                                <td style="width:5%; text-align:center; border-left: 1px solid #000; 
                                  border-bottom: 0px solid #000; height:20px; vertical-align:middle;
                                  background:#d8d8d8;">
                                  MES
                                </td>
                                <td style="width:2%; text-align:left; border-left: 1px solid #000; 
                                  border-bottom: 0px solid #000; height:20px; vertical-align:middle;">&nbsp;
                                   
                                </td>
                                <td style="width:5%; text-align:center; border-left: 1px solid #000; 
                                  border-bottom: 0px solid #000; height:20px; vertical-align:middle;
                                  background:#d8d8d8;">
                                  AÑO
                                </td>
                                <td style="width:22%; text-align:left; border-left: 1px solid #000; 
                                  border-bottom: 0px solid #000; height:20px; vertical-align:middle;">
                                  <b>NACIONALIDAD:</b> 
                                </td>
                                <td style="width:22%; text-align:left; border-left: 1px solid #000; 
                                  border-bottom: 0px solid #000; height:20px; vertical-align:middle;">
                                  <b>PROFESION:</b> 
                                </td>
                                <td style="width:22%; text-align:left; border-left: 1px solid #000; 
                                  border-bottom: 0px solid #000; height:20px; vertical-align:middle;">
                                  <b>CARGO:</b> 
                                </td> 
                               </tr>
                             </table>
                          </td>
                         </tr>
                         <tr>
                          <td style="width:100%; text-align:center; border-bottom: 1px solid #000; height:10px;
                             vertical-align:middle; background:#d8d8d8;" colspan="2">
                             REFERENCIAS COMERCIALES
                          </td>
                         </tr>
                         <tr>
                          <td style="width:100%; text-align:center; border-bottom: 1px solid #000; height:25px;
                             vertical-align:middle;" colspan="2">
                             <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                               <tr>
                                <td style="width:50%; text-align:left; vertical-align:middle; 
                                  border-bottom: 0px solid #000;">
                                  <b>NOMBRE Y APELLIDOS O RAZON SOCIAL: </b>&nbsp;<?=$razon_social;?>
                                </td>
                                <td style="width:30%; text-align:left; border-left: 1px solid #000; 
                                  border-bottom: 0px solid #000; height:20px; vertical-align:middle;">
                                  <b>DIRECCION:</b>&nbsp;<?=$direccion_oficina;?> 
                                </td>
                                <td style="width:20%; text-align:left; border-left: 1px solid #000; 
                                  border-bottom: 0px solid #000; height:20px; vertical-align:middle;">
                                  <b>TELEFONO</b>&nbsp;<?=$telefono;?> 
                                </td> 
                               </tr>
                             </table>
                          </td>
                         </tr>
                         <tr>
                          <td style="width:100%; text-align:center; border-bottom: 0px solid #000; height:10px;
                             vertical-align:middle; background:#d8d8d8;" colspan="2">
                             FIRMAS DE REPRESENTANTES LEGALES
                          </td>
                         </tr>                       
                     </table>      
                  </td>      
                </tr>
                <tr>
                  <td style="width:100%; height:25px; border-bottom: 0px solid #000;
                     padding-top:10px">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                         <tr>
                          <td style="width:5%;">&nbsp;</td>
                          <td style="width:30%; text-align:left; vertical-align:middle; height:95px; 
                            border-bottom: 0px solid #000; border-top: 1px solid #000; 
                            border-left: 1px solid #000;">&nbsp;
                            
                          </td>
                          <td style="width:30%; text-align:left; border-left: 1px solid #000; 
                            border-bottom:0px solid #000; height:95px; vertical-align:middle;
                            border-top: 1px solid #000;">&nbsp;
                             
                          </td>
                          <td style="width:30%; text-align:left; border-left: 1px solid #000; 
                            border-bottom: 0px solid #000; height:95px; vertical-align:middle;
                            border-top: 1px solid #000; border-right: 1px solid #000;">&nbsp;
                             
                          </td>
                          <td style="width:5%;">&nbsp;</td> 
                         </tr>
                         <tr>
                          <td style="width:5%;">&nbsp;</td>
                          <td style="width:30%; text-align:center; vertical-align:middle; height:20px;
                            border-bottom: 1px solid #000; border-top: 1px solid #000; border-left: 1px solid #000;">
                            Firma y Sello del Representante Legal 
                          </td>
                          <td style="width:30%; text-align:center; border-left: 1px solid #000; 
                            border-bottom: 1px solid #000; height:20px; vertical-align:middle;
                            border-top: 1px solid #000;">
                            Firma y Sello del Representante Legal  
                          </td>
                          <td style="width:30%; text-align:center; border-left: 1px solid #000; 
                            border-bottom: 1px solid #000; height:20px; vertical-align:middle;
                            border-top: 1px solid #000; border-right: 1px solid #000;">
                            Firma y Sello del Representante Legal 
                          </td>
                          <td style="width:5%;">&nbsp;</td> 
                         </tr>
                       </table>
                  </td>
                </tr>
            </table>
            
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 75%; font-family: Arial; 
                margin-top:70px;">
               <tr>
                <td style="width:12%;"></td> 
                <td style="width:30%; border-bottom: 1px solid #000;">&nbsp;</td>
                <td style="width:16%;"></td>
                <td style="width:30%; border-bottom: 1px solid #000;">&nbsp;</td>
                <td style="width:12%;"></td>  
               </tr>
               <tr>
                <td style="width:12%;"></td> 
                <td style="width:30%; text-align:center;">
                  Firma y Sello<br>
                  Funcionario que gestiona el llenado del Formulario
                </td>
                <td style="width:16%;"></td>
                <td style="width:30%; text-align:center;">
                  Firma y Sello<br>
                  Funcionario que recibeel Formulario<br>
                  (SUDAMERICANA)
                </td>
                <td style="width:12%;"></td>  
               </tr>
            </table><!---->
                   	
        </div>            
         
      </div>
   </div>
   
<?php
	$html = ob_get_clean();
	return $html;
}
/*
function get_date_format_fat_au($fecha){
	$date = date_create($fecha);
	
	$day = date_format($date, 'd');
	$month = date_format($date, 'F');
	$year = date_format($date, 'Y');
	
	return $day.' de '.get_month_es_fat_au($month).' de '.$year;
}

function get_month_es_fat_au($month){
	switch ($month) {
		case 'January':
			return 'Enero';
			break;
		case 'February':
			return 'Febrero';
			break;
		case 'March':
			return 'Marzo';
			break;
		case 'April':
			return 'Abril';
			break;
		case 'May':
			return 'Mayo';
			break;
		case 'June':
			return 'Junio';
			break;
		case 'July':
			return 'Julio';
			break;
		case 'August':
			return 'Agosto';
			break;
		case 'September':
			return 'Septiembre';
			break;
		case 'October':
			return 'Octubre';
			break;
		case 'November':
			return 'Noviembre';
			break;
		case 'December':
			return 'Diciembre';
			break;
	}
}
*/
?>