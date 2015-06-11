<?php
function au_formulario_vt($link, $row, $rsDt, $url, $implant, $fac, $reason = '', $product) {
	//CONSULTA SOLICITUD
	$query = "select 
				aucc.id_cotizacion,
				aucc.no_cotizacion,
				aucc.id_ef as idef,
				aucc.id_cliente,
				aucc.certificado_provisional,
				aucc.garantia,
				aucc.tipo,
				aucc.ini_vigencia,
				aucc.fin_vigencia,
				aucc.tipo_plazo as tip_plz_code,
				aucc.plazo,
				aucc.tipo_plazo,
				aucc.fecha_creacion,
				aucc.id_usuario,
				aucc.forma_pago as code_fpago,
				(case aucc.forma_pago
				  when 'CO' then 'Al Contado'
				  when 'CR' then 'Al Credito'
				 end) as forma_pago,
				sc.nombre as compania,
				sc.logo as logo_cia,
				sc.id_compania,
				sef.nombre as ef_nombre,
				sef.logo as logo_ef,
				su.nombre as u_nombre,
				su.email as u_email,
				sdu.departamento as u_departamento,
				case clt.tipo
					when 0 then 'titular'
					when 1 then 'empresa'
				end as tipo_cliente,
				clt.razon_social,
				clt.actividad,
				clt.ejecutivo,
				clt.cargo,
				clt.paterno,
				clt.materno,
				clt.nombre,
				clt.ap_casada,
				clt.ci as nit,
				concat(clt.ci,
						' ',
						clt.complemento,
						' ',
						sd.codigo) as ci,
				clt.fecha_nacimiento,
				clt.telefono_domicilio,
				clt.telefono_celular,
				clt.telefono_oficina,
				clt.direccion_domicilio,
				clt.direccion_laboral,
				clt.email,
				sefc.id_ef_cia,
				sc.id_compania,
				sh.monto_facultativo,
				sh.anio as anio_max,
			    '' as no_emision 
			from
				s_au_cot_cabecera as aucc
					inner join
				s_entidad_financiera as sef ON (sef.id_ef = aucc.id_ef)
					inner join
				s_ef_compania as sefc ON (sefc.id_ef = sef.id_ef
					and sefc.producto = '".$product."')
					inner join
				s_compania as sc ON (sc.id_compania = sefc.id_compania)
					inner join
				s_usuario as su ON (su.id_usuario = aucc.id_usuario)
					inner join
				s_departamento as sdu on (sdu.id_depto = su.id_depto)	
					inner join
				s_au_cot_cliente as clt ON (clt.id_cliente = aucc.id_cliente)
					inner join
				s_departamento as sd ON (sd.id_depto = clt.extension)
					inner join
				s_sgc_home as sh ON (sh.id_ef = aucc.id_ef
					and sh.producto = '".$product."')
			where
				aucc.id_cotizacion = '".$row['id_cotizacion']."'
					and sc.id_compania = '".$row['id_compania']."';";				
	$consult = $link->query($query, MYSQLI_STORE_RESULT);
	$rowsc = $consult->fetch_array(MYSQLI_ASSOC);
	$data = $link->getTasaAu($rowsc['idef'], $rowsc['id_cotizacion']);
	if (count($data) > 0) {
		foreach ($data as $key => $vh) {
			if ($vh['v_prima'] < $vh['v_prima_minima']) {
				$vh['v_prima'] = $vh['v_prima_minima'];
			}

		}
	}
	//SOLICITUD VEHICULOS
	$queryVh = "select 
			auc.id_vehiculo,
			auc.id_cotizacion,
			(case auc.plaza
			   when 'LP' then 'La Paz'
			   when 'CB' then 'Cochabamba'
			   when 'SC' then 'Santa Cruz'
			   when 'RP' then 'Resto del Pais'
			end) as plaza,
			auc.motor,
			auc.anio,
			auc.placa,
			auc.cilindrada,
			auc.chasis,
			auc.uso,
			auc.traccion,
			auc.color,
			auc.km,
			auc.no_asiento,
			auc.valor_asegurado,
			auc.tasa,
			auc.prima,
			auc.created_at,
			auc.updated_at,
			auc.facultativo,
			autv.vehiculo,
			autv.categoria as categoria_vh,
			aumr.marca,
			aumod.modelo
		from
			s_au_cot_detalle as auc
				left join
			s_au_tipo_vehiculo as autv ON (autv.id_tipo_vh = auc.id_tipo_vh)
				left join
			s_au_marca as aumr ON (aumr.id_marca = auc.id_marca)
				left join
			s_au_modelo as aumod ON (aumod.id_modelo = auc.id_modelo)
		where
			auc.id_cotizacion = '".$rowsc['id_cotizacion']."';";
	$consultVh = $link->query($queryVh,MYSQLI_STORE_RESULT);
	$rowDtVh = $consultVh->fetch_array(MYSQLI_ASSOC);				
	ob_start();
?>

  <div id="container-c" style="width: 785px; height: auto; 
    border: 0px solid #0081C2; padding: 5px;">
	  <div id="main-c" style="width: 775px; font-weight: normal; font-size: 12px; 
      font-family: Arial, Helvetica, sans-serif; color: #000000;">
<?php
     $j = 0;
     $num_titulares = $rsDt->num_rows;
	 $text = '';		
     while($rowDt = $rsDt->fetch_array(MYSQLI_ASSOC)){
		   if($row['tipo_cliente']=='J'){
			   //SOLICITUD
			   $razon_social = $rowsc['razon_social'];
			   $actividad = $rowsc['actividad'];
			   $ejecutivo = $rowsc['ejecutivo'];
			   $cargo = $rowsc['cargo'];
			   $nit_ci = $rowsc['nit'];
			   $cliente = $razon_social;
			   
			   $ap_paterno = '';
			   $ap_materno = '';
			   $nombre = '';
			   $fono_celular = '';
			   $fono_domicilio = $rowsc['telefono_domicilio'];
			   
			   //EMISION
			   $cliente_nombre = $row['cl_razon_social'];
			   $cliente_nitci = $row['ci'];
			   $cliente_direccion = $row['direccion_laboral'];
			   $cliente_fono = $row['telefono_oficina'];
		   }elseif($row['tipo_cliente']=='N'){
			   //SOLICITUD
			   $ap_paterno = $rowsc['paterno'];
			   $ap_materno = $rowsc['materno'];
			   $nombre = $rowsc['nombre'];
			   $fono_celular = $rowsc['telefono_celular'];
			   $fono_domicilio = $rowsc['telefono_domicilio'];
			   $nit_ci = $rowsc['ci'];
			   $cliente = $rowsc['nombre'].' '.$rowsc['paterno'].' '.$rowsc['materno'];
			   
			   $razon_social = '';
			   $actividad = '';
			   $ejecutivo = '';
			   $cargo = '';
			   
			   //EMISION
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
          <!--SOLICITUD-->
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
                            FORMULARIO DE SOLICITUD DE SEGURO DE AUTOMOTOR<br>
                            PARA ENTIDADES FINANCIERAS
                          </td>
                        </tr>
                        <tr>
                          <td style="width:100%; font-size:60%; text-align:center; font-weight:bold;">
                            CODIGO APS No.: 109-910502-2007 12 311 3002<br>
                            R.A.  APS/DS/No. 395-2012
                          </td>
                        </tr>
                    </table>
                  </td>
                  <td style="width:20%; text-align:right;">
                     <img src="<?=$url;?>images/<?=$rowsc['logo_cia'];?>" height="60"/>
                  </td>
              </tr>
              <tr>
                <td style="width:100%; text-align:right; font-size:65%; padding-right:12px; padding-top:3px;" colspan="3">
                 Solicitud No <?=$rowsc['no_cotizacion'];?>
                </td>
              </tr>
            </table>      
         </div>
         <br/>
         <div style="width: 775px; border: 0px solid #FFFF00;">
             <span style="font-weight:bold; font-size:80%;">
               INFORMACION GENERAL:
             </span>
             <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-family: Arial; 
                padding-bottom:5px;">
                <tr> 
                  <td style="width:100%; padding-bottom:4px;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 100%;">
                          <tr> 
                            <td style="width:100%; padding-bottom:4px;">
                               <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 100%;">
                                  <tr>
                                    <td style="width:25%; border-top: 1px solid #333; border-left: 1px solid #333;
                                      border-bottom: 1px solid #333; background:#d8d8d8;">
                                      Nombre o Razón Social del Solicitante: </td>
                                    <td style="border: 1px solid #333; width:75%;">&nbsp;
                                        <?=$razon_social;?>
                                    </td>
                                  </tr>
                               </table>
                            </td>      
                          </tr>
                          <tr>
                            <td style="width:100%; padding-bottom:4px;">
                               <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                  <tr>
                                    <td style="width:17%; border: 1px solid #333; background:#d8d8d8;">
                                      Nombre Persona  Particular 
                                    </td>
                                    <td style="width:27%; border-top: 1px solid #333; 
                                      border-bottom: 1px solid #333; text-align:center;">
                                      <?=$ap_paterno;?>                                     
                                    </td>
                                    <td style="width:28%; border-top: 1px solid #333; border-bottom: 1px solid #333;
                                      border-left: 1px solid #333; text-align:center;">
                                      <?=$ap_materno;?>
                                    </td>
                                    <td style="width:28%; border-top: 1px solid #333; border-right: 1px solid #333;
                                      border-bottom: 1px solid #333; border-left: 1px solid #333; 
                                      text-align:center;">
                                      <?=$nombre;?>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td style="width:17%;">
                                    </td>
                                    <td style="width:27%; text-align:center;">
                                     APELLIDO PATERNO
                                    </td>
                                    <td style="width:28%; text-align:center;">
                                     APELLIDO MATERNO
                                    </td>
                                    <td style="width:28%; text-align:center;">
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
                                    <td style="width:17%; border-top: 1px solid #333; border-left: 1px solid #333;
                                      border-bottom: 1px solid #333; background:#d8d8d8;">
                                      Domicilio Legal: </td>
                                    <td style="border: 1px solid #333; width:83%;">&nbsp;
                                        <?=$rowsc['direccion_domicilio'];?>
                                    </td>
                                  </tr>
                               </table>
                            </td>      
                          </tr>
                          <tr> 
                            <td style="width:100%; padding-bottom:4px;">
                               <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 100%;">
                                  <tr>
                                    <td style="width:17%; border-top: 1px solid #333; border-left: 1px solid #333;
                                      border-bottom: 1px solid #333; background:#d8d8d8;">
                                      Dirección Laboral: </td>
                                    <td style="border: 1px solid #333; width:83%;">&nbsp;
                                        <?=$rowsc['direccion_laboral'];?>
                                    </td>
                                  </tr>
                               </table>
                            </td>      
                          </tr>
                          <tr> 
                            <td style="width:100%; padding-bottom:4px;">
                               <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 100%;">
                                  <tr>
                                    <td style="width:11%; border: 1px solid #333; background:#d8d8d8;
                                      text-align:left;">
                                      NIT/C.I.: 
                                    </td>
                                    <td style="border-top: 1px solid #333; border-right: 1px solid #333; 
                                      border-bottom: 1px solid #333; width:39%;">&nbsp;
                                        <?=$nit_ci;?>
                                    </td>
                                    <td style="border-top: 1px solid #333; border-right: 1px solid #333; width:10%;
                                      border-bottom: 1px solid #333; background:#d8d8d8;">
                                      Email: 
                                    </td>
                                    <td style="border-top: 1px solid #333; border-right: 1px solid #333; width:40%;
                                      border-bottom: 1px solid #333;">&nbsp;
                                        <?=$rowsc['email'];?>
                                    </td>
                                  </tr>
                               </table>
                            </td>      
                          </tr>
                          <tr>
                            <td style="width:100%; padding-bottom:4px;">
                               <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                  <tr>
                                    <td style="width:10%; border:1px solid #333; background:#d8d8d8;
                                      text-align:left;">
                                      Teléfono Dom: 
                                    </td>
                                    <td style="width:24%; border-top:1px solid #333; border-right:1px solid #333;
                                      border-bottom:1px solid #333;">&nbsp;
                                        <?=$fono_domicilio;?>
                                    </td>
                                    <td style="width:10%; border-top:1px solid #333; border-right:1px solid #333;
                                      border-bottom:1px solid #333; background:#d8d8d8;">
                                      Telefono Of. 
                                    </td>
                                    <td style="width:24%; border-top:1px solid #333; border-right:1px solid #333;
                                      border-bottom:1px solid #333;">&nbsp;
                                        <?=$rowsc['telefono_oficina'];?>
                                    </td>
                                    <td style="width:8%; border-top:1px solid #333; border-right:1px solid #333;
                                      border-bottom:1px solid #333; background:#d8d8d8;">
                                      Celular: 
                                    </td>
                                    <td style="width:24%; border-top:1px solid #333; border-right:1px solid #333;
                                      border-bottom:1px solid #333;">&nbsp;
                                        <?=$fono_celular;?>
                                    </td> 
                                  </tr>
                                </table> 
                            </td>              
                          </tr>   
                       </table>
                     <span style="font-weight:bold;">
                      LLENAR SOLO EN CASO DE SER PERSONA JURIDICA
                     </span>
                     <table 
                          cellpadding="0" cellspacing="0" border="0" 
                          style="width: 100%; height: auto; font-size: 100%; font-family: Arial; 
                          padding-bottom:5px;">    
                          <tr> 
                            <td style="width:100%; padding-bottom:4px;">
                               <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 100%;">
                                  <tr>
                                    <td style="width:20%; border-top: 1px solid #333; border-left: 1px solid #333;
                                      border-bottom: 1px solid #333; background:#d8d8d8;">
                                      Actividad y/o Giro del Negocio: </td>
                                    <td style="border: 1px solid #333; width:80%;">&nbsp;
                                        <?=$actividad;?>
                                    </td>
                                  </tr>
                               </table>
                            </td>
                          </tr>
                          <tr> 
                            <td style="width:100%; padding-bottom:4px;">
                               <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 100%;">
                                  <tr>
                                    <td style="width:20%; border-top: 1px solid #333; border-left: 1px solid #333;
                                      border-bottom: 1px solid #333; background:#d8d8d8;">
                                      Principal Ejecutivo: </td>
                                    <td style="border: 1px solid #333; width:80%;">&nbsp;
                                        <?=$ejecutivo;?>
                                    </td>
                                  </tr>
                               </table>
                            </td>      
                          </tr>
                          <tr>
                            <td style="width:100%; padding-bottom:4px;">
                               <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                   <tr>
                                     <td style="width: 7%; border: 1px solid #333; background:#d8d8d8;">Cargo: </td>
                                     <td style="width: 65%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333;">&nbsp;
                                       <?=$cargo;?>
                                     </td>
                                     <td style="width: 8%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333; background:#d8d8d8;">
                                       Teléfono: 
                                     </td>
                                     <td style="width: 20%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333;">
                                       <?=$rowsc['telefono_oficina'];?>
                                     </td>
                                   </tr>
                                </table> 
                            </td> 
                          </tr>
                       </table>
                     <span style="font-weight:bold;">
                        DATOS DEL VEHICULO
                     </span>
                     <table 
                          cellpadding="0" cellspacing="0" border="0" 
                          style="width: 100%; height: auto; font-size: 100%; font-family: Arial; 
                          padding-top:0px;">
                          <tr>
                            <td style="width:100%; padding-bottom:4px;">
                               <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                 <tr>
                                  <td style="width:12%; border: 1px solid #333; background:#d8d8d8;">Tipo de Vehiculo: </td>
                                  <td style="width:34%; border-top: 1px solid #333; border-right: 1px solid #333;
                                     border-bottom: 1px solid #333;">&nbsp;
                                     <?=$rowDtVh['vehiculo'];?>
                                  </td>
                                  <td style="width:7%; border-top: 1px solid #333; border-right: 1px solid #333;
                                     border-bottom: 1px solid #333; background:#d8d8d8;">
                                     Marca:
                                  </td> 
                                  <td style="width:20%; border-top: 1px solid #333; border-right: 1px solid #333;
                                     border-bottom: 1px solid #333;">&nbsp;
                                     <?=$rowDtVh['marca'];?>
                                  </td> 
                                  <td style="width:7%; border-top: 1px solid #333; border-right: 1px solid #333;
                                     border-bottom: 1px solid #333; background:#d8d8d8;">
                                     Motor
                                  </td> 
                                  <td style="width:20%; border-top: 1px solid #333; border-right: 1px solid #333;
                                     border-bottom: 1px solid #333;">&nbsp;
                                     <?=$rowDtVh['motor'];?>
                                  </td>  
                                 </tr> 
                               </table>                                 
                            </td>
                          </tr>
                          <tr> 
                            <td style="width:100%; padding-bottom:4px;">
                               <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                   <tr>
                                    <td style="width:12%; border: 1px solid #333; background:#d8d8d8;">Traccion: </td>
                                    <td style="width:24%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333;">&nbsp;
                                       <?=$rowDtVh['traccion'];?>
                                    </td>
                                    <td style="width:7%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333; background:#d8d8d8;">
                                       Color:
                                    </td> 
                                    <td style="width:20%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333;">&nbsp;
                                       <?=$rowDtVh['color'];?>
                                    </td> 
                                    <td style="width:7%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333; background:#d8d8d8;">
                                       Modelo:
                                    </td> 
                                    <td style="width:30%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333;">&nbsp;
                                       <?=$rowDtVh['modelo'];?>
                                    </td>  
                                   </tr> 
                                </table>
                            </td>       
                          </tr>
                          <tr> 
                            <td style="width:100%; padding-bottom:4px;">
                               <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                   <tr>
                                    <td style="width:12%; border: 1px solid #333; background:#d8d8d8;">Placa: </td>
                                    <td style="width:24%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333;">&nbsp;
                                       <?=$rowDtVh['placa'];?>
                                    </td>
                                    <td style="width:8%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333; background:#d8d8d8;">
                                       Cilindrada:
                                    </td> 
                                    <td style="width:19%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333;">&nbsp;
                                       <?=$rowDtVh['cilindrada'];?>
                                    </td> 
                                    <td style="width:7%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333; background:#d8d8d8;">
                                       Chasis:
                                    </td> 
                                    <td style="width:30%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333;">&nbsp;
                                       <?=$rowDtVh['chasis'];?>
                                    </td>  
                                   </tr> 
                                </table>
                            </td>       
                          </tr>
                          <tr>
                            <td style="width:100%; padding-bottom:4px;">
                               <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                  <tr>
                                    <td style="width:11%; border: 1px solid #333; background:#d8d8d8;">
                                       Año Comercial: 
                                     </td>
                                    <td style="width:15%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333;">&nbsp;
                                        <?=$rowDtVh['anio'];?>
                                    </td>
<?php
                               if((boolean)$rowsc['garantia']===false){//no subrogado
?>                                    
                                    <td style="width:6%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333; background:#d8d8d8;">
                                        Uso:
                                    </td>
                                    <td style="width:8%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333;">
                                        Particular
                                    </td>
                                    <td style="width:60%; text-align:left;">
                                        (Particular/Publico)
                                    </td>
<?php
							   }else{// subrogado
?>                                   
                                    <td style="width:74%;" colspan="3">&nbsp;
                                        
                                    </td>
<?php
							   }
?>                                    
                                  </tr>
                                </table>                                  
                            </td>
                          </tr>
                          <tr>
                            <td style="width:100%; padding-bottom:4px;">
                               <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                  <tr>
                                    <td style="width:13%; border: 1px solid #333; background:#d8d8d8;">
                                       Suma Asegurada: 
                                    </td>
                                    <td style="width:30%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333;">&nbsp;
                                        <?=number_format($rowDtVh['valor_asegurado'], 2, '.', ',');?>
                                    </td>
                                    <td style="width:15%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333; background:#d8d8d8;">
                                        Ciudad de Circulacion:
                                    </td>
                                    <td style="width:42%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333;">&nbsp;
                                        <?=$rowDtVh['plaza'];?>
                                    </td>
                                  </tr>
                                </table>                                  
                            </td>
                          </tr>
                          <tr>
                            <td style="width:100%; padding-bottom:1px;">
                               <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                  <tr>
                                    <td style="width:13%; border: 1px solid #333;
                                      text-align:left; font-weight:bold;">
                                      VIGENCIA: 
                                    </td>
                                    <td style="width:15%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333; background:#d8d8d8;">
                                       Desde horas 12:01 PM 
                                    </td>
                                    <td style="width:14%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333; background:#d8d8d8;">&nbsp;
                                       
                                    </td>
                                    <td style="width:15%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333; text-align:center;">
                                       <?=$rowsc['ini_vigencia'];?>
                                    </td>
                                    <td style="width:15%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333; background:#d8d8d8;">
                                       Hasta horas 12:01 PM 
                                    </td>
                                    <td style="width:14%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333; background:#d8d8d8;">&nbsp;
                                       
                                    </td>
                                    <td style="width:14%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333; text-align:center;">
                                       <?=$rowsc['fin_vigencia'];?>  
                                    </td>   
                                  </tr>
                                  <tr>
                                    <td style="width:13%;"></td>
                                    <td style="width:15%;"></td>
                                    <td style="width:14%;"></td>
                                    <td style="width:15%; text-align:center;">
                                       dd/mm/aa
                                    </td>
                                    <td style="width:15%;"></td>
                                    <td style="width:14%;"></td>
                                    <td style="width:14%; text-align:center;">
                                       dd/mm/aa
                                    </td>   
                                  </tr>
                                </table> 
                            </td>              
                          </tr>
                          <tr>
                            <td style="width:100%; padding-bottom:4px;">
                               <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                   <tr>
                                     <td style="width:14%; border: 1px solid #333; background:#d8d8d8;">
                                       Período del Seguro: 
                                     </td>
                                     <td style="width:23%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333;">
                                       Anual con renovación automática
                                     </td>
                                     <td style="width:12%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333; background:#d8d8d8;">
                                       Forma de Pago: 
                                     </td>
                                     <td style="width:20%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333;">&nbsp;
                                       <?=$rowsc['forma_pago'];?>
                                     </td>
                                     <td style="width:8%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333; background:#d8d8d8;">
                                       Moneda: 
                                     </td>
                                     <td style="width:23%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333;">
                                        &nbsp;Dolar
                                     </td>
                                   </tr>
                                </table> 
                            </td> 
                          </tr>    
                       </table>
                     <br/>
                     <table 
                          cellpadding="0" cellspacing="0" border="0" 
                          style="width:100%; height:auto; font-size:100%; font-family:Arial; 
                          padding-top:0px; padding-bottom:6px;">
                          <tr>
                            <td style="width:100%; padding-bottom:4px;">
                               <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                   <tr>
                                      <td style="width:16%; border: 1px solid #333; background:#d8d8d8;">
                                         PRIMA TOTAL ANUAL: 
                                      </td>
                                      <td style="width:33%; border-top:1px solid #333; border-right:1px solid #333;
                                         border-bottom: 1px solid #333;">&nbsp;
                                         <?=number_format($vh['v_prima'], 2, '.', ',');?>
                                      </td>
                                      <td style="width:18%; border-top:1px solid #333; border-right:1px solid #333;
                                         border-bottom: 1px solid #333; background:#d8d8d8;">
                                         PRIMA TOTAL MENSUAL:
                                      </td>
                                      <td style="width:33%; border-top:1px solid #333; border-right:1px solid #333;
                                         border-bottom: 1px solid #333;">&nbsp;
                                         <?php 
											if($rowsc['code_fpago']==='CR'){
											  echo number_format(($vh['v_prima']/12), 2, '.', ',');
											}
										  ?>
                                      </td>   
                                   </tr>
                               </table>
                            </td> 
                          </tr>
                       </table>
                     <span style="font-weight:bold;">
                       Requiere este Seguro subrogación de Derechos:   
                     </span>
                     <table 
                          cellpadding="0" cellspacing="0" border="0" 
                          style="width: 100%; height: auto; font-size: 100%; font-family: Arial; 
                          padding-top:0px; padding-bottom:3px;">
                          <tr>
                            <td style="width:18%; border:1px solid #333; background:#d8d8d8;">
                               Detallar Nombres y montos:
                            </td>
                            <td style="width:82%; border-top:1px solid #333; border-right:1px solid #333;
                               border-bottom: 1px solid #333;">&nbsp;
                               
                            </td>
                          </tr>  
                       </table><br>
                     <table 
                        cellpadding="0" cellspacing="0" border="0" 
                        style="width: 100%; height: auto; font-size: 100%; font-family: Arial; 
                        padding-top:0px; padding-bottom:3px;">
                        <tr>
                          <td style="width:100%; border-top:1px solid #333;" colspan="2">&nbsp;</td>
                        </tr>
                        <tr>
                          <td style="width:100%;" colspan="2">
                             COBERTURAS Y VALORES ASEGURADOS
                          </td>
                        </tr>  
                        <tr>
                          <td style="width:50%; font-size:100%; text-align: justify; padding-right:5px; 
                          border:0px solid #333;" valign="top">
                             <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                 <tr>
                                   <td style="width:60%; font-weight:bold;">Responsabilidad Civil Extracontractual </td>
                                   <td style="width:40%;">$us. 25.000,00</td>
                                 </tr>
                                 <tr>
                                   <td style="width:60%; font-weight:bold;">Responsabilidad Civil Consecuencial </td>
                                   <td style="width:40%;">$us. 3.000,00</td>
                                 </tr>
                                 <tr>
                                   <td style="width:60%; font-weight:bold;">Responsabilidad Civil a Ocupantes, p/ocupante:</td>
                                   <td style="width:40%;">$us. 3.000,00</td>
                                 </tr>
                                 <tr>
                                   <td style="width:60%; font-weight:bold;">Casco </td>
                                   <td style="width:40%;">$us. <?=number_format($rowDtVh['valor_asegurado'], 2, '.', ',');?></td>
                                 </tr>
                                 <tr>
                                   <td style="width:60%; font-weight:bold;">Accesorios</td>
                                   <td style="width:40%;">No Incluye</td>
                                 </tr>
                             </table>    
                          </td>
                          <td style="width:50%; font-size:100%; text-align: justify; padding:5px; 
                          border:0px solid #333;" valign="top">
                             <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                 <tr>
                                   <td style="width:60%; font-weight:bold;">Muerte Accidental, p/ocupante: </td>
                                   <td style="width:40%;">$us. 5.000,00</td>
                                 </tr>
                                 <tr>
                                   <td style="width:60%; font-weight:bold;">Invalidez Permanente, p/ocupante:</td>
                                   <td style="width:40%;">$us. 5.000,00</td>
                                 </tr>
                                 <tr>
                                   <td style="width:60%; font-weight:bold;">Gastos Médicos, p/ocupante:</td>
                                   <td style="width:40%;">$us. 1.000,00</td>
                                 </tr>
                                 <tr>
                                   <td style="width:60%; font-weight:bold;">Gastos de Sepelio, p/ocupante:</td>
                                   <td style="width:40%;">$us. 1.000,00</td>
                                 </tr>
                                 <tr>
                                   <td style="width:60%; font-weight:bold;"></td>
                                   <td style="width:40%;"></td>
                                 </tr>
                             </table>  
                          </td>
                        </tr>
                        <tr>
                          <td style="width:100%; border-bottom:1px solid #333;" colspan="2">&nbsp;</td>
                        </tr>
                    </table>         
                  </td>
                </tr>
             </table>
             <div style="font-size: 70%; text-align:center; margin-top:120px;">  
                • Av. Arce Nº 2631, Edificio Multicine Piso 14 • Teléfono: (591-2) 217 7000 • Fax: (591-2) 214 1928 • La Paz – Bolivia.<br> 
• Autorizado por Resolución Administrativa Nº 158 del 7 de julio de 1999 de la Superintendencia de Pensiones Valores y Seguros.   
             </div>        
          </div>
          
          <page><div style="page-break-before: always;">&nbsp;</div></page>
          
          <div style="width: 775px; border: 0px solid #FFFF00;">
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-family: Arial;">
                <tr><td style="width:100%; text-align:right;" colspan="2">
                 <img src="<?=$url;?>images/<?=$rowsc['logo_cia'];?>" height="60"/> 
                </td></tr>
                <tr><td style="width:100%; font-weight:bold; text-align:left;" colspan="2">
                 COBERTURAS
                </td></tr>
                <tr>
                  <td style="width:50%; font-size:80%; text-align: justify; padding-right:5px; 
                  border:0px solid #333;" valign="top">
                    <b>Sección I</b>
                    <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Responsabilidad Civil Extracontractual</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Responsabilidad Civil Consecuencial</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                    </table>
                    <b>Sección II</b>
                    <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Perdida Total por Robo al 100%</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Perdida Total por Robo al 80%</td>
                          <td style="width:10%; text-align:right;">No Cubre</td>
                        </tr>
                    </table>
                    <b>Sección III</b>
                    <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Perdida Total por Accidente al 100%</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                    </table>
                    <b>Sección IV</b>
                    <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Daños Propios, VEH. LIVIANOS c/Franquicia Deducible</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">$us 50.- hasta $us. 50.000.- de valor de casco</td>
                          <td style="width:10%; text-align:right;"></td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">y $us. 200.- con valor de casco mayor a $us. 50.000.-</td>
                          <td style="width:10%; text-align:right;"></td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Daños Propios, VEH. PESADOS c/Franquicia Deducible</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">$us 150.- hasta $us. 50.000.- de valor de casco </td>
                          <td style="width:10%; text-align:right;"></td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">y $us. 300.- con valor de casco mayor a $us. 50.000.-</td>
                          <td style="width:10%; text-align:right;"></td>
                        </tr>
                    </table>
                    <b>Sección V</b>
                    <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Conmoción Civil, Huelgas, Daño Malicioso, Sabotaje,</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Vandalismo y Terrorismo, VEH. LIVIANOS c/Franquicia</td>
                          <td style="width:10%; text-align:right;"></td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Deducible $us 50.- hasta $us. 50.000.- de valor de</td>
                          <td style="width:10%; text-align:right;"></td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">casco y $us. 200.- con valor de casco mayor</td>
                          <td style="width:10%; text-align:right;"></td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">a $us 50.000.-</td>
                          <td style="width:10%; text-align:right;"></td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Conmoción Civil, Huelgas, Daño Malicioso, Sabotaje,</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Vandalismo y Terrorismo, VEH. PESADOS c/Franquicia</td>
                          <td style="width:10%; text-align:right;"></td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Deducible $us 150.- hasta $us. 50.000.- de valor de</td>
                          <td style="width:10%; text-align:right;"></td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">casco y $us. 300.- con valor de casco mayor</td>
                          <td style="width:10%; text-align:right;"></td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">a $us 50.000.-</td>
                          <td style="width:10%; text-align:right;"></td>
                        </tr>
                    </table>
                    <b>Sección VI</b>
                    <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Robo Parcial al 80% </td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                    </table>
                    <b>Sección VII</b>
                    <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Muerte Accidental</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Invalidez Permanente (Total y Parcial)</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Gastos Médicos</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                    </table> 
                  </td>
                  
                  <td style="width:50%; font-size:80%; text-align: justify; padding-right:5px; 
                  border:0px solid #333;" valign="top">
                    <b>Coberturas Adicionales</b>
                    <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Anexo de Ausencia de Control para el Seguro de Automotores para empresas		
		
</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Anexo de Daños a Causa de Riesgos de la Naturaleza c/Franquicia deducible estipulada en la Sección IV y V</td>
                          <td style="width:10%; text-align:right;" valign="top">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Clausula de Circulación en Vías No Habilitadas para el Tránsito Vehicular</td>
                          <td style="width:10%; text-align:right;" valign="top">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Anexo de Extraterritorialidad (Por la vigencia de la Poliza)</td>
                          <td style="width:10%; text-align:right;" valign="top">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Anexo para Accesorio de vehículos</td>
                          <td style="width:10%; text-align:right;" valign="top">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Anexo de Gastos de Sepelio para Accidentes </td>
                          <td style="width:10%; text-align:right;" valign="top"></td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">personales</td>
                          <td style="width:10%; text-align:right;" valign="top">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Clausula de Autoreemplazo (excluye motocicletas/quatracks y vehículos pesados)</td>
                          <td style="width:10%; text-align:right;" valign="top">Cubre</td>
                        </tr>
                    </table>
                    <b>Cláusulas y Anexos Adicionales</b>
                    <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Cláusula de Rehabilitación Automática de la suma Asegurada</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Anexo para Restringir de Copia Legalizada</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Cláusula de Adelanto del 50% del Siniestro</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Cláusula de Elegibilidad de Ajustadores</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Anexo para Vehículos con antigüedad  mayor</td>
                          <td style="width:10%; text-align:right;"></td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">a 15 años y para vehículos transformados</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Anexo para Robo de Llantas, Partes, Equipos de Música y otras piezas</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Anexo de Elegibilidad de Talleres</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Cláusula de Ampliación de Aviso de Siniestro a Diez Días</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Anexo de Asistencia al Vehículo (excepto </td>
                          <td style="width:10%; text-align:right;"></td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;"> motocicletas/quadratracks y vehículos pesados)</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Anexo de Beneficio de Asistencia Jurídica</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Cláusula de Rescisión de Contrato a Prorrata</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Cláusula de cobertura para Flete Aéreo ( hasta $us. 500.-)</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Anexo para Cubrir la Responsabilidad Civil a Ocupantes</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Cláusula de Valor Acordado</td>
                          <td style="width:10%; text-align:right;">No Cubre</td>
                        </tr>
                    </table>
                  </td>
                </tr>
             </table>
             <br/>
             <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-family: Arial; 
                padding-top:0px; padding-bottom:3px;">
                <tr>
                  <td style="width:100%; text-align:justify;">
                   <b>CONDICIONES ESPECIFICAS</b><br>
                    Este documento conjuntamente con los detalles presentados por el solicitante se constituirá en la Declaración hecha por el Asegurado para la contratación de la póliza y forma parte integrante de la misma.<br>
                    La cobertura de la presente solicitud correrá una vez  la Compañía Aseguradora efectúe el análisis del Riesgo y emita la Póliza correspondiente, de acuerdo a las declaraciones de la solicitud.<br>
                    El solicitante que suscribe será responsable de la exactitud de la información en la presente Solicitud y se compromete a efectuar el pago de las primas correspondientes, una vez emitida la Póliza.
                  </td>
                </tr>
            </table>
            <br>
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-family: Arial; 
                padding-top:0px;">
                <tr>
                  <td style="width:100%; border:1px solid #333; font-weight:bold;">
                     EXCLUSIONES: 
                  </td>
                </tr>  
            </table>
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-family: Arial; 
                padding-top:5px;">
                <tr>
                  <td style="width:100%;">
                     <b>Exclusiones Adicionales a las Condiciones Generales</b><br>
                     La cobertura de Extraterritorialidad excluye el autoreemplazo (si es que cuenta con esta cobertura) y la asistencia jurídica. 
                  </td>
                </tr>  
            </table>
            <br>
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-family: Arial; 
                padding-top:0px; padding-bottom:0px;">
                <tr>
                  <td style="width:18%; border:1px solid #333; background:#d8d8d8; font-weight:bold;">
                     LUGAR Y FECHA: 
                  </td>
                  <td style="width:82%; border-top:1px solid #333; border-right:1px solid #333;
                     border-bottom: 1px solid #333;">&nbsp;
                     <?=$rowsc['u_departamento'].' '.$rowsc['fecha_creacion'];?>
                  </td>
                </tr>  
            </table>
            <br><br><br>
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-family: Arial; 
                padding-top:0px; padding-bottom:3px;">
                <tr>
                  <td style="width:45%; border-bottom:1px solid #333;">&nbsp;
                     
                  </td>
                  <td style="width:55%;">&nbsp;
                     
                  </td>
                </tr>
                <tr>
                  <td style="width:45%; text-align:center;">
                     FIRMA DEL CLIENTE
                  </td>
                  <td style="width:55%;">&nbsp;
                     
                  </td>
                </tr>  
            </table>
            <br>
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-family: Arial; 
                padding-top:0px; padding-bottom:3px;">
                <tr>
                  <td style="width:16%; font-weight:bold; text-align:left;">
                     NOMBRE DEL CLIENTE: 
                  </td>
                  <td style="width:29%; border-bottom:1px solid #333;">&nbsp;
                     <?=$cliente;?>
                  </td>
                  <td style="width:55%;">&nbsp;</td>
                </tr>
                <tr><td colspan="3" style="width:100%;">&nbsp;</td></tr>
                <tr>
                  <td style="width:16%; font-weight:bold; text-align:left;">
                     No. CI: 
                  </td>
                  <td style="width:29%; border-bottom:1px solid #333;">&nbsp;
                     <?=$nit_ci;?>
                  </td>
                  <td style="width:55%;">&nbsp;</td>
                </tr>  
            </table>
            <div style="font-size: 70%; text-align:center; margin-top:120px;">  
                • Av. Arce Nº 2631, Edificio Multicine Piso 14 • Teléfono: (591-2) 217 7000 • Fax: (591-2) 214 1928 • La Paz – Bolivia.<br> 
• Autorizado por Resolución Administrativa Nº 158 del 7 de julio de 1999 de la Superintendencia de Pensiones Valores y Seguros.   
            </div>       
          </div>
          <!--FIN SOLICITUD-->
          
          <page><div style="page-break-before: always;">&nbsp;</div></page>
          
          <!--FORMULARIO AUTORIZACION-->
<?php
          $data_count = json_decode($row['nro_cuenta_tomador'],true);
	 
		  if($row['fecha_emision']!=='0000-00-00'){
			  $fecha_em = $row['fecha_emision'];
		  }else{
			  $fecha_em = $row['fecha_creacion'];
			  /*$fecha = new DateTime();
			  $fecha_em = $fecha->format('Y-m-d');*/
		  }
?>
          <div style="width: 775px; border: 0px solid #FFFF00; text-align:center;">
              <table 
                  cellpadding="0" cellspacing="0" border="0" 
                  style="width: 100%; height: auto; font-family: Arial;">
                  <tr>
                    <td style="width:100%; text-align:right;">
                       <img src="<?=$url;?>images/<?=$row['logo_ef'];?>"/> 
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
                       En razón que el CLIENTE ha decidido contratar de forma voluntaria una póliza de seguros de la empresa BISA SEGUROS Y REASEGUROS S.A., el CLIENTE instruye al Banco BISA S.A. a proporcionar su información con la que cuenta el Banco, a la Aseguradora referida y a la empresa Sudamericana S.R.L. Corredores de Seguros y Reaseguros, para la obtención de la póliza de seguros escogida por el propio CLIENTE.
                       <br><br>
                       Asimismo, autorizo a realizar el débito automático para el pago de las cuotas que se generen de esta póliza de la cuenta corriente/ahorro Nº._____<?=$data_count['numero'];?>_____ a nombre de ______<?=$row['tomador_nombre'];?>______
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
                               <?=strtoupper(get_date_format_au_vt($fecha_em));?>
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
                               <b>POLIZA NRO.:</b>&nbsp; <?=$row['no_emision'];?> 
                            </td>
                          </tr>
                          <tr>
                            <td style="width:100%; text-align:left;">
                               <b>LUGAR Y FECHA:</b>&nbsp; <?=strtoupper($row['u_departamento']);?>, <?=strtoupper(get_date_format_au_vt($row['fecha_emision']));?> 
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
                              <b>Vigencia:</b>&nbsp;<?=$row['plazo_dias'];?>, A PARTIR DE LAS 12:01 P.M. HORAS DEL DIA <?=strtoupper(get_date_format_au_vt($row['fecha_iniv']));?>, HASTA LA MISMA HORA DEL <?=strtoupper(get_date_format_au_vt($row['fecha_finv']));?>.
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
                        <b>PÓLIZA Nro.:</b>&nbsp;<?=$row['no_emision'];?>	
                        <br><br>
                        <b>LUGAR Y FECHA:</b>&nbsp;<?=strtoupper(get_date_format_au_vt($row['fecha_emision']));?>
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
                        <b>PÓLIZA Nro.:</b>&nbsp;<?=$row['no_emision'];?>	
                        <br><br>
                        <b>LUGAR Y FECHA:</b>&nbsp;<?=strtoupper(get_date_format_au_vt($row['fecha_emision']));?>
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
                        <b>PÓLIZA Nro.:</b>&nbsp;<?=$row['no_emision'];?>	
                        <br><br>
                        <b>LUGAR Y FECHA:</b>&nbsp;<?=strtoupper(get_date_format_au_vt($row['fecha_emision']));?>	
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
                        <b>PÓLIZA Nro.:</b>&nbsp;<?=$row['no_emision'];?>	
                        <br><br>
                        <b>LUGAR Y FECHA:</b>&nbsp;<?=strtoupper(get_date_format_au_vt($row['fecha_emision']));?>	
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
          <!--FIN EMISION--> 
          
          <page><div style="page-break-before: always;">&nbsp;</div></page>
<?php
        if((boolean)$row['garantia']===true){
		  $materia_seguro = $rowDt['tipo_vechiculo'].' '.$rowDt['marca'].' '.$rowDt['modelo'].' '.$rowDt['placa'];		   
?>            
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
                      <b>Póliza Nro.:</b>&nbsp;<?=$row['no_emision'];?><br>
                      <b>Materia del Seguro Subrogada:</b>&nbsp;<?=$materia_seguro;?><br>
                      <b>Ubicación del Riesgo:</b><br>
                      <b>Vigencia del Seguro:</b>&nbsp;desde <?=$row['fecha_iniv'];?> hasta <?=$row['fecha_finv'];?><br>
                      <b>Vigencia de la Subrogación:</b>&nbsp;Durante la vigencia del crédito<br>
                      <b>Acreedor (Beneficiario de Subrogación):</b>&nbsp;<?=$row['ef_nombre'];?><br>
                      <b>Lugar y Fecha:</b>&nbsp;<?=$row['u_departamento'].' '.$fecha_em;?> 
                    </td>      
                  </tr>
                  <tr>
                    <td style="width:100%; padding-bottom:4px;">&nbsp;
                                                       
                    </td>
                  </tr>   
                  <tr>
                    <td style="width:100%; padding-bottom:4px; text-align:justify;">
                      Se deja constancia por el presente anexo, que a solicitud expresa de los tomadores y/o contratantes y/o asegurados, “EL ACREEDOR” será considerado como beneficiario hasta por el importe de su acreencia sin exceder la suma asegurada de la Póliza Nro. <?=$row['no_emision'];?>.
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
          <!--FIN ANEXO SUBROGACION-->
          
          <page><div style="page-break-before: always;">&nbsp;</div></page>
<?php		  
	    }
		
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
		
		if($row['tipo_cliente']=='J'){
			$sucursal = $row['u_departamento'];
			$razon_social = $row['cl_razon_social'];
			$nit = $row['ci'];
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
			$direccion_oficina = $row['direccion_laboral'];
			$telefono = $row['telefono_oficina'];
			$representante_legal = $row['ejecutivo'];
			$cargo = $row['cargo'];
			$nacionalidad = $row['pais'];
?>
            <!--FORMULARIO UIF JURIDICO-->
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
            <!--FIN FORMULARIO UIF JURIDICO-->                      
<?php			
		}elseif($row['tipo_cliente']=='N'){
			$sucursal = $row['u_departamento'];
			$cliente = $row['nombre'].' '.$row['paterno'].' '.$row['materno'];
			$ci = $row['ci'];
			$extension = $row['extension'];
			$pais = $row['pais'];
			$direccion = $row['direccion'];
			$vec_fn = explode('-',$row['fecha_nacimiento']);
			$dia_fn = $vec_fn[2];
			$mes_fn = $vec_fn[1];
			$anio_fn = $vec_fn[0];
			$prof_ocupacion = $row['desc_ocupacion'];
			$lugar_trabajo = $row['direccion_laboral'];
			$cargo = $row['cargo'];
?>	
            <!--FORMULARIO UIF NATURAL-->
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
                                     //echo $value[0];
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
                                                      if ($value[0] === $row['estado_civil']) {
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
                                $k=1; 
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
        ?>                           
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
            <!--FIN FORMULARIO UIF NATURAL-->    
<?php			
		}
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
?>
          <page><div style="page-break-before: always;">&nbsp;</div></page>
          
          <!--CARTA SUDAMERICANA-->
          <div style="width: 775px; border: 0px solid #FFFF00; text-align:center;">
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-family: Arial;">
                <tr>
                  <td style="width:100%; text-align:right;">
                     La Paz, <?=get_date_format_au_vt($fecha_em)?><br><br>
                     SUD/<?=$mes;?>/<?=$digi;?>

                  </td> 
                </tr>
                <tr>
                  <td style="width:100%; font-weight:bold; text-align:left; font-size: 80%;">
                     Señor(a)<br>
                     <?=$cliente_nombre;?><br>
                     Presente.-<br><br>
                     Ref.:	Póliza Automotor  N° <?=$row['no_emision'];?>
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
                      border-top: 1px solid #000; border-left: 1px solid #000; border-bottom: 1px solid #000;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                      <tr>
                        <td style="width:2%; font-weight:bold;" valign="top">1.&nbsp;</td>
                        <td style="width:98%;">
                          <b>PÓLIZA <?=$row['no_emision'];?></b>
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
                        <td style="width:98%; text-align:justify; padding-top:10px;">
                           <b>AVISO DE VENCIMIENTO:</b>
                        </td>
                      </tr>
                      <tr>
                         <td style="width:2%; font-weight:bold; padding-top:10px;" valign="top">4.&nbsp;</td>
                         <td style="width:98%; padding-top:10px;">
                           <b>NOTA ACLARATORIA:</b><br><br>
                           <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                              <tr>
                                <td style="width:2%; font-weight:bold;" valign="top">&bull;</td>
                                <td style="width:98%;">
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
                         <td style="width:2%; font-weight:bold; padding-top:10px;" valign="top">5.&nbsp;</td>
                         <td style="width:98%; padding-top:10px;">
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
		
		  if($num_titulares <> $j)
		     echo "<page><div style='page-break-before: always;'>&nbsp;</div></page>";
	   
	 
	 }
	
?>        
                 
      </div>
   </div> 

<?php
	$html = ob_get_clean();
	return $html;
}

function get_date_format_au_vt($fecha){
	$date = date_create($fecha);
	
	$day = date_format($date, 'd');
	$month = date_format($date, 'F');
	$year = date_format($date, 'Y');
	
	return $day.' de '.get_month_espanol_vt($month).' de '.$year;
}

function get_month_espanol_vt($month){
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