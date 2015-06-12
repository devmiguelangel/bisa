<?php
function au_em_certificate($link, $row, $rsDt, $url, $implant, $fac, $reason = '', $type) {
		
	ob_start();
?>

  <div id="container-c" style="width: 785px; height: auto; 
    border: 0px solid #0081C2; padding: 5px;">
	  <div id="main-c" style="width: 775px; font-weight: normal; font-size: 12px; 
      font-family: Arial, Helvetica, sans-serif; color: #000000;">
<?php
     $j = 0;
     $num_titulares = $rsDt->num_rows;
	 
	 $poliza = (91).''.plaza_au($row['u_departamento']).''.$row['garantia'].''.str_pad($row['no_emision'],7,'0',STR_PAD_LEFT);
	 
	 $text = '';		
     while($rowDt = $rsDt->fetch_array(MYSQLI_ASSOC)){
		   if($row['tipo_cliente']=='J'){
			   $cliente_nombre = $row['cl_razon_social'];
			   $cliente_nitci = $row['ci'];
			   $cliente_direccion = $row['direccion_laboral'];
			   $cliente_fono = $row['telefono_oficina'];
		   }elseif($row['tipo_cliente']=='N'){
			   $cliente_nombre = $row['nombre'].' '.$row['paterno'].' '.$row['materno'];
			   $cliente_nitci = $row['ci'].$row['complemento'].' '.$row['extension'];
			   $cliente_direccion = $row['direccion'].' '.$row['no_domicilio'];
			   $cliente_fono = $row['telefono_domicilio'].' '.$row['telefono_celular'];
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
                    <td style="width:20%;">&nbsp;</td>
                    <td style="width:60%;">
                      <table cellpadding="0" cellspacing="0" border="0" style="width: 100%;">
                          <tr>
                            <td style="width:100%; font-size:70%; text-align:center; font-weight:bold;
                              border: 0px solid #FFFF00;">
                              SEGUROS DE AUTOMOTORES INDIVIDUAL<br>
                              CONDICIONES PARTICULARES
                            </td>
                          </tr>
                          <tr>
                            <td style="width:100%; font-size:60%; text-align:center; font-weight:bold;">
                              CODIGO SPVS No.: 109-910502-2007 12 311<br>
                              R.A. 014/08
                            </td>
                          </tr>
                       </table>
                    </td>
                    <td style="width:20%; text-align:right;">
                       <img src="<?=$url;?>images/<?=$row['logo_cia'];?>" height="60"/>
                    </td>
                </tr>
              </table>    
              <div style="text-align:right; font-size:60%; padding-right:30px;"><?=$text;?></div>      
          </div>
          <div style="width: 775px; border: 0px solid #FFFF00;">
              <table 
                  cellpadding="0" cellspacing="0" border="0" 
                  style="width: 100%; height: auto; font-size: 70%; font-family: Arial;">
                  <tr> 
                    <td style="width:100%; border-bottom: 0px solid #333;">
                       <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 100%;">
                          <tr>
                            <td style="width:100%; text-align:left;">
                               <b>POLIZA NRO.:</b>&nbsp; <?=$poliza;?> 
                            </td>
                          </tr>
                          <tr>
                            <td style="width:100%; text-align:left;">
                               <b>LUGAR Y FECHA:</b>&nbsp; <?=strtoupper($row['u_departamento']);?>, <?=strtoupper(get_date_format_au($row['fecha_emision']));?> 
                            </td>
                          </tr>
                       </table>
                    </td>      
                  </tr>
                  <tr>
                    <td style="width:100%;">
                       <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                          <tr>
                            <td style="width:100%; text-align:left; font-weight:bold; 
                              border-bottom: 1px solid #333; border-top: 1px solid #333;" colspan="2">
                              DATOS DEL ASEGURADO/TOMADOR/PAGADOR
                            </td>
                          </tr>
                          <tr>
                            <td style="width:100%; text-align:left;" colspan="2"><b>Tomador:</b>
                            &nbsp;<?=$row['tomador_nombre'];?> </td>
                          </tr>
                          <tr>
                            <td style="width:60%; text-align:left; padding-top:5px;"><b>Asegurado:</b>
                             &nbsp;<?=$cliente_nombre;?></td>
                            <td style="width:40%; text-align:left; padding-top:5px;"><b>NIT o CI:</b>
                             &nbsp;<?=$cliente_nitci?></td>
                          </tr>
                          <tr>
                            <td style="width:60%; text-align:left;"><b>Direccion:</b>
                             &nbsp;<?=$cliente_direccion;?></td>
                            <td style="width:40%; text-align:left;"><b>Teléfono/Celular:</b>
                            &nbsp;<?=$cliente_fono?></td>
                          </tr>
                          <tr>
                            <td style="width:60%; text-align:left; padding-top:5px;"><b>Asegurado:</b> </td>
                            <td style="width:40%; text-align:left; padding-top:5px;"><b>NIT o CI:</b></td>
                          </tr>
                          <tr>
                            <td style="width:60%; text-align:left;"><b>Direccion:</b> </td>
                            <td style="width:40%; text-align:left;"><b>Teléfono/Celular:</b></td>
                          </tr>
                       </table>                                  
                    </td>
                  </tr>
                  <tr>
                    <td style="width:100%; padding-top:5px;">
                       <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                          <tr>
                            <td style="width:100%; text-align:left; font-weight:bold; 
                              border-bottom: 1px solid #333; border-top: 1px solid #333;" colspan="3">
                              DATOS DE LA POLIZA
                            </td>
                          </tr>
                          <tr>
                            <td style="width:25%; text-align:left;"><b>Producto:</b></td>
                            <td style="width:25%; text-align:left;">AUTO PLUS</td>
                            <td style="width:50%;"></td>
                          </tr>
                          <tr>
                            <td style="width:25%; text-align:left;"><b>Alcance Territorial, Competencia y Jurisdicción:</b></td>
                            <td style="width:25%; text-align:left;"></td>
                            <td style="width:50%; text-align:left;">ESTADO PLURINACIONAL DE BOLIVIA</td>
                          </tr>
                          <tr>
                            <td style="width:100%; text-align:left;" colspan="3"><b>Intermediario:</b></td>
                          </tr>
                       </table>
                    </td>
                  </tr>   
                  <tr>
                    <td style="width:100%; padding-top:5px;">
                       <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                          <tr>
                            <td style="width:100%; text-align:left; font-weight:bold; 
                              border-bottom: 1px solid #333; border-top: 1px solid #333; margin-bottom:5px;" colspan="6">
                              INFORMACION DE LA MATERIA DEL SEGURO Y VALORES ASEGURADOS
                            </td>
                          </tr>
                          <tr>
                           <td colspan="6" style="width:100%; text-align:left; font-weight:bold;">
                              Datos del Vehículo
                           </td>
                          </tr>
                          <tr>
                            <td style="width:19%; text-align:left; font-weight:bold;">Clase: </td>
                            <td style="width:25%;">&nbsp;
                                
                            </td>
                            <td style="width:12%; text-align:left; font-weight:bold;">Marca: </td>
                            <td style="width:15%;">&nbsp;<?=$rowDt['marca'];?>
                                
                            </td>
                            <td style="width:13%; text-align:left; font-weight:bold;">Tipo: </td>
                            <td style="width:16%;">&nbsp;<?=$rowDt['tipo_vechiculo'];?>
                                 
                            </td>   
                          </tr>
                          <tr>
                            <td style="width:19%; text-align:left; font-weight:bold;">Color: </td>
                            <td style="width:25%;">&nbsp;<?=$rowDt['color'];?>
                                
                            </td>
                            <td style="width:12%; text-align:left; font-weight:bold;">Modelo: </td>
                            <td style="width:15%;">&nbsp;<?=$rowDt['modelo']?>
                                
                            </td>
                            <td style="width:13%; text-align:left; font-weight:bold;">No. Chasis: </td>
                            <td style="width:16%;">&nbsp;<?=$rowDt['chasis']?>
                                 
                            </td>   
                          </tr>
                          <tr>
                            <td style="width:19%; text-align:left; font-weight:bold;">No. Motor: </td>
                            <td style="width:25%;">&nbsp;<?=$rowDt['motor'];?>
                                
                            </td>
                            <td style="width:12%; text-align:left; font-weight:bold;">Placa: </td>
                            <td style="width:15%;">&nbsp;<?=$rowDt['placa']?>
                                
                            </td>
                            <td style="width:13%; text-align:left; font-weight:bold;">Cap/Ton:</td>
                            <td style="width:16%;">&nbsp;<?=$rowDt['cap_ton']?>
                                 
                            </td>   
                          </tr>
                          <tr>
                            <td style="width:19%; text-align:left; font-weight:bold;">
<?php
                            if((boolean)$row['garantia']===false)
							     echo 'Uso';
?>                            
                             
                            
                            </td>
                            <td style="width:25%;">&nbsp;
                                
                            </td>
                            <td style="width:12%; text-align:left; font-weight:bold;">No. de Ocupantes: </td>
                            <td style="width:15%;">&nbsp;<?=$rowDt['no_asiento'];?>
                                
                            </td>
                            <td style="width:13%; text-align:left; font-weight:bold;">Cilindrada:</td>
                            <td style="width:16%;">&nbsp;
                                
                            </td>   
                          </tr>
                          <tr>
                            <td style="width:100%;" colspan="6">
                               <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;"> 
                                 <tr>
                                   <td style="width:55%; text-align:left;">
                                      <b>Caracteristicas especiales:</b>
                                   </td>
                                   <td style="width:45%; text-align:left;">
                                      <b>Plaza de Circulación:</b>
                                   </td>
                                 </tr>
                               </table> 
                            </td>
                          </tr>
                       </table> 
                    </td>              
                  </tr>
                  <tr>
                    <td style="width:100%; font-weight:bold; text-align:left; border-bottom: 0px solid #333;
                       padding-top:5px;">
                       Valores Asegurados
                     </td>
                  </tr>
                  <tr>
                    <td style="width:100%; border-bottom: 0px solid #333; padding-top:5px;">
                        <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                           <tr>
                             <td style="width:25%; text-align:left; font-weight:bold;">
                               Responsabilidad Civil Extracontractual: 
                             </td>
                             <td style="width:10%; text-align:right;">
                                          
                             </td>
                             <td style="width:12%; text-align:right;" valign="top">$us&nbsp;25.000,00</td>
                             <td style="width:8%;">
                               
                             </td>
                             <td style="width:24%; text-align:left; font-weight:bold;">
                               Casco:<br>
                               Accesorios: 
                             </td>
                             <td style="width:10%;">&nbsp;
                               
                             </td>
                             <td style="width:11%; text-align:left;">
                               $us <?=number_format($rowDt['valor_asegurado'],2,'.',',');?><br>
                               -
                             </td>
                           </tr>
                           <tr>
                             <td style="width:25%; text-align:left; font-weight:bold;">
                              Responsabilidad Civil Consecuencial: 
                             </td>
                             <td style="width:10%; text-align:right;">
                                          
                             </td>
                             <td style="width:12%; text-align:right;" valign="top">$us&nbsp;3.000,00</td>
                             <td style="width:8%;">
                               
                             </td>
                             <td style="width:24%; text-align:left; font-weight:bold;">
                               Muerte Accidental, p/ocupante:<br>
                               Invalidez Permanente, p/ocupante        
                             </td>
                             <td style="width:10%; text-align:right;">
                               
                             </td>
                             <td style="width:11%; text-align:left;">
                               $us&nbsp;5.000,00<br>
                               $us&nbsp;5.000,00
                             </td>
                           </tr>
                           <tr>
                             <td style="width:25%; text-align:left; font-weight:bold;">
                              Responsabilidad Civil a Ocupantes, p/ocupante: 
                             </td>
                             <td style="width:10%; text-align:right;">
                                          
                             </td>
                             <td style="width:12%; text-align:right;" valign="top">$us&nbsp;1.000,00</td>
                             <td style="width:8%;">
                               
                             </td>
                             <td style="width:24%; text-align:left; font-weight:bold;">
                               Gastos Médicos, p/ocupante:<br>
                               Gastos de Sepelio, p/ocupante:        
                             </td>
                             <td style="width:10%; text-align:right;">
                               
                             </td>
                             <td style="width:11%; text-align:left;">
                               $us&nbsp;1.000,00<br>
                               $us&nbsp;1.000,00
                             </td>
                           </tr>
                        </table> 
                    </td> 
                  </tr>
                  <tr>
                    <td style="width:100%; text-align:left; font-weight:bold; 
                      border-bottom: 1px solid #333; border-top: 1px solid #333; margin-bottom:5px;">
                      SECCIONES/COBERTURAS, CLAUSULAS Y ANEXOS
                    </td>
                  </tr>
                  <tr>
                    <td style="width:100%; border-bottom: 0px solid #333; padding-top:5px;">
                       <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:90%;">
                            <tr>
                              <td style="width:50%; font-size:100%; text-align: justify; padding-right:5px; 
                                  border:0px solid #333;" valign="top">
                                  <b>Coberturas</b><br>
                                  <b>Sección I</b>
                                  <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                      <tr>
                                        <td style="width:90%;">Responsabilidad Civil Extracontractual</td>
                                        <td style="width:10%; text-align:right;">Cubre</td>
                                      </tr>
                                      <tr>
                                        <td style="width:90%;">Responsabilidad Civil Consecuencial</td>
                                        <td style="width:10%; text-align:right;">Cubre</td>
                                      </tr>
                                  </table>
                                  <b>Sección II</b>
                                  <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                      <tr>
                                        <td style="width:90%;">Perdida Total por Robo al 100%</td>
                                        <td style="width:10%; text-align:right;">Cubre</td>
                                      </tr>
                                      <tr>
                                        <td style="width:90%;">Perdida Total por Robo al 80%</td>
                                        <td style="width:10%; text-align:right;">No Cubre</td>
                                      </tr>
                                  </table>
                                  <b>Sección III</b>
                                  <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                      <tr>
                                        <td style="width:90%;">Perdida Total por Accidente al 100%</td>
                                        <td style="width:10%; text-align:right;">Cubre</td>
                                      </tr>
                                  </table>
                                  <b>Sección IV</b>
                                  <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                      <tr>
                                        <td style="width:90%;">Daños Propios, VEH. LIVIANOS c/Franquicia Deducible</td>
                                        <td style="width:10%; text-align:right;">Cubre</td>
                                      </tr>
                                      <tr>
                                        <td style="width:90%;">$us 50.- hasta $us. 50.000.- de valor de casco</td>
                                        <td style="width:10%; text-align:right;"></td>
                                      </tr>
                                      <tr>
                                        <td style="width:90%;">y $us. 200.- con valor de casco mayor a $us. 50.000.-</td>
                                        <td style="width:10%; text-align:right;"></td>
                                      </tr>
                                      <tr>
                                        <td style="width:90%;">Daños Propios, VEH. PESADOS c/Franquicia Deducible</td>
                                        <td style="width:10%; text-align:right;">Cubre</td>
                                      </tr>
                                      <tr>
                                        <td style="width:90%;">$us 150.- hasta $us. 50.000.- de valor de casco </td>
                                        <td style="width:10%; text-align:right;"></td>
                                      </tr>
                                      <tr>
                                        <td style="width:90%;">y $us. 300.- con valor de casco mayor a $us. 50.000.-</td>
                                        <td style="width:10%; text-align:right;"></td>
                                      </tr>
                                  </table>
                                  <b>Sección V</b>
                                  <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                      <tr>
                                        <td style="width:90%;">Conmoción Civil, Huelgas, Daño Malicioso, Sabotaje,</td>
                                        <td style="width:10%; text-align:right;">Cubre</td>
                                      </tr>
                                      <tr>
                                        <td style="width:90%;">Vandalismo y Terrorismo, VEH. LIVIANOS c/Franquicia</td>
                                        <td style="width:10%; text-align:right;"></td>
                                      </tr>
                                      <tr>
                                        <td style="width:90%;">Deducible $us 50.- hasta $us. 50.000.- de valor de</td>
                                        <td style="width:10%; text-align:right;"></td>
                                      </tr>
                                      <tr>
                                        <td style="width:90%;">casco y $us. 200.- con valor de casco mayor</td>
                                        <td style="width:10%; text-align:right;"></td>
                                      </tr>
                                      <tr>
                                        <td style="width:90%;">a $us 50.000.-</td>
                                        <td style="width:10%; text-align:right;"></td>
                                      </tr>
                                      <tr>
                                        
                                        <td style="width:90%;">Conmoción Civil, Huelgas, Daño Malicioso, Sabotaje,</td>
                                        <td style="width:10%; text-align:right;">Cubre</td>
                                      </tr>
                                      <tr>
                                        
                                        <td style="width:90%;">Vandalismo y Terrorismo, VEH. PESADOS c/Franquicia</td>
                                        <td style="width:10%; text-align:right;"></td>
                                      </tr>
                                      <tr>
                                        
                                        <td style="width:90%;">Deducible $us 150.- hasta $us. 50.000.- de valor de</td>
                                        <td style="width:10%; text-align:right;"></td>
                                      </tr>
                                      <tr>
                                       
                                        <td style="width:90%;">casco y $us. 300.- con valor de casco mayor</td>
                                        <td style="width:10%; text-align:right;"></td>
                                      </tr>
                                      <tr>
                                       
                                        <td style="width:90%;">a $us 50.000.-</td>
                                        <td style="width:10%; text-align:right;"></td>
                                      </tr>
                                  </table>
                                  <b>Sección VI</b>
                                  <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                      <tr>
                                        
                                        <td style="width:90%;">Robo Parcial al 80% </td>
                                        <td style="width:10%; text-align:right;">Cubre</td>
                                      </tr>
                                  </table>
                                  <b>Sección VII</b>
                                  <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                      <tr>
                                        
                                        <td style="width:90%;">Muerte Accidental</td>
                                        <td style="width:10%; text-align:right;">Cubre</td>
                                      </tr>
                                      <tr>
                                        
                                        <td style="width:90%;">Invalidez Permanente (Total y Parcial)</td>
                                        <td style="width:10%; text-align:right;">Cubre</td>
                                      </tr>
                                      <tr>
                                        
                                        <td style="width:90%;">Gastos Médicos</td>
                                        <td style="width:10%; text-align:right;">Cubre</td>
                                      </tr>
                                  </table> 
                              </td>
                              <td style="width:50%; font-size:100%; text-align: justify; padding-left:5px; 
                                  border:0px solid #333;" valign="top">
                                  <b>Coberturas Adicionales</b>
                                  <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                      <tr>
                                        
                                        <td style="width:90%;">Anexo de Ausencia de Control para el Seguro de Automotores para empresas		
                      
              </td>
                                        <td style="width:10%; text-align:right;">Cubre</td>
                                      </tr>
                                      <tr>
                                        
                                        <td style="width:90%;">Anexo de Daños a Causa de Riesgos de la Naturaleza c/Franquicia deducible estipulada en la Sección IV y V</td>
                                        <td style="width:10%; text-align:right;" valign="top">Cubre</td>
                                      </tr>
                                      <tr>
                                        
                                        <td style="width:90%;">Clausula de Circulación en Vías No Habilitadas para el Tránsito Vehicular</td>
                                        <td style="width:10%; text-align:right;" valign="top">Cubre</td>
                                      </tr>
                                      <tr>
                                        
                                        <td style="width:90%;">Anexo de Extraterritorialidad (Por la vigencia de la Poliza)</td>
                                        <td style="width:10%; text-align:right;" valign="top">Cubre</td>
                                      </tr>
                                      <tr>
                                        
                                        <td style="width:90%;">Anexo para Accesorio de vehículos</td>
                                        <td style="width:10%; text-align:right;" valign="top">Cubre</td>
                                      </tr>
                                      <tr>
                                        
                                        <td style="width:90%;">Anexo de Gastos de Sepelio para Accidentes </td>
                                        <td style="width:10%; text-align:right;" valign="top"></td>
                                      </tr>
                                      <tr>
                                        
                                        <td style="width:90%;">personales</td>
                                        <td style="width:10%; text-align:right;" valign="top">Cubre</td>
                                      </tr>
                                      <tr>
                                        
                                        <td style="width:90%;">Clausula de Autoreemplazo (excluye motocicletas/quatracks y vehículos pesados)</td>
                                        <td style="width:10%; text-align:right;" valign="top">Cubre</td>
                                      </tr>
                                  </table>
                                  <b>Cláusulas y Anexos Adicionales</b>
                                  <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                      <tr>
                                        
                                        <td style="width:90%;">Cláusula de Rehabilitación Automática de la suma Asegurada</td>
                                        <td style="width:10%; text-align:right;">Cubre</td>
                                      </tr>
                                      <tr>
                                        
                                        <td style="width:90%;">Anexo para Restringir de Copia Legalizada</td>
                                        <td style="width:10%; text-align:right;">Cubre</td>
                                      </tr>
                                      <tr>
                                        
                                        <td style="width:90%;">Cláusula de Adelanto del 50% del Siniestro</td>
                                        <td style="width:10%; text-align:right;">Cubre</td>
                                      </tr>
                                      <tr>
                                        
                                        <td style="width:90%;">Cláusula de Elegibilidad de Ajustadores</td>
                                        <td style="width:10%; text-align:right;">Cubre</td>
                                      </tr>
                                      <tr>
                                        
                                        <td style="width:90%;">Anexo para Vehículos con antigüedad  mayor</td>
                                        <td style="width:10%; text-align:right;"></td>
                                      </tr>
                                      <tr>
                                        
                                        <td style="width:90%;">a 15 años y para vehículos transformados</td>
                                        <td style="width:10%; text-align:right;">Cubre</td>
                                      </tr>
                                      <tr>
                                        
                                        <td style="width:90%;">Anexo para Robo de Llantas, Partes, Equipos de Música y otras piezas</td>
                                        <td style="width:10%; text-align:right;">Cubre</td>
                                      </tr>
                                      <tr>
                                        
                                        <td style="width:90%;">Anexo de Elegibilidad de Talleres</td>
                                        <td style="width:10%; text-align:right;">Cubre</td>
                                      </tr>
                                      <tr>
                                        
                                        <td style="width:90%;">Cláusula de Ampliación de Aviso de Siniestro a Diez Días</td>
                                        <td style="width:10%; text-align:right;">Cubre</td>
                                      </tr>
                                      <tr>
                                        
                                        <td style="width:90%;">Anexo de Asistencia al Vehículo (excepto </td>
                                        <td style="width:10%; text-align:right;"></td>
                                      </tr>
                                      <tr>
                                        
                                        <td style="width:90%;"> motocicletas/quadratracks y vehículos pesados)</td>
                                        <td style="width:10%; text-align:right;">Cubre</td>
                                      </tr>
                                      <tr>
                                        
                                        <td style="width:90%;">Anexo de Beneficio de Asistencia Jurídica</td>
                                        <td style="width:10%; text-align:right;">Cubre</td>
                                      </tr>
                                      <tr>
                                        
                                        <td style="width:90%;">Cláusula de Rescisión de Contrato a Prorrata</td>
                                        <td style="width:10%; text-align:right;">Cubre</td>
                                      </tr>
                                      <tr>
                                        
                                        <td style="width:90%;">Cláusula de cobertura para Flete Aéreo ( hasta $us. 500.-)</td>
                                        <td style="width:10%; text-align:right;">Cubre</td>
                                      </tr>
                                      <tr>
                                        
                                        <td style="width:90%;">Anexo para Cubrir la Responsabilidad Civil a Ocupantes</td>
                                        <td style="width:10%; text-align:right;">Cubre</td>
                                      </tr>
                                      <tr>
                                        
                                        <td style="width:90%;">Cláusula de Valor Acordado</td>
                                        <td style="width:10%; text-align:right;">No Cubre</td>
                                      </tr>
                                  </table>
                              </td>
                            </tr>
                        </table>
                    </td>     
                  </tr>
                  <tr>
                    <td style="width:100%; text-align:left; font-weight:bold; 
                      border-bottom: 1px solid #333; border-top: 1px solid #333;">
                      EXCLUSIONES
                    </td>
                  </tr>
                  <tr>
                    <td style="width:100%; text-align:left; padding-top:5px; padding-bottom:5px;">
                       <b>Exclusiones Adicionales a las Condiciones Generales</b><br>
                       La cobertura de Extraterritorialidad excluye el autoreemplazo (si es que cuenta con esta cobertura) y la asistencia jurídica.
                    </td> 
                  </tr>
                  <tr>
                    <td style="width:100%; text-align:left; font-weight:bold; 
                      border-bottom: 1px solid #333; border-top: 1px solid #333;">
                      VIGENCIA/PRIMA/FORMA DE PAGO
                    </td>
                  </tr>
<?php
          if($row['forma_pago']=='CO'){
			  $prima_co = number_format($row['prima_total'],2,'.',',');
			  $prima_cr = '';
			  $prima_mensual = '';
		  }elseif($row['forma_pago']=='CR'){
			  $prima_co = '';
			  $prima_cr = number_format($row['prima_total'],2,'.',',');
			  $prima_mensual = $row['prima_total']/12;
		  }
?>   
                  <tr>
                    <td style="width:100%; padding-top:5px;">
                       <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 100%;">
                          <tr>
                            <td style="width:100%; text-align:left; padding-bottom:5px;" colspan="4">
                              <b>Vigencia:</b>&nbsp;<?=$row['plazo_dias'];?>, A PARTIR DE LAS 12:01 P.M. HORAS DEL DIA <?=strtoupper(get_date_format_au($row['fecha_iniv']));?>, HASTA LA MISMA HORA DEL <?=strtoupper(get_date_format_au($row['fecha_finv']));?>.
                            </td>
                          </tr>
                          <tr>
                            <td style="width:100%; text-align:left; font-weight:bold;" colspan="4">
                              Prima Anual
                            </td>
                          </tr>
                          <tr>
                            <td style="width:20%; text-align:left;"></td>
                            <td style="width:20%; text-align:left;">Al Contado: $us.&nbsp;<?=$prima_co;?></td>
                            <td style="width:25%; text-align:left;">Incluye impuestos de Ley</td>
                            <td style="width:35%;"></td>
                          </tr>
                          <tr>
                            <td style="width:20%; text-align:left;"></td>
                            <td style="width:20%; text-align:left;">Al Credito: $us.&nbsp;<?=$prima_cr;?></td>
                            <td style="width:25%; text-align:left;">Incluye impuestos de Ley y costos de los interés</td>
                            <td style="width:35%;"></td>
                          </tr>
                          <tr>
                            <td style="width:100%; text-align:left; font-weight:bold; padding-top:5px;" colspan="4">
                              Forma Pago:
                            </td>
                          </tr>
                          <tr>
                            <td style="width:20%; text-align:left;">CONTADO</td>
                            <td style="width:20%; text-align:left;">$US&nbsp;<?=$prima_co;?></td>
                            <td style="width:25%;"></td>
                            <td style="width:35%;"></td>
                          </tr>
                       </table>
                    </td>
                  </tr>
                  <tr>
                    <td style="width:100%; text-align:left; padding-top:5px; padding-bottom:5px;">
                      Le recordamos que para contar con Cobertura el pago de la prima debe ser efectuado en los plazos establecidos con Ud. Según el siguiente detalle
                    </td>
                  </tr>
                  <tr>
                    <td style="width:100%;">
                       <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 100%;">
                          <tr>
                            <td style="width:100%; text-align:left; font-weight:bold;" colspan="5">
                              La modalidad de renovación de esta Póliza es : "Anual con Renovacion Automatica"
                            </td>
                          </tr>
                          <tr>
                            <td style="width:20%; text-align:left; font-weight:bold; 
                              border-bottom: 1px solid #333; border-top: 1px solid #333;">
                              Forma Pago
                            </td>
                            <td style="width:20%; text-align:left; font-weight:bold; 
                              border-bottom: 1px solid #333; border-top: 1px solid #333;">
                              Moneda
                            </td>
                            <td style="width:20%; text-align:left; font-weight:bold; 
                              border-bottom: 1px solid #333; border-top: 1px solid #333;">
                              Monto
                            </td>
                            <td style="width:25%; text-align:left; font-weight:bold; 
                              border-bottom: 1px solid #333; border-top: 1px solid #333;">
                              Fecha de Vencimiento de Pago
                            </td>
                            <td style="width:15%; text-align:left; font-weight:bold; 
                              border-bottom: 1px solid #333; border-top: 1px solid #333;">&nbsp;
                              
                            </td>
                          </tr> 
                          <tr>
                            <td style="width:20%; text-align:left; font-weight:bold; padding-bottom:5px;">
                              Cuota Inicial
                            </td>
                            <td style="width:20%; text-align:left; font-weight:bold; padding-bottom:5px;">
                              $us.
                            </td>
                            <td style="width:20%; text-align:left; font-weight:bold; padding-bottom:5px;">&nbsp;
                              
                            </td>
                            <td style="width:25%; text-align:left; font-weight:bold; padding-bottom:5px;">&nbsp;
                              
                            </td>
                            <td style="width:15%; text-align:left; font-weight:bold; padding-bottom:5px;">&nbsp;
                              
                            </td>
                          </tr>
                          <tr>
                            <td style="width:20%; text-align:left; font-weight:bold; 
                              border-bottom: 1px solid #333; border-top: 1px solid #333;">
                              TOTAL
                            </td>
                            <td style="width:20%; text-align:left; font-weight:bold; 
                              border-bottom: 1px solid #333; border-top: 1px solid #333;">
                              $us
                            </td>
                            <td style="width:20%; text-align:left; font-weight:bold; 
                              border-bottom: 1px solid #333; border-top: 1px solid #333;">&nbsp;
                              
                            </td>
                            <td style="width:25%; text-align:left; font-weight:bold; 
                              border-bottom: 1px solid #333; border-top: 1px solid #333;">&nbsp;
                              
                            </td>
                            <td style="width:15%; text-align:left; font-weight:bold; 
                              border-bottom: 1px solid #333; border-top: 1px solid #333;">&nbsp;
                              
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
									s_au_em_cabecera
								where
									anulado = 1
										and id_cotizacion = '".$row['id_cotizacion']."';";
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
                  if((boolean)$rowDt['facultativo']===true){
                     if((boolean)$rowDt['vh_aprobado']===true){
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
                                  
                                  <td style="width:5%; text-align: center; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=strtoupper($rowDt['vh_aprobado']);?></td>
                                  <td style="width:5%; text-align: center; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=strtoupper($rowDt['vh_tasa_recargo']);?></td>
                                  <td style="width:7%; text-align: center; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=$rowDt['vh_porcentaje_recargo'];?> %</td>
                                  <td style="width:7%; text-align: center; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=$rowDt['vh_tasa_actual'];?> %</td>
                                  <td style="width:7%; text-align: center; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=$rowDt['vh_tasa_final'];?> %</td>
                                  <td style="width:69%; text-align: justify; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=$rowDt['motivo_facultativo'];?> |<br /><?=$rowDt['vh_observacion'];?></td>
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
                                <?=$rowDt['motivo_facultativo'];?>
                              </td>
                             </tr>
  
                        </table>
  <?php
                     }
                  }
  ?>    
              </div>   	
          </div>            
<?php
     if($type!=='MAIL' && ( (boolean)$row['emitir']===true || ( (boolean)$row['emitir']===false && (boolean)$row['garantia']===true && (boolean)$row['facultativo']===false ) ) && (boolean)$row['anulado']===false){
?>            
          <page><div style="page-break-before: always;">&nbsp;</div></page>
          
          <div style="width: 775px; border: 0px solid #FFFF00;">
              <table 
                  cellpadding="0" cellspacing="0" border="0" 
                  style="width: 100%; height: auto; font-size: 70%; font-family: Arial;">
                  <tr>
                    <td style="width:100%; text-align:left; padding-bottom:5px;">
                      En caso que el pago no sea efectuado en el plazo estipulado, la vigencia del presente seguro quedara suspendida y por tanto, cualquier siniestro ocurrido, cuando la póliza este impaga o en mora no será cubierto, según lo estipulado en el artículo 58 inciso d) de la .Ley de Seguros 1883. Asimismo cabe denotar que la Poliza sera anulada luego de pasados 60 dias del no pago de las primas establecidas.
                    </td>
                  </tr>
<?php
             if((boolean)$row['garantia']===true){
?>                       
                  <tr>
                    <td style="width:100%; text-align:left; font-weight:bold; 
                      border-bottom: 1px solid #333; border-top: 1px solid #333;">
                      SUBROGACIONES
                    </td>
                  </tr>
                  <tr>
                    <td style="width:100%; text-align:left;">
                     Subrogatario:<br>
                     Monto a Subrogar:
                    </td>
                  </tr>
<?php
			 }
?>                  
                  <tr>
                    <td style="width:100%; text-align:left; font-weight:bold; 
                      border-bottom: 1px solid #333; border-top: 1px solid #333;">
                      BENEFICIOS GRATUITOS
                    </td>
                  </tr>
                  <tr>
                    <td style="width:100%; text-align:left; padding-top:5px; padding-bottom:5px;">
                     *Los accesorios instalados al momento de asegurar el vehículo quedan automáticamente cubiertos sin costo adicional hasta los límites establecidos en el Anexo para Robo de LLantas, Equipos de Música y Otras Piezas. En caso que el accesorio supere los límites establecidos, el Asegurado puede incluir mediante anexo y pago de prima adicional.<br>
                    Los accesorios que se instalen con posterioridad deben incluirse mediante anexo y pago de prima adicional.<br>              *En caso de anulación de la Póliza la prima será devuelta a prorrata. (descontando los impuestos de Ley)<br><br>
                    <b>Extraterritorialidad</b><br><br>
                    Se otorga la cobertura de Extraterritorialidad anualmente cubriendo al vehículo asegurado mientras se encuentra fuera del Territorio de Bolivia máximo 60 días por cada viaje y de acuerdo a condiciones del anexo respectivo.
                    <br><br>
                    <b>Asistencia al Vehículo dentro del Territorio Nacional (No aplica para motocicletas/quadratracks y vehículos pesados)</b><br><br>
                    Bisa Seguros en caso de emergencia le otorga Asistencia al Vehículo y a los ocupantes del mismo las 24 horas del días y los 365 días del año llamando al Numero Gratuito 800-10-6060, de acuerdo a las condiciones y términos establecidos en el Anexo respectivo.<br><br>
                   <b>Asistencia Jurídica</b><br><br>
                   En caso de siniestro Bisa Seguros y Reaseguros le proporciona un servicio de asistencia jurídica solo dentro de territorio Boliviano, que incluye:<br>
  *Asistencia a audiencias  de tránsito o ante otras autoridades que tengan jurisdicción en el  accidente<br>
  *Preparación de memoriales<br>
  *Presentación de fianzas judiciales hasta el límite del valor asegurado de responsabilidad civil<br>
  *Asistencia a audiencias de conciliación
                    </td>
                  </tr>
                  <tr>
                    <td style="width:100%; text-align:left; font-weight:bold; 
                      border-bottom: 1px solid #333; border-top: 1px solid #333;">
                      ACLARACIONES
                    </td>
                  </tr>
                  <tr>
                    <td style="width:100%; text-align:left; padding-top:5px; padding-bottom:5px;">
                     <b>Desgaste de Llantas:</b> Es responsabilidad del Asegurado el correcto reemplazo de las llantas del vehículo Asegurado, ya que en caso de presentarse un siniestro, y se determine que el mal estado de las llantas (desgaste superior al tiempo de vida útil de las mismas) propicio el siniestro, este no estará cubierto por ser considerado como una situación de Agravación de Riesgo.<br><br>
                     <b>Anexo para restringir el requisito de presentación de Copia Legalizada de la denuncia a Transito:</b> En casos en que el monto del siniestro, sea inferior a $us. 500.-; excepto para casos de responsabilidad civil y perdida total, aplicable solamente dentro territorio boliviano, en caso de que el asegurado solicite extraterritorialidad esta clausula no aplica, siendo que el asegurado deberá presentar todos los informes y copias del siniestro de las autoridades extranjeras en caso de tener un siniestro fuera del país.<br><br>
                     <b>Accesorios</b><br><br>
                     Los accesorios que se coloquen con posterioridad deben incluirse mediante anexo y pago de prima adicional
                     <br><br>
<?php
                 if((boolean)$row['garantia']===true){
?>                     
                     <b>Vigencia</b><br><br>
                     Se aclara que esta póliza no se renovará posteriormente a la cancelación total de la operación crediticia del asegurado con el contratante, de acuerdo al monto subrogado y declarado en la póliza. Se aclara que la vigencia de la póliza podrá terminar en forma anticipada, cuando el Asegurado realice el pago anticipado del monto total de su operación crediticia adeudada al Contratante. Sin embargo, si la prima fue pagada al contado, la póliza se mantendrá vigente hasta su finalización.
                     <br><br>
<?php
				 }
?>                     
                     <b>Monto Indemnizable en caso de Siniestro</b><br><br>
                     Se acuerda y establece, que para definir el monto indemnizable en caso de siniestro por Pérdida Total (sea por Robo o Accidente) ocurrido durante el primer año de vigencia, de un vehículo adquirido del concesionario representante de marca y asegurado como "0" KM, se deducirá el valor fiscal  del 13% (correspondiente a la factura de compra) y se aplicara una depreciación del 10% sobre el valor asegurable total. 
<?php
                if((boolean)$row['garantia']===true){
?>                     
                     Si el monto indemnizable, después de esta deducción, es menor al saldo insoluto adeudado por el Asegurado al Subrogatario, la Compañía indemnizará el saldo insoluto al Subrogatario.
<?php
				}
?>                     
                     <br>
<?php
               if((boolean)$row['garantia']===true){ 
?>                     
                     En caso de perdida total por robo o accidente, la aseguradora no aplicara infraseguro<br><br>
<?php
			   }else{
?>                   
                    <br>
<?php
			   }
?>                      
                     <b>Depreciación</b><br><br>
                     Se acuerda y establece, que la depreciación se aplicara en un 10% para los primeros 5 años, manteniendose constante una vez concluido este periodo.         
                    </td>
                  </tr>
                  <tr>
                    <td style="width:100%; text-align:left; font-weight:bold; 
                      border-bottom: 1px solid #333; border-top: 1px solid #333;">
                      CONDICIONES ESPECIALES:
                    </td>
                  </tr>
                  <tr>
                    <td style="width:100%; text-align:left; padding-top:5px; padding-bottom:5px;">
                     El Asegurado autoriza a la Compañía de Seguros a enviar el reporte a la Central de Riesgos del Mercado de Seguros acorde a las Normativas Reglamentarias de la Autoridad de Fiscalización y Control de Pensiones y Seguros - APS.          
                    </td>
                  </tr>
                  <tr>
                    <td style="width:100%; text-align:left; font-weight:bold; 
                      border-bottom: 1px solid #333; border-top: 1px solid #333;">
                      NOTAS ESPECIALES:
                    </td>
                  </tr>
                  <tr>
                    <td style="width:100%; text-align:left; padding-top:5px; padding-bottom:5px;">
                     * En caso de siniestro se otorgara cobertura cuando la Licencia de Conducir no esté vigente hasta 60 días
                     después de la fecha de su vencimiento.<br>                   
                     * No es requisito obligatorio presentar a la Compañía la denuncia a Diprove en caso de robo de partes y 
                     piezas.<br>                                         
                     * Alcoholemia permitida hasta 0,5 gramos por Litro de Sangre, de acuerdo al Decreto Supremo Nº 1347, 
                     Articulo 14º.<br>
                     * La cobertura de Robo Parcial al 80% se limita a cubrir el robo de 1 llanta con aro hasta $us.700.-, 
                     incluida la llanta de auxilio y sus accesorios. 1 Equipo de música y accesorios hasta $us. 350.-,  una 
                     vez al año. Se excluye el robo de radios o equipos de comunicación, equipos similares y sus accesorios.                   <br>
                     Para la ciudad de Santa Cruz se excluye el robo de la llanta de auxilio y sus accesorios.<br>                   * En casos de accidentes de tránsito en la ciudad de Santa Cruz el sistema de seguridad pasiva (bolsas de
                      aire mas accesorios) se cubre hasta $us. 1.500 por evento<br>
                     * Autoreemplazo sujeto a términos y condiciones según Cláusula adjunta. Excepto motocicletas/quadratracks
                     y vehículos pesados (camiones, tracto-camiones, acoplados).<br>
                     * La cobertura de robo parcial al 80% para motocicletas/quadratracks queda limitada a 1 evento por 
                     durante la vigencia de la póliza.<br>
                     * La cobertura de Pérdida Total  por Robo para motocicletas/quadratracks queda limitada al 80%.<br>                   <b>SEGURIDAD PREVENTIVA</b><br>
                     *La compañía podrá realizar campañas de seguridad preventiva durante la vigencia de la presente póliza, 
                     estableciendo condiciones específicas para tal efecto las cuales serán de cumplimiento obligatorio por 
                     parte del asegurado.          
                    </td>
                  </tr>
                  <tr>
                    <td style="width:100%; text-align:left; font-weight:bold; 
                      border-bottom: 1px solid #333; border-top: 1px solid #333;">
                      OBSERVACIONES:
                    </td>
                  </tr>
                  <tr>
                    <td style="width:100%; text-align:left; padding-top:5px; padding-bottom:5px;">
                     Las presentes Condiciones Particulares prevalecen sobre las Condiciones Generales preimpresas adjuntas,  debiendo aplicarse las últimas en ausencia de disposición específica de las presentes Condiciones Particulares.        
                    </td>
                  </tr>
              </table>
              <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 65%; font-family: Arial;">
               <tr>
                <td style="width:30%;">&nbsp;</td>
                <td style="width:40%; text-align:center;">
                  BISA SEGUROS Y REASEGUROS S.A.
                </td>
                <td style="width:30%;">&nbsp;</td>
               </tr>
               <tr>
                <td style="width:30%;">&nbsp;</td>
                <td style="width:40%; border-bottom: 1px solid #333; text-align:center;">
                  <img src="<?=$url;?>img/firmas_bisa.png" height="115"/>
                </td>
                <td style="width:30%;">&nbsp;</td>
               </tr>
               <tr>
                <td style="width:30%;">&nbsp;</td>
                <td style="width:40%; text-align:center;">
                  FIRMAS AUTORIZADAS
                </td>
                <td style="width:30%;">&nbsp;</td>
               </tr>
            </table>            
          </div>
          
          <page><div style="page-break-before: always;">&nbsp;</div></page>
          
          <div style="width: 775px; border: 0px solid #FFFF00;">
              <table 
                    cellpadding="0" cellspacing="0" border="0" 
                    style="width: 100%; height: auto; font-size: 75%; font-family: Arial;">                   
                    <tr>
                      <td style="width:50%; font-size:100%; text-align: justify; padding-right:5px; 
                      border:0px solid #333;" valign="top">
<?php
               if($rowDt['categoria_vh']=='L'){ 
?>                       
                        <div style="text-align: center; font-weight:bold;">
                           CLAUSULA DE AUTO REEMPLAZO<br>
                           Código ASFI: 109-910502-2007 12 311-2046<br>
                           R.A. 436/2010
                        </div>
                        <b>PÓLIZA Nro.:</b>&nbsp;<?=$poliza;?>	
                        <br><br>
                        <b>LUGAR Y FECHA:</b>&nbsp;<?=strtoupper(get_date_format_au($row['fecha_emision']));?>
                        <br><br>
                        De acuerdo a la prima adicional acordada, queda entendido y convenido mediante la presente Cláusula que en caso de siniestro y si la reparación del vehículo excede 10 días calendario, la Compañía proporcionará al Asegurado un vehículo compacto por un plazo máximo de 10 días calendario en exceso de los primeros 10 días calendario de reparación, siempre que se cumplan las siguientes condiciones:
                        <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;
                           padding-bottom:3px;">
                          <tr>
                            <td style="width:2%;" valign="top">&bull;</td>
                            <td style="width:98%;">Que el siniestro se encuentre cubierto por la póliza principal.</td>
                          </tr>
                          <tr>
                            <td style="width:2%;" valign="top">&bull;</td>
                            <td style="width:98%;">Que se cuente con todos los repuestos necesarios para iniciar la 
                            reparación.</td>
                          </tr>
                          <tr>
                            <td style="width:2%;" valign="top">&bull;</td>
                            <td style="width:98%;">Que no se haya concluido la reparación del vehículo asegurado.</td>
                          </tr>
                          <tr>
                            <td style="width:2%;" valign="top">&bull;</td>
                            <td style="width:98%;">El Asegurado tiene la obligación de cumplir con las obligaciones 
                            establecidas en el Contrato con la Empresa de Alquiler del vehículo otorgado. </td>
                          </tr>
                        </table>
                        Todos los demás términos y condiciones de la Póliza se mantienen sin variación alguna.
                        <table 
                            cellpadding="0" cellspacing="0" border="0" 
                            style="width: 100%; height: auto; font-size: 100%; font-family: Arial; 
                            padding-top:8px; padding-bottom:8px;">
                           <tr>
                            <td style="width:20%;">&nbsp;</td>
                            <td style="width:60%; text-align:center;">
                              BISA SEGUROS Y REASEGUROS S.A.
                            </td>
                            <td style="width:20%;">&nbsp;</td>
                           </tr>
                           <tr>
                            <td style="width:20%;">&nbsp;</td>
                            <td style="width:60%; border-bottom: 1px solid #333;">
                              <img src="<?=$url;?>img/firmas_bisa.png" height="90"/>
                            </td>
                            <td style="width:20%;">&nbsp;</td>
                           </tr>
                           <tr>
                            <td style="width:20%;">&nbsp;</td>
                            <td style="width:60%; text-align:center;">
                              FIRMAS AUTORIZADAS
                            </td>
                            <td style="width:20%;">&nbsp;</td>
                           </tr>
                        </table>
<?php
			   }
?>                        
                        <div style="text-align: center; font-weight:bold;">
                           ANEXO PARA EL ROBO DE LLANTAS, PARTES, EQUIPOS DE MÚSICA Y OTRAS PIEZAS<br>
                           CÓDIGOAPS:109-910502-2007 12 311 2052<br>
                           R.A. 136/11<br>
                        </div>
                        <b>PÓLIZA Nro.:</b>&nbsp;<?=$poliza;?>	
                        <br><br>
                        <b>LUGAR Y FECHA:</b>&nbsp;<?=strtoupper(get_date_format_au($row['fecha_emision']));?>
                        <br><br>
                        Queda entendido y convenido mediante el presente Anexo, que no obstante lo estipulado en contrario
                        en la Póliza a que este Anexo se refiere, la responsabilidad de la Compañía por el robo de las 
                        piezas detalladas abajo, se limita de acuerdo a lo siguiente:
                        <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;
                           padding-bottom:3px;">
                          <tr>
                            <td style="width:75%; font-weight:bold; text-align:center; border-left:1px solid #333;
                              border-top:1px solid #333; border-right:1px solid #333; border-bottom: 1px solid #333;">
                              Descripción de pieza cubierta:
                            </td>
                            <td style="width:25%; font-weight:bold; text-align:center;
                              border-top:1px solid #333; border-right:1px solid #333; border-bottom: 1px solid #333;">
                              Hasta un máximo como límite total anual:
                            </td>
                          </tr>
                          <tr>
                            <td style="width:75%; border-left:1px solid #333; border-bottom: 1px solid #333; 
                              border-right:1px solid #333; ">
                              Llanta con aro y/o accesorios
                            </td>
                            <td style="width:25%; text-align:center; border-right:1px solid #333;
                              border-bottom: 1px solid #333;">
                              $us. 700.00
                            </td>
                          </tr>
                          <tr>
                            <td style="width:75%; border-left:1px solid #333; border-bottom: 1px solid #333; 
                              border-right:1px solid #333;">
                              Llanta de auxilio con aro y/ accesorios
                            </td>
                            <td style="width:25%; text-align:center; border-right:1px solid #333;
                              border-bottom:1px solid #333;">
                              $us. 700.00
                            </td>
                          </tr>
                          <tr>
                            <td style="width:75%; border-left:1px solid #333; border-bottom: 1px solid #333; 
                              border-right:1px solid #333;">Equipo de música y/o sus accesorios como ser: amplificador, 
                              ecualizador, parlantes de todo tipo, CD, MP3, DVD, y otros (excluye el robo del control 
                              remoto)
                            </td>
                            <td style="width:25%; text-align:center; border-right:1px solid #333;
                              border-bottom:1px solid #333;" valign="top">
                              $us. 350.00
                            </td>
                          </tr>
                          <tr>
                            <td style="width:75%; border-left:1px solid #333; border-bottom: 1px solid #333; 
                              border-right:1px solid #333;">
                              Mascarilla desmontable del equipo de música
                            </td>
                            <td style="width:25%; text-align:center; border-right:1px solid #333;
                              border-bottom:1px solid #333;" valign="top">
                              Sin cobertura
                            </td>
                          </tr>
                          <tr>
                            <td style="width:75%; border-left:1px solid #333; border-bottom: 1px solid #333; 
                              border-right:1px solid #333;">
                              Equipo integrado de TV-pantalla-video-DVD
                            </td>
                            <td style="width:25%; text-align:center; border-right:1px solid #333;
                              border-bottom:1px solid #333;" valign="top">
                              Sin cobertura
                            </td>
                          </tr>
                          <tr>
                            <td style="width:75%; border-left:1px solid #333; border-bottom: 1px solid #333; 
                              border-right:1px solid #333;">
                              Sistema de navegación GPS
                            </td>
                            <td style="width:25%; text-align:center; border-right:1px solid #333;
                              border-bottom:1px solid #333;" valign="top">
                              Sin cobertura
                            </td>
                          </tr>
                          <tr>
                            <td style="width:75%; border-left:1px solid #333; border-bottom: 1px solid #333; 
                              border-right:1px solid #333;">
                              Juego de halógenos y/o rompenieblas (instalado como accesorio)
                            </td>
                            <td style="width:25%; text-align:center; border-right:1px solid #333;
                              border-bottom:1px solid #333;" valign="top">
                              Sin cobertura
                            </td>
                          </tr>
                          <tr>
                            <td style="width:75%; border-left:1px solid #333; border-bottom: 1px solid #333; 
                              border-right:1px solid #333;">
                              Deflectores de viento
                            </td>
                            <td style="width:25%; text-align:center; border-right:1px solid #333;
                              border-bottom:1px solid #333;" valign="top">
                              Sin cobertura
                            </td>
                          </tr>
                          <tr>
                            <td style="width:75%; border-left:1px solid #333; border-bottom: 1px solid #333; 
                              border-right:1px solid #333;">
                              Cola de pato
                            </td>
                            <td style="width:25%; text-align:center; border-right:1px solid #333;
                              border-bottom:1px solid #333;" valign="top">
                              Sin cobertura
                            </td>
                          </tr>
                        </table>
                        Los equipos o componentes que normalmente no forman parte de un vehículo automotor, como ser, pero
                        no limitado a: computadoras, equipos de radio comunicación, equipos científicos, equipos de 
                        servicio de cualquier naturaleza, teléfonos celulares, deberán ser expresamente declarados y 
                        valorados, sujetos al pago de una prima adicional, caso contrario, quedan expresamente excluidos 
                        de las coberturas de las pólizas.<br> 
    
                        Los equipos arriba detallados para tener cobertura, deberán encontrase sujetos o fijados al 
                        vehículo.<br>
                        Todos los demás términos y condiciones quedan sin alteración alguna.
                        <table 
                            cellpadding="0" cellspacing="0" border="0" 
                            style="width: 100%; height: auto; font-size: 100%; font-family: Arial; 
                            padding-top:8px; padding-bottom:8px;">
                           <tr>
                            <td style="width:20%;">&nbsp;</td>
                            <td style="width:60%; text-align:center;">
                              BISA SEGUROS Y REASEGUROS S.A.
                            </td>
                            <td style="width:20%;">&nbsp;</td>
                           </tr>
                           <tr>
                            <td style="width:20%;">&nbsp;</td>
                            <td style="width:60%; border-bottom: 1px solid #333;">
                               <img src="<?=$url;?>img/firmas_bisa.png" height="90"/>
                            </td>
                            <td style="width:20%;">&nbsp;</td>
                           </tr>
                           <tr>
                            <td style="width:20%;">&nbsp;</td>
                            <td style="width:60%; text-align:center;">
                              FIRMAS AUTORIZADAS
                            </td>
                            <td style="width:20%;">&nbsp;</td>
                           </tr>
                        </table>
    
                      </td>
                      <td style="width:50%; font-size:100%; text-align: justify; padding-left:5px; 
                        border:0px solid #333;" valign="top">
                        <div style="text-align: right; border:0px solid #FFFF00; margin-bottom:10px;">
                          <img src="<?=$url;?>images/<?=$row['logo_cia'];?>" height="60"/> 
                        </div>
                        <div style="text-align: center; font-weight:bold;">
                           ANEXO PARA VEHÍCULOS CON ANTIGÜEDAD MAYOR A 15 AÑOS Y PARA VEHÍCULOS TRANSFORMADOS 
                           (TRANSFORMERS)<br><br>
    
                           CÓDIGO SPVS No. 109-910502-2007 12 311 - 2015<br>
                           R.A. 014/08
                        </div>
                        <b>PÓLIZA Nro.:</b>&nbsp;<?=$poliza;?>	
                        <br><br>
                        <b>LUGAR Y FECHA:</b>&nbsp;<?=strtoupper(get_date_format_au($row['fecha_emision']));?>	
                        <br><br>
                        Se acuerda y establece mediante el presente Anexo, que en caso de siniestro que se encuentre 
                        cubierto por la póliza, laCompañía tendrá el derecho de reparar, reponer o indemnizar en dinero 
                        por los daños que se hubieran producido.
                        <br><br> 
                        Queda asimismo entendido y convenido que en caso de no encontrarse en el mercado local partes y/o 
                        piezas que fueran necesarias para reparación, la responsabilidad de obtener los repuestos será del
                        Asegurado, el cual además deberá obtener el consentimiento previo de Bisa Seguros y Reaseguros S. 
                        A. sobre el precio de los mismos.
                        <br><br> 
                        En ningún caso Bisa Seguros y Reaseguros S. A. pagará por la reparación o reposición de piezas un 
                        monto mayor al que tendría que pagar por la reparación o reposición de piezas de un vehículo 
                        similar, cuyos repuestos puedan encontrarse en el mercado.
                        <br><br>
                        La Compañía se reserva el derecho de reemplazar las piezas robadas o dañadas con piezas que no 
                        sean  originales o genuinas las mismas que podrán ser obtenidas en un mercado alternativo o 
                        seminuevas o que sean fabricadas localmente. 
                        <br><br>
                        Todos los demás términos y condiciones de la póliza quedan sin alteración alguna.
                        <table 
                            cellpadding="0" cellspacing="0" border="0" 
                            style="width: 100%; height: auto; font-size: 100%; font-family: Arial; 
                            padding-top:8px; padding-bottom:8px;">
                           <tr>
                            <td style="width:20%;">&nbsp;</td>
                            <td style="width:60%; text-align:center;">
                              BISA SEGUROS Y REASEGUROS S.A.
                            </td>
                            <td style="width:20%;">&nbsp;</td>
                           </tr>
                           <tr>
                            <td style="width:20%;">&nbsp;</td>
                            <td style="width:60%; border-bottom: 1px solid #333;">
                              <img src="<?=$url;?>img/firmas_bisa.png" height="90"/>
                            </td>
                            <td style="width:20%;">&nbsp;</td>
                           </tr>
                           <tr>
                            <td style="width:20%;">&nbsp;</td>
                            <td style="width:60%; text-align:center;">
                              FIRMAS AUTORIZADAS
                            </td>
                            <td style="width:20%;">&nbsp;</td>
                           </tr>
                        </table>
                        <div style="text-align: center; font-weight:bold;">
                           CLAUSULA DE COBERTURA PARA FLETE AÉREO
                           <br><br>
                           CÓDIGO SPVS No. 109-910502-2007 12 311 - 2017<br>
                           R.A. 014/08
                        </div>
                        <b>PÓLIZA Nro.:</b>&nbsp;<?=$poliza;?>	
                        <br><br>
                        <b>LUGAR Y FECHA:</b>&nbsp;<?=strtoupper(get_date_format_au($row['fecha_emision']));?>	
                        <br><br>
                        Queda entendido y convenido que, en adición a los términos, exclusiones, Cláusulas y Condiciones 
                        contenidos en la Póliza o a ella anexados, este seguro se extiende a cubrir los gastos 
                        adicionales por concepto de flete aéreo para la importación de partes o piezas, siempre y cuando 
                        dichos gastos se hayan generado en conexión con cualquier pérdida de o daño indemnizable a los 
                        objetos asegurados bajo esta Póliza.
                        <br><br>
                        Deducible:   20% de los gastos extras indemnizables, mínimo para cada evento.
                        <table 
                            cellpadding="0" cellspacing="0" border="0" 
                            style="width: 100%; height: auto; font-size: 100%; font-family: Arial; 
                            padding-top:8px; padding-bottom:8px;">
                           <tr>
                            <td style="width:20%;">&nbsp;</td>
                            <td style="width:60%; text-align:center;">
                              BISA SEGUROS Y REASEGUROS S.A.
                            </td>
                            <td style="width:20%;">&nbsp;</td>
                           </tr>
                           <tr>
                            <td style="width:20%;">&nbsp;</td>
                            <td style="width:60%; border-bottom: 1px solid #333;">
                              <img src="<?=$url;?>img/firmas_bisa.png" height="90"/>
                            </td>
                            <td style="width:20%;">&nbsp;</td>
                           </tr>
                           <tr>
                            <td style="width:20%;">&nbsp;</td>
                            <td style="width:60%; text-align:center;">
                              FIRMAS AUTORIZADAS
                            </td>
                            <td style="width:20%;">&nbsp;</td>
                           </tr>
                        </table>
                      </td>
                    </tr>  
                </table>
          </div> 
<?php
	 }
	      if($num_titulares <> $j)
		     echo "<page><div style='page-break-before: always;'>&nbsp;</div></page>";
	   
	 
	 }
	if ($fac === TRUE) {
        $url .= 'index.php?ms='.md5('MS_AU').'&page='.md5('P_fac').'&ide='.base64_encode($row['id_emision']).'';
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
?>            
      </div>
   </div> 

<?php
	$html = ob_get_clean();
	return $html;
}

function get_date_format_au($fecha){
	$date = date_create($fecha);
	
	$day = date_format($date, 'd');
	$month = date_format($date, 'F');
	$year = date_format($date, 'Y');
	
	return $day.' de '.get_month_espanol($month).' de '.$year;
}

function get_month_espanol($month){
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

function plaza_au($sucursal){
  	switch ($sucursal) {
		case 'La Paz':
			return 1;
			break;
		case 'Santa Cruz':
			return 2;
			break;
		case 'Cochabamba':
			return 3;
			break;
		case 'Chuquisaca':
			return 4;
			break;
		case 'Tarija':
			return 5;
			break;
		case 'Oruro':
			return 6;
			break;
		case 'Potosí':
			return 7;
			break;
		case 'Beni':
			return 8;
			break;
		case 'Pando':
			return 9;
			break;
	}
}
?>