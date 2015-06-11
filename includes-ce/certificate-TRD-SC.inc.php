<?php
function trd_sc_certificate($link, $row, $rsDt, $url, $implant, $fac, $reason = '') {
	
	
	ob_start();
?>
   <div id="container-c" style="width: 785px; height: auto; 
    border: 0px solid #0081C2; padding: 5px;">
	  <div id="main-c" style="width: 775px; font-weight: normal; font-size: 12px; 
      font-family: Arial, Helvetica, sans-serif; color: #000000;">
<?php
   $j = 1;
   $num_titulares=$rsDt->num_rows;
			
   while($rowDt = $rsDt->fetch_array(MYSQLI_ASSOC)){
	    if($row['tipo_cliente']==='Juridico'){
		   $razon_social = $row['razon_social'];
		   $actividad = $row['actividad'];
		   $nit_ci = $row['ci_nit'];
		   $cliente = $razon_social;
		   
		   $ap_paterno = '';
		   $ap_materno = '';
		   $nombre = '';
		   $direccion_domicilio = '';
		   $direccion_laboral = $row['direccion_laboral'];
		   $fono_celular = $row['telefono_celular'];
		   $fono_domicilio = '';
		   $fono_oficina = $row['telefono_oficina'];
		   $nit_ci = $row['ci_nit'];
		   $email = $row['email'];
		   
	   }elseif($row['tipo_cliente']==='Natural'){
		   $ap_paterno = $row['paterno'];
		   $ap_materno = $row['materno'];
		   $nombre = $row['nombre'];
		   $direccion_domicilio = $row['direccion_domicilio'];
		   $direccion_laboral = $row['direccion_laboral'];
		   $fono_celular = $row['telefono_celular'];
		   $fono_domicilio = $row['telefono_domicilio'];
		   $fono_oficina = $row['telefono_oficina'];
		   $nit_ci = $row['ci_nit'];
		   $email = $row['email'];
		   $cliente = $row['nombre'].' '.$row['paterno'].' '.$row['materno'];
		   
		   $razon_social = '';
		   $actividad = '';
		   
	   }
?>
        <div style="width: 775px; border: 0px solid #FFFF00; text-align:center;">
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-family: Arial;">
                <tr>
                  <td style="width:100%; text-align:right;">
                     <img src="<?=$url;?>images/<?=$row['logo_cia'];?>" height="60"/>
                  </td> 
                </tr>
                <tr>
                  <td style="width:100%; font-weight:bold; text-align:center; font-size: 80%;">
                     SOLICITUD DE SEGURO DE TODO RIESGO DAÑOS A LA PROPIEDAD<br>
                     CODIGO SPVS No.: 109-910101-2006 07 252 - 3001<br>
                     R.A. 740/06<br>

                  </td> 
                </tr>
            </table>     
        </div>
        <br/>
        
        <div style="width: 775px; border: 0px solid #FFFF00;">
			<span style="font-weight:bold; font-size:80%;">
          INFORMACION GENERAL:</span> 
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-family: Arial; 
                padding-top:4px; padding-bottom:3px;">
                <tr> 
                  <td style="width:100%; padding-bottom:4px;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 100%;">
                        <tr>
                          <td style="width:25%;">Nombre o Razón Social del Solicitante:  </td>
                          <td style="border-bottom: 1px solid #333; width:75%;">
                              &nbsp;<?=$razon_social;?>
                          </td>
                        </tr>
                     </table>
                  </td>      
                </tr>
                <tr>
                  <td style="width:100%; padding-bottom:4px;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                        <tr>
                          <td style="width:18%;">Nombre Persona  Particular:</td>
                          <td style="border-bottom: 1px solid #333; width:26%; text-align:center;">
                              &nbsp;<?=$ap_paterno;?>
                          </td>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="border-bottom: 1px solid #333; width:26%; text-align:center;">
                              &nbsp;<?=$ap_materno;?>
                          </td>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="border-bottom: 1px solid #333; width:26%; text-align:center;">
                              &nbsp;<?=$nombre;?>
                          </td>
                        </tr>
                        <tr>
                          <td style="width:18%;"></td>
                          <td style="width:26%; text-align:center;">
                              APELLIDO PATERNO      
                          </td>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:26%; text-align:center;">
                              APELLIDO MATERNO                             
                          </td>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:26%; text-align:center;">
                              NOMBRES
                          </td>
                        </tr>
                      </table>                                  
                  </td>
                </tr>
                <tr> 
                  <td style="width:100%; padding-bottom:4px;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 100%;">
                        <tr>
                          <td style="width:11%;">Domicilio Legal:   </td>
                          <td style="border-bottom: 1px solid #333; width:89%;">
                              &nbsp;<?=$direccion_domicilio;?>
                          </td>
                        </tr>
                     </table>
                  </td>      
                </tr>   
                <tr>
                  <td style="width:100%; padding-bottom:4px;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                        <tr>
                          <td style="width:15%;">Dirección de Cobranza: </td>
                          <td style="width:43%; border-bottom: 1px solid #333;">
                              &nbsp;<?=$direccion_laboral;?>
                          </td>
                          <td style="width:6%;">NIT/C.I.</td>
                          <td style="width:36%; border-bottom: 1px solid #333;">
                              &nbsp;<?=$nit_ci;?>
                          </td>
                        </tr>
                      </table> 
                  </td>              
                </tr>
                <tr>
                  <td style="width:100%; padding-bottom:4px;">
                      <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                         <tr>
                           <td style="width: 7%;">Teléfonos: </td>
                           <td style="width: 67%; border-bottom: 1px solid #333;">
                             &nbsp;<?=$fono_domicilio.' '.$fono_oficina;?>
                           </td>
                           <td style="width: 6%;">Celular: </td>
                           <td style="width: 20%; border-bottom: 1px solid #333;">
                             &nbsp;<?=$fono_celular;?>
                           </td>
                         </tr>
                      </table> 
                  </td> 
                </tr>
                <tr>
                  <td style="width:100%; padding-bottom:4px;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                         <tr>
                          <td style="width:5%;">Email: </td>
                          <td style="width:27%; border-bottom: 1px solid #333;">
                             &nbsp;<?=$email;?>
                          </td>
                           <td style="width:20%;">Actividad y/o Giro del Negocio: </td>
                          <td style="width:48%; border-bottom: 1px solid #333;">
                            &nbsp;<?=$actividad;?>
                          </td> 
                         </tr> 
                      </table>
                  </td>     
                </tr> 
                <tr>
                  <td style="width:100%; padding-bottom:4px;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                         <tr>
                          <td style="width:20%;">Ubicación(es) de (los) Riesgo(s): </td>
                          <td style="width:80%; border-bottom: 1px solid #333;">
                             &nbsp;<?=$rowDt['localidad'].' '.$rowDt['zona'].' '.$rowDt['direccion'];?>
                          </td>
                         </tr>
                         <tr>
                           <td style="width:100%; border-bottom: 1px solid #333;" colspan="2">&nbsp;</td>
                         </tr> 
                    </table>
                  </td>     
                </tr> 
            </table>
            <br>
            <span style="font-size:80%;">INFORMACIÓN SOBRE BIENES PATRIMONIALES Y VALORES DE PROPIEDAD DEL SOLICITANTE Y BIENES DE TERCEROS BAJO CUSTODIA CONTROL Y RESPONSABILIDAD DEL ASEGURADO. (Esta sección debe ser completada considerando todas las ubicaciones que se quieran asegurar.</span>
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-family: Arial; 
                padding-top:2px; padding-bottom:3px;">
                <tr>
                  <td rowspan="2" style="width:55%; text-align:center; font-weight:bold; border-left: 1px solid #333;
                    border-top: 1px solid #333; border-right: 1px solid #333;">
                    Clasificación de Bienes y valores
                  </td>
                  <td colspan="3" style="width:45%; text-align:center; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    Especificar según corresponda en cada rubro
                  </td>
                </tr>
                <tr>
                  <td style="width:15%; text-align:center; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    Valor de adquisición
                  </td>
                  <td style="width:15%; text-align:center; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    Valor Residual
                  </td>
                  <td style="width:15%; text-align:center; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    Valor de Reemplazo
                  </td>
                </tr>
                <tr>
                  <td style="width:55%; border-left: 1px solid #333; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                     Rubro 1: Edificios incluyendo instalaciones fijas y permanentes (excluyendo el valor del terreno)
                  </td>
                  <td style="width:15%; text-align:center; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    NO APLICA
                  </td>
                  <td style="width:15%; text-align:center; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    NO APLICA
                  </td>
                  <td style="width:15%; text-align:center; border-top: 1px solid #333;
                    border-right: 1px solid #333;"></td>
                </tr>
                <tr>
                  <td style="width:55%; border-left: 1px solid #333; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                     Rubro 2: Activos fijos en general no clasificados en otros rubros
                  </td>
                  <td style="width:15%; text-align:center; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    NO APLICA
                  </td>
                  <td style="width:15%; text-align:center; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    NO APLICA
                  </td>
                  <td style="width:15%; text-align:center; border-top: 1px solid #333;
                    border-right: 1px solid #333;"></td>
                </tr>
                <tr>
                  <td style="width:55%; border-left: 1px solid #333; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                     Rubro 3: Maquinaria y equipos electromecánicos en general (fijos o móviles)
                  </td>
                  <td style="width:15%; text-align:center; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    NO APLICA
                  </td>
                  <td style="width:15%; text-align:center; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    NO APLICA
                  </td>
                  <td style="width:15%; text-align:center; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    NO APLICA
                    </td>
                </tr>
                <tr>
                  <td style="width:55%; border-left: 1px solid #333; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                     Rubro 4: Existencias de herramientas eléctricas y mecánicas, repuestos y accesorios.
                  </td>
                  <td style="width:15%; text-align:center; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    NO APLICA
                  </td>
                  <td style="width:15%; text-align:center; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    NO APLICA
                  </td>
                  <td style="width:15%; text-align:center; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    NO APLICA
                    </td>
                </tr>
                <tr>
                  <td style="width:55%; border-left: 1px solid #333; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    Rubro 5: Equipos Electrónicos o digitales en general  (fijos o móviles)
                  </td>
                  <td style="width:15%; text-align:center; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    NO APLICA
                  </td>
                  <td style="width:15%; text-align:center; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    NO APLICA
                  </td>
                  <td style="width:15%; text-align:center; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    NO APLICA
                    </td>
                </tr>
                <tr>
                  <td style="width:55%; border-left: 1px solid #333; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    Rubro 6: Existencias de materias primas y/o productos en proceso y/o productos terminados (Establecer el monto estimado máximo previsto de concentración en una sola ubicación)
                  </td>
                  <td style="width:15%; text-align:center; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    NO APLICA
                  </td>
                  <td style="width:15%; text-align:center; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    NO APLICA
                  </td>
                  <td style="width:15%; text-align:center; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    NO APLICA
                    </td>
                </tr>
                <tr>
                  <td style="width:55%; border-left: 1px solid #333; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    Rubro 7: Cuadros, pinturas y objetos de arte
                  </td>
                  <td style="width:15%; text-align:center; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    NO APLICA
                  </td>
                  <td style="width:15%; text-align:center; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    NO APLICA
                  </td>
                  <td style="width:15%; text-align:center; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    NO APLICA
                    </td>
                </tr>
                <tr>
                  <td style="width:55%; border-left: 1px solid #333; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    Rubro 8: Libros, textos y otros materiales de biblioteca
                  </td>
                  <td style="width:15%; text-align:center; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    NO APLICA
                  </td>
                  <td style="width:15%; text-align:center; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    NO APLICA
                  </td>
                  <td style="width:15%; text-align:center; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    NO APLICA
                    </td>
                </tr>
                <tr>
                  <td style="width:55%; border-left: 1px solid #333; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    Rubro 9: Avisos luminosos, paneles publicitarios y otros similares
                  </td>
                  <td style="width:15%; text-align:center; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    NO APLICA
                  </td>
                  <td style="width:15%; text-align:center; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    NO APLICA
                  </td>
                  <td style="width:15%; text-align:center; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    NO APLICA
                    </td>
                </tr>
                <tr>
                  <td style="width:55%; border-left: 1px solid #333; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    Rubro 10: Dinero y/o valores (Monto máximo de concentración previsto en una sola ubicación)
                  </td>
                  <td style="width:15%; text-align:center; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    NO APLICA
                  </td>
                  <td style="width:15%; text-align:center; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    NO APLICA
                  </td>
                  <td style="width:15%; text-align:center; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    NO APLICA
                    </td>
                </tr>
                <tr>
                  <td style="width:55%; border-left: 1px solid #333; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    Rubro 11: Bienes de terceros bajo custodia, responsabilidad y control de la Empresa.
                  </td>
                  <td style="width:15%; text-align:center; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    NO APLICA
                  </td>
                  <td style="width:15%; text-align:center; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    NO APLICA
                  </td>
                  <td style="width:15%; text-align:center; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    NO APLICA
                    </td>
                </tr>
                <tr>
                  <td style="width:55%; border-left: 1px solid #333; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    Rubro 12:  Otros bienes a declarar
                  </td>
                  <td style="width:15%; text-align:center; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    NO APLICA
                  </td>
                  <td style="width:15%; text-align:center; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    NO APLICA
                  </td>
                  <td style="width:15%; text-align:center; border-top: 1px solid #333;
                    border-right: 1px solid #333;">
                    NO APLICA
                    </td>
                </tr>
                <tr>
                  <td style="width:55%; border-left: 1px solid #333; border-top: 1px solid #333;
                    border-right: 1px solid #333; border-bottom: 1px solid #333;">
                    Rubro 13: Otros bienes a declarar
                  </td>
                  <td style="width:15%; text-align:center; border-top: 1px solid #333;
                    border-right: 1px solid #333; border-bottom: 1px solid #333;">
                    NO APLICA
                  </td>
                  <td style="width:15%; text-align:center; border-top: 1px solid #333;
                    border-right: 1px solid #333; border-bottom: 1px solid #333;">
                    NO APLICA
                  </td>
                  <td style="width:15%; text-align:center; border-top: 1px solid #333;
                    border-right: 1px solid #333; border-bottom: 1px solid #333;">
                    NO APLICA
                    </td>
                </tr>
            </table><br>    
            <span style="font-size:80%; font-weight:bold;">Descripción de colindancias:</span>
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-family: Arial; 
                padding-top:4px; padding-bottom:3px;">
                <tr> 
                  <td style="width:100%; padding-bottom:2px;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 100%;">
                        <tr>
                          <td style="width:5%;">Norte: </td>
                          <td style="border-bottom: 1px solid #333; width:95%;">&nbsp;
                              
                          </td>
                        </tr>
                        <tr>
                          <td style="width:5%;">Sur: </td>
                          <td style="border-bottom: 1px solid #333; width:95%;">&nbsp;
                              
                          </td>
                        </tr>
                        <tr>
                          <td style="width:5%;">Este: </td>
                          <td style="border-bottom: 1px solid #333; width:95%;">&nbsp;
                              
                          </td>
                        </tr>
                        <tr>
                          <td style="width:5%;">Oeste: </td>
                          <td style="border-bottom: 1px solid #333; width:95%;">&nbsp;
                              
                          </td>
                        </tr>
                     </table>
                  </td>      
                </tr>
                <tr>
                  <td style="width:100%; padding-bottom:4px;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                        <tr>
                          <td style="width:13%;">Período del Seguro: </td>
                          <td style="width:27%; border-bottom: 1px solid #333; text-align:center;">
                              <?=$row['tip_plazo_text'];?>
                          </td>
                          <td style="width:5%;">Desde: </td>
                          <td style="width:25%; border-bottom: 1px solid #333; text-align:center;">
                              <?=$row['ini_vigencia'];?>
                          </td>
                          <td style="width:5%;">Hasta: </td>
                          <td style="width:25%; border-bottom: 1px solid #333; text-align:center;">
                              <?=$row['fin_vigencia'];?>
                          </td>
                        </tr>
                      </table>                                  
                  </td>
                </tr>   
            </table>
<?php
         if((boolean)$row['garantia']===true){
?>            
            <br>
            <span style="font-weight:bold; font-size:80%;">Requiere este Seguro subrogación de Derechos:   </span> 
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-family: Arial; 
                padding-top:4px; padding-bottom:6px;">
               <tr>
                  <td style="width:18%; text-align:left;">Detallar Nombres y montos: </td>
                  <td style="width:82%; text-align:left; border-bottom: 1px solid #333;">&nbsp;
                     
                  </td>
               </tr>
               <tr><td style="width:100%; border-bottom: 1px solid #333;" colspan="2">&nbsp;</td></tr>
            </table>
<?php
		 }
?>                 
            <br><br><br><br><br>
            <div style="font-size: 80%; text-align:center;">  
                • Av. Arce Nº 2631, Edificio Multicine Piso 14 • Teléfono: (591-2) 217 7000 • Fax: (591-2) 214 1928 
                • La Paz – Bolivia.<br> 
                • Autorizado por Resolución Administrativa Nº 158 del 7 de julio de 1999 de la Superintendencia de 
                Pensiones Valores y Seguros
            </div>  	
        </div>            
        
        <page><div style="page-break-before: always;">&nbsp;</div></page>
        
        <div style="width: 775px; border: 0px solid #FFFF00;"> 
            <div style="text-align:right; margin-bottom:5px;">
               <img src="<?=$url;?>images/<?=$row['logo_cia'];?>" height="60"/>
            </div>
            <table 
               cellpadding="0" cellspacing="0" border="0" 
               style="width: 100%; height: auto; font-size: 80%; font-family: Arial;">
               <tr>
                 <td style="width:100%; text-align:justify;">
                   <b>Mercaderías:</b><br><br>
                   Favor presentar un breve detalle de tipo de mercadería a ser asegurada, lugares de almacenamiento y sistemas de seguridad:<br> 
                   NO APLICA
                 </td>
               </tr>
               <tr>
                  <td style="width:100%; text-align:left; border-bottom: 1px solid #333;">&nbsp;
                     
                  </td>
               </tr>
               <tr>
                  <td style="width:100%; text-align:left; border-bottom: 1px solid #333;">&nbsp;
                     
                  </td>
               </tr>
            </table>
            <br>
            <table 
               cellpadding="0" cellspacing="0" border="0" 
               style="width: 100%; height: auto; font-size: 80%; font-family: Arial;">
               <tr>
                 <td style="width:100%; text-align:center; font-weight:bold;">
                   INFORMACIÓN SOBRE GRADOS DE SENSIBILIDAD DEL NEGOCIO
                 </td>
               </tr>
               <tr>
                  <td style="width:100%;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;
                         margin-top:9px; margin-bottom:0px;">
                         <tr>
                           <td style="width: 2%;" valign="top">1.</td>
                           <td style="width: 98%; text-align:left;">
                             En caso de destrucción total cuál es el tiempo estimado (meses)  para reemplazar:
                             <table cellpadding="0" cellspacing="0" border="0" style="width: 70%; font-size:100%;
                                margin-top:4px; margin-bottom:4px;">
                                 <tr>
                                   <td style="width: 2%;">a)</td>
                                   <td style="width: 34%;">
                                      Edificios
                                   </td>
                                   <td style="width: 34%;">
                                      NO APLICA
                                   </td>
                                 </tr>
                                 <tr><td colspan="3" style="width:70%;">&nbsp;</td></tr>
                                 <tr>
                                   <td style="width: 2%;">b)</td>
                                   <td style="width: 34%;">
                                      Maquinaria y equipo       
                                   </td>
                                   <td style="width: 34%;">
                                      NO APLICA
                                   </td>
                                 </tr>
                                 <tr><td colspan="3" style="width:70%;">&nbsp;</td></tr>
                                 <tr>
                                   <td style="width: 2%;">c)</td>
                                   <td style="width: 34%;">
                                      Materias primas                     
                                   </td>
                                   <td style="width: 34%;">
                                      NO APLICA
                                   </td>
                                 </tr>
                             </table>       
                           </td>
                         </tr>
                         <tr>
                           <td style="width:2%; padding-top:9px;" valign="top">2.</td>
                           <td style="width:98%; text-align:left; padding-top:9px;">
                             ¿Qué materiales, máquinas o equipos son difíciles de reemplazar?
                           </td>
                         </tr>
                         <tr>
                           <td style="width:100%;" colspan="2">
                              <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                 <tr>
                                   <td style="width: 8%; font-weight:bold;">NO APLICA: </td>
                                   <td style="width: 92%; border-bottom: 1px solid #333;">&nbsp;
                                      
                                   </td>
                                 </tr>
                               </table>    
                           </td>
                         </tr>
                         <tr>
                           <td style="width:2%; padding-top:9px;" valign="top">3.</td>
                           <td style="width:98%; text-align:left; padding-top:9px;">
                             ¿Si es así, que tiempo demoraría en obtenerlos?
                           </td>
                         </tr>
                         <tr>
                           <td style="width:100%;" colspan="2">
                              <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                 <tr>
                                   <td style="width: 8%; font-weight:bold;">NO APLICA: </td>
                                   <td style="width: 92%; border-bottom: 1px solid #333;">&nbsp;
                                      
                                   </td>
                                 </tr>
                               </table>    
                           </td>
                         </tr>
                      </table> 
                  </td>
               </tr>
               <tr>
                  <td style="width:100%; text-align:center; font-weight:bold; padding-top:10px;">
                    INFORMACIÓN SOBRE PERSONAL DE LA EMPRESA 
                  </td>
               </tr>
               <tr>
                  <td style="width:100%;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                         <tr>
                           <td style="width:50%; text-align:center; border-left: 1px solid #333;
                             border-top: 1px solid #333; border-right: 1px solid #333;">
                             Clasificación de Empleados
                           </td>
                           <td style="width:25%; text-align:center; border-top: 1px solid #333;
                             border-right: 1px solid #333;">
                             Nro. De Empleados
                           </td>
                           <td style="width:25%; text-align:center; border-top: 1px solid #333;
                             border-right: 1px solid #333;">
                             Monto estimado anual de planillas
                           </td>
                         </tr>
                         <tr>
                           <td style="width:50%; text-align:left; border-left: 1px solid #333;
                             border-top: 1px solid #333; border-right: 1px solid #333;">
                             Empleados fijos
                           </td>
                           <td style="width:25%; text-align:center; border-top: 1px solid #333;
                             border-right: 1px solid #333;">
                             NO APLICA
                           </td>
                           <td style="width:25%; text-align:center; border-top: 1px solid #333;
                             border-right: 1px solid #333;">
                             NO APLICA
                           </td>
                         </tr>
                         <tr>
                           <td style="width:50%; text-align:left; border-left: 1px solid #333;
                             border-top: 1px solid #333; border-right: 1px solid #333;">
                             Empleados Eventuales
                           </td>
                           <td style="width:25%; text-align:center; border-top: 1px solid #333;
                             border-right: 1px solid #333;">
                             NO APLICA
                           </td>
                           <td style="width:25%; text-align:center; border-top: 1px solid #333;
                             border-right: 1px solid #333;">
                             NO APLICA
                           </td>
                         </tr>
                         <tr>
                           <td style="width:50%; text-align:left; border-left: 1px solid #333;
                             border-top: 1px solid #333; border-right: 1px solid #333;">
                             Obreros
                           </td>
                           <td style="width:25%; text-align:center; border-top: 1px solid #333;
                             border-right: 1px solid #333;">
                             NO APLICA
                           </td>
                           <td style="width:25%; text-align:center; border-top: 1px solid #333;
                             border-right: 1px solid #333;">
                             NO APLICA
                           </td>
                         </tr>
                         <tr>
                           <td style="width:50%; text-align:left; border-left: 1px solid #333;
                             border-top: 1px solid #333; border-right: 1px solid #333; border-bottom: 1px solid #333;">
                             Totales Consolidados
                           </td>
                           <td style="width:25%; text-align:center; border-top: 1px solid #333;
                             border-right: 1px solid #333; border-bottom: 1px solid #333;">
                             NO APLICA
                           </td>
                           <td style="width:25%; text-align:center; border-top: 1px solid #333;
                             border-right: 1px solid #333; border-bottom: 1px solid #333;">
                             NO APLICA
                           </td>
                         </tr>
                     </table>      
                  </td>
               </tr>
               <tr><td style="width:100%; height:20px;">&nbsp;</td></tr>
               <tr>
                  <td style="width:100%; text-align:center; font-weight:bold;">
                    INFORMACIÓN SOBRE MOVIMIENTO DE DINERO EN EFECTIVO Y/O VALORES
                  </td>
               </tr>
               <tr>
                  <td style="width:100%;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                         <tr>
                           <td style="width:50%; text-align:center; border-left: 1px solid #333;
                             border-top: 1px solid #333; border-right: 1px solid #333;">
                             Clasificación de Empleados
                           </td>
                           <td style="width:25%; text-align:center; border-top: 1px solid #333;
                             border-right: 1px solid #333;">
                             Nro. De Empleados
                           </td>
                           <td style="width:25%; text-align:center; border-top: 1px solid #333;
                             border-right: 1px solid #333;">
                             Monto estimado anual de planillas
                           </td>
                         </tr>
                         <tr>
                           <td style="width:50%; text-align:left; border-left: 1px solid #333;
                             border-top: 1px solid #333; border-right: 1px solid #333;">
                             Monto Normal transportado diariamente
                           </td>
                           <td style="width:25%; text-align:center; border-top: 1px solid #333;
                             border-right: 1px solid #333;">
                             NO APLICA
                           </td>
                           <td style="width:25%; text-align:center; border-top: 1px solid #333;
                             border-right: 1px solid #333;">
                             NO APLICA
                           </td>
                         </tr>
                         <tr>
                           <td style="width:50%; text-align:left; border-left: 1px solid #333;
                             border-top: 1px solid #333; border-right: 1px solid #333;">
                             Monto En exceso del anterior transportado eventualmente en el año
                           </td>
                           <td style="width:25%; text-align:center; border-top: 1px solid #333;
                             border-right: 1px solid #333;">
                             NO APLICA
                           </td>
                           <td style="width:25%; text-align:center; border-top: 1px solid #333;
                             border-right: 1px solid #333;">
                             NO APLICA
                           </td>
                         </tr>
                         <tr>
                           <td style="width:50%; text-align:left; border-left: 1px solid #333;
                             border-top: 1px solid #333; border-right: 1px solid #333;">
                             Monto normal en efectivo, valores o cheques concentrados en los locales diariamente
                           </td>
                           <td style="width:25%; text-align:center; border-top: 1px solid #333;
                             border-right: 1px solid #333;">
                             NO APLICA
                           </td>
                           <td style="width:25%; text-align:center; border-top: 1px solid #333;
                             border-right: 1px solid #333;">
                             NO APLICA
                           </td>
                         </tr>
                         <tr>
                           <td style="width:50%; text-align:left; border-left: 1px solid #333;
                             border-top: 1px solid #333; border-right: 1px solid #333; border-bottom: 1px solid #333;">
                             Montos adicionales al importe normal que eventualmente se encuentran en los locales
                           </td>
                           <td style="width:25%; text-align:center; border-top: 1px solid #333;
                             border-right: 1px solid #333; border-bottom: 1px solid #333;">
                             NO APLICA
                           </td>
                           <td style="width:25%; text-align:center; border-top: 1px solid #333;
                             border-right: 1px solid #333; border-bottom: 1px solid #333;">
                             NO APLICA
                           </td>
                         </tr>
                     </table>      
                  </td>
               </tr>
               <tr>
                  <td style="width:100%; padding-top:9px;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                         <tr>
                           <td style="width:2%;" valign="top">1.</td>
                           <td style="width:98%; text-align:left;">
                             Quienes se encargan de realizar las remesas de dinero en efectivo, valores o cheques en la empresa (Indicar si existen niveles de control):
                           </td>
                         </tr>
                         <tr>
                           <td style="width:100%;" colspan="2">
                              <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                 <tr>
                                   <td style="width: 8%; font-weight:bold;">NO APLICA: </td>
                                   <td style="width: 92%; border-bottom: 1px solid #333;">&nbsp;
                                      
                                   </td>
                                 </tr>
                               </table>    
                           </td>
                         </tr>
                         <tr>
                           <td style="width:2%; padding-top:9px;" valign="top">2.</td>
                           <td style="width:98%; text-align:left; padding-top:9px;">
                             Informar sobre cantidad, ubicación, marcas y dimensiones de cajas fuertes o bóvedas de la Empresa:
                           </td>
                         </tr>
                         <tr>
                           <td style="width:100%;" colspan="2">
                              <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                 <tr>
                                   <td style="width: 8%; font-weight:bold;">NO APLICA: </td>
                                   <td style="width: 92%; border-bottom: 1px solid #333;">&nbsp;
                                      
                                   </td>
                                 </tr>
                               </table>    
                           </td>
                         </tr>
                      </table> 
                  </td>
               </tr>
               <tr>
                  <td style="width:100%; text-align:center; font-weight:bold; padding-top:10px;">
                    HISTORIA DE PÉRDIDAS<br> 
                    Indicar detalle de pérdidas importantes durante los últimos tres (3) años (bajo cobertura de seguro o no):

                  </td>
               </tr>
               <tr>
                  <td style="width:100%;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                         <tr>
                           <td style="width:36%; text-align:center; border-left: 1px solid #333;
                             border-top: 1px solid #333; border-right: 1px solid #333;">
                             Año
                           </td>
                           <td style="width:32%; text-align:center; border-top: 1px solid #333;
                             border-right: 1px solid #333;">
                             Descripción del suceso o pérdida
                           </td>
                           <td style="width:32%; text-align:center; border-top: 1px solid #333;
                             border-right: 1px solid #333;">
                             Importe de la pérdida
                           </td>
                         </tr>
                         <tr>
                           <td style="width:36%; text-align:left; border-left: 1px solid #333;
                             border-top: 1px solid #333; border-right: 1px solid #333;">
                             NO APLICA 
                           </td>
                           <td style="width:32%; text-align:left; border-top: 1px solid #333;
                             border-right: 1px solid #333;">
                             NO APLICA
                           </td>
                           <td style="width:32%; text-align:left; border-top: 1px solid #333;
                             border-right: 1px solid #333;">
                             NO APLICA
                           </td>
                         </tr>
                         <tr>
                           <td style="width:36%; text-align:left; border-left: 1px solid #333;
                             border-top: 1px solid #333; border-right: 1px solid #333; border-bottom: 1px solid #333;">&nbsp;
                             
                           </td>
                           <td style="width:32%; text-align:center; border-top: 1px solid #333;
                             border-right: 1px solid #333; border-bottom: 1px solid #333;">&nbsp;
                             
                           </td>
                           <td style="width:32%; text-align:center; border-top: 1px solid #333;
                             border-right: 1px solid #333; border-bottom: 1px solid #333;">&nbsp;
                             
                           </td>
                         </tr>
                     </table>      
                  </td>
               </tr>
               
            </table>
            <br><br><br><br><br>
            <div style="font-size: 80%; text-align:center;">  
                • Av. Arce Nº 2631, Edificio Multicine Piso 14 • Teléfono: (591-2) 217 7000 • Fax: (591-2) 214 1928 
                • La Paz – Bolivia.<br> 
                • Autorizado por Resolución Administrativa Nº 158 del 7 de julio de 1999 de la Superintendencia de 
                Pensiones Valores y Seguros
            </div>      
        </div>
        
        <page><div style="page-break-before: always;">&nbsp;</div></page>
        
        <div style="width: 775px; border: 0px solid #FFFF00;">
            <div style="text-align:right; margin-bottom:10px;">
               <img src="<?=$url;?>images/<?=$row['logo_cia'];?>" height="60"/>
            </div>
            
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-family: Arial; 
                padding-top:4px; padding-bottom:3px;">
                <tr>
                  <td style="width:100%; text-align:left; font-weight:bold;">
                     Forma de Pago Propuesto:
                  </td>
                </tr>
                <tr><td style="width:100%;">&nbsp;</td></tr>
                <tr>
                  <td style="width:100%; text-align:left; font-weight:bold;">
                     Al Contado:
                  </td>
                </tr>
                <tr><td style="width:100%;">&nbsp;</td></tr>
                <tr>
                  <td style="width:100%; padding-bottom:30px;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                         <tr>
                           <td style="width:8%; text-align:left; font-weight:bold;">Al Crédito: </td>
                           <td style="width:15%;">&nbsp;
                             
                           </td>
                           <td style="width:10%; text-align:left; font-weight:bold;">Nro de Cuotas: </td>
                           <td style="width:25%; border-bottom: 1px solid #333;">&nbsp;</td>
                           <td style="width:42%;">&nbsp;</td>
                         </tr>
                     </table>      
                  </td>
                </tr>
                <tr> 
                  <td style="width:100%; padding-bottom:4px; text-align:justify;">
                     Este documento conjuntamente con los detalles presentados por la Empresa Solicitante se constituirá en la Declaración hecha por el Asegurado para la contratación de la póliza y forma parte integrante de la misma.<br><br>

La cobertura de la presente solicitud correrá una vez  la Compañía Aseguradora efectúe el análisis del Riesgo y emita la Póliza correspondiente, de acuerdo a las declaraciones de la solicitud.<br><br>

El solicitante que suscribe será responsable de la exactitud de la información en la presente Solicitud y se compromete a efectuar el pago de las primas correspondientes, una vez emitida la Póliza.<br><br>

El solicitante deberá proporcionar adjunto a la presente Solicitud, los respectivos inventarios valorizados de los activos fijos cubiertos.

                     <br><br><br><br>
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                         <tr> 
                          <td style="width:13%;">LUGAR Y FECHA: </td>
                          <td style="width:37%; border-bottom: 1px solid #333;">
                             &nbsp;<?=$row['u_departamento'] .' '. get_date_format_trd($row['fecha_creacion']);?>
                          </td>
                          <td style="width:50%;"></td>  
                         </tr> 
                     </table>
                  </td>      
                </tr>
            </table>
            <br><br><br><br><br><br>
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-family: Arial;">
               <tr>
                <td style="width:25%;"></td>
                <td style="width:50%; border-bottom: 1px solid #333;">&nbsp;
                  
                </td>
                <td style="width:25%;"></td> 
               </tr>
               <tr>
                <td style="width:25%;"></td>
                <td style="width:50%; text-align:center; font-weight:bold;">
                  FIRMA DEL CLIENTE
                </td>
                <td style="width:25%;"></td> 
               </tr>  
            </table>
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

function get_date_format_trd($fecha){
	$date = date_create($fecha);
	
	$day = date_format($date, 'd');
	$month = date_format($date, 'F');
	$year = date_format($date, 'Y');
	
	return $day.' de '.get_month_espanol_trd($month).' de '.$year;
}

function get_month_espanol_trd($month){
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