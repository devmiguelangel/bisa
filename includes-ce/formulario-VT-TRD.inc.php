<?php
function trd_formulario_vt($link, $row, $rsDt, $url, $implant, $fac, $reason = '', $product) {
	$query = "select 
				trdc.id_cotizacion,
				trdc.no_cotizacion,
				trdc.id_ef as idef,
				trdc.id_cliente,
				trdc.garantia,
				trdc.tipo,
				trdc.ini_vigencia,
				trdc.fin_vigencia,
				trdc.forma_pago,
				trdc.plazo,
				trdc.tipo_plazo,
				@plazo:=(case trdc.tipo_plazo
					when 'Y' then 'Año(s)'
					when 'D' then 'Dias'
					when 'M' then 'Meses'
					when 'W' then 'Semanas'
				end) as tipo_plazo_text,
				concat(trdc.plazo, ' ', @plazo) as tip_plazo_text,
				trdc.fecha_creacion,
				trdc.id_usuario,
				trdc.prima_total,
				sc.nombre as compania,
				sc.logo as logo_cia,
				ef.nombre as ef_nombre,
				ef.logo as logo_ef,
				su.nombre as u_nombre,
				su.email as u_email,
				sdu.departamento as u_departamento,
				(case trdclt.tipo
					when 0 then 'Natural'
					when 1 then 'Juridico'
				end) as tipo_cliente,
				trdclt.razon_social,
				trdclt.paterno,
				trdclt.materno,
				trdclt.nombre,
				trdclt.ap_casada,
				trdclt.fecha_nacimiento,
				trdclt.ci,
				sde.codigo as extension,
				trdclt.complemento,
				(case trdclt.tipo
					when 0 then concat(trdclt.ci,'',trdclt.complemento,' ',sde.codigo)
					when 1 then trdclt.ci
				end) as ci_nit,
				trdclt.avenida,
				trdclt.direccion_domicilio,
				trdclt.localidad,
				trdclt.telefono_domicilio,
				trdclt.telefono_oficina,
				trdclt.telefono_celular,
				trdclt.email,
				trdclt.actividad,
				trdclt.desc_ocupacion,
				trdclt.direccion_laboral,
				socu.ocupacion
			from
				s_trd_cot_cabecera as trdc
					inner join
				s_entidad_financiera as ef ON (ef.id_ef = trdc.id_ef)
					inner join
				s_ef_compania as sefc ON (sefc.id_ef = ef.id_ef
					and sefc.producto = '".$product."')
					inner join
				s_compania as sc ON (sc.id_compania = sefc.id_compania)
					inner join
				s_usuario as su ON (su.id_usuario = trdc.id_usuario)
					inner join
				s_departamento as sdu ON (sdu.id_depto = su.id_depto)
					inner join
				s_trd_cot_cliente as trdclt ON (trdclt.id_cliente = trdc.id_cliente)
					inner join
				s_departamento as sde on (sde.id_depto = trdclt.extension)
					left join
				s_ocupacion as socu ON (socu.id_ocupacion = trdclt.id_ocupacion
					and socu.producto = '".$product."')
			where
				trdc.id_cotizacion = '".$row['idc']."'
					and sc.id_compania = '".$row['id_compania']."';";
					
	$consult = $link->query($query,MYSQLI_STORE_RESULT);
	$rowsc = $consult->fetch_array(MYSQLI_ASSOC);
	
	$queryDtSc = "select 
			  trdd.id_inmueble,
			  trdd.id_cotizacion,
			  case trdd.tipo_in
				  when 'ED' then 'Edificio'
				  when 'MC' then 'Mueble o Contenido'
			  end as tipo_inmueble,
			  case trdd.uso
				  when 'CM' then 'Comercial'
				  when 'DM' then 'Domiciliario'
			  end as uso_inmueble,
			  trdd.uso_otro,
			  trdd.zona,
			  trdd.localidad,
			  trdd.direccion,
			  trdd.valor_asegurado,
			  trdd.tasa,
			  trdd.prima,
			  sd.departamento
		  from
			  s_trd_cot_detalle as trdd
				  inner join
			  s_departamento as sd ON (sd.id_depto = trdd.departamento)
		  where
			  trdd.id_cotizacion = '".$row['idc']."';";
	$consultDtSc = $link->query($queryDtSc, MYSQLI_STORE_RESULT);
	$rowDtSc = $consultDtSc->fetch_array(MYSQLI_ASSOC);		  
	
	$query="select 
			  stec.id_emision,
			  stec.no_emision,
			  stec.id_ef,
			  stec.id_cotizacion,
			  sted.adjacent
		  from
			  s_trd_em_cabecera as stec
				  inner join
			  s_trd_em_detalle as sted ON (sted.id_emision = stec.id_emision)
		  where
			stec.id_cotizacion = '".$row['idc']."'
				and stec.id_ef = '".$row['idef']."' ";
			  if((boolean)$row['garantia']===false){ 	
			    $query.="and stec.emitir = true
				         and stec.anulado = false;";
			  }
	 $consult = $link->query($query,MYSQLI_STORE_RESULT);
	 if($consult->num_rows>0){
		$rowCld = $consult->fetch_array(MYSQLI_ASSOC);
		$arrCld = json_decode($rowCld['adjacent'],true);
		$norte = $arrCld['N'];
		$este = $arrCld['E'];
		$sur = $arrCld['S'];
		$oeste = $arrCld['W']; 
	 }else{
		$norte = '';
		$este = '';
		$sur = '';
		$oeste = ''; 
	 }			
	 
	 		
	ob_start();
?>
  <div id="container-c" style="width: 785px; height: auto; 
      border: 0px solid #0081C2; padding: 5px;">
	  <div id="main-c" style="width: 775px; font-weight: normal; font-size: 12px; 
        font-family: Arial, Helvetica, sans-serif; color: #000000;">
<?php
     $j = 0;
	 $text = '';
	 
	 $poliza = (92).''.plaza_trd_vt($row['u_depto']).''.$row['garantia'].''.str_pad($row['no_emision'],7,'0',STR_PAD_LEFT);
	  
     $num_titulares=$rsDt->num_rows;
			
     while($rowDt = $rsDt->fetch_array(MYSQLI_ASSOC)){
		 if($row['tipo_cliente']=='J'){
			 //SOLICITUD
			 $razon_social = $rowsc['razon_social'];
			 $actividad = $rowsc['actividad'];
			 $nit_ci = $rowsc['ci_nit'];
			 $cliente = $razon_social;
			   
			 $ap_paterno = '';
			 $ap_materno = '';
			 $nombre = '';
			 $direccion_domicilio = '';
			 $direccion_laboral = $rowsc['direccion_laboral'];
			 $fono_celular = $rowsc['telefono_celular'];
			 $fono_domicilio = '';
			 $fono_oficina = $rowsc['telefono_oficina'];
			 $nit_ci = $rowsc['ci_nit'];
			 $email = $rowsc['email'];
			 
			 //EMISION
			 $cliente_nombre = $row['cl_razon_social'];
			 $cliente_nitci = $row['cl_ci'];
			 $cliente_direccion = $row['cl_direccion_laboral'];
			 
		 }elseif($row['tipo_cliente']=='N'){
			 //SOLICITUD
			 $ap_paterno = $rowsc['paterno'];
			 $ap_materno = $rowsc['materno'];
			 $nombre = $rowsc['nombre'];
			 $direccion_domicilio = $rowsc['direccion_domicilio'];
			 $direccion_laboral = $rowsc['direccion_laboral'];
			 $fono_celular = $rowsc['telefono_celular'];
			 $fono_domicilio = $rowsc['telefono_domicilio'];
			 $fono_oficina = $rowsc['telefono_oficina'];
			 $nit_ci = $rowsc['ci_nit'];
			 $email = $rowsc['email'];
			 $cliente = $rowsc['nombre'].' '.$rowsc['paterno'].' '.$rowsc['materno'];
			 
			 $razon_social = '';
			 $actividad = '';
			 
			 //EMISION
			 $cliente_nombre = $row['cl_nombre'].' '.$row['cl_paterno'].' '.$row['cl_materno'];
			 $cliente_nitci = $row['cl_ci'].$row['cl_complemento'].' '.$row['cl_extension'];
			 $cliente_direccion = $row['cl_direccion'];
		 }
		 $ubicacion_riesgo = $rowDt['pr_departamento'].' '.$rowDt['pr_zona'].' '.$rowDt['pr_localidad'].' '.$rowDt['pr_direccion'];
		 $materia_seguro = $rowDt['pr_tipo_inmueble'].' '.$rowDt['pr_uso_inmueble'];
		 $valor_total_riesgo = number_format($rowDt['pr_valor_asegurado']+$rowDt['pr_valor_contenido'], 2, '.', ',').' $us.';
		 $valores_asegurados = 'Valor Asegurado: '.number_format($rowDt['pr_valor_asegurado'],2,'.',',').' $us.<br>'.'Valor Muebles y/o contenido: '.number_format($rowDt['pr_valor_contenido'],2,'.',',').' $us.';
		 
		 $j += 1;
		 if($row['no_copia']>0){
			 if($row['no_copia']>1) $text='COPIA'; else $text='ORIGINAL';
		 }
		 
		 if($row['forma_pago']=='CR'){
			 $forma_pago_cr = number_format($rowDt['pr_prima'],2,'.',',').' $us.';
			 $forma_pago_co = '';
			 if((boolean)$row['garantia']===true){
			  
				  $sqlCu="select 
							count(stec.id_emision) as num_cuotas
						from
							s_trd_em_cabecera as stec
								inner join
							s_trd_cobranza as strc ON (strc.id_emision = stec.id_emision)
						where
							stec.id_cotizacion = '".$row['idc']."'
								and stec.id_ef = '".$row['idef']."'
								and stec.emitir = true
								and stec.anulado = false;"; 				
				  $consult_cu = $link->query($sqlCu,MYSQLI_STORE_RESULT);
				  if($consult_cu->num_rows>0){
					  $row_cu = $consult_cu->fetch_array(MYSQLI_ASSOC);
					  if($row_cu['num_cuotas']>0){
						$num_cuotas = $row_cu['num_cuotas'];  
					  }else{
						$num_cuotas = '';  
					  }
				  }
			  			   
		     }else{
			   $num_cuotas = 12; 
		     }
		 }elseif($row['forma_pago']=='CO'){
			 $forma_pago_co = number_format($rowDt['pr_prima'],2,'.',',').' $us.';
			 $forma_pago_cr = '';
			 $num_cuotas = '';
		 }
		 
		 
?>
        <!--SOLICITUD-->
        <div style="width: 775px; border: 0px solid #FFFF00; text-align:center;">
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-family: Arial;">
                <tr>
                  <td style="width:100%; text-align:right;">
                     <img src="<?=$url;?>images/<?=$rowsc['logo_cia'];?>" height="60"/>
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
			<span style="font-weight:bold; font-size:80%;">INFORMACION GENERAL:</span> 
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
                             &nbsp;<?=$rowDtSc['localidad'].' '.$rowDtSc['zona'].' '.$rowDtSc['direccion'];?>
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
                             <?=$norte;?> 
                          </td>
                        </tr>
                        <tr>
                          <td style="width:5%;">Sur: </td>
                          <td style="border-bottom: 1px solid #333; width:95%;">&nbsp;
                             <?=$sur;?> 
                          </td>
                        </tr>
                        <tr>
                          <td style="width:5%;">Este: </td>
                          <td style="border-bottom: 1px solid #333; width:95%;">&nbsp;
                             <?=$este;?>  
                          </td>
                        </tr>
                        <tr>
                          <td style="width:5%;">Oeste: </td>
                          <td style="border-bottom: 1px solid #333; width:95%;">&nbsp;
                              <?=$oeste;?>
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
                              <?=$rowsc['tip_plazo_text'];?>
                          </td>
                          <td style="width:5%;">Desde: </td>
                          <td style="width:25%; border-bottom: 1px solid #333; text-align:center;">
                              <?=date("d-m-Y", strtotime($row['ini_vigencia']));?>
                          </td>
                          <td style="width:5%;">Hasta: </td>
                          <td style="width:25%; border-bottom: 1px solid #333; text-align:center;">
                              <?=date("d-m-Y", strtotime($row['fin_vigencia']));?>
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
               <img src="<?=$url;?>images/<?=$rowsc['logo_cia'];?>" height="60"/>
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
               <img src="<?=$url;?>images/<?=$rowsc['logo_cia'];?>" height="60"/>
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
                  <td style="width:100%; text-align:left;">
                     <b>Al Contado:</b>&nbsp;<?=$forma_pago_co;?>
                  </td>
                </tr>
                <tr><td style="width:100%;">&nbsp;</td></tr>
                <tr>
                  <td style="width:100%; padding-bottom:30px;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                         <tr>
                           <td style="width:8%; text-align:left; font-weight:bold;">Al Crédito: </td>
                           <td style="width:15%;">&nbsp;<?=$forma_pago_cr;?>
                             
                           </td>
                           <td style="width:10%; text-align:left; font-weight:bold;">Nro de Cuotas: </td>
                           <td style="width:25%; border-bottom: 1px solid #333;">&nbsp;<?=$num_cuotas;?></td>
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
                             &nbsp;<?=$rowsc['u_departamento'] .' '. get_date_format_trd_vt($rowsc['fecha_creacion']);?>
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
        <!--FIN SOLICITUD-->
        
        <page><div style="page-break-before: always;">&nbsp;</div></page>
<?php
         $nro_cuenta = json_decode($row['nro_cuenta_tomador'],true);
	 
		 if($row['fecha_emision']!=='0000-00-00'){
			 $fecha_em = $row['fecha_emision'];
		 }else{
			 $fecha_em = $row['fecha_creacion'];
		 }
		 
		 
?>        
        <!--FORMULARIO AUTORIZACION-->
        <div style="width: 775px; border: 0px solid #FFFF00; text-align:center;">
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-family: Arial;">
                <tr>
                  <td style="width:100%; text-align:right;">
                     <img src="<?=$url;?>images/<?=$row['ef_logo'];?>"/> 
                  </td> 
                </tr>
            </table>     
        </div>
        <br>
        <br>
        <div style="width: 775px; border: 0px solid #FFFF00;">
			
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-family: Arial; 
                padding-top:4px; padding-bottom:10px;">
                <tr> 
                  <td style="width:100%; padding-bottom:4px; font-weight:bold; text-align:center;">
                     <u>FORMULARIO DE AUTORIZACIÓN</u>
                  </td>      
                </tr> 
            </table>
            <br><br><br><br>
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-family: Arial; 
                padding-top:4px; padding-bottom:3px;">
                <tr> 
                  <td style="width:100%; padding-bottom:4px; text-align:justify;">
                     En razón que el CLIENTE ha decidido contratar de forma voluntaria una póliza de seguros de la empresa BISA SEGUROS Y REASEGUROS S.A., el CLIENTE instruye al Banco BISA S.A. a proporcionar su información con la que cuenta el Banco, a la Aseguradora referida y a la empresa Sudamericana SRL. Corredores de Seguros y Reaseguros, para la obtención de la póliza de seguros escogida por el propio CLIENTE.
                     <br><br>
                     Asimismo, autorizo a realizar el débito automático para el pago de las cuotas que se generen de esta póliza de la cuenta corriente/ahorro Nº.______<?=$nro_cuenta['numero'];?>______ a nombre de ______<?=$row['tomador_nombre'];?>______
                     <br><br><br>
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                         <tr>
                          <td style="width:20%;">CLIENTE: </td>
                          <td style="width:50%; border-bottom: 1px solid #333;">&nbsp;
                             <?=$cliente_nombre;?>
                          </td>
                          <td style="width:30%;"></td>
                         </tr>
                         <tr><td colspan="3" style="width:100%;">&nbsp;</td></tr>
                         <tr> 
                          <td style="width:20%;">LUGAR Y FECHA: </td>
                          <td style="width:50%; border-bottom: 1px solid #333;">&nbsp;
                             <?=strtoupper(get_date_format_trd_vt($fecha_em));?>
                          </td>
                          <td style="width:30%;"></td>  
                         </tr> 
                     </table>
                  </td>      
                </tr>
            </table>
            <br><br><br><br><br><br><br><br>
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
        <!--FIN FORMULARIO AUTORIZACION-->
        
        <page><div style="page-break-before: always;">&nbsp;</div></page>
      
        <!--EMISION--> 
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
                     POLIZA No&nbsp;<?=$poliza;?><br>  
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
<?php
               if((boolean)$row['garantia']===true){//subrogado 
?>                    
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
                    <tr><td style="width:100%;" colspan="2">&nbsp;</td></tr>
                    <tr>
                      <td style="width:35%; text-align: left; padding-right:5px; font-weight:bold; 
                        border:0px solid #0081C2;" valign="top">
                        PAGADOR: 
                      </td>
                      <td style="width:65%; text-align: justify; padding-left:5px; 
                        border:0px solid #0081C2; font-style:italic;" valign="top">
                        <?=$cliente_nombre;?>
                      </td>  
                    </tr>
                    <tr><td style="width:100%;" colspan="2">&nbsp;</td></tr>
                    <tr>
                      <td style="width:35%; text-align: left; padding-right:5px; font-weight:bold; 
                        border:0px solid #0081C2;" valign="top">
                        UBICACIÓN DEL RIESGO:
                      </td>
                      <td style="width:65%; text-align: justify; padding-left:5px; 
                        border:0px solid #0081C2; font-style:italic;" valign="top">
                        <?=$ubicacion_riesgo;?>
                      </td>  
                    </tr>
                    <tr><td style="width:100%;" colspan="2">&nbsp;</td></tr>
                    <tr>
                      <td style="width:35%; text-align: left; padding-right:5px; font-weight:bold; 
                        border:0px solid #0081C2;" valign="top">
                        DIRECCIÓN LEGAL:
                      </td>
                      <td style="width:65%; text-align: justify; padding-left:5px; 
                        border:0px solid #0081C2; font-style:italic;" valign="top">
                        <?=$cliente_direccion;?>
                      </td>  
                    </tr>
                    <tr><td style="width:100%;" colspan="2">&nbsp;</td></tr>
                    <tr>
                      <td style="width:35%; text-align: left; padding-right:5px; font-weight:bold; 
                        border:0px solid #0081C2;" valign="top">
                        MATERIA DEL SEGURO:
                      </td>
                      <td style="width:65%; text-align: justify; padding-left:5px; 
                        border:0px solid #0081C2; font-style:italic;" valign="top">
                        <?=$materia_seguro;?>
                      </td>  
                    </tr>
                    <tr><td style="width:100%;" colspan="2">&nbsp;</td></tr>
                    <tr>
                      <td style="width:35%; text-align: left; padding-right:5px; font-weight:bold; 
                        border:0px solid #0081C2;" valign="top">
                        VALORES TOTAL A RIESGO:
                      </td>
                      <td style="width:65%; text-align: justify; padding-left:5px; 
                        border:0px solid #0081C2; font-style:italic;" valign="top">
                        <?=$valor_total_riesgo;?>
                      </td>  
                    </tr>
                    <tr><td style="width:100%;" colspan="2">&nbsp;</td></tr>
                    <tr>
                      <td style="width:35%; text-align: left; padding-right:5px; font-weight:bold; 
                        border:0px solid #0081C2;" valign="top">
                        VALORES ASEGURADOS:
                      </td>
                      <td style="width:65%; text-align: justify; padding-left:5px; 
                        border:0px solid #0081C2; font-style:italic;" valign="top">
                        <?=$valores_asegurados;?>
                      </td>  
                    </tr>
<?php
			   }else{//no subrogado
?>                    
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
                    <tr><td style="width:100%;" colspan="2">&nbsp;</td></tr>
                    <tr>
                      <td style="width:35%; text-align: left; padding-right:5px; font-weight:bold; 
                        border:0px solid #0081C2;" valign="top">
                        PAGADOR: 
                      </td>
                      <td style="width:65%; text-align: justify; padding-left:5px; 
                        border:0px solid #0081C2; font-style:italic;" valign="top">
                        <?=$cliente_nombre;?>
                      </td>  
                    </tr>
                    <tr><td style="width:100%;" colspan="2">&nbsp;</td></tr>
                    <tr>
                      <td style="width:35%; text-align: left; padding-right:5px; font-weight:bold; 
                        border:0px solid #0081C2;" valign="top">
                        UBICACIÓN DEL RIESGO:
                      </td>
                      <td style="width:65%; text-align: justify; padding-left:5px; 
                        border:0px solid #0081C2; font-style:italic;" valign="top">
                        <?=$ubicacion_riesgo;?>
                      </td>  
                    </tr>
                    <tr><td style="width:100%;" colspan="2">&nbsp;</td></tr>
                    <tr>
                      <td style="width:35%; text-align: left; padding-right:5px; font-weight:bold; 
                        border:0px solid #0081C2;" valign="top">
                        DIRECCIÓN LEGAL:
                      </td>
                      <td style="width:65%; text-align: justify; padding-left:5px; 
                        border:0px solid #0081C2; font-style:italic;" valign="top">
                        <?=$cliente_direccion;?>
                      </td>  
                    </tr>
                    <tr><td style="width:100%;" colspan="2">&nbsp;</td></tr>
                    <tr>
                      <td style="width:35%; text-align: left; padding-right:5px; font-weight:bold; 
                        border:0px solid #0081C2;" valign="top">
                        MATERIA DEL SEGURO:
                      </td>
                      <td style="width:65%; text-align: justify; padding-left:5px; 
                        border:0px solid #0081C2; font-style:italic;" valign="top">
                        <?=$materia_seguro;?>
                      </td>  
                    </tr>
                    <tr><td style="width:100%;" colspan="2">&nbsp;</td></tr>
                    <tr>
                      <td style="width:35%; text-align: left; padding-right:5px; font-weight:bold; 
                        border:0px solid #0081C2;" valign="top">
                        VALORES TOTAL A RIESGO:
                      </td>
                      <td style="width:65%; text-align: justify; padding-left:5px; 
                        border:0px solid #0081C2; font-style:italic;" valign="top">
                        <?=$valor_total_riesgo;?>
                      </td>  
                    </tr>
                    <tr><td style="width:100%;" colspan="2">&nbsp;</td></tr>
                    <tr>
                      <td style="width:35%; text-align: left; padding-right:5px; font-weight:bold; 
                        border:0px solid #0081C2;" valign="top">
                        VALORES ASEGURADOS:
                      </td>
                      <td style="width:65%; text-align: justify; padding-left:5px; 
                        border:0px solid #0081C2; font-style:italic;" valign="top">
                        DE ACUERDO A DECLARACIÓN QUE REALICE CADA CLIENTE.
                      </td>  
                    </tr> 
<?php
			   }
?>                    
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
                    <tr><td style="width:100%;" colspan="2">&nbsp;</td></tr>
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
                                      RIESGOS COMERCIALES
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
                            <td  style="text-align: center; font-weight: bold; background: #e57474; 
                              color: #FFFFFF; width:100%;">
                              Caso Facultativo
                            </td>
                           </tr>
                           <tr>
                            <td style="text-align: center; font-weight: bold; border: 1px solid #dedede; 
                              background: #e57474; width:100%;">
                              Observaciones
                            </td>
                           </tr>
                           <tr>
                            <td style="text-align: justify; background: #e78484; color: #FFFFFF; 
                              border: 1px solid #dedede; width:100%;">
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
        
        <page><div style="page-break-before: always;">&nbsp;</div></page>
        
        <div style="width: 775px; border: 0px solid #FFFF00;">
            <div style="width: 775px; border: 0px solid #FFFF00; text-align:center; margin-bottom:40px;">
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
                    border:0px solid #0081C2; padding-bottom:10px;" valign="top">
                    EXCLUSIONES: 
                  </td>
                  <td style="width:65%; text-align: justify; padding-left:5px; 
                    border:0px solid #0081C2; font-style:italic; padding-bottom:10px;" valign="top">
                    DE ACUERDO A CONDICIONADO GENERAL, ANEXOS Y CLAUSULAS DE LA PÓLIZA.<br>
                    ADICIONALMENTE A LAS EXCLUSIONES ESTIPULADAS SE EXCLUYE:<br>
                    -BIENES EN CÁMARAS FRIGORÍFICAS Y EN EL AGUA<br>
                    -ROBO, HURTO Y/O RATERÍA<br>
                    -BIENES BAJO TIERRA.<br>
                    -BIENES A LA INTEMPERIE QUE NO SEAN APTOS PARA TAL FIN<br>    
                    -DINERO, JOYAS Y/O VALORES         
                  </td>  
                </tr>
<?php
            if((boolean)$row['garantia']===true){
?> 
                <tr>
                  <td style="width:35%; text-align: left; padding-right:5px; font-weight:bold; 
                    border:0px solid #0081C2; padding-bottom:10px;" valign="top">
                    VIGENCIA 
                  </td>
                  <td style="width:65%; text-align: justify; padding-left:5px; 
                    border:0px solid #0081C2; font-style:italic; padding-bottom:10px;" valign="top">
                    SE ACLARA QUE ESTA PÓLIZA NO SE RENOVARÁ POSTERIORMENTE A LA CANCELACIÓN TOTAL DE LA OPERACIÓN CREDITICIA DEL ASEGURADO CON EL CONTRATANTE, DE ACUERDO AL MONTO SUBROGADO Y DECLARADO EN LA PÓLIZA. SE ACLARA QUE LA VIGENCIA DE LA PÓLIZA PODRÁ TERMINAR EN FORMA ANTICIPADA, CUANDO EL ASEGURADO REALICE EL PAGO ANTICIPADO DEL MONTO TOTAL DE SU OPERACIÓN CREDITICIA ADEUDADA AL CONTRATANTE.  SIN EMBARGO, SI LA PRIMA FUE PAGADA AL CONTADO, LA PÓLIZA SE MANTENDRÁ VIGENTE HASTA SU FINALIZACIÓN.         
                  </td>  
                </tr>
<?php
			}
?>                
                <tr>
                  <td style="width:35%; text-align: left; padding-right:5px; font-weight:bold; 
                    border:0px solid #0081C2; padding-bottom:10px;" valign="top">
                    RENOVACIÓN 
                  </td>
                  <td style="width:65%; text-align: justify; padding-left:5px; 
                    border:0px solid #0081C2; font-style:italic; padding-bottom:10px;" valign="top">
                    ANUAL CON RENOVACIÓN AUTOMÁTICA.         
                  </td>  
                </tr>
<?php
            if((boolean)$row['garantia']===true){
?>                 
                <tr>
                  <td style="width:35%; text-align: left; padding-right:5px; font-weight:bold; 
                    border:0px solid #0081C2; padding-bottom:10px;" valign="top">
                    CLAUSULA DE SUBROGACIÓN:
                  </td>
                  <td style="width:65%; text-align: justify; padding-left:5px; 
                    border:0px solid #0081C2; font-style:italic; padding-bottom:10px;" valign="top">
                    CLAUSULA ADJUNTA A LA PRESENTE PÓLIZA        
                  </td>  
                </tr>
<?php
			}
?>                
                <tr>
                  <td style="width:35%; text-align: left; padding-right:5px; font-weight:bold; 
                    border:0px solid #0081C2; padding-bottom:10px;" valign="top">
                    FORMA DE PAGO:
                  </td>
                  <td style="width:65%; text-align: justify; padding-left:5px; 
                    border:0px solid #0081C2; font-style:italic; padding-bottom:10px;" valign="top">
                    AL CONTADO O CON DEBITO EN CUENTA 
                  </td>  
                </tr>
                <tr>
                  <td style="width:35%; text-align: left; padding-right:5px; font-weight:bold; 
                    border:0px solid #0081C2; padding-bottom:10px;" valign="top">
                    CONDICIONES ESPECIALES:
                  </td>
                  <td style="width:65%; text-align: justify; padding-left:5px; 
                    border:0px solid #0081C2; font-style:italic; padding-bottom:10px;" valign="top">
                    EL ASEGURADO AUTORIZA A LA COMPAÑÍA DE SEGUROS A ENVIAR EL REPORTE A LA CENTRAL DE RIESGOS DEL MERCADO DE SEGUROS, ACORDE A LAS NORMATIVAS REGLAMENTARIAS DE LA AUTORIDAD DE FISCALIZACIÓN Y CONTROL DE PENSIONES Y SEGUROS - APS.<br>
                    EL ASEGURADO DEBERÁ PRESENTAR:
                    <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
<?php
                      if((boolean)$row['garantia']===true){
?>                    
                         <tr>
                           <td style="width:2%; font-weight:bold;">o</td>
                           <td style="width:98%;">UNA COPIA DEL AVALÚO TÉCNICO DEL INMUEBLE A ASEGURAR, EN CASO QUE EL BIEN ASEGURADO SEA SUBROGADO.</td>
                         </tr>
<?php
					  }
?>                         
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
                  </td>  
                </tr>
<?php
            if((boolean)$row['garantia']===true){
?>                 
                <tr>
                  <td style="width:35%; text-align: left; padding-right:5px; font-weight:bold; 
                    border:0px solid #0081C2; padding-bottom:10px;" valign="top">
                    INFRA SEGURO:
                  </td>
                  <td style="width:65%; text-align: justify; padding-left:5px; 
                    border:0px solid #0081C2; font-style:italic; padding-bottom:10px;" valign="top">
                    PARA PÓLIZAS SUBROGADAS NO SE APLICARA INFRA SEGURO        
                  </td>  
                </tr>
<?php
			}
?>                        
                <tr>
                   <td style="width:35%; text-align: left; padding-right:5px; font-weight:bold; 
                    border:0px solid #0081C2; padding-bottom:10px;" valign="top">
                    ANULACIÓN POR MORA:
                   </td>
                   <td style="width:65%; text-align: justify; padding-left:5px; 
                    border:0px solid #0081C2; font-style:italic; padding-bottom:10px;" valign="top">
                    LA PÓLIZA SERA ANULADA LUEGO DE PASADOS 60 DÍAS DEL NO PAGO DE LAS PRIMAS ESTABLECIDAS.
                   </td>
                </tr>
                
                <!--
                <tr>
                   <td style="width:35%; text-align: left; padding-right:5px; padding-top:10px; font-weight:bold; 
                    border:0px solid #0081C2;" valign="top">&nbsp;
                    
                   </td>
                   <td style="width:65%; text-align: justify; padding-left:5px; padding-top:10px; 
                     border:0px solid #0081C2; font-style:italic;" valign="top">
                     BISA SEGUROS Y REASEGUROS S.A., DENTRO DE TERRITORIO NACIONAL LE OTORGA EL SERVICIO DE ASISTENCIA DOMICILIARIA, LAS 24 HORAS DEL DÍA Y LOS 365 DÍAS DEL AÑO, CON BENEFICIOS ESTABLECIDOS EN EL ANEXO DE SERVICIO DE ASISTENCIA DOMICILIARIA.<br>                                                           
PARA MAYOR INFORMACIÓN SOBRE LOS SERVICIOS Y LIMITES REFIÉRASE AL ANEXO DE SERVICIO DE ASISTENCIA DOMICILIARIA.
<?php
            if((boolean)$row['garantia']===false){
?> 
<br><br>
EL PROVEEDOR DEL SERVICIO DE ASISTENCIA DOMICILIARIA ES RESPONSABLE DE LOS SERVICIOS PRESTADOS A LOS ASEGURADOS. 
<?php
			}
?>

                   </td>
                </tr>
                -->
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
            <br><br>
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-family: Arial; 
                padding-top:20px;">
               <tr>
                <td style="width:25%;"></td>
                <td style="width:50%; text-align:center;">
                  <?=$row['u_depto']?>, <?=strtoupper(get_date_format_trd_vt($fecha_em));?>
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
        <!--FIN EMISION-->
<?php
	    if((boolean)$row['garantia']===true){
			if($row['fecha_emision']!=='0000-00-00'){
				$fecha_em = $row['fecha_emision'];	 
			}else{
				$fecha_em = $row['fecha_creacion'];
			}
			
			
?>
            <page><div style="page-break-before: always;">&nbsp;</div></page>
            
            <!--ANEXO DE SUBROGACION-->
            <div style="width: 775px; border: 0px solid #FFFF00; text-align:center;">
                <table 
                    cellpadding="0" cellspacing="0" border="0" 
                    style="width: 100%; height: auto; font-family: Arial;">
                    <tr>
                      <td style="width:25%; text-align:left;">&nbsp;
                           
                      </td>
                      <td style="width:50%; font-weight:bold; text-align:center; font-size: 85%;">
                         ANEXO DE SUBROGACIÓN DE DERECHOS PARA ACREEDORES<br><br>
                         Código APS: XXX-XXXXXX-XXXXXXXXXXXXX<br>
                         R.A. XXX/XXXX
                      </td>
                      <td style="width:25%; text-align:right;">
                      
                      </td> 
                    </tr>
                </table>     
            </div>
            <br/>
            
            <div style="width: 775px; border: 0px solid #FFFF00;"> 
                <table 
                    cellpadding="0" cellspacing="0" border="0" 
                    style="width: 100%; height: auto; font-size: 80%; font-family: Arial; 
                    padding-top:4px; padding-bottom:3px;">
                    <tr> 
                      <td style="width:100%; padding-bottom:4px;">
                        <b>Asegurado:</b>&nbsp;<?=$cliente_nombre;?><br>
                        <b>Póliza Nro.:</b>&nbsp;<?=$poliza;?><br>
                        <b>Materia del Seguro Subrogada:</b>&nbsp;<?=$materia_seguro;?><br>
                        <b>Ubicación del Riesgo:</b>&nbsp;<?=$ubicacion_riesgo;?><br>
                        <b>Vigencia del Seguro:</b>&nbsp;desde <?=date("Y-m-d", strtotime($row['ini_vigencia']));?> hasta <?=date("Y-m-d", strtotime($row['fin_vigencia']));?><br>
                        <b>Vigencia de la Subrogación:</b>&nbsp;Durante la vigencia del crédito<br>
                        <b>Acreedor (Beneficiario de Subrogación):</b>&nbsp;<?=$row['ef_nombre'];?><br>
                        <b>Lugar y Fecha:</b>&nbsp;<?=$row['u_depto'].' '.date("Y-m-d", strtotime($fecha_em));?> 
                      </td>      
                    </tr>
                    <tr>
                      <td style="width:100%; padding-bottom:4px;">&nbsp;
                                                         
                      </td>
                    </tr>   
                    <tr>
                      <td style="width:100%; padding-bottom:4px; text-align:justify;">
                        Se deja constancia por el presente anexo, que a solicitud expresa de los tomadores y/o contratantes y/o asegurados, “EL ACREEDOR” será considerado como beneficiario hasta por el importe de su acreencia sin exceder la suma asegurada de la Póliza Nro. <?=$poliza;?>.
                        <br><br>
                        En consecuencia, el Asegurado no podrá ejercitar sus derechos, sino por intermedio del Acreedor.
                        <br><br>
                        
                        La Aseguradora solo estará obligada a pagar al Acreedor la suma equivalente al saldo adeudado por el Asegurado, y el excedente, si lo hubiera, será pagado al Asegurado.
                        <br><br>
                        Queda entendido y convenido por la Aseguradora, que ninguna modificación en las condiciones de la presente póliza, sean estas generales, particulares o especiales, y que afecten los intereses del Acreedor,  serán introducidas sin el previo consentimiento escrito del Acreedor. Para este efecto,  toda modificación solicitada debe ser acompañada de la correspondiente aprobación escrita por parte del Acreedor.
                        <br><br>
                        Consecuentemente se considera como no inserta o no puesta cualquier modificación que no haya sido expresamente autorizada por el Acreedor.
                        <br><br>
                        La Aseguradora se obliga a notificar por escrito al Acreedor en caso que el original Tomador o Asegurado no pague – total o parcialmente – la prima correspondiente, otorgando siete (7) días calendarios adicionales de cobertura a partir de la notificación, a efectos de que el Acreedor pueda hacerse cargo del pago correspondiente de la prima adeudada.
                        <br><br>
                        En caso de renovación de la póliza y de no mediar solicitud en contrario del Acreedor, la Aseguradora conviene que extenderá automáticamente el tenor de esta cláusula en la nueva póliza, aunque no medie solicitud en ese sentido.
                        <br><br>
                        La presente cláusula será preeminente sobre cualquier cláusula, anexo, condiciones generales, particulares o especiales que se opongan a la misma, aun cuando sea de  fecha posterior.
                        <br><br><br><br><br><br>
                      </td>              
                    </tr>
                    <tr>
                      <td style="width:100%;">
                          <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                             <tr>
                               <td style="width:16%;">&nbsp;</td>
                               <td style="width:25%; border-bottom: 1px solid #333;">
                                 <img src="<?=$url;?>img/firmas_bisa.png" height="90"/>
                               </td>
                               <td style="width:17%;">&nbsp;</td>
                               <td style="width:25%; border-bottom: 1px solid #333;">&nbsp;
                                 
                               </td>
                               
                               <td style="width:16%;">&nbsp;</td>
                             </tr>
                          </table> 
                      </td> 
                    </tr>
                    <tr>
                      <td style="width:100%;">
                          <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                             <tr>
                               <td style="width:16%;">&nbsp;</td>
                               <td style="width:25%; text-align:center;">
                                 Firma de la Cía. de Seguros
                               </td>
                               <td style="width:17%;">&nbsp;</td>
                               <td style="width:25%; text-align:center;">
                                 Firma del Asegurado
                               </td>
                               <td style="width:16%;">&nbsp;</td>
                             </tr>
                          </table> 
                      </td> 
                    </tr>
                </table>	
            </div>
            <!--FIN ANEXO DE SUBROGACION-->         
<?php			
		}
		if($row['tipo_cliente']=='J'){
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
?>		
            <page><div style="page-break-before: always;">&nbsp;</div></page>
            
            <!--UIF JURIDICO-->
            <div style="width: 775px; border-left: 1px solid #000; border-top: 1px solid #000; 
              border-right: 1px solid #000; border-bottom: 1px solid #000;">       
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
                  </table>
              </div>
            </div> 
            <!--FIN UIF JURIDICO-->        
<?php    		
		}elseif($row['tipo_cliente']=='N'){
			$ingreso_mensual = $link->monthly_income[$row['tipo_cliente']];
			$estadoc = $link->status;
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
			$cliente = $row['cl_nombre'].' '.$row['cl_paterno'].' '.$row['cl_materno'];
			$ci = $row['cl_ci'];
			$extension = $row['cl_extension'];
			$pais = $row['cl_pais'];
			$direccion = $row['cl_direccion'];
			$vec_fn = explode('-',$row['cl_fecha_nacimiento']);
			$dia_fn = $vec_fn[2];
			$mes_fn = $vec_fn[1];
			$anio_fn = $vec_fn[0];
			$prof_ocupacion = $row['cl_desc_ocupacion'];
			$lugar_trabajo = $row['cl_direccion_laboral'];
			$cargo = $row['cl_cargo'];  
?>	
            <page><div style="page-break-before: always;">&nbsp;</div></page>
            
            <!--UIF NATURAL-->
            <div style="width: 775px; border-left: 1px solid #000; border-top: 1px solid #000; 
              border-right: 1px solid #000; border-bottom: 1px solid #000;">
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
                                 border-left: 1px solid #000; border-bottom: 1px solid #000; height:25px;
                                 background:#d8d8d8; vertical-align:middle;">Sucursal</td>
                                <td style="width:20%; text-align:center; border-top: 0px solid #000;
                                 border-left: 1px solid #000; border-bottom: 1px solid #000; height:25px;
                                 background:#d8d8d8; vertical-align:middle;">Día</td>
                                <td style="width:20%; text-align:center; border-top: 0px solid #000;
                                 border-left: 1px solid #000; border-bottom: 1px solid #000; height:25px;
                                 background:#d8d8d8; vertical-align:middle;">Mes</td>
                                <td style="width:20%; text-align:center; border-top: 0px solid #000;
                                 border-left: 1px solid #000; border-bottom: 1px solid #000; height:25px;
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
                                 border-bottom: 1px solid #000; height:25px; 
                                 border-right: 1px solid #000;"><?=$anio;?></td>
                              </tr>
                           </table>
                        </td> 
                      </tr>
                      <tr>
                        <td style="width:100%; font-weight:bold; text-align:center; padding-top:15px;" colspan="2">
                           FORMULARIO DE IDENTIFICACION DE CLIENTE Y BENEFICIARIO ECONOMICO  - PERSONAS NATURALES / PRIMAS MENORES A $US. 5,000 <br> Politica Conozca su Cliente ART 26 D.S.24771
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
                        <td style="width:100%; padding-bottom:4px; height:25px; text-align:left;
                          border-top: 1px solid #000; border-bottom: 1px solid #000; background:#d8d8d8;
                          vertical-align:middle;" colspan="3">
                           DATOS PERSONALES Y LABORALES
                        </td>      
                      </tr>
                      <tr>
                        <td style="width:52%; border-bottom: 1px solid #000; height:25px; text-align:left;
                         vertical-align:middle;">
                           <b>NOMBRES Y APELLIDOS:</b>&nbsp;<?=$cliente;?>                                 
                        </td>
                        <td style="width:28%; border-bottom: 1px solid #000; height:25px; text-align:left;
                          border-left: 1px solid #000; vertical-align:middle;">
                          <b>N° DOC. DE IDENTIDAD:</b>&nbsp;<?=$ci;?>                                  
                        </td>
                        <td style="width:20%; border-bottom: 1px solid #000; height:25px; text-align:left;
                          border-left: 1px solid #000; vertical-align:middle;">
                          <b>EXTENSION:</b>&nbsp;<?=$extension;?>                                 
                        </td>
                      </tr>
                      <tr>
                        <td style="width:52%; border-bottom: 1px solid #000; height:25px; text-align:left;">
                           <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                               <tr>
                                <td style="width:73%; text-align:left; height:25px;
                                vertical-align:middle;"><b>NACIONALIDAD:</b>&nbsp;<?=$pais;?></td>
                                <td style="width:27%; text-align:left; border-left: 1px solid #000; height:25px;
                                vertical-align:middle;">
                                   <b>FECHA DE NACIMIENTO:</b> 
                                </td> 
                               </tr> 
                           </table>                                 
                        </td>
                        <td style="width:28%; border-bottom: 1px solid #000; height:25px; text-align:left;
                          border-left: 1px solid #000;">
                           <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                               <tr>
                                 <td style="width:9%; height:25px; text-align:center;
                                 background:#d8d8d8; vertical-align:middle;">DIA </td>
                                 <td style="width:31%; height:25px; border-left: 1px solid #000;
                                   text-align:center;"><?=$dia_fn;?></td>
                                 <td style="width:9%; height:25px; border-left: 1px solid #000;
                                   text-align:center; background:#d8d8d8; vertical-align:middle;">MES </td>
                                 <td style="width:40%; height:25px; border-left: 1px solid #000;
                                   text-align:center;"><?=$mes_fn;?></td>
                                 <td style="width:11%; height:25px; border-left: 1px solid #000;
                                  text-align:center; background:#d8d8d8; vertical-align:middle;">AÑO</td>
                               </tr>
                            </table>                        
                        </td>
                        <td style="width:20%; border-bottom: 1px solid #000; height:25px; text-align:center;
                          border-left: 1px solid #000;">
                            <?=$anio_fn;?>                              
                        </td>
                      </tr>
                      <tr>
                        <td style="width:100%; height:25px; border-bottom: 1px solid #000;" colspan="3">
                           <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                               <tr>
                                 <td style="width:8%; height:25px; text-align:left; 
                                   background:#d8d8d8; vertical-align:middle;">
                                   <b>ESTADO CIVIL</b>
                                 </td>
      <?php
                              foreach ($estadoc as $key => $value) {
      ?>                           
                                 <td style="width:12%; height:25px; border-left: 1px solid #000; 
                                    vertical-align:middle;">
                                    <table cellspacing="0" cellpadding="0" border="0" style="width:100%;
                                       font-size:100%; border: 0px solid #000;">
                                       <tr>
                                          <td style="width:30%; padding-left:8px;">
      
                                            <div style="width: 15px; height: 12px; border: 1px solid #000; 
                                               text-align:center; vertical-align:middle;">
      <?php
                                                if ($value[0] === $row['cl_estado_civil']) {
                                                      echo 'X';
                                                }else {
                                                      echo '&nbsp;';
                                                } 
      ?>
                                             </div> 
                                          </td>
                                          <td style="width:70%; text-align:center;">
                                              <?=strtoupper($value[1]);?>
                                          </td>
                                       </tr>
                                    </table>    
                                 </td>
      <?php
                              }
      ?>                           
                                 <!--
                                 <td style="width:12%; height:25px; border-left: 1px solid #000;
                                    vertical-align:middle;">
                                    <table cellspacing="0" cellpadding="0" border="0" style="width:100%;
                                       font-size:100%; border: 0px solid #000;">
                                       <tr>
                                          <td style="width:30%; padding-left:8px;">
                                            <div style="width: 15px; height: 12px; border: 1px solid #000; 
                                               text-align:center;">
                                              
                                             </div> 
                                          </td>
                                          <td style="width:70%; text-align:center;">
                                              CASADO(A)
                                          </td>
                                       </tr>
                                    </table>   
                                 </td>
                                 <td style="width:12%; height:25px; border-left: 1px solid #000;
                                    vertical-align:middle;">
                                    <table cellspacing="0" cellpadding="0" border="0" style="width:100%;
                                       font-size:100%; border: 0px solid #000;">
                                       <tr>
                                          <td style="width:30%; padding-left:8px;">
                                            <div style="width: 15px; height: 12px; border: 1px solid #000; 
                                               text-align:center;">
                                              
                                             </div> 
                                          </td>
                                          <td style="width:70%; text-align:center;">
                                              UNION LIBRE
                                          </td>
                                       </tr>
                                    </table> 
                                 </td>
                                 <td style="width:12%; height:25px; border-left: 1px solid #000; 
                                    vertical-align:middle;">
                                    <table cellspacing="0" cellpadding="0" border="0" style="width:100%;
                                       font-size:100%; border: 0px solid #000;">
                                       <tr>
                                          <td style="width:30%; padding-left:8px;">
                                            <div style="width: 15px; height: 12px; border: 1px solid #000; 
                                               text-align:center;">
                                              
                                             </div> 
                                          </td>
                                          <td style="width:70%; text-align:center;">
                                              DIVORCIADO(A)
                                          </td>
                                       </tr>
                                    </table> 
                                 </td>
                                 <td style="width:10%; height:25px; border-left: 1px solid #000;
                                    vertical-align:middle;">
                                    <table cellspacing="0" cellpadding="0" border="0" style="width:100%;
                                       font-size:100%; border: 0px solid #000;">
                                       <tr>
                                          <td style="width:30%; padding-left:8px;">
                                            <div style="width: 15px; height: 12px; border: 1px solid #000; 
                                               text-align:center;">
                                              
                                             </div> 
                                          </td>
                                          <td style="width:70%; text-align:center;">
                                              VIUDO(A)
                                          </td>
                                       </tr>
                                    </table> 
                                 </td>
                                 -->
                                 <td style="width:32%; height:25px; border-left: 1px solid #000; text-align:left;">
                                    <b>DIRECCION DOMICILIO:</b>&nbsp;<?=$direccion;?>
                                 </td>
                               </tr>
                            </table> 
                        </td>
                      </tr>
                      <tr>
                        <td style="width:100%; height:25px; border-bottom: 1px solid #000;" colspan="3">
                            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                              <tr>
                                <td style="width:43%; text-align:left; height:25px; vertical-align:middle;">
                                  <b>PROFESION/ OCUPACION:</b>&nbsp;<?=$prof_ocupacion;?>
                                </td>
                                <td style="width:32%; text-align:left; height:25px; border-left: 1px solid #000;
                                   vertical-align:middle;">
                                  <b>LUGAR DE TRABAJO:</b>&nbsp;<?=$lugar_trabajo;?>    
                                </td>
                                <td style="width:25%; text-align:left; height:25px; border-left: 1px solid #000;
                                  vertical-align:middle;">
                                  <b>CARGO:</b>&nbsp;<?=$cargo;?>
                                </td>
                              </tr>
                            </table> 
                        </td>
                      </tr>
                      <tr>
                        <td style="width:100%; height:25px; border-bottom: 1px solid #000;" colspan="3">
                           <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                               <tr>
                                 <td style="width:12.5%; height:25px; text-align:left; 
                                 background:#d8d8d8; vertical-align:middle;"><b>INGRESOS MENSUALES</b></td>
      <?php
                         if(is_array($ingreso_mensual)){       
                              foreach ($ingreso_mensual as $key => $value) {
      
      ?>  
                                 <td style="width:12.5%; height:25px; border-left: 1px solid #000;
                                    vertical-align:middle;">
                                    <table cellspacing="0" cellpadding="0" border="0" style="width:100%;
                                       font-size:100%; border: 0px solid #000;">
                                       <tr>
                                          <td style="width:30%; padding-left:8px;">
                                            <div style="width: 15px; height: 12px; border: 1px solid #000; 
                                               text-align:center; vertical-align:middle;">
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
                         }
      ?>                           
                                 <!--
                                 <td style="width:12.5%; height:25px; border-left: 1px solid #000;
                                    vertical-align:middle;">
                                    <table cellspacing="0" cellpadding="0" border="0" style="width:100%;
                                       font-size:100%; border: 0px solid #000;">
                                       <tr>
                                          <td style="width:30%; padding-left:8px;">
                                            <div style="width: 15px; height: 12px; border: 1px solid #000; 
                                               text-align:center;">
                                              &nbsp;
                                             </div> 
                                          </td>
                                          <td style="width:70%; text-align:center;">
                                              De Bs. 2,001 a Bs. 4,000
                                          </td>
                                       </tr>
                                    </table>   
                                 </td>
                                 <td style="width:12.5%; height:25px; border-left: 1px solid #000;
                                    vertical-align:middle;">
                                    <table cellspacing="0" cellpadding="0" border="0" style="width:100%;
                                       font-size:100%; border: 0px solid #000;">
                                       <tr>
                                          <td style="width:30%; padding-left:8px;">
                                            <div style="width: 15px; height: 12px; border: 1px solid #000; 
                                               text-align:center;">
                                              &nbsp;
                                             </div> 
                                          </td>
                                          <td style="width:70%; text-align:center;">
                                              De Bs. 4,001 a Bs. 8,000
                                          </td>
                                       </tr>
                                    </table> 
                                 </td>
                                 <td style="width:12.5%; height:25px; border-left: 1px solid #000; 
                                    vertical-align:middle;">
                                    <table cellspacing="0" cellpadding="0" border="0" style="width:100%;
                                       font-size:100%; border: 0px solid #000;">
                                       <tr>
                                          <td style="width:30%; padding-left:8px;">
                                            <div style="width: 15px; height: 12px; border: 1px solid #000; 
                                               text-align:center;">
                                              &nbsp;
                                             </div> 
                                          </td>
                                          <td style="width:70%; text-align:center;">
                                              De Bs. 8,001 a Bs. 12,000
                                          </td>
                                       </tr>
                                    </table> 
                                 </td>
                                 <td style="width:12.5%; height:25px; border-left: 1px solid #000;
                                    vertical-align:middle;">
                                    <table cellspacing="0" cellpadding="0" border="0" style="width:100%;
                                       font-size:100%; border: 0px solid #000;">
                                       <tr>
                                          <td style="width:30%; padding-left:8px;">
                                            <div style="width: 15px; height: 12px; border: 1px solid #000; 
                                               text-align:center;">
                                              &nbsp;
                                             </div> 
                                          </td>
                                          <td style="width:70%; text-align:center;">
                                              De Bs. 12,001 a Bs. 15,000
                                          </td>
                                       </tr>
                                    </table> 
                                 </td>
                                 <td style="width:12.5%; height:25px; border-left: 1px solid #000;
                                    vertical-align:middle;">
                                    <table cellspacing="0" cellpadding="0" border="0" style="width:100%;
                                       font-size:100%; border: 0px solid #000;">
                                       <tr>
                                          <td style="width:30%; padding-left:8px;">
                                            <div style="width: 15px; height: 12px; border: 1px solid #000; 
                                               text-align:center;">
                                              &nbsp;
                                             </div> 
                                          </td>
                                          <td style="width:70%; text-align:center;">
                                              De Bs. 15,001 a Bs. 20,000
                                          </td>
                                       </tr>
                                    </table> 
                                 </td>
                                 <td style="width:12.5%; height:25px; border-left: 1px solid #000;
                                    vertical-align:middle;">
                                    <table cellspacing="0" cellpadding="0" border="0" style="width:100%;
                                       font-size:100%; border: 0px solid #000;">
                                       <tr>
                                          <td style="width:30%; padding-left:8px;">
                                            <div style="width: 15px; height: 12px; border: 1px solid #000; 
                                               text-align:center;">
                                              &nbsp;
                                             </div> 
                                          </td>
                                          <td style="width:70%; text-align:center;">
                                              De 20,000 en adelante 
                                          </td>
                                       </tr>
                                    </table> 
                                 </td>
                                 -->
                               </tr>
                            </table> 
                        </td>
                      </tr>    
                  </table>
                  
                  <table 
                      cellpadding="0" cellspacing="0" border="0" 
                      style="width: 100%; height: auto; font-size: 75%; font-family: Arial; 
                      margin-top:100px;">
                     <tr>
                      <td style="width:10%;"></td>
                      <td style="width:80%; border-bottom: 1px solid #000;">&nbsp;</td>
                      <td style="width:10%;"></td>  
                     </tr>
                     <tr>
                      <td style="width:10%;"></td>
                      <td style="width:80%; text-align:center; font-weight:bold;">
                       Firma del Declarante (Cliente)<br>
                       * El presente formulario tiene carácter de declaración jurada, firmo en conformidad de los datos contenidos en el presente documento 
                      </td>
                      <td style="width:10%;"></td>  
                     </tr>
                  </table>
                  
                  <table 
                      cellpadding="0" cellspacing="0" border="0" 
                      style="width: 100%; height: auto; font-size: 75%; font-family: Arial; 
                      margin-top:100px;">
                     <tr>
                      <td style="width:10%;"></td>
                      <td style="width:80%; border-bottom: 1px solid #000;">&nbsp;</td>
                      <td style="width:10%;"></td>  
                     </tr>
                     <tr>
                      <td style="width:10%;"></td>
                      <td style="width:80%; text-align:center; font-weight:bold;">
                       Firma y Sello del Funcionario de Sudamericana que recibe el Formulario 
                      </td>
                      <td style="width:10%;"></td>  
                     </tr>
                     <tr><td style="width:100%; height:150px;" colspan="3"></td></tr>
                  </table>      	
              </div>
            </div>
            <!--FIN UIF NATURAL-->              
<?php			
		}
?>
        <page><div style="page-break-before: always;">&nbsp;</div></page>   
        
        
<?php
        if($row['fecha_emision']!=='0000-00-00'){
		  $fecha_em = $row['fecha_emision'];
		  $vec_f = explode('-',$fecha_em);
		  $digi = substr($vec_f[0], -2);
		  $mes = $vec_f[1];
	    }else{
		  $fecha_em = $row['fecha_creacion'];
		  $vec_f = explode('-',$fecha_em);
		  $digi = substr($vec_f[0], -2);
		  $mes = $vec_f[1];
	    } 
?>      <!--CARTA SUDAMERICANA-->  
        <div style="width: 775px; border: 0px solid #FFFF00; text-align:center;">
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-family: Arial;">
                <tr>
                  <td style="width:100%; text-align:right;">
                     La Paz, <?=get_date_format_trd_vt($fecha_em)?><br><br>
                     BI-TR-SUD/<?=str_pad($row['no_emision'],4,'0',STR_PAD_LEFT);?>

                  </td> 
                </tr>
                <tr>
                  <td style="width:100%; font-weight:bold; text-align:left; font-size: 80%;">
                     Señor<br>
                     <?=$cliente_nombre;?><br>
                     Presente.-<br><br>
                     Ref.:	Póliza Multiriesgo N° <?=$poliza;?>
                  </td> 
                </tr>
            </table>     
        </div>
        <br/>
        <div style="width: 775px; border: 0px solid #FFFF00;">
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-family: Arial; 
                padding-top:4px; padding-bottom:3px;">
                <tr> 
                  <td style="width:100%; padding-bottom:4px; text-align:justify;">
                     A tiempo de agradecerle(s) por la confianza depositada en SUDAMERICANA S.R.L., le(s) informamo(s) 
                     que cumpliendo con sus instrucciones, procedimos a solicitar a la compañía BISA SEGUROS S.A la 
                     emisión de la Póliza, de acuerdo a Términos y Condiciones previamente aprobados por Usted(es).
                     <br><br>
                     Remitimos la documentación original según detalle líneas abajo, de acuerdo a lo establecido en el
                     Art. 1013 Discrepancias en las Pólizas, del Código de Comercio, su contenido se considerará 
                     aceptado.
                  </td>      
                </tr>
            </table>
            
            <table 
              cellpadding="0" cellspacing="0" border="0" 
              style="width: 100%; height: auto; font-size: 80%; font-family: Arial;">
              <tr>
                  <td style="width: 50%; text-align: justify; padding: 5px; border-right: 1px solid #000;
                      border-top: 1px solid #000; border-left: 1px solid #000; border-bottom: 1px solid #000;"
                      valign="top">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                      <tr>
                        <td style="width:2%; font-weight:bold;" valign="top">1.&nbsp;</td>
                        <td style="width:98%;">
                           <b>PÓLIZA <?=$poliza;?></b>
                           <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                              <tr>
                                <td style="width:2%; font-weight:bold; padding-top:10px;" valign="top">&bull;</td>
                                <td style="width:98%; padding-top:10px;">
                                   <b>Condiciones Particulares que estipulan:</b>
                                   <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                      <tr>
                                        <td style="width:2%; font-weight:bold;">o</td>
                                        <td style="width:98%;">
                                           	Compañía de Seguros
                                        </td>
                                      </tr>
                                      <tr>
                                        <td style="width:2%; font-weight:bold;">o</td>
                                        <td style="width:98%;">
                                           	Número de Póliza
                                        </td>
                                      </tr>
                                      <tr>
                                        <td style="width:2%; font-weight:bold;">o</td>
                                        <td style="width:98%;">
                                           	Ramo de Seguro
                                        </td>
                                      </tr>
                                      <tr>
                                        <td style="width:2%; font-weight:bold;">o</td>
                                        <td style="width:98%;">
                                           	Materia del Seguro
                                        </td>
                                      </tr>
                                      <tr>
                                        <td style="width:2%; font-weight:bold;">o</td>
                                        <td style="width:98%;">
                                           	Coberturas y Límites Asegurados
                                        </td>
                                      </tr>
                                      <tr>
                                        <td style="width:2%; font-weight:bold;">o</td>
                                        <td style="width:98%;">
                                           	Cláusulas Adicionales
                                        </td>
                                      </tr>
                                      <tr>
                                        <td style="width:2%; font-weight:bold;">o</td>
                                        <td style="width:98%;">
                                           	Condiciones Especiales
                                        </td>
                                      </tr>
                                      <tr>
                                        <td style="width:2%; font-weight:bold;">o</td>
                                        <td style="width:98%;">
                                           	Exclusiones Generales
                                        </td>
                                      </tr>
                                      <tr>
                                        <td style="width:2%; font-weight:bold;">o</td>
                                        <td style="width:98%;">
                                           	Prima
                                        </td>
                                      </tr>
                                      <tr>
                                        <td style="width:2%; font-weight:bold;">o</td>
                                        <td style="width:98%;">
                                           	Vigencia
                                        </td>
                                      </tr>
                                      <tr>
                                        <td style="width:2%; font-weight:bold;">o</td>
                                        <td style="width:98%;">
                                           	Forma de Pago
                                        </td>
                                      </tr>
                                   </table>      
                                </td>
                              </tr>
                              <tr>
                                <td style="width:2%; font-weight:bold;" valign="top">&bull;</td>
                                <td style="width:98%;">
                                   <b>Condicionados Generales</b>
                                </td>
                              </tr>
                              <tr>
                                <td style="width:2%; font-weight:bold;">&bull;</td>
                                <td style="width:98%;">
                                   <b>Textos de Clausulas Adicionales y/o Anexos</b>
                                </td>
                              </tr>
                           </table> 
                        </td>       
                      </tr>
                      <tr>
                        <td style="width:2%; font-weight:bold; padding-top:10px;" valign="top">2.&nbsp;</td>
                        <td style="width:98%; text-align:justify; font-weight:bold; padding-top:10px;">
                           PAGOS DE PRIMAS<br><br>
                           de acuerdo a lo estipulado en el Art. 58 Inciso d) de la Ley de Seguros 1883, de no 
                           realizar el pago dentro de los plazos establecidos, la vigencia y cobertura de su Póliza 
                           quedan suspendidas, como consecuencia, si ocurriera un siniestro mientras las primas se 
                           encuentren impagas, el mismo no estará cubierto.
                        </td>
                      </tr>
                      <tr>
                         <td style="width:2%; font-weight:bold; padding-top:10px;" valign="top">3.&nbsp;</td>
                         <td style="width:98%; padding-top:10px;">
                           <b>NOTA ACLARATORIA:</b>
                           <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                              <tr>
                                <td style="width:2%; font-weight:bold; padding-top:10px;" valign="top">&bull;</td>
                                <td style="width:98%; padding-top:10px;">
                                    Se aclara que esta póliza no se renovará posteriormente a la cancelación total de 
                                    la operación crediticia del asegurado con el contratante, de acuerdo al monto 
                                    subrogado y declarado en la póliza
                                </td>
                              </tr>
                              <tr>
                                <td style="width:2%; font-weight:bold;" valign="top">&bull;</td>
                                <td style="width:98%;">
                                    Se aclara que la vigencia de la póliza podrá terminar en forma anticipada, cuando 
                                    el Asegurado realice el pago anticipado del monto total de su operación crediticia
                                    adeudada al Contratante. Sin embargo, si la prima fue pagada al contado, la póliza
                                    se mantendrá vigente hasta su finalización.
                                </td>
                              </tr>
                           </table>
                         </td>
                       </tr>
                      <tr>
                        <td colspan="2" style="width:100%; font-weight:bold; text-align:justify;">
                           <u>En tal sentido al ser una póliza que no cuenta con renovación posterior al pago de la deuda 
                        con el contratante y siendo este el tomador de la póliza, Sudamericana no enviara aviso de 
                        vencimiento para la renovación o finalización de vigencia.</u>    
                        </td>
                      </tr>
                     </table>
                  </td>
                  <td style="width: 50%; text-align: justify; padding: 5px; border-top: 1px solid #000;
                     border-right: 1px solid #000; border-bottom: 1px solid #000;" valign="top">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                       <tr>
                         <td style="width:2%; font-weight:bold;" valign="top">4.&nbsp;</td>
                         <td style="width:98%;" valign="top">
                           <b>ASPECTOS RELEVANTES DE LA NORMA VIGENTE:  (CÓDIGO DE COMERCIO)</b><br><br>
                           <b>Art. 1000.- (OBLIGACIÓN DE MANTENER EL ESTADO DE RIESGO).</b> El asegurado está obligado
                            a mantener el estado del riesgo, en tal virtud, debe comunicar por escrito al asegurador 
                            las agravaciones substanciales del riesgo debidas a hechos propios, antes de su ejecución 
                            y los ajenos a su voluntad, dentro de los ocho días siguientes al momento en que los 
                            conozca. Si se omite la comunicación de estos hechos, cesan en lo futuro las obligaciones 
                            del asegurador, correspondiendo al mismo probar la agravación del riesgo. Comunicada la 
                            agravación del riesgo dentro de los términos previstos en este artículo, el asegurador 
                            puede rescindir el contrato o exigir el reajuste a que haya lugar en el importe de la 
                            prima, dentro de los quince días siguientes.<br><br>
                            
                            <b>Art. 1004.- (DOLO O MALA FE).</b> El dolo o mala fe del asegurado en la agravación del 
                            riesgo hace nulo el contrato de seguro en los términos del artículo 999. (Las 
                            declaraciones falsas o reticentes hechas con dolo o mala fe hacen nulo el contrato de 
                            seguro. En este caso el asegurado no tendrá derecho a la devolución de las primas pagadas)
                            <br><br>
                            Si hubiera necesidad de aclarar cualquier aspecto relacionado con esta documentación, nos 
                            ponemos a vuestra entera disposición y les reiteramos nuestras consideraciones más 
                            distinguidas.
                         </td> 
                       </tr>
                     </table>     
                  </td>   
              </tr>
            </table>
            <div style="font-size: 80%; text-align:justify; margin-top:10px; margin-bottom:35px;">  
               <b>Procedimientos en Caso de Siniestro:</b> El asegurado o beneficiario, tan pronto y a más tardar dentro de los tres (3) días calendario de tener conocimiento del siniestro, deben comunicar tal hecho al asegurador, salvo fuerza mayor o impedimento justificado, de acuerdo a lo que estipulan las condiciones de la póliza. El asegurador debe pronunciarse sobre el derecho del asegurado o beneficiario dentro de los 15 días calendario siguientes de recibida la información y evidencia requerida requeridas precedentemente, dicho plazo no corre termino hasta que se presente la totalidad de la información y evidencias requeridas de acuerdo a las condiciones de la póliza, el asegurador debe pagar a obligación dentro de los treinta (30) días siguientes.<br>
<b>Sudamericana S.R.L, se pone a su total disposición para aclarar cualquier aspecto adicional que necesite respecto a la documentación entregada, como para la atención y asesoramiento de siniestros que afecten las coberturas mencionadas en la presente nota, para lo cual ponemos a su disposición los numero de nuestras oficinas que se encuentran al pie de página.</b>
            </div>
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-family: Arial; 
                padding-top:20px;">
               <tr>
                <td style="width:14%; text-align:left;">FIRMA CLIENTE</td>
                <td style="width:30%;">&nbsp;
                  
                </td>
                <td style="width:25%;">FIRMA SUDAMERICANA (ESCANEADA)</td>
                                
                <td style="width:33%;">&nbsp;
                  
                </td> 
               </tr>
            </table>   	
        </div>   
        <!--FIN CARTA SUDAMERICANA--> 
		
<?php		
	 }
?>        
        
      </div>
   </div>

<?php
	$html = ob_get_clean();
	return $html;
}

function get_date_format_trd_vt($fecha){
	$date = date_create($fecha);
	
	$day = date_format($date, 'd');
	$month = date_format($date, 'F');
	$year = date_format($date, 'Y');
	
	return $day.' de '.get_month_espanol_trd_vt($month).' de '.$year;
}

function get_month_espanol_trd_vt($month){
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

function plaza_trd_vt($sucursal){
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