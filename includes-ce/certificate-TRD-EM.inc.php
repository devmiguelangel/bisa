<?php
function trd_em_certificate($link, $row, $rsDt, $url, $implant, $fac, $reason = '', $type) {
	
	ob_start();
?>
  <div id="container-c" style="width: 785px; height: auto; 
      border: 0px solid #0081C2; padding: 5px;">
	  <div id="main-c" style="width: 775px; font-weight: normal; font-size: 12px; 
        font-family: Arial, Helvetica, sans-serif; color: #000000;">
<?php
     $j = 0;
	 $text = ''; 
     $num_titulares=$rsDt->num_rows;
			
     while($rowDt = $rsDt->fetch_array(MYSQLI_ASSOC)){
		 if($row['tipo_cliente']=='Juridico'){
			 $cliente_nombre = $row['cl_razon_social'];
			 $cliente_nitci = $row['cl_ci'];
			 $cliente_direccion = $row['cl_direccion_laboral'];
		 }elseif($row['tipo_cliente']=='Natural'){
			 $cliente_nombre = $row['cl_nombre'].' '.$row['cl_paterno'].' '.$row['cl_materno'];
			 $cliente_nitci = $row['cl_ci'].$row['cl_complemento'].' '.$row['cl_extension'];
			 $cliente_direccion = $row['cl_direccion'];
		 }
		 $j += 1;
		 if($row['no_copia']>0){
			 if($row['no_copia']>1) $text='COPIA'; else $text='ORIGINAL';
		 }
?>
        <div style="width: 775px; border: 0px solid #FFFF00; text-align:center;">
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-family: Arial;">
                <tr>
                  <td style="width:100%; text-align:right;">
                     <img src="<?=$url;?>images/<?=$row['cia_logo'];?>" height="60"/>
                  </td> 
                </tr>
                <tr>
                  <td style="width:100%; font-weight:bold; text-align:center; font-size: 90%;">
                     SEGURO DE TODO RIESGO DE DAÑOS A LA PROPIEDAD<br>
                     POLIZA No<br>  
                     CONDICIONES PARTICULARES<br>
                  </td> 
                </tr>
                <tr>
                  <td style="width:100%; font-weight:bold; text-align:center;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 64%;">
                        <tr>
                          <td style="width:10%;"></td>
                          <td style="width:80%; text-align:center;">
                            CODIGO SPVS No.: 109-910101-2006 07 252
                          </td>
                          <td style="width:10%;"></td>  
                        </tr>
                        <tr>
                          <td style="width:10%;"></td>
                          <td style="width:80%; text-align:center;">
                            R.A. 740/06
                          </td>
                          <td style="width:10%; text-align:right;"><?=$text;?></td>
                        </tr>  
                     </table>   
                  </td>
                </tr>
            </table>     
        </div>
        <br/>
        
        <div style="width: 775px; border: 0px solid #FFFF00;">
			 <table 
                    cellpadding="0" cellspacing="0" border="0" 
                    style="width: 100%; height: auto; font-size: 75%; font-family: Arial;">
                    <tr>
                      <td style="width:35%; text-align: left; padding-right:5px; font-weight:bold; 
                        border:0px solid #0081C2;" valign="top">
                        ASEGURADO: 
                      </td>
                      <td style="width:65%; text-align: justify; padding-left:5px; 
                        border:0px solid #0081C2; font-style:italic;" valign="top">
                        <?=$cliente_nombre;?>
                      </td>  
                    </tr>
                    <tr>
                      <td style="width:35%; text-align: left; padding-right:5px; font-weight:bold; 
                        border:0px solid #0081C2;" valign="top">
                        PAGADOR: 
                      </td>
                      <td style="width:65%; text-align: justify; padding-left:5px; 
                        border:0px solid #0081C2; font-style:italic;" valign="top">
                        <?=$row['ef_nombre'];?>
                      </td>  
                    </tr>
                    <tr>
                      <td style="width:35%; text-align: left; padding-right:5px; font-weight:bold; 
                        border:0px solid #0081C2;" valign="top">
                        UBICACIÓN DEL RIESGO:
                      </td>
                      <td style="width:65%; text-align: justify; padding-left:5px; 
                        border:0px solid #0081C2; font-style:italic;" valign="top">
                        <?=$rowDt['pr_departamento'].'&nbsp;'.$rowDt['pr_zona'].'&nbsp;'.$rowDt['pr_localidad'].'&nbsp;'.$rowDt['pr_direccion'];?>
                      </td>  
                    </tr>
                    <tr>
                      <td style="width:35%; text-align: left; padding-right:5px; font-weight:bold; 
                        border:0px solid #0081C2;" valign="top">
                        DIRECCIÓN LEGAL:
                      </td>
                      <td style="width:65%; text-align: justify; padding-left:5px; 
                        border:0px solid #0081C2; font-style:italic;" valign="top">
                        <?=$cliente_direccion?>
                      </td>  
                    </tr>
                    <tr>
                      <td style="width:35%; text-align: left; padding-right:5px; font-weight:bold; 
                        border:0px solid #0081C2;" valign="top">
                        MATERIA DEL SEGURO:
                      </td>
                      <td style="width:65%; text-align: justify; padding-left:5px; 
                        border:0px solid #0081C2; font-style:italic;" valign="top">
                        BIENES INMUEBLES U OTRA PROPIEDAD QUE SEA OBJETO  DE GARANTÍA A FAVOR DEL CONTRATANTE.
                      </td>  
                    </tr>
                    <tr>
                      <td style="width:35%; text-align: left; padding-right:5px; font-weight:bold; 
                        border:0px solid #0081C2;" valign="top">
                        VALORES TOTAL A RIESGO:
                      </td>
                      <td style="width:65%; text-align: justify; padding-left:5px; 
                        border:0px solid #0081C2; font-style:italic;" valign="top">
                        DE ACUERDO A CADA OPERACIÓN QUE REALICE EL BANCO BISA CON CADA PRESTATARIO.
                      </td>  
                    </tr>
                    <tr>
                      <td style="width:35%; text-align: left; padding-right:5px; font-weight:bold; 
                        border:0px solid #0081C2;" valign="top">
                        VALORES ASEGURADOS:
                      </td>
                      <td style="width:65%; text-align: justify; padding-left:5px; 
                        border:0px solid #0081C2; font-style:italic;" valign="top">
                        DE ACUERDO A CADA OPERACIÓN QUE REALICE EL BANCO BISA CON CADA PRESTATARIO.
                      </td>  
                    </tr>
                    <tr><td style="width:100%;" colspan="2">&nbsp;</td></tr>
                    <tr>
                      <td style="width:35%; text-align: left; padding-right:5px; font-weight:bold; 
                        border:0px solid #0081C2;" valign="top">
                        COBERTURAS:
                      </td>
                      <td style="width:65%; text-align: justify; padding-left:5px; 
                        border:0px solid #0081C2; font-style:italic;" valign="top">
                        <b>A VALOR TOTAL:</b><br>
                        Todo Riesgo de Dañosa la Propiedad, incluyendo:
                        <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                            <tr>
                              <td style="width:2%; font-weight:bold;" valign="top">&bull;</td>
                              <td style="width:98%;">Temblor, Terremoto, Movimientos Sísmicos y Erupciones Volcánicas.</td>
                            </tr>
                            <tr>
                              <td style="width:2%; font-weight:bold;" valign="top">&bull;</td>
                              <td style="width:98%;">Perdidas y/o Daños Directos ocasionados por Caídas de Rocas</td>
                            </tr>
                            <tr>
                              <td style="width:2%; font-weight:bold;" valign="top">&bull;</td>
                              <td style="width:98%;">Anegación y Enfangamiento</td>
                            </tr>
                            <tr>
                              <td style="width:2%; font-weight:bold;" valign="top">&bull;</td>
                              <td style="width:98%;">Perdidas y Daños Directamente ocasionados por Derrumbe, Deslizamiento y Asentamiento</td>
                            </tr>
                            <tr>
                              <td style="width:2%; font-weight:bold;" valign="top">&bull;</td>
                              <td style="width:98%;">Hundimiento</td>
                            </tr>
                            <tr>
                              <td style="width:2%; font-weight:bold;" valign="top">&bull;</td>
                              <td style="width:98%;">Riadas Y Lodos</td>
                            </tr>
                            <tr>
                              <td style="width:2%; font-weight:bold;" valign="top">&bull;</td>
                              <td style="width:98%;">Colapso</td>
                            </tr>
                            <tr>
                              <td style="width:2%; font-weight:bold;" valign="top">&bull;</td>
                              <td style="width:98%;">Desplome</td>
                            </tr>
                            <tr>
                              <td style="width:2%; font-weight:bold;" valign="top">&bull;</td>
                              <td style="width:98%;">Pérdidas u Daños ocasionados por Aeronaves, Artefactos Aéreos u Objetos que caigan de ellos</td>
                            </tr>
                            <tr>
                              <td style="width:2%; font-weight:bold;" valign="top">&bull;</td>
                              <td style="width:98%;">Pérdidas y Daños ocasionados por Impacto de Vehículos</td>
                            </tr>
                            <tr>
                              <td style="width:2%; font-weight:bold;" valign="top">&bull;</td>
                              <td style="width:98%;">Daños por Agua, Grifería y Tanques</td>
                            </tr>
                            <tr>
                              <td style="width:2%; font-weight:bold;" valign="top">&bull;</td>
                              <td style="width:98%;">Lluvia</td>
                            </tr>
                            <tr>
                              <td style="width:2%; font-weight:bold;" valign="top">&bull;</td>
                              <td style="width:98%;">Inundaciones</td>
                            </tr>
                            <tr>
                              <td style="width:2%; font-weight:bold;" valign="top">&bull;</td>
                              <td style="width:98%;">Granizo y/o Nevada</td>
                            </tr>
                            <tr>
                              <td style="width:2%; font-weight:bold;" valign="top">&bull;</td>
                              <td style="width:98%;">Helada</td>
                            </tr>
                            <tr>
                              <td style="width:2%; font-weight:bold;" valign="top">&bull;</td>
                              <td style="width:98%;">Huracán y/o Tempestad</td>
                            </tr>
                            <tr>
                              <td style="width:2%; font-weight:bold;" valign="top">&bull;</td>
                              <td style="width:98%;">Viento cualquiera sea su velocidad, intensidad, duración o denominación.</td>
                            </tr>
                            <tr>
                              <td style="width:2%; font-weight:bold;" valign="top">&bull;</td>
                              <td style="width:98%;">Daños por Humo y Hollín.</td>
                            </tr>
                            <tr><td style="width:100%;" colspan="2">&nbsp;</td></tr>
                            <tr>
                              <td style="width:100%; text-align:left; font-weight:bold;" colspan="2">SUBLIMITES</td>
                            </tr>
                            <tr>
                              <td style="width:2%; font-weight:bold;" valign="top">&bull;</td>
                              <td style="width:98%;">Motines, Huelgas, Conmoción Civil, Daño Malicioso, Vandalismo, Sabotaje,  Terrorismo y Saqueo hasta el 50 % (por ciento) del valor asegurado del inmueble.</td>
                            </tr>
                            <tr>
                              <td style="width:2%; font-weight:bold;" valign="top">&bull;</td>
                              <td style="width:98%;">ROTURA DE VIDRIOS Y/O CRISTALES HASTA US$ 1.000,00</td>
                            </tr>
                            <tr>
                              <td style="width:2%; font-weight:bold;" valign="top">&bull;</td>
                              <td style="width:98%;">Gastos extraordinarios (Remoción de escombros -Gastos de salvataje - Honorarios de arquitectos, ingenieros y topógrafos), hasta el 10 % (por ciento) del valor asegurado del inmueble</td>
                            </tr>
                            <tr>
                              <td style="width:2%; font-weight:bold;" valign="top">&bull;</td>
                              <td style="width:98%;">Daños ocasionados por los medios empleados para combatir el incendio hasta del 10 % (por ciento) del valor asegurado del inmueble.</td>
                            </tr>
                            <tr>
                              <td style="width:2%; font-weight:bold;" valign="top">&bull;</td>
                              <td style="width:98%;">Gastos de Aceleración de Reclamos hasta US$ 1.000,00.</td>
                            </tr>
                          </table>
                      </td>  
                    </tr>
                    <tr>
                      <td style="width:35%; text-align: left; padding-right:5px; font-weight:bold; 
                        border:0px solid #0081C2;" valign="top">
                        CLÁUSULAS ADICIONALES:
                      </td>
                      <td style="width:65%; text-align: justify; padding-left:5px; 
                        border:0px solid #0081C2; font-style:italic;" valign="top">
                        <br>
                        <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                            <tr>
                              <td style="width:2%; font-weight:bold;" valign="top">&bull;</td>
                              <td style="width:98%;">CLAUSULA DE ELEGIBILIDAD DE AJUSTADORES, NO APLICABLE A RIESGOS POLÍTICOS Y TERRORISMO.</td>
                            </tr>
                            <tr>
                              <td style="width:2%; font-weight:bold;" valign="top">&bull;</td>
                              <td style="width:98%;">CLAUSULA DE ADELANTO DEL 50% DEL SINIESTRO</td>
                            </tr>
                            <tr>
                              <td style="width:2%; font-weight:bold;" valign="top">&bull;</td>
                              <td style="width:98%;">CLAUSULA DE AMPLIACIÓN DE AVISO DE SINIESTRO A 10 DÍAS</td>
                            </tr>
                            <tr>
                              <td style="width:2%; font-weight:bold;" valign="top">&bull;</td>
                              <td style="width:98%;">CLAUSULA DE RESCISIÓN DEL CONTRATO A PRORRATA</td>
                            </tr>
                            <tr>
                              <td style="width:2%; font-weight:bold;" valign="top">&bull;</td>
                              <td style="width:98%;">CLAUSULA DE ERRORES U OMISIONES</td>
                            </tr>
                        </table>     
                      </td>  
                    </tr>
                    <tr>
                      <td style="width:35%; text-align: left; padding-right:5px; font-weight:bold; 
                        border:0px solid #0081C2;" valign="top">
                        PRIMA TOTAL: 
                      </td>
                      <td style="width:65%; text-align: justify; padding-left:5px; 
                        border:0px solid #0081C2; font-style:italic;" valign="top">
                        (SEGÚN TARIFICADOR)
                      </td>  
                    </tr>
              </table>
              <br>
              <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 75%; font-family: Arial;">
                <tr> 
                  <td style="width:100%;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 100%;">
                        <tr>
                          <td style="width:20%; font-weight:bold; text-align:left;">FRANQUICIAS DEDUCIBLES:</td>
                          <td style="width:80%; text-align:left;">
                              POR EVENTO Y/O RECLAMO
                          </td>
                        </tr>
                     </table>
                  </td>      
                </tr>
                <tr> 
                  <td style="width:100%;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 100%;">
                        <tr>
                          <td style="width:20%; text-align:left;">&nbsp;</td>
                          <td style="width:80%;">
                             <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 100%;">
                                <tr>
                                  <td style="width:50%; font-weight:bold; text-align:left;">
                                     COBERTURA
                                  </td>
                                  <td style="width:25%; font-weight:bold; text-align:center;">
                                      RIESGOS DOMICILIARIOS
                                  </td>
                                  <td style="width:25%; font-weight:bold; text-align:center;">
                                      POR EVENTO Y/O RECLAMO
                                  </td>
                                </tr>
                                <tr>
                                  <td style="width:100%; border-bottom: 1px solid #333; padding-top:10px;" colspan="3">&nbsp;
                                    
                                  </td>
                                </tr>
                                <tr>
                                  <td style="width:50%; text-align:left;">
                                     * TODO RIESGO DE DAÑOS A LA PROPIEDAD
                                  </td>
                                  <td style="width:25%; text-align:center;">
                                      -.-
                                  </td>
                                  <td style="width:25%; text-align:center;">
                                      US$ 100,00
                                  </td>
                                </tr>
                                <tr>
                                  <td style="width:50%; text-align:left;">
                                     * RIESGOS POLITICOS Y TERRORISMO (SOBRE EL VALOR DEL SINIESTRO)
                                  </td>
                                  <td style="width:25%; text-align:center;">
                                      -.-
                                  </td>
                                  <td style="width:25%; text-align:center;">
                                      1% (POR CIENTO) CON UN MÍNIMO DE US$ 100,00
                                  </td>
                                </tr>
                                <tr>
                                  <td style="width:50%; text-align:left;">
                                     * TERREMOTO, TEMBLOR Y MOVIMIENTOS SÍSMICOS (SOBRE EL VALOR ASEGURADO DEL PREDIO AFECTADO)
                                  </td>
                                  <td style="width:25%; text-align:center;">
                                      1% (POR CIENTO)
                                  </td>
                                  <td style="width:25%; text-align:center;">
                                      1% (POR CIENTO)
                                  </td>
                                </tr>
                                <tr>
                                  <td style="width:50%; text-align:left;">
                                     * ROTURA DE VIDRIOS Y/O CRISTALES
                                  </td>
                                  <td style="width:25%; text-align:center;">
                                      US$ 25,00
                                  </td>
                                  <td style="width:25%; text-align:center;">
                                      US$ 50,00
                                  </td>
                                </tr>
                             </table>
                          </td>
                        </tr>
                     </table>
                  </td>      
                </tr>
              </table>
              <div style="'width: 100%; height: auto; margin: 0 0 5px 0;">
<?php
             $queryVar = 'set @anulado = "Polizas Anuladas: ";';
             if($link->query($queryVar,MYSQLI_STORE_RESULT)){
                 $canceled="select 
                                max(@anulado:=concat(@anulado, prefijo, '-', no_emision, ', ')) as cert_canceled
                            from
                                s_trd_em_cabecera
                            where
                                anulado = 1
                                    and id_cotizacion = '".$row['idc']."';";
                 if($resp = $link->query($canceled,MYSQLI_STORE_RESULT)){
                     $regis = $resp->fetch_array(MYSQLI_ASSOC);
                     echo '<span style="font-size:8px;">'.trim($regis['cert_canceled'],', ').'</span>';
                 }else{
                     echo "Error en la consulta "."\n ".$link->errno. ": " . $link->error;
                 }
             }else{
               echo "Error en la consulta "."\n ".$link->errno. ": " . $link->error;   
             }
?>
            </div>
            <div style="'width: 100%; height: auto; margin: 0 0 5px 0;">
<?php
			    if((boolean)$row['facultativo']===true){
				   if((boolean)$row['f_aprobado']===true){
?>
                      <table border="0" cellpadding="1" cellspacing="0" style="width: 100%; font-size: 8px; font-weight: normal; font-family: Arial; margin: 2px 0 0 0; padding: 0; border-collapse: collapse; vertical-align: bottom;">
                            <tr>
                                <td colspan="7" style="width:100%; text-align: center; font-weight: bold; background: #e57474; color: #FFFFFF;">Caso Facultativo</td>
                            </tr>
                            <tr>
                                
                                <td style="width:5%; text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e57474;">Aprobado</td>
                                <td style="width:5%; text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e57474;">Tasa de Recargo</td>
                                <td style="width:7%; text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e57474;">Porcentaje de Recargo</td>
                                <td style="width:7%; text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e57474;">Tasa Actual</td>
                                <td style="width:7%; text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e57474;">Tasa Final</td>
                                <td style="width:69%; text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e57474;">Observaciones</td>
                            </tr>
                            <tr>
                                
                                <td style="width:5%; text-align: center; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=strtoupper($row['f_aprobado']);?></td>
                                <td style="width:5%; text-align: center; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=strtoupper($row['f_tasa_recargo']);?></td>
                                <td style="width:7%; text-align: center; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=$row['f_porcentaje_recargo'];?> %</td>
                                <td style="width:7%; text-align: center; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=$row['f_tasa_actual'];?> %</td>
                                <td style="width:7%; text-align: center; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=$row['f_tasa_final'];?> %</td>
                                <td style="width:69%; text-align: justify; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=$row['motivo_facultativo'];?> |<br /><?=$row['f_observacion'];?></td>
                            </tr>
                       </table>
<?php
				   }else{	 
?> 
                      <table border="0" cellpadding="1" cellspacing="0" style="width: 100%; font-size: 9px; border-collapse: collapse; font-weight: normal; font-family: Arial; margin: 2px 0 0 0; padding: 0; border-collapse: collapse; vertical-align: bottom;">         
                           <tr>
                            <td  style="text-align: center; font-weight: bold; background: #e57474; color: #FFFFFF;">
                              Caso Facultativo
                            </td>
                           </tr>
                           <tr>
                            <td style="text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e57474;">
                              Observaciones
                            </td>
                           </tr>
                           <tr>
                            <td style="text-align: justify; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;">
							  <?=$row['motivo_facultativo'];?>
                            </td>
                           </tr>

                      </table>
<?php
				   }
				}
?>    
            </div>           
              <br><br><br><br>
              <div style="font-size: 80%; text-align:center;">  
                 • Av. Arce Nº 2631, Edificio Multicine Piso 14 • Teléfono: (591-2) 217 7000 • Fax: (591-2) 214 1928 • La Paz – Bolivia.<br> 
• Autorizado por Resolución Administrativa Nº 158 del 7 de julio de 1999 de la Superintendencia de Pensiones Valores y Seguros.
              </div>  	
        </div>            
<?php
       if($type!=='MAIL' && (boolean)$row['emitir']===true && (boolean)$row['anulado']===false){
?>        
        
        <page><div style="page-break-before: always;">&nbsp;</div></page>
        
        <div style="width: 775px; border: 0px solid #FFFF00;">
            <div style="width: 775px; border: 0px solid #FFFF00; text-align:center;">
                <table 
                    cellpadding="0" cellspacing="0" border="0" 
                    style="width: 100%; height: auto; font-family: Arial;">
                    <tr>
                      <td style="width:100%; text-align:right;">
                         <img src="<?=$url;?>images/<?=$row['cia_logo'];?>" height="60"/>
                      </td> 
                    </tr>
                    <tr>
                      <td style="width:100%; font-weight:bold; text-align:center; font-size: 90%;">
                         SEGURO DE TODO RIESGO DE DAÑOS A LA PROPIEDAD
                      </td> 
                    </tr>
                </table>     
            </div>
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 75%; font-family: Arial;">
                <tr>
                  <td style="width:35%; text-align: left; padding-right:5px; font-weight:bold; 
                    border:0px solid #0081C2;" valign="top">
                    EXCLUSIONES: 
                  </td>
                  <td style="width:65%; text-align: justify; padding-left:5px; 
                    border:0px solid #0081C2; font-style:italic;" valign="top">
                    DE ACUERDO A CONDICIONADO GENERAL, ANEXOS Y CLAUSULAS DE LA PÓLIZA.<br>
                    ADICIONALMENTE A LAS EXCLUSIONES ESTIPULADAS SE EXCLUYE:<br>
                    -BIENES EN CÁMARAS FRIGORÍFICAS Y EN EL AGUA<br>
                    -ROBO, HURTO Y/O RATERÍA<br>
                    -BIENES BAJO TIERRA.<br>
                    -BIENES A LA INTEMPERIE QUE NO SEAN APTOS PARA TAL FIN<br>    
                    -DINERO, JOYAS Y/O VALORES         
                  </td>  
                </tr>
                <tr>
                  <td style="width:35%; text-align: left; padding-right:5px; font-weight:bold; 
                    border:0px solid #0081C2;" valign="top">
                    VIGENCIA 
                  </td>
                  <td style="width:65%; text-align: justify; padding-left:5px; 
                    border:0px solid #0081C2; font-style:italic;" valign="top">
                    ANUAL CON RENOVACIÓNAUTOMÁTICA.         
                  </td>  
                </tr>
                <tr>
                  <td style="width:35%; text-align: left; padding-right:5px; font-weight:bold; 
                    border:0px solid #0081C2;" valign="top">
                    CLAUSULA DE SUBROGACIÓN:
                  </td>
                  <td style="width:65%; text-align: justify; padding-left:5px; 
                    border:0px solid #0081C2; font-style:italic;" valign="top">
                    CLAUSULA ADJUNTA A LA PRESENTE PÓLIZA        
                  </td>  
                </tr>
                <tr>
                  <td style="width:35%; text-align: left; padding-right:5px; font-weight:bold; 
                    border:0px solid #0081C2;" valign="top">
                    FORMA DE PAGO:
                  </td>
                  <td style="width:65%; text-align: justify; padding-left:5px; 
                    border:0px solid #0081C2; font-style:italic;" valign="top">
                    AL CONTADOO CON DEBITO EN CUENTA 
                  </td>  
                </tr>
                <tr>
                  <td style="width:35%; text-align: left; padding-right:5px; font-weight:bold; 
                    border:0px solid #0081C2;" valign="top">
                    CONDICIONES ESPECIALES:
                  </td>
                  <td style="width:65%; text-align: justify; padding-left:5px; 
                    border:0px solid #0081C2; font-style:italic;" valign="top">
                    EL ASEGURADO AUTORIZA A LA COMPAÑÍA DE SEGUROS A ENVIAR EL REPORTE A LA CENTRAL DE RIESGOS DEL MERCADO DE SEGUROS, ACORDE A LAS NORMATIVAS REGLAMENTARIAS DE LA AUTORIDAD DE FISCALIZACIÓN Y CONTROL DE PENSIONES Y SEGUROS - APS.<br>
                    EL ASEGURADO DEBERÁ PRESENTAR:
                    <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                         <tr>
                           <td style="width:2%; font-weight:bold;">o</td>
                           <td style="width:98%;">UNA COPIA DEL AVALÚO TÉCNICO DEL INMUEBLE A ASEGURAR, EN CASO QUE EL BIEN ASEGURADO SEA SUBROGADO.</td>
                         </tr>
                         <tr>
                           <td style="width:2%; font-weight:bold;">o</td>
                           <td style="width:98%;">FOTOCOPIA DEL DOCUMENTO DE IDENTIDAD Y/O NIT DEL ASEGURADO, SEGÚN CORRESPONDA.</td>
                         </tr>
                         <tr>
                           <td style="width:2%; font-weight:bold;">o</td>
                           <td style="width:98%;">FORMULARIO DE SOLICITUD DE SEGURO DEBIDAMENTE FIRMADO Y FECHADO</td>
                         </tr>
                         <tr>
                           <td style="width:2%; font-weight:bold;">o</td>
                           <td style="width:98%;">Y/O CUALQUIER OTRO DOCUMENTO ADICIONAL QUE REQUIERA LA COMPAÑÍA EN CASO DE SER NECESARIO.</td>
                         </tr>
                         
                    </table>
                    <br>
                    BISA SEGUROS Y REASEGUROS S.A., DENTRO DE TERRITORIO NACIONAL LE OTORGA EL SERVICIO DE ASISTENCIA DOMICILIARIA, LAS 24 HORAS DEL DÍA Y LOS 365 DÍAS DEL AÑO, CON BENEFICIOS ESTABLECIDOS EN EL ANEXO DE SERVICIO DE ASISTENCIA DOMICILIARIA.<br>                                                           
PARA MAYOR INFORMACIÓN SOBRE LOS SERVICIOS Y LIMITES REFIÉRASE AL ANEXO DE SERVICIO DE ASISTENCIA DOMICILIARIA.<br><br>

EL PROVEEDOR DEL SERVICIO DE ASISTENCIA DOMICILIARIA ES RESPONSABLE DE LOS SERVICIOS PRESTADOS A LOS ASEGURADOS.
     
                  </td>  
                </tr>
                <tr>
                  <td style="width:35%; text-align: left; padding-right:5px; font-weight:bold; 
                    border:0px solid #0081C2;" valign="top">
                    OBSERVACIONES:
                  </td>
                  <td style="width:65%; text-align: justify; padding-left:5px; 
                    border:0px solid #0081C2; font-style:italic;" valign="top">
                    EL ASEGURADO DECLARA HABER RECIBIDO TODOS LOS CONDICIONADOS, CLAUSULAS Y ANEXOS QUE FORMAN PARTE INTEGRANTE DE LA PRESENTE PÓLIZA, DEBIENDO DECLARAR SU CONFORMIDAD CON LA MISMA Y COMPROMETIÉNDOSE A DAR A CONOCER A LA COMPAÑÍA CUALQUIER DISCREPANCIA DENTRO DE LOS 15 DÍAS DE RECIBIDA.         
                  </td>  
                </tr>
            </table>
           
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-family: Arial; 
                padding-top:20px;">
               <tr>
                <td style="width:25%;"></td>
                <td style="width:50%; text-align:center;">
                  <?=$row['u_depto']?>, <?=strtoupper(get_date_format_trd_em($row['fecha_emision']));?>
                  <br>
                  <br>
                  <b>BISA SEGUROS Y REASEGUROS S.A.</b>
                </td>
                <td style="width:25%;"></td> 
               </tr>
               <tr>
                <td style="width:25%;"></td>
                <td style="width:50%; border-bottom: 1px solid #333; text-align:center;">
                  <img src="<?=$url;?>img/firmas_bisa.png" height="90"/>
                </td>
                <td style="width:25%;"></td> 
               </tr>
               <tr>
                <td style="width:25%;"></td>
                <td style="width:50%; text-align:center; font-weight:bold;">
                  FIRMAS AUTORIZADAS
                </td>
                <td style="width:25%;"></td> 
               </tr>  
            </table>
            <br><br><br><br>
            <div style="font-size: 80%; text-align:center;">  
               • Av. Arce Nº 2631, Edificio Multicine Piso 14 • Teléfono: (591-2) 217 7000 • Fax: (591-2) 214 1928 • La Paz – Bolivia.<br> 
• Autorizado por Resolución Administrativa Nº 158 del 7 de julio de 1999 de la Superintendencia de Pensiones Valores y Seguros.
            </div>     
        </div>
<?php
	   }
	   
	 }
?>        
        
      </div>
   </div>
<?php
	if ($fac === TRUE) {
		 $url .= 'index.php?ms='.md5('MS_TRD').'&page='.md5('P_fac').'&ide='.base64_encode($row['id_emision']).'';
?>
         <br/>
         <div style="width:500px; height:auto; padding:10px 15px; font-size:11px; font-weight:bold; text-align:left;">
            No. de Slip de Cotizaci&oacute;n: <?=$row['no_cotizacion'];?>
         </div><br>
         <div style="width:500px; height:auto; padding:10px 15px; border:1px solid #FF2D2D; background:#FF5E5E; color:#FFF; font-size:10px; font-weight:bold; text-align:justify;">
            Observaciones en la solicitud del seguro:<br><br><?=$reason;?>
         </div>
         <div style="width:500px; height:auto; padding:10px 15px; font-size:11px; font-weight:bold; text-align:left;">
            Para procesar la solicitud ingrese al siguiente link con sus credenciales de usuario:<br>
            <a href="<?=$url;?>" target="_blank">Procesar caso facultativo</a>
         </div>
<?php
	}
	$html = ob_get_clean();
	return $html;
}

function get_date_format_trd_em($fecha){
	$date = date_create($fecha);
	
	$day = date_format($date, 'd');
	$month = date_format($date, 'F');
	$year = date_format($date, 'Y');
	
	return $day.' de '.get_month_espanol_trd_em($month).' de '.$year;
}

function get_month_espanol_trd_em($month){
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
?>