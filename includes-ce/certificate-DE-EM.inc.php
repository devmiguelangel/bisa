<?php
function de_em_certificate($link, $row, $rsDt, $url, $implant, $fac, $reason = '') {
	$conexion = $link;
	
	$fuenteTXT = 'font-size: 8px;';		$widthMAIN = 'width: 795px;';	$marginCONTAINER = 'margin: 0 auto;';
	$fuenteDet = 'font-size: 6px;';
	
	//STYLE
	//	#container-c
	$cssContainer = 'width: 795px; height: auto; '.$marginCONTAINER.' padding: 0;';
	
	//	#main-c{
	$cssMain = $widthMAIN.' height: auto; margin: 0 auto; padding: 0; font-weight: normal; '.$fuenteTXT.' font-family: Arial;';
	
	//	.h1-c
	$cssH1 = 'font-weight: normal; '.$fuenteTXT.' font-family: Arial; text-align: center; margin: 0; padding: 0;';
	//	.h2-c
	$cssH2 = $cssH1.' font-weight: bold; text-align: left; margin: 0; padding: 0;';
	
	//	.content-c1
	$cssContent1 = 'width: 100%; font-weight: normal; '.$fuenteTXT.' font-family: Arial; margin: 0; padding: 0;';
	//	.content-c2
	$cssContent2 = $cssContent1.' margin: 0; padding: 2px 0;';
	
	//	.table-c
	$cssTable1 = 'font-weight: normal; '.$fuenteTXT.' font-family: Arial; width: 100%; margin: 0; padding: 0; border-collapse: collapse;';
		$cssTable1TrTd = 'vertical-align: bottom; padding: 1px 0;';
	
	//	.border-fc
	$cssBorderF = 'border: 1px solid #000000; ';
	//	.align-tc tr td{
	$cssAlignTop = 'vertical-align: top;';
	$cssBA = $cssBorderF.$cssAlignTop;
	
	//	.border-tc
	$cssBorderT = $cssBorderF.' border-top: 0 none;';
	$cssBA2 = $cssBorderF.$cssAlignTop.$cssBorderT;
	
	//	.border-bc tr td{
	$cssBorderB = 'border-bottom: 1px solid #000000;';
	
	//	.tittle-c
	$cssTittle = 'font-weight: bold; text-align: left; margin: 0; padding: 0;';
	
	//	.check-c
	$cssCheckDisplay = 'display: inline-block; #display: inline; _display: inline; zoom: 1; ';
	$cssCheck1 = 'border: 1px solid #000000; margin-left:5px; width: 8px; height: 8px; text-align: center; ';
	/*
	if($email == false){
		$cssCheck1 .= $cssCheckDisplay;
	}*/
	//	.check-c2{
	$cssCheck2 = 'margin-left: -3px; '.$cssCheck1;
	
	//	.datos-c{
	$cssDatos = 'display:block; margin: 0; padding: 1px 0 1px 5px; height: 10px;';
	
	//	.text-page-c{
	$cssTextPage = 'width: 50%; padding: 0 3px; text-align: justify; '.$fuenteTXT.' vertical-align: top;';
	
	//	.margin-top-cp{
	$cssMarginTop = 'margin-top: 3px;';
	
	//	.text-exclusion-c tr td{
	$cssTextExclusion = 'vertical-align: top; '.$fuenteTXT.'';
	
	//	.tab-c{
	$cssTab = 'margin-left: 30px; display: inline-block; #display: inline; _display: inline; zoom: 1;';  
	ob_start();
?>
<div id="container-c" style="<?=$cssContainer;?>">
  <div id="main-c" style="<?=$cssMain;?>">
  
	<h1 style="<?=$cssH1;?> margin-top: 45px;">
    SEGURO DESGRAVAMEN HIPOTECARIO EN GRUPO - MENSUAL RENOVABLE / SOLICITUD DE SEGURO, DECLARACION DE SALUD Y BENEFICIARIOS<br/>COD. 205 - 934903 - 2004 01 003 4007/RESOLUCIÓN ADMINISTRATIVA: APS/DS/Nº 893-2012
    </h1>
    <div style="<?=$cssContent1;?>">
		<table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td style="width: 30%; <?=$cssTable1TrTd;?> text-align:left;">
					No de Certificado DE-<?=$row['no_emision'];?>
				</td>
				<td style="width: 40%; <?=$cssTable1TrTd;?>"></td>
				<td style="width: 30%; <?=$cssTable1TrTd;?> text-align:right;">
					<strong><?=$row['text_copia'].$row['num_copia'];?></strong>
				</td>
			</tr>
		</table>
	</div>
    <div style="<?=$cssContent2;?>">
		<table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td style="width: 100%; <?=$cssTable1TrTd;?>"><h2 style="<?=$cssH2;?>">Ud(s). Solicita(n) el seguro (alcance de cobertura) de tipo:</h2></td>
			</tr>
			<tr>
				<td style="width: 100%; <?=$cssTable1TrTd;?>">
<?php
	$cobertura = formatCheck($row['tipo_seguro']);
?>
					<table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td style="width: 4.5%; padding: 0; <?=$cssTable1TrTd;?>"><span style="<?=$cssTittle;?>">Individual</span></td>
							<td style="width: 2%; padding: 0; <?=$cssTable1TrTd;?>"><div style="<?=$cssCheck1;?>"><?=$cobertura[0];?></div></td>
							<td style="width: 43.5%; padding: 0; <?=$cssTable1TrTd;?>">Si marca esta opción, solo debe completar la información requerida al TITULAR 1</td>
							<td style="width: 6.5%; padding: 0; <?=$cssTable1TrTd;?>"><span style="<?=$cssTittle;?>">Mancomunada</span></td>
							<td style="width: 2%; padding: 0; <?=$cssTable1TrTd;?>"><div style="<?=$cssCheck1;?>"><?=$cobertura[1];?></div></td>
							<td style="width: 41.5%; padding: 0; <?=$cssTable1TrTd;?>">Si marca esta opción, debe completar la información requerida al TITULAR 1 y al TITULAR 2</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style="width: 100%; <?=$cssTable1TrTd;?>">
					Si hubiesen más solicitantes (codeudores), éstos deben completar declaraciones de salud adicionales
				</td>
			</tr>
		</table>
	</div>
    <div style="<?=$cssContent2;?>">
		<span style="<?=$cssTittle;?>">DATOS PERSONALES</span>
        <?php
		  if($rsDt->data_seek(0)){
          	
			  while($regi2=$rsDt->fetch_array(MYSQLI_ASSOC)){
		?>
                <table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="width: 100%; padding: 2px 0; <?=$cssBorderF;?>" colspan="4"><span style="<?=$cssTittle;?>">INFORMACION SOBRE EL TITULAR <?=$regi2['titular_num'];?></span></td>
                    </tr>
                    <tr>
                        <td style="width: 25%; <?=$cssBA;?>">
                            1.- Apellido Paterno
                            <p style="<?=$cssDatos;?>">&nbsp;<?=$regi2['paterno'];?></p>
                        </td>
                        <td style="width: 25%; <?=$cssBA;?>">
                            2.- Apellido Materno
                            <p style="<?=$cssDatos;?>">&nbsp;<?=$regi2['materno'];?></p>
                        </td>
                        <td style="width: 25%; <?=$cssBA;?>">
                            3.- Apellido de Casada
                            <p style="<?=$cssDatos;?>">&nbsp;<?=$regi2['ap_casada'];?></p>
                        </td>
                        <td style="width: 25%; <?=$cssBA;?>">
                            4.- Nombres Completos
                            <p style="<?=$cssDatos;?>">&nbsp;<?=$regi2['nombre'];?></p>
                        </td>
                    </tr>
                </table>
                <?php
                    $sexoA = formatCheck($regi2['sexo']);
                ?>
                <table style="<?=$cssTable1;?><?=$cssBorderT;?>" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="width: 20%; <?=$cssBA2;?>">
                            5.- Sexo
                            <p style="<?=$cssDatos;?>">Masculino(&nbsp; <?=$sexoA[0];?> &nbsp;)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Femenino(&nbsp; <?=$sexoA[1];?>&nbsp;)</p>
                        </td>
                        <td style="width: 18%; <?=$cssBA2;?>">
                            6.- Fecha de Nacimiento
                            <p style="<?=$cssDatos;?>">&nbsp;<?=$regi2['fecha_nacimiento'];?></p>
                        </td>
                        <td style="width: 5%; <?=$cssBA2;?>">
                            7.- Edad
                            <p style="<?=$cssDatos;?>">&nbsp;<?=$regi2['edad'];?></p>
                        </td>
                        <td style="width: 25%; <?=$cssBA2;?>">
                            8.- Ciudad y Pais de Nacimiento
                            <p style="<?=$cssDatos;?>">&nbsp;<?=$regi2['lugar_nacimiento'];?></p>
                        </td>
                        <td style="width: 32%; <?=$cssBA2;?>">
                            9.- Tipo y Número de Carnet de Identidad
                            <p style="<?=$cssDatos;?>">&nbsp;<?=$regi2['ci'];?></p>
                        </td>
                    </tr>
                </table>
                <table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="width: 7%; <?=$cssBA2;?>">
                            10.- Peso (Kg)
                            <p style="<?=$cssDatos;?>">&nbsp;<?=$regi2['peso'];?></p>
                        </td>
                        <td style="width: 7%; <?=$cssBA2;?>">
                            11.- Estatura
                            <p style="<?=$cssDatos;?>">&nbsp;<?=$regi2['estatura'];?></p>
                        </td>
                        <td style="width: 28%; <?=$cssBA2;?>">
                            12.- Dirección y número de Domicilio
                            <p style="<?=$cssDatos;?>">&nbsp;<?=$regi2['direccion'];?></p>
                        </td>
                        <td style="width: 14%; <?=$cssBA2;?>">
                            13.- Teléfonos
                            <p style="<?=$cssDatos;?>">&nbsp;<?=$regi2['telefono'];?></p>
                        </td>
                        <td style="width: 26%; <?=$cssBA2;?>">
                            14.- Ocupación
                            <p style="<?=$cssDatos;?>">&nbsp;<?=$regi2['ocupacion'];?></p>
                        </td>
                        <td style="width: 18%; <?=$cssBA2;?>">
                            15.- Porcentaje Crédito
                            <p style="<?=$cssDatos;?>">&nbsp;<?=$regi2['porcentaje_credito'];?></p>
                        </td>
                    </tr>
                </table>
                <table style="<?=$cssTable1;?>">
                    <tr>
                        <td style="width: 60%; <?=$cssBA2;?>">
                            16.- ¿Cúal es su actividad cotidiana ligada al crédito que usted solicita?
                            <p style="<?=$cssDatos;?>">&nbsp;<?=$regi2['desc_ocupacion'];?></p>
                        </td>
                        <td style="width: 40%; <?=$cssBA2;?>">
                            17.- Para escribir y/o firmar ¿qué mano utiliza?
                            <p style="<?=$cssDatos;?>">&nbsp;<?=$regi2['mano_utilizada'];?></p>
                        </td>
                    </tr>
                </table><br />
            
        <?php	
			  }
			  if($row['num_cliente']!=2){
			   ?>
                  <table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="width: 100%; padding: 2px 0; <?=$cssBorderF;?>" colspan="4"><span style="<?=$cssTittle;?>">INFORMACION SOBRE EL TITULAR 2</span></td>
                    </tr>
                    <tr>
                        <td style="width: 25%; <?=$cssBA;?>">
                            1.- Apellido Paterno
                            <p style="<?=$cssDatos;?>">&nbsp;</p>
                        </td>
                        <td style="width: 25%; <?=$cssBA;?>">
                            2.- Apellido Materno
                            <p style="<?=$cssDatos;?>">&nbsp;</p>
                        </td>
                        <td style="width: 25%; <?=$cssBA;?>">
                            3.- Apellido de Casada
                            <p style="<?=$cssDatos;?>">&nbsp;</p>
                        </td>
                        <td style="width: 25%; <?=$cssBA;?>">
                            4.- Nombres Completos
                            <p style="<?=$cssDatos;?>">&nbsp;</p>
                        </td>
                    </tr>
                </table>
                <table style="<?=$cssTable1;?><?=$cssBorderT;?>" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="width: 20%; <?=$cssBA2;?>">
                            5.- Sexo
                            <p style="<?=$cssDatos;?>">Masculino(&nbsp;&nbsp;)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Femenino(&nbsp;&nbsp;)</p>
                        </td>
                        <td style="width: 18%; <?=$cssBA2;?>">
                            6.- Fecha de Nacimiento
                            <p style="<?=$cssDatos;?>">&nbsp;</p>
                        </td>
                        <td style="width: 5%; <?=$cssBA2;?>">
                            7.- Edad
                            <p style="<?=$cssDatos;?>">&nbsp;</p>
                        </td>
                        <td style="width: 25%; <?=$cssBA2;?>">
                            8.- Ciudad y Pais de Nacimiento
                            <p style="<?=$cssDatos;?>">&nbsp;</p>
                        </td>
                        <td style="width: 32%; <?=$cssBA2;?>">
                            9.- Tipo y Número de Carnet de Identidad
                            <p style="<?=$cssDatos;?>">&nbsp;</p>
                        </td>
                    </tr>
                </table>
                <table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="width: 7%; <?=$cssBA2;?>">
                            10.- Peso (Kg)
                            <p style="<?=$cssDatos;?>">&nbsp;</p>
                        </td>
                        <td style="width: 7%; <?=$cssBA2;?>">
                            11.- Estatura
                            <p style="<?=$cssDatos;?>">&nbsp;</p>
                        </td>
                        <td style="width: 28%; <?=$cssBA2;?>">
                            12.- Dirección y número de Domicilio
                            <p style="<?=$cssDatos;?>">&nbsp;</p>
                        </td>
                        <td style="width: 14%; <?=$cssBA2;?>">
                            13.- Teléfonos
                            <p style="<?=$cssDatos;?>">&nbsp;</p>
                        </td>
                        <td style="width: 26%; <?=$cssBA2;?>">
                            14.- Ocupación
                            <p style="<?=$cssDatos;?>">&nbsp;</p>
                        </td>
                        <td style="width: 18%; <?=$cssBA2;?>">
                            15.- Porcentaje Crédito
                            <p style="<?=$cssDatos;?>">&nbsp;</p>
                        </td>
                    </tr>
                </table>
                <table style="<?=$cssTable1;?>">
                    <tr>
                        <td style="width: 60%; <?=$cssBA2;?>">
                            16.- ¿Cúal es su actividad cotidiana ligada al crédito que usted solicita?
                            <p style="<?=$cssDatos;?>">&nbsp;</p>
                        </td>
                        <td style="width: 40%; <?=$cssBA2;?>">
                            17.- Para escribir y/o firmar ¿qué mano utiliza?
                            <p style="<?=$cssDatos;?>">&nbsp;</p>
                        </td>
                    </tr>
                </table>
               <?php	  
			  }
		  }
		?>    
	</div>
    <div style="<?=$cssContent2;?>">
		<span style="<?=$cssTittle;?>">CUESTIONARIO</span>
		<table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td style="width: 70%;"></td>
				<td style="width: 15%; text-align: center;"><span style="<?=$cssTittle;?>">TITULAR 1</span></td>
				<td style="width: 15%; text-align: center;"><span style="<?=$cssTittle;?>">TITULAR 2</span></td>
			</tr>
		</table>
		
		<table style="<?=$cssTable1;?> border:#000 solid 1px;" border="0" cellpadding="1"cellspacing="0">
			<tr>
				<td style="width: 70%; text-align: left;"><span style="<?=$cssTittle;?>">Preguntas</span></td>
				<td style="width: 7.5%; text-align: center;">SI</td>
				<td style="width: 7.5%; text-align: center;">NO</td>
				<td style="width: 7.5%; text-align: center;">SI</td>
				<td style="width: 7.5%; text-align: center;">NO</td>				
			</tr>
            <?php
               if($rsDt->data_seek(0)){
				   $arrayQs = array();
				   $vec = array();
				   $obvs = array();
				   $c=1;
				   while($resp=$rsDt->fetch_array(MYSQLI_ASSOC)){
					   $jsonData=$resp['respuesta'];
                       $phpArray = json_decode($jsonData, true);
					   if($resp['titular_txt']=='DD'){
						   $j=1;
						   foreach($phpArray as $value){
							  //echo $value; echo'<br/>'; 
							  $vec=explode('|',$value);
							  $arrayQs[$j][1]=$vec[1];
							  if($row['num_cliente']==1){
							    $arrayQs[$j][2] = '';
						      } 
							  $j++;
						   }
						   $obvs[$c][1]=$resp['observacion'];
						   if($row['num_cliente']==1){
							   $obvs[$c+1][2]='';
						   }  
					   }
					   
					   if($resp['titular_txt']=='CC'){
						     $j=1;
							 foreach($phpArray as $value){
							   //echo $value; echo'<br/>'; 
							   $vec=explode('|',$value);
							   $arrayQs[$j][2] = $vec[1];
							   $j++;
						     }
							 $obvs[$c][2]=$resp['observacion'];
					   }
					   $c++;
				   }
			   }	
				
				$select5="select
							  pregunta,
							  respuesta,
							  orden
							from
							  s_pregunta
							where
							  id_ef_cia='".$row['id_ef_cia']."'   
							order by
							   orden asc;";
				$res5 = $conexion->query($select5,MYSQLI_STORE_RESULT);
				$k=1;
				while($regi5=$res5->fetch_array(MYSQLI_ASSOC)){
					
			?>
                      <tr>
                          <td style="width: 70%; text-align: left;"><?=$regi5['pregunta']?></td>
                          <?php 
						    if($arrayQs[$k][1]==1){
					      ?>
                              <td style="width: 7.5%; padding-left:26px;"><div style="<?=$cssCheck2;?>">X</div></td>
                              <td style="width: 7.5%; padding-left:26px;"><div style="<?=$cssCheck2;?>">&nbsp;</div></td>
                           <?php
							}elseif($arrayQs[$k][1]==0){
						   ?>   
                              <td style="width: 7.5%; padding-left:26px;"><div style="<?=$cssCheck2;?>">&nbsp;</div></td>
                              <td style="width: 7.5%; padding-left:26px;"><div style="<?=$cssCheck2;?>">X</div></td>
                           <?php
							}
							if($row['num_cliente']!=1){
								if($arrayQs[$k][2]==1){
						   ?>   
                              		<td style="width: 7.5%; padding-left:26px;" align="center"><div style="<?=$cssCheck2;?>">X</div></td>
                              		<td style="width: 7.5%; padding-left:26px;" align="center"><div style="<?=$cssCheck2;?>">&nbsp;</div></td>
                           <?php
								}elseif($arrayQs[$k][2]==0){
						   ?>
							  		<td style="width: 7.5%; padding-left:26px;" align="center"><div style="<?=$cssCheck2;?>">&nbsp;</div></td>
                              		<td style="width: 7.5%; padding-left:26px;" align="center"><div style="<?=$cssCheck2;?>">X</div></td>
                           <?php 	
							    }
							}else{
							?>	
                               <td style="width: 7.5%; padding-left:26px;" align="center"><div style="<?=$cssCheck2;?>">&nbsp;</div></td>
                               <td style="width: 7.5%; padding-left:26px;" align="center"><div style="<?=$cssCheck2;?>">&nbsp;</div></td>
                            
							<?php	
							}
						   ?>   
                      </tr>
			<?php
				  $k++;
			    }
			?>
		</table><br />
		Si ha contestado afirmativamente a alguno de los puntos 2 al 8, debe detallar su respuesta señalando además cuando ocurrió, duración, tratamiento, fecha de curación, secuelas, observaciones u otros:
		<table style="<?=$cssTable1;?> margin-top: 5px;" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td style="width: 15%;">Titular 1:</td>
				<td style="width: 85%;">
					<table style="<?=$cssTable1;?>">
						<tr><td style="width: 100%; <?=$cssBorderB;?>">&nbsp;<?=$obvs[1][1];?></td></tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style="width: 15%;">Titular 2:</td>
				<td style="width: 85%;">
					<table style="<?=$cssTable1;?>">
						<tr><td style="width: 100%; <?=$cssBorderB;?>">&nbsp;<?=$obvs[2][2];?></td></tr>
					</table>
				</td>
			</tr>
		</table><br />
		Declaro(amos) que las respuestas que he(mos) consignado en esta solicitud son verdaderas y completas y que es de mi (nuestro) conocimiento que cualquier declaración reticente o inexacta, omisión u ocultación dará lugar a la impugnación del contrato y por lo tanto hará perder todos los beneficios dentro de los dos primeros años de vigencia del contrato de seguro, de acuerdo con lo establecido en el artículo 1138 del Código de Comercio.<br /><br />
		
		Declaro(amos) beneficiario a titulo oneroso de ésta póliza a <?=$row['ef_nombre']?> para el pago del saldo deudor existente, en caso de sinistro cubierto de acuerdo a los términos y condiciones del Seguro.<br /><br />
		
		Asimismo autorizo(amos) a los médicos, clínicas hospitales y otros centros de salud que me (nos) hayan atendido o que me (nos) atiendan en el futuro, para que proporcionen a <?=$row['compania']?> todos los resultados de los informes referentes a mi (nuestra) salud, en caso de enfermedad o accidente, para lo cual releva a dichos m&eacute;dicos y centros m&eacute;dicos en relaci&oacute;n con su secreto profesional de toda responsabilidad en que pudiera incurrir al proporcionar tales informes. Asimismo, autorizo(amos) a <?=$row['compania']?> a proporcionar resultados a <?=$row['ef_nombre']?> Asimismo me (nos) comprometo(emos) a hacer conocer a los beneficiarios la existencia de este Seguro y sus terminos y condiciones. <br /><br />
	</div>
    <div style="<?=$cssContent2;?>">
		<span style="<?=$cssTittle;?>">BENEFICIARIO PARA LA INDEMNIZACIÓN DE SEPELIO: </span>El asegurado debe designar como beneficiario para la cobertura adicional de sepelio a la persona que a su fallecimiento recibir&aacute; el Capital que la Compa&ntilde;ia otorga para &eacute;sta cobertura.
        <?php
          $selectBenef="select
						  sded.id_cliente,
						  sded.porcentaje_credito,
						  (case sded.titular
							 when 'DD' then 1
							 when 'CC' then 2
							end) as titular_num,
						  sdb.cobertura,
						  sdb.paterno,
						  sdb.materno,
						  sdb.nombre,
						  sdb.ci,
						  sdb.parentesco
						from
						  s_de_em_detalle as sded
						  inner join s_de_beneficiario as sdb on (sdb.id_detalle=sded.id_detalle)
						where
						  sded.id_emision='".$row['id_emision']."';";
		  $res3 = $conexion->query($selectBenef,MYSQLI_STORE_RESULT);				  
		  
		  if($res3->data_seek(0)){
			  echo'<table style="'.$cssTable1.' margin-top: 12px;" border="0" cellpadding="0" cellspacing="0">';
			  while($sep=$res3->fetch_array(MYSQLI_ASSOC)){
				  if($sep['cobertura']=='SP'){
				  ?>  
                        <tr>
                            <td style="width: 8%; text-align: center;">TITULAR <?=$sep['titular_num'];?></td>
                            <td style="width: 23%; text-align: center;">
                                <table style="<?=$cssTable1;?>">
                                    <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;<?=$sep['paterno'];?></td></tr>
                                </table>
                            </td>
                            <td style="width: 23%; text-align: center;">
                                <table style="<?=$cssTable1;?>">
                                    <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;<?=$sep['materno'];?></td></tr>
                                </table>
                            </td>
                            <td style="width: 20%; text-align: center;">
                                <table style="<?=$cssTable1;?>">
                                    <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;<?=$sep['nombre'];?></td></tr>
                                </table>
                            </td>
                            <td style="width: 13%; text-align: center;">
                                <table style="<?=$cssTable1;?>">
                                    <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;<?=$sep['parentesco'];?></td></tr>
                                </table>
                            </td>
                            <td style="width: 13%; text-align: center;">
                                <table style="<?=$cssTable1;?>">
                                    <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;<?=$sep['ci'];?></td></tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 8%; text-align: center;"></td>
                            <td style="width: 23%; text-align: center;" align="center">Apellido Paterno</td>
                            <td style="width: 23%; text-align: center;" align="center">Apellido Materno</td>
                            <td style="width: 20%; text-align: center;" align="center">Nombres</td>
                            <td style="width: 13%; text-align: center;" align="center">Parentesco</td>
                            <td style="width: 13%; text-align: center;" align="center">C.I./RUN</td>
                        </tr>
                        <tr>
                            <td style="width: 8%;"></td><td style="width: 23%;"></td><td style="width: 23%;"></td>
                            <td style="width: 20%;"></td><td style="width: 13%;"></td><td style="width: 13%;">&nbsp;</td>
                        </tr>		
                  <?php
				  }  
			  }
			  if($row['num_cliente']!=2){
			   ?>
                   <tr>
                        <td style="width: 8%; text-align: center;">TITULAR 2</td>
                        <td style="width: 23%; text-align: center;">
                            <table style="<?=$cssTable1;?>">
                                <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;</td></tr>
                            </table>
                        </td>
                        <td style="width: 23%; text-align: center;">
                            <table style="<?=$cssTable1;?>">
                                <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;</td></tr>
                            </table>
                        </td>
                        <td style="width: 20%; text-align: center;">
                            <table style="<?=$cssTable1;?>">
                                <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;</td></tr>
                            </table>
                        </td>
                        <td style="width: 13%; text-align: center;">
                            <table style="<?=$cssTable1;?>">
                                <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;</td></tr>
                            </table>
                        </td>
                        <td style="width: 13%; text-align: center;">
                            <table style="<?=$cssTable1;?>">
                                <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;</td></tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 8%; text-align: center;"></td>
                        <td style="width: 23%; text-align: center;" align="center">Apellido Paterno</td>
                        <td style="width: 23%; text-align: center;" align="center">Apellido Materno</td>
                        <td style="width: 20%; text-align: center;" align="center">Nombres</td>
                        <td style="width: 13%; text-align: center;" align="center">Parentesco</td>
                        <td style="width: 13%; text-align: center;" align="center">C.I./RUN</td>
                    </tr>
               <?php	  
			  }
			  echo'</table>'; 
		  }
		?>
	</div>
    <div style="<?=$cssContent2;?>">
		<span style="<?=$cssTittle;?>">LUGAR Y FECHA: </span><?=$row['user_departamento'];?>, <?=$row['fecha_emision'];?><br />
		<table style="<?=$cssTable1;?> margin-top: 35px;" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td style="width: 50%;" align="center">FIRMA DEL SOLICITANTE (TITULAR 1)</td>
				<td style="width: 50%;" align="center">FIRMA DEL SOLICITANTE (TITULAR 2)</td>
			</tr>
		</table>
	</div>
    <div style="<?=$cssContent2;?>">
		<span style="<?=$cssTittle;?>">NOTA: </span>La Compañia se reserva el derecho de solicitar examen(es) médico(s) o información adicional.<br />
		<span style="<?=$cssTittle;?>">DEL CRÉDITO SOLICITADO (A ser completado por la Entidad Financiera)</span>
		<?php
            //echo $row['tipo_credito'];echo'LLL<br/>';
			$tOperacion = formatCheck($row['tipo_operacion']);
            $tCredito = formatCheck($row['tipo_credito']);
            $moneda = formatCheck($row['moneda']);
        ?>
		<table style="<?=$cssTable1;?>" border="0" cellpadding="1" cellspacing="0">
			<tr>
				<td style="width: 40%;"><span style="<?=$cssTittle;?>">TIPO DE OPERACIÓN / MOVIMIENTO: </span></td>
				<td style="width: 15%;"></td>
				<td style="width: 15%;">
					<table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td style="width: 85%; text-align: right;">Primera/Única &nbsp;</td>
							<td style="width: 10%;"><div style="<?=$cssCheck1;?>"><?=$tOperacion[0];?></div></td>
						</tr>
					</table>
				</td>
				<td style="width: 15%;">
					<table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td style="width: 85%; text-align: right;">Adicional &nbsp;</td>
							<td style="width: 10%;"><div style="<?=$cssCheck1;?>"><?=$tOperacion[1];?></div></td>
						</tr>
					</table>
				</td>
				<td style="width: 15%;">
					<table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td style="width: 85%; text-align: right;">Línea de Crédito &nbsp;</td>
							<td style="width: 10%;"><div style="<?=$cssCheck1;?>"><?=$tOperacion[2];?></div></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style="width: 40%;"><span style="<?=$cssTittle;?>">TIPO DE CRÉDITO: </span></td>
				<td style="width: 15%;">
					<table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td style="width: 85%; text-align: right;">Hipotecario &nbsp;</td>
							<td style="width: 10%;"><div style="<?=$cssCheck1;?>"><?=$tCredito[0];?></div></td>
						</tr>
					</table>
				</td>
				<td style="width: 15%;">
					<table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td style="width: 85%; text-align: right;">Comercial &nbsp;</td>
							<td style="width: 10%;"><div style="<?=$cssCheck1;?>"><?=$tCredito[1];?></div></td>
						</tr>
					</table>
				</td>
				<td style="width: 15%;">
					<table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td style="width: 85%; text-align: right;">Consumo &nbsp;</td>
							<td style="width: 10%;"><div style="<?=$cssCheck1;?>"><?=$tCredito[2];?></div></td>
						</tr>
					</table>
				</td>
				<td style="width: 15%;">
					<table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td style="width: 85%; text-align: right;">Otros &nbsp;</td>
							<td style="width: 10%;"><div style="<?=$cssCheck1;?>"><?=$tCredito[3];?></div></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style="width: 40%;"><span style="<?=$cssTittle;?>">MONEDA: </span></td>
				<td style="width: 15%;">
					<table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td style="width: 85%; text-align: right;">Dólares &nbsp;</td>
							<td style="width: 10%;"><div style="<?=$cssCheck1;?>"><?=$moneda[0];?></div></td>
						</tr>
					</table>
				</td>
				<td style="width: 15%;">
					<table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td style="width: 85%; text-align: right;">Bolivianos &nbsp;</td>
							<td style="width: 10%;"><div style="<?=$cssCheck1;?>"><?=$moneda[1];?></div></td>
						</tr>
					</table>
				</td>
				<!--
                <td style="width: 15%;">
					<table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td style="width: 85%; text-align: right;">UFV &nbsp;</td>
							<td style="width: 10%;"><div style="<?=$cssCheck1;?>"><?=$moneda[2];?></div></td>
						</tr>
					</table>
				</td>-->
				<td style="width: 15%;"></td>
			</tr>
		</table>
		<table style="<?=$cssTable1;?> margin-top: 8px;" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td style="width: 23%;"><span style="<?=$cssTittle;?>">SALDO DEUDOR ACTUAL DEL ASEGURADO: </span></td>
				<td style="width: 15%;">
					<table style="<?=$cssTable1;?>">
						<tr><td style="width: 100%; <?=$cssBorderB;?>">&nbsp;<?=number_format($row['saldo_deudor'],2,'.',',');?> Bs.</td></tr>
					</table>
				</td>
				<td style="width: 6%"><span style="<?=$cssTittle;?>">SUCURSAL: </span></td>
				<td style="width: 12.5%">
					<table style="<?=$cssTable1;?>">
						<tr><td style="width: 100%; <?=$cssBorderB;?>">&nbsp;<?=$row['user_departamento'];?></td></tr>
					</table>
				</td>
				<td style="width: 6%"><span style="<?=$cssTittle;?>">TELÉFONO: </span></td>
				<td style="width: 12.5%">
					<table style="<?=$cssTable1;?>">
						<tr><td style="width: 100%; <?=$cssBorderB;?>">&nbsp;<?=$row['fono_sucursal'];?></td></tr>
					</table>
				</td>
				<td style="width: 25%"></td>
			</tr>
		</table>
		<table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td style="width: 17%"><span style="<?=$cssTittle;?>">MONTO ACTUAL SOLICITADO: </span></td>
				<td style="width: 23%">
					<table style="<?=$cssTable1;?>">
						<tr><td style="width: 100%; <?=$cssBorderB;?>">&nbsp;<?=number_format($row['monto_solicitado'],2,'.',',');?></td></tr>
					</table>
				</td>
				<td style="width: 60%"></td>
			</tr>
		</table>
		<table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td style="width: 17%"><span style="<?=$cssTittle;?>">MONTO ACTUAL ACUMULADO: </span></td>
				<td style="width: 26%">
					<table style="<?=$cssTable1;?>">
						<tr><td style="width: 100%; <?=$cssBorderB;?>">&nbsp;<?=number_format($row['monto_actual_acumulado'],2,'.',',');?> Bs.</td></tr>
					</table>
				</td>
				<td style="width: 7%"><span style="<?=$cssTittle;?>">FUNCIONARIO: </span></td>
				<td style="width: 20%">
					<table style="<?=$cssTable1;?>">
						<tr><td style="width: 100%; <?=$cssBorderB;?>">&nbsp;<?=$row['u_nombre'];?></td></tr>
					</table>
				</td>
				<td style="width: 30%"></td>
			</tr>
		</table>
		<table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td style="width: 18%"><span style="<?=$cssTittle;?>">PLAZO DEL PRESENTE CRÉDITO: </span></td>
				<td style="width: 39%">
					<table style="<?=$cssTable1;?>">
						<tr><td style="width: 100%; <?=$cssBorderB;?>">&nbsp;<?=$row['tipo_plazo'];?></td></tr>
					</table>
				</td>
				<td style="width: 16%"><span style="<?=$cssTittle;?>">NOMBRE, SELLO Y  FIRMA: </span></td>
				<td style="width: 27%">
					<table style="<?=$cssTable1;?>">
						<tr><td style="width: 100%; <?=$cssBorderB;?>">&nbsp;</td></tr>
					</table>
				</td>
			</tr>
		</table>
	</div><br />
    <div style="<?=$cssContent2;?> border: 1px solid #000000;">
		<span style="<?=$cssTittle;?>">BENEFICIARIOS PARA EL SEGURO DE VIDA EN GRUPO: </span>El asegurado debe designar como beneficiario para la cobertura del seguro de vida a la persona que a su fallecimiento recibirá el Capital que la Compañia otorga para esta cobertura.
		<?php
		if($row['verifica_vida'] == 0){  
            if($res3->data_seek(0)){
				 $vg=1;
				 echo'<table style="'.$cssTable1.' margin-top: 8px;" border="0" cellpadding="0" cellspacing="0">';
				 while($vida=$res3->fetch_array(MYSQLI_ASSOC)){ echo $vg;
					 if($vida['cobertura']=='VG'){
						 $select5="select
									  paterno,
									  materno,
									  nombre,
									  ap_casada,
									  ci
									from
									  s_cliente
									where
									  id_cliente='".$vida['id_cliente']."';";
						 $res5 = $conexion->query($select5,MYSQLI_STORE_RESULT);				  
						 $regi5=$res5->fetch_array(MYSQLI_ASSOC);
						 ?>
                          
                            <tr>
                                <td style="width: 18%;">TITULAR <?=$vg?></td>
                                <td style="width: 18%;">
                                    <table style="<?=$cssTable1;?>">
                                        <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;<?=$regi5['paterno'];?></td></tr>
                                    </table>
                                </td>
                                <td style="width: 18%;">
                                    <table style="<?=$cssTable1;?>">
                                        <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;<?=$regi5['materno'];?></td></tr>
                                    </table>
                                </td>
                                <td style="width: 18%;">
                                    <table style="<?=$cssTable1;?>">
                                        <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;<?=$regi5['nombre'];?></td></tr>
                                    </table>
                                </td>
                                <td style="width: 18%;">
                                    <table style="<?=$cssTable1;?>">
                                        <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;</td></tr>
                                    </table>
                                </td>
                                <td style="width: 10%;">
                                    <table style="<?=$cssTable1;?>">
                                        <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;<?=$regi5['ci'];?></td></tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 18%;"></td>
                                <td style="width: 18%;" align="center">Apellido Paterno</td>
                                <td style="width: 18%;" align="center">Apellido Materno</td>
                                <td style="width: 18%;" align="center">Nombres</td>
                                <td style="width: 18%;" align="center">Parentesco</td>
                                <td style="width: 10%;" align="center">C.I./RUN</td>
                            </tr>
                            <tr>
                                <td></td><td></td><td></td><td></td><td></td><td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td style="width: 18%;">BEMNEFICIARIO DEL TITULAR <?=$vg?></td>
                                <td style="width: 18%;">
                                    <table style="<?=$cssTable1;?>">
                                        <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;<?=$vida['paterno'];?></td></tr>
                                    </table>
                                </td>
                                <td style="width: 18%;">
                                    <table style="<?=$cssTable1;?>">
                                        <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;<?=$vida['materno'];?></td></tr>
                                    </table>
                                </td>
                                <td style="width: 18%;">
                                    <table style="<?=$cssTable1;?>">
                                        <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;<?=$vida['nombre'];?></td></tr>
                                    </table>
                                </td>
                                <td style="width: 18%;">
                                    <table style="<?=$cssTable1;?>">
                                        <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;<?=$vida['parentesco'];?></td></tr>
                                    </table>
                                </td>
                                <td style="width: 10%;">
                                    <table style="<?=$cssTable1;?>">
                                        <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;<?=$vida['ci'];?></td></tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 18%;"></td>
                                <td style="width: 18%;" align="center">Apellido Paterno</td>
                                <td style="width: 18%;" align="center">Apellido Materno</td>
                                <td style="width: 18%;" align="center">Nombres</td>
                                <td style="width: 18%;" align="center">Parentesco</td>
                                <td style="width: 10%;" align="center">C.I./RUN</td>
                            </tr>
                            <tr>
                                <td></td><td></td><td></td><td></td><td></td><td>&nbsp;</td>
                            </tr>  
                         <?php
						 $vg++;			     
					 }
					 
				 }
				 if($row['num_cliente']!=2){
			     ?>		 
					 <tr>
                        <td style="width: 18%;">TITULAR 2</td>
                        <td style="width: 18%;">
                            <table style="<?=$cssTable1;?>">
                                <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;</td></tr>
                            </table>
                        </td>
                        <td style="width: 18%;">
                            <table style="<?=$cssTable1;?>">
                                <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;</td></tr>
                            </table>
                        </td>
                        <td style="width: 18%;">
                            <table style="<?=$cssTable1;?>">
                                <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;</td></tr>
                            </table>
                        </td>
                        <td style="width: 18%;">
                            <table style="<?=$cssTable1;?>">
                                <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;</td></tr>
                            </table>
                        </td>
                        <td style="width: 10%;">
                            <table style="<?=$cssTable1;?>">
                                <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;</td></tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 18%;"></td>
                        <td style="width: 18%;" align="center">Apellido Paterno</td>
                        <td style="width: 18%;" align="center">Apellido Materno</td>
                        <td style="width: 18%;" align="center">Nombres</td>
                        <td style="width: 18%;" align="center">Parentesco</td>
                        <td style="width: 10%;" align="center">C.I./RUN</td>
                    </tr>
                    <tr>
                        <td></td><td></td><td></td><td></td><td></td><td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="width: 18%;">BEMNEFICIARIO DEL TITULAR 2</td>
                        <td style="width: 18%;">
                            <table style="<?=$cssTable1;?>">
                                <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;</td></tr>
                            </table>
                        </td>
                        <td style="width: 18%;">
                            <table style="<?=$cssTable1;?>">
                                <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;</td></tr>
                            </table>
                        </td>
                        <td style="width: 18%;">
                            <table style="<?=$cssTable1;?>">
                                <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;</td></tr>
                            </table>
                        </td>
                        <td style="width: 18%;">
                            <table style="<?=$cssTable1;?>">
                                <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;</td></tr>
                            </table>
                        </td>
                        <td style="width: 10%;">
                            <table style="<?=$cssTable1;?>">
                                <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;</td></tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 18%;"></td>
                        <td style="width: 18%;" align="center">Apellido Paterno</td>
                        <td style="width: 18%;" align="center">Apellido Materno</td>
                        <td style="width: 18%;" align="center">Nombres</td>
                        <td style="width: 18%;" align="center">Parentesco</td>
                        <td style="width: 10%;" align="center">C.I./RUN</td>
                    </tr>
				 <?php	 
				 }
				 echo'</table> ';
		    }
		}else{
		?>	
			 <table style="<?=$cssTable1;?> margin-top: 8px;" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="width: 18%;">TITULAR 1</td>
                    <td style="width: 18%;">
                        <table style="<?=$cssTable1;?>">
                            <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;</td></tr>
                        </table>
                    </td>
                    <td style="width: 18%;">
                        <table style="<?=$cssTable1;?>">
                            <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;</td></tr>
                        </table>
                    </td>
                    <td style="width: 18%;">
                        <table style="<?=$cssTable1;?>">
                            <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;</td></tr>
                        </table>
                    </td>
                    <td style="width: 18%;">
                        <table style="<?=$cssTable1;?>">
                            <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;</td></tr>
                        </table>
                    </td>
                    <td style="width: 10%;">
                        <table style="<?=$cssTable1;?>">
                            <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;</td></tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="width: 18%;"></td>
                    <td style="width: 18%;" align="center">Apellido Paterno</td>
                    <td style="width: 18%;" align="center">Apellido Materno</td>
                    <td style="width: 18%;" align="center">Nombres</td>
                    <td style="width: 18%;" align="center">Parentesco</td>
                    <td style="width: 10%;" align="center">C.I./RUN</td>
                </tr>
                <tr>
                    <td></td><td></td><td></td><td></td><td></td><td>&nbsp;</td>
                </tr>
                <tr>
                    <td style="width: 18%;">BEMNEFICIARIO DEL TITULAR 1</td>
                    <td style="width: 18%;">
                        <table style="<?=$cssTable1;?>">
                            <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;</td></tr>
                        </table>
                    </td>
                    <td style="width: 18%;">
                        <table style="<?=$cssTable1;?>">
                            <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;</td></tr>
                        </table>
                    </td>
                    <td style="width: 18%;">
                        <table style="<?=$cssTable1;?>">
                            <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;</td></tr>
                        </table>
                    </td>
                    <td style="width: 18%;">
                        <table style="<?=$cssTable1;?>">
                            <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;</td></tr>
                        </table>
                    </td>
                    <td style="width: 10%;">
                        <table style="<?=$cssTable1;?>">
                            <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;</td></tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="width: 18%;"></td>
                    <td style="width: 18%;" align="center">Apellido Paterno</td>
                    <td style="width: 18%;" align="center">Apellido Materno</td>
                    <td style="width: 18%;" align="center">Nombres</td>
                    <td style="width: 18%;" align="center">Parentesco</td>
                    <td style="width: 10%;" align="center">C.I./RUN</td>
                </tr>
                <tr>
                    <td></td><td></td><td></td><td></td><td></td><td>&nbsp;</td>
                </tr>
                <tr>
                    <td style="width: 18%;">TITULAR 2</td>
                    <td style="width: 18%;">
                        <table style="<?=$cssTable1;?>">
                            <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;</td></tr>
                        </table>
                    </td>
                    <td style="width: 18%;">
                        <table style="<?=$cssTable1;?>">
                            <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;</td></tr>
                        </table>
                    </td>
                    <td style="width: 18%;">
                        <table style="<?=$cssTable1;?>">
                            <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;</td></tr>
                        </table>
                    </td>
                    <td style="width: 18%;">
                        <table style="<?=$cssTable1;?>">
                            <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;</td></tr>
                        </table>
                    </td>
                    <td style="width: 10%;">
                        <table style="<?=$cssTable1;?>">
                            <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;</td></tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="width: 18%;"></td>
                    <td style="width: 18%;" align="center">Apellido Paterno</td>
                    <td style="width: 18%;" align="center">Apellido Materno</td>
                    <td style="width: 18%;" align="center">Nombres</td>
                    <td style="width: 18%;" align="center">Parentesco</td>
                    <td style="width: 10%;" align="center">C.I./RUN</td>
                </tr>
                <tr>
                    <td></td><td></td><td></td><td></td><td></td><td>&nbsp;</td>
                </tr>
                <tr>
                    <td style="width: 18%;">BEMNEFICIARIO DEL TITULAR 2</td>
                    <td style="width: 18%;">
                        <table style="<?=$cssTable1;?>">
                            <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;</td></tr>
                        </table>
                    </td>
                    <td style="width: 18%;">
                        <table style="<?=$cssTable1;?>">
                            <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;</td></tr>
                        </table>
                    </td>
                    <td style="width: 18%;">
                        <table style="<?=$cssTable1;?>">
                            <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;</td></tr>
                        </table>
                    </td>
                    <td style="width: 18%;">
                        <table style="<?=$cssTable1;?>">
                            <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;</td></tr>
                        </table>
                    </td>
                    <td style="width: 10%;">
                        <table style="<?=$cssTable1;?>">
                            <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;</td></tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="width: 18%;"></td>
                    <td style="width: 18%;" align="center">Apellido Paterno</td>
                    <td style="width: 18%;" align="center">Apellido Materno</td>
                    <td style="width: 18%;" align="center">Nombres</td>
                    <td style="width: 18%;" align="center">Parentesco</td>
                    <td style="width: 10%;" align="center">C.I./RUN</td>
                </tr>
            </table> 
		<?php	
	    }
        ?>
		
		<br />
		La(s) Persona(s) (Titular 1 y Titular 2) manifiesta(n) su plena y absoluta conformidad para ser incorporado(s) como Asegurado(s) al Contrato de Seguro de Vida en Grupo, contratado por la entidad financiera, quien actúa en dicho Contrato como Tomador del Seguro. <br />Los Beneficiarios declarados, cobrarán la Indemnizacion establecida en el Contrato del Seguro en caso de producirse el falecimiento del Prestatario, autorizando que el costo de la prima correspondiente, se pague a la entidad aseguradora de acuerdo a las polizas de la entidad financiera y a los términos del Contrato de Seguro.
		<br />
		<span>LUGAR Y FECHA: </span> <?=$row['user_departamento'];?>, <?=$row['fecha_emision'];?>
		<table style="<?=$cssTable1;?> margin-top: 35px;" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td style="width: 50%;" align="center">FIRMA DEL SOLICITANTE (TITULAR 1)</td>
				<td style="width: 50%;" align="center">FIRMA DEL SOLICITANTE (TITULAR 2)</td>
			</tr>
		</table>
	</div>
	<br />
    <span style="<?=$cssTittle;?>">Emisión de certificado de acuerdo a Slip de Cotización No. <?=$row['no_cotizacion'];?> &nbsp;&nbsp;<?php
      $anulados="select
				  no_emision,
				  id_cotizacion,
				  anulado
				from
				  s_de_em_cabecera
				where
				  id_cotizacion='".$row['id_cotizacion']."' and anulado=1;";
	  $resu=$conexion->query($anulados, MYSQLI_STORE_RESULT);
	  $num=$resu->num_rows;
	  $text_anulados='';
	  if($num>0){
		  echo'Certificados anulados:&nbsp;'; 
		  while($regi=$resu->fetch_array(MYSQLI_ASSOC)){
		      $text_anulados .= 'DE-'.$regi['no_emision'].', ';
		  }
		  echo trim($text_anulados,', ');
	  }			  
		?> 
	</span><br /><br /> 
     <?php
     if((boolean)$row['facultativo'] === TRUE){
		 if(!empty($row['aprobado'])){ 
		?>
              <table border="0" cellpadding="1" cellspacing="0" style="width: 100%; font-size: 8px; border-collapse: collapse;">
                    <tr>
                        <td colspan="7" style="width:100%; text-align: center; font-weight: bold; background: #e57474; color: #FFFFFF;">Caso Facultativo</td>
                    </tr>
                    <tr>
                        
                        <td style="width:5%; text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e57474;">Aprobado</td>
                        <td style="width:12%; text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e57474;">Tasa de Recargo</td>
                        <td style="width:14%; text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e57474;">Porcentaje de Recargo</td>
                        <td style="width:12%; text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e57474;">Tasa Actual</td>
                        <td style="width:12%; text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e57474;">Tasa Final</td>
                        <td style="width:45%; text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e57474;">Observaciones</td>
                    </tr>
                    <tr>
                        
                        <td style="width:5%; text-align: center; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=$row['aprobado'];?></td>
                        <td style="width:12%; text-align: center; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=$row['tasa_recargo'];?></td>
                        <td style="width:14%; text-align: center; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=$row['porcentaje_recargo'];?> %</td>
                        <td style="width:12%; text-align: center; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=$row['tasa_actual'];?> %</td>
                        <td style="width:12%; text-align: center; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=$row['tasa_final'];?> %</td>
                        <td style="width:45%; text-align: justify; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=$row['observacion'];?></td>
                    </tr>
               </table>
              
		<?php
		 }else{
		?> 	 
			<table border="0" cellpadding="1" cellspacing="0" style="width: 100%; font-size: 8px; border-collapse: collapse;">
                    <tr>
                        <td colspan="7" style="width:100%; text-align: center; font-weight: bold; background: #e57474; color: #FFFFFF;">Caso Facultativo</td>
                    </tr>
                    <tr>
                        <td style="width:100%; text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e57474;">Observaciones</td>
                    </tr>
                    <tr>
                        
                        <td style="width:100%; text-align: justify; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=$row['motivo_facultativo'];?></td>
                    </tr>
               </table>
     <?php
		 }
	  }
	 ?>
     
     <div style="page-break-after: always;">&nbsp;</div>
         <div style="<?=$cssContent2;?> margin-top: 50px; <?=$fuenteDet;?>">
			<h1 style="<?=$cssH1;?>">
				<span style="text-decoration: underline;">SEGURO DE DESGRAVAMEN HIPOTECARIO EN GRUPO - MENSUAL RENOVABLE<br />
				CERTIFICADO DE COBERTURA INDIVIDUAL</span><br /><br />
				COD.205-934903-2004 01 003 - 4001<br/>
				RESOLUCIÓN ADMINISTRATIVA DE LA SPVS: IS No. 075, 19/03/04<br /><br />
				<span style="text-decoration: underline;">PÓLIZA NRO. <?=$row['no_poliza'];?></span>
			</h1>
			<table style="<?=$cssTable1;?> margin-top: 5px; <?=$fuenteDet;?>" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td style="<?=$cssTextPage;?> <?=$fuenteDet;?>">
						El presente Certificado de Cobertura Individual, tan solo representa un documento referencial. En caso que el Asegurado desee conocer inextenso las normas, términos y condiciones del seguro, éste deberá; referirse únicamente a la Póliza original, la misma que se encuentra en posesión del Tomador.
						<br />
						<b style="<?=$cssMarginTop;?>">TOMADOR: ECOFUTURO S.A. FFP</b><br/>
						<b>ASEGURADO:</b> Datos que figuran en Titular 1 y/o Titular 2 en la declaraci&oacute;n de salud (reverso) y/o Cotitulares adicionales
						<br/>
						<b>GRUPO ASEGURADO:</b> Clientes de Cartera del Tomador<br/>
						<b>ACTIVIDAD GENERAL:</b> Varias y sin limitaciones<br />
										
						<b style="<?=$cssMarginTop;?>">COBERTURAS:</b><br/>
						<b>PRINCIPAL:</b><br/>
						<b>A)</b> Muerte por cualquier Causa, que no est&eacute; excluida en el Condicionado General de la P&oacute;liza incluido el suicidio despu&eacute;s de un a&ntilde;o de vigencia ininterrumpida de la cobertura individual del seguro.
						<br />
						
						<b style="<?=$cssMarginTop;?>">ADICIONALES:</b><br/>
						<b >B)</b> Pago anticipado del capital asegurado en caso de invalidez total y permanente por accidente o enfermedad, en forma irreversible por lo menos en un 65% seg&uacute;n el manual de normas autorizado por la AUTORIDAD DE FISCALIZACION Y CONTROL SOCIAL DE PENSIONES,  en actual vigencia.
						<br/>
						<b >C)</b> Gastos de Sepelio, son los gastos que demanden los herederos legales o nominados por el Sepelio de un Asegurado (titular o c&oacute;nyuge), como consecuencia del fallecimiento por una enfermedad o un accidente cubierto, por un monto de $us200.- por asegurado, establecido para esta cobertura, para el titular o c&oacute;nyuge.
						
						<br/>
						<b >D)</b> DESEMPLEO PARA TRABAJADORES DEPENDIENTES: El pago de la indemnizaci&oacute;n se realizar&aacute; de acuerdo a cuotas mensuales y si la forma de pago del prestatario es diferente a la mensual, se convertir&aacute;n a cuotas mensuales y se cubrir&aacute;n el equivalente a 4 cuotas mensuales, con un m&aacute;ximo de $us1,000 por cuota y por persona. La forma de pago al contratante ser&aacute; realizada bajo un &uacute;nico pago.
						
						<br />
						<b style="<?=$cssMarginTop;?>">CAPITALES ASEGURADOS:<br/>COBERTURA PRINCIPAL:</b><br/>
						
						<b >A)</b>&nbsp;Saldo Deudor.<br />
						<b style="<?=$cssMarginTop;?>">COBERTURAS ADICIONALES:</b><br/>
						<b >B)</b> Saldo deudor<br/>
						<b >C)</b> $us200.00 que se pagar&aacute;n de la siguiente forma: $us100 contra la presentaci&oacute;n, al Tomador, del Certificadode Defunci&oacute;n (sin que esto signifique la aceptaci&oacute;n del reclamo y con el objeto de que se tramiten el resto de los documentos) y los restantes $us100 si la indemnizaci&oacute;n del reclamo corresponde.
						<br/>
						<b >D)</b> 4 cuotas de acuerdo con el plan de pagos del cr&eacute;dito
						
						<br />
						<b style="<?=$cssMarginTop;?>">EXCLUSIONES:</b> La Compa&ntilde;&iacute;a no cubre y esta eximida de toda responsabilidad en caso de fallecimiento del asegurado en los siguientes casos:
						<br />
						
						<table style="<?=$cssTable1;?> <?=$cssTextExclusion?> <?=$fuenteDet;?>" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td style="width:3%;" align="left" valign="top">a.</td>
								<td style="width:96%;">Si el asegurado participa como conductor o acompa&ntilde;ante en competencias de autom&oacute;viles, motocicletas, lanchas de motor o avionetas, practicas de paraca&iacute;das.
								</td>
							</tr>
							<tr>
								<td style="width:3%;" align="left" valign="top">b.</td>
								<td style="width:96%;">Si el asegurado realiza operaciones o viajes submarinos o en transportes a&eacute;reos no autorizados para transporte de pasajeros, vuelos no regulares.
								</td>
							</tr>
							<tr>
								<td style="width:3%;" align="left" valign="top">c.</td>
								<td style="width:96%;">Enfermedades pre-existentes conocidas al inicio del seguro o enfermedad cong&eacute;nita, para cr&eacute;ditos mayores a $us5,000.00 &oacute; Bs35,000.00 dentro de los plazos establecidos en el art&iacute;culo 1138 del C&oacute;digo de Comercio.
								</td>
							</tr>
							<tr>
								<td style="width:3%;" align="left" valign="top">d.</td>
								<td style="width:96%;">SIDA/HIV para siniestros a partir de $us5,000.00 &oacute; Bs35,000.00
								</td>
							</tr>
							<tr>
								<td style="width:3%;" align="left" valign="top">e.</td>
								<td style="width:96%;">Guerra, invasi&oacute;n, revoluci&oacute;n, sublevaci&oacute;n, mot&iacute;n o hechos que las leyes clasifiquen como delitos contra la seguridad del Estado, siempre y cuando el asegurado no participe activamente.
								</td>
							</tr>
							<tr>
								<td style="width:3%;" align="left" valign="top">f.</td>
								<td style="width:96%;">Suicidio practicado por el asegurado dentro del primer a&ntilde;o de vigencia ininterrumpida de su cobertura. En consecuencia este riesgo quedara cubierto a partir del primer d&iacute;a del segundo a&ntilde;o de vigencia para cada operaci&oacute;n asegurada.
								</td>
							</tr>
							<tr>
								<td style="width:3%;" align="left" valign="top">g.</td>
								<td style="width:96%;">Fisi&oacute;n o fusi&oacute;n nuclear</td>
							</tr>
						</table>
						<b style="<?=$cssMarginTop;?>">PERIODO DE CARENCIA PARA SINIESTROS HASTA $us5,000.00 &oacute; Bs35,000.00:</b>
						<br/>
						Se establece un periodo de carencia de 30 d&iacute;as para fallecimiento por enfermedad, computables a partir de la fecha de desembolso del cr&eacute;dito.
						<br />
						
						<b style="<?=$cssMarginTop;?>">PERIODO DE CARENCIA PARA SINIESTROS DE $us5,000.00 &oacute; Bs35,000.00 EN ADELANTE:</b>
						<br/>
						Se establece un periodo de carencia de 90 d&iacute;as para fallecimiento por enfermedad, computables a partir de la fecha de desembolso del cr&eacute;dito.
						<br />
						
						<b style="<?=$cssMarginTop;?>">EXCLUSIONES PARA DESEMPLEO:</b> La Compa&ntilde;&iacute;a no cubre y esta eximida de toda responsabilidad cuando la cesant&iacute;a del asegurado se deba a:
						<br />
						<table style="<?=$cssTable1;?> <?=$cssTextExclusion?> <?=$fuenteDet;?>" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td style="width:3%;" align="left" valign="top">a.</td>
								<td style="width:97%;">Renuncia del Trabajador</td>
							</tr>
							<tr>
								<td style="width:3%;" align="left" valign="top">b.</td>
								<td style="width:97%;">Muerte del Trabajador</td>
							</tr>
							<tr>
								<td style="width:3%;" align="left" valign="top">c.</td>
								<td style="width:97%;">Vencimiento del plazo convenido en el Contrato de Trabajo</td>
							</tr>
							<tr>
								<td style="width:3%;" align="left" valign="top">d.</td>
								<td style="width:97%;">Conclusi&oacute;n de Trabajo o servicio que dio origen al Contrato de Trabajo</td>
							</tr>
							<tr>
								<td style="width:3%;" align="left" valign="top">e.</td>
								<td style="width:97%;">Falta de probidad v&iacute;as de hecho, injurias o conducta inmoral debidamente comprobada.
								</td>
							</tr>
							<tr>
								<td style="width:3%;" align="left" valign="top">f.</td>
								<td style="width:97%;">Negociaciones que ejecute el trabajador dentro del giro del negocio y que hubieran sido prohibidas por escrito por el empleador en el respectivo contrato de trabajo.
								</td>
							</tr>
							<tr>
								<td style="width:3%;" align="left" valign="top">g.</td>
								<td style="width:97%;">No concurrencia del trabajador a sus labores sin causa justificada durante 6 d&iacute;as consecutivos.
								</td>
							</tr>
							<tr>
								<td style="width:3%;" align="left" valign="top">h.</td>
								<td style="width:97%;">Abandono de trabajo por parte del trabajador</td>
							</tr>
							<tr>
								<td style="width:3%;" align="left" valign="top">i.</td>
								<td style="width:97%;">El perjuicio material causado intencionalmente en las instalaciones, maquinarias, herramientas, &uacute;tiles de trabajo, productos o mercader&iacute;as.
								</td>
							</tr>
							<tr>
								<td style="width:3%;" align="left" valign="top">j.</td>
								<td style="width:97%;">Incumplimiento grave de las obligaciones que impone el Contrato de Trabajo</td>
							</tr>
							<tr>
								<td style="width:3%;" align="left" valign="top">k.</td>
								<td style="width:97%;">Acto delictivo cometido por el asegurado, que derive en la situaci&oacute;n de Cesant&iacute;a del mismo.
								</td>
							</tr>
							<tr>
								<td style="width:3%;" align="left" valign="top">l.</td>
								<td style="width:97%;">Embriaguez o uso de narc&oacute;ticos que deriven en la situaci&oacute;n de Cesant&iacute;a del Asegurado.
								</td>
							</tr>
							<tr>
								<td style="width:3%;" align="left" valign="top">m.</td>
								<td style="width:97%;">
									Participaci&oacute;n activa del asegurado en acto terrorista, entendi&eacute;ndose como acto terrorista toda conducta calificada como tal por Ley, as&iacute; como el uso de fuerza o violencia o amenaza de esta, por parte de cualquier persona o grupo, motivada por causas pol&iacute;ticas, religiosas, ideol&oacute;gicas, o similares, con la intenci&oacute;n de ejercer influencia sobre cualquier Gobierno o de atemorizar a la poblaci&oacute;n &oacute; a cualquier segmento de la misma.
								</td>
							</tr>
							<tr>
								<td style="width:3%;" align="left" valign="top">n.</td>
								<td style="width:97%;">Suspensi&oacute;n de relaci&oacute;n laboral sin goce de haberes por inicio de proceso administrativo interno.
								</td>
							</tr>
							<tr>
								<td style="width:3%;" align="left" valign="top">o.</td>
								<td style="width:97%;">
									Sustituci&oacute;n como resultado de un proceso disciplinario a administrativo por responsabilidad por la funci&oacute;n P&uacute;blica o Proceso Judicial  con cesant&iacute;a condenatoria ejecutoriada.
								</td>
							</tr>
							<tr>
								<td style="width:3%;" align="left" valign="top">p.</td>
								<td style="width:97%;">Cuando el Asegurado se acoja a los beneficios de la Jubilaci&oacute;n</td>
							</tr>
							<tr>
								<td style="width:3%;" align="left" valign="top">q.</td>
								<td style="width:97%;">
									Cuando el Asegurado se acoja a los beneficios de los Seguros de riesgo profesional y Riesgo com&uacute;n del Sistema Integral de Pensiones SIP.
								</td>
							</tr>
						</table>
						<br />
						<b>EDAD DE ADMISIÓN Y PERMANENCIA:</b><br/>
						
						<b>A) Muerte por cualquier causa, B) Invalidez Total y Permanente y C) Sepelio</b><br/>
						De admisión: Mínima: 18 años <span style="<?=$cssTab;?>">Máxima: 72 años (Hasta cumplir 73 años)</span><br/>
						De Permanencia: <span style="<?=$cssTab;?>">Máxima: 76 años (Hasta cumplir 77 años)</span><br />
						
						<b style="<?=$cssMarginTop;?>">D) Desempleo:</b><br/>
						De admisión: Mínima: 18 años <span style="<?=$cssTab;?>">Máxima: 70 años (Hasta cumplir 71 años)</span><br/>
						De Permanencia: <span style="<?=$cssTab;?>">Máxima: 71 años (Hasta cumplir 72 años)</span><br />
						
						<b style="<?=$cssMarginTop;?>">TASA MENSUAL TOTAL:</b>
						<span style="<?=$cssTab;?>"><?=$row['tasa_final'];?>% o por mil mensual</span>
			   <?php
                if($row['verifica_vida'] == 1){
                ?>
					<table style="<?=$cssTable1;?> <?=$cssMarginTop;?> <?=$fuenteDet;?>" border="0" cellspsacing="0" cellpadding="0">
						<tr>
							<td style="width:20%;" align="left">
								<b>INTERMEDIARIO:<br/>
									DIRECCION:<br/>TELEFONO</b>
							</td>
							<td style="width:80%;" align="left">
								SUDAMERICANA SRL<br/>
								Prolongaci&oacute;n Cordero N&deg; 163, San Jorge, La Paz
								<br/>2433500&nbsp;&nbsp;&nbsp;
								<b>FAX:</b> 2128329
							</td>
						</tr>
					</table>
				<?php
                }
                ?>
						</td>
						<td style="<?=$cssTextPage;?> <?=$fuenteDet;?>">
							<b>PRIMA:</b> De acuerdo a declaraciones mensuales del contratante
							<br/>
							<b>FORMA DE PAGO:</b> Contado a trav&eacute;s de ECOFUTURO S.A. FFP
							
							<br />
							<b style="<?=$cssMarginTop;?>">BENEFICIARIO:</b> ECOFUTURO S.A. FFP A T&Iacute;TULO ONEROSO<br />
							
							<b style="<?=$cssMarginTop;?>">OBSERVACIONES: </b>
							Las primas de este seguro no constituyen hecho generador de tributo seg&uacute;n el Art. No. 54 de la Ley de Seguros 1883 del 25 de Junio de 1998 y a la Resoluci&oacute;n Ministerial Nro. 880 del 28 de Junio de 1999. Autorizo a la compa&ntilde;&iacute;a mi reporte a la Central de Riesgos del Mercado de Seguros, acorde a las normativas reglamentarias de la Autoridad de Fiscalizaci&oacute;n y Control de Pensiones y Seguros.
							<br />
							<b style="<?=$cssMarginTop;?>">ADHESI&Oacute;N VOLUNTARIA AL SEGURO:</b>
							<br/>
							Se deja expresamente establecido que el Asegurado que figura en el presente Certificado de Cobertura Individual, ha aceptado y consentido voluntariamente su inclusi&oacute;n en la P&oacute;liza, cuyo n&uacute;mero figura en el t&iacute;tulo del Certificado.
							
							<br />
							<b style="<?=$cssMarginTop;?>">AVISO DE SINIESTRO: </b>
							En caso de fallecimiento o invalidez total y permanente del Asegurado, el tomador, tan pronto y a m&aacute;s tardar dentro de los <u>30 d&iacute;as calendario</u> siguientes de tener conocimiento del siniestro, debe comunicar el mismo a la Compa&ntilde;&iacute;a, salvo fuerza mayor o impedimento justificado, caso contrario la Compa&ntilde;&iacute;a se libera de cualquier responsabilidad indemnizatoria por extemporaneidad.
							
							<br />					
							<b style="<?=$cssMarginTop;?>">PAGO DE SINIESTRO: </b>
							En caso de muerte del Asegurado, la indemnizaci&oacute;n del capital asegurado ser&aacute; pagada a ECOFUTURO S.A. FFP, en su calidad de beneficiario a t&iacute;tulo oneroso, como m&aacute;ximo a los 15 d&iacute;as a partir de la presentaci&oacute;n de documentos, de acuerdo al siguiente detalle:
							
							<br />
							<b style="<?=$cssMarginTop;?>">Para siniestros hasta $us3,500.00 Bs24,500.00</b>
							
							<table style="<?=$cssTable1;?> <?=$cssTextExclusion?> <?=$fuenteDet;?>" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td align="left" style="width:3%;" valign="top">&bull;</td>
									<td style="width:97%;">
										Certificado de Defunci&oacute;n o excepcionalmente Certificado de la persona de jerarqu&iacute;a en la comunidad del asegurado. 
									</td>
								</tr>
								<tr>
									<td style="width:3%;" align="left" valign="top">&bull;</td>
									<td style="width:97%;">
										Fotocopia de C. I. y/o Fotocopia de certificado de nacimiento y/o Fotocopia del Carnet de identidad RUN y/o Fotocopia de la libreta del servicio militar del asegurado debidamente visado por el Jefe de Agencia.
									</td>
								</tr>
								<tr>
									<td style="width:3%;" align="left" valign="top">&bull;</td>
									<td style="width:97%;">Liquidaci&oacute;n de cartera con el monto indemnizable.</td>
								</tr>
								<tr>
									<td style="width:3%;" align="left" valign="top">&bull;</td>
									<td style="width:97%;">
										Para el caso de Invalidez: Dictamen de Invalidez emitido por un m&eacute;dico calificador con registro en la Autoridad de Fiscalizaci&oacute;n y Control de Pensiones y Seguros (APS).
									</td>
								</tr>
								<tr>
									<td style="width:3%;" align="left" valign="top">&bull;</td>
									<td style="width:97%;">
										Para Gastos de Sepelio, Formulario de Declaraci&oacute;n de Beneficiarios o en ausencia de &eacute;ste la Declaratoria de Herederos.
									</td>
								</tr>
							</table>
							
							<b style="<?=$cssMarginTop;?>">
							Para siniestros con desembolso desde $us3,501.00 hasta $us5,000.00 o desde Bs24,501.00 hasta 35,000.00</b>
							
							<table border="0" cellspacing="0" cellpadding="0" style="<?=$cssTable1;?> <?=$cssTextExclusion?> <?=$fuenteDet;?>">
								<tr>
									<td align="left" style="width:3%;" valign="top">&bull;</td>
									<td style="width:97%;">Certificado de Defunci&oacute;n y uno de los 3 siguientes documentos:</td>
								</tr>
								<tr>
									<td style="width:3%;">&nbsp;</td>
									<td style="width:97%;">
										<table border="0" cellspacing="0" cellpadding="0" style="<?=$cssTable1;?> <?=$cssTextExclusion?> <?=$fuenteDet;?>">
											<tr>
												<td align="left" style="width:3%;" valign="top">&bull;</td>
												<td style="width:97%;">
													Certificado Forense, si el fallecimiento ocurriese de manera violenta, accidental o en ejecuci&oacute;n de un hecho delictivo.
												</td>
											</tr>
											<tr>
												<td style="width:3%;" valign="top">&bull;</td>
												<td style="width:97%;">
													Certificado &Uacute;nico de defunci&oacute;n si la muerte sucede en circunstancias distintas a las descritas en el anterior punto
												</td>
											</tr>
											<tr>
												<td style="width:3%;" valign="top">&bull;</td>
												<td style="width:97%;">
													Declaraci&oacute;n del fallecimiento emitida por la autoridad comunal reconocida, cuando el fallecimiento ocurra en localizaciones donde no se hallen disponibles servicios m&eacute;dicos
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td style="width:3%;" valign="top">&bull;</td>
									<td style="width:97%;">
										Fotocopia de C. I. y/o Fotocopia de certificado de nacimiento y/o Fotocopia del Carnet de identidad RUN y/o Fotocopia de la libreta del servicio militar del asegurado debidamente visado por el Jefe de Agencia.
									</td>
								</tr>
								<tr>
									<td style="width:3%;" valign="top">&bull;</td>
									<td style="width:97%;">Liquidaci&oacute;n de cartera con el monto indemnizable.</td>
								</tr>
								<tr>
									<td style="width:3%;" valign="top">&bull;</td>
									<td style="width:97%;">
										Para el caso de Invalidez: Dictamen de Invalidez emitido por un m&eacute;dico calificador con registro en la Autoridad de Fiscalizaci&oacute;n y Control de Pensiones y Seguros (APS).
									</td>
								</tr>
								<tr>
									<td style="width:3%;" valign="top">&bull;</td>
									<td style="width:97%;">
										Para Gastos de Sepelio, Formulario de Declaraci&oacute;n de Beneficiarios o en ausencia de &eacute;ste la Declaratoria de Herederos.
									</td>
								</tr>
							</table> 
							
							<b style="<?=$cssMarginTop;?>">Para siniestros con desembolso mayor a $us5,000.00 &oacute; Bs35,000.00</b>
							
							<table border="0" cellspacing="0" cellpadding="0" style="<?=$cssTable1;?> <?=$cssTextExclusion?> <?=$fuenteDet;?>">
								<tr>
									<td style="width:3%;" align="left"  valign="top">&bull;</td>
									<td style="width:97%;">Certificado de Defunci&oacute;n y uno de los 3 siguientes documentos:</td>
								</tr>
								<tr>
									<td style="width:3%;">&nbsp;</td>
									<td style="width:97%;">
										<table border="0" cellspacing="0" cellpadding="0" style="<?=$cssTable1;?> <?=$cssTextExclusion?> <?=$fuenteDet;?>">
											<tr>
												<td align="left" style="width:3%;" valign="top">&bull;</td>
												<td style="width:97%;">
													Certificado Forense, si el fallecimiento ocurriese de manera violenta, accidental o en la ejecuci&oacute;n de un hecho delictivo.
												</td>
											</tr>
											<tr>
												<td style="width:3%;" valign="top">&bull;</td>
												<td style="width:97%;">
													Certificado &Uacute;nico de defunci&oacute;n si la muerte sucede en circunstancias distintas a las descritas en el antrior punto
												</td>
											</tr>
											<tr>
												<td style="width:3%;" valign="top">&bull;</td>
												<td style="width:97%;">
													Declaraci&oacute;n del fallecimiento emitida por la autoridad comunal reconocida, cuando el fallecimiento ocurra en localizaciones donde no se hallen disponibles servicios m&eacute;dicos
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td style="width:3%;" valign="top">&bull;</td>
									<td style="width:97%;">
										Fotocopia de C. I. y/o Fotocopia de certificado de nacimiento y/o Fotocopia del Carnet de identidad RUN y/o Fotocopia de la libreta del servicio militar del asegurado debidamente visado por el Jefe de Agencia.
									</td>
								</tr>
								<tr>
									<td style="width:3%;" valign="top">&bull;</td>
									<td style="width:97%;">Liquidaci&oacute;n de cartera con el monto indemnizable.</td>
								</tr>
								<tr>
									<td style="width:3%;" valign="top">&bull;</td>
									<td style="width:97%;">Formulario de Declaraci&oacute;n de Salud.</td>
								</tr>
								<tr>
									<td style="width:3%;" valign="top">&bull;</td>
									<td style="width:97%;">
										Informe del Oficial de Cr&eacute;dito dando fe del suceso y descripci&oacute;n breve de lo acontecido (causa de la muerte), con el respectivo visto bueno de su inmediato superior.
									</td>
								</tr>
								<tr>
									<td style="width:3%;" valign="top">&bull;</td>
									<td style="width:97%;">Historial cl&iacute;nico si existiera.</td>
								</tr>
								<tr>
									<td style="width:3%;" valign="top">&bull;</td>
									<td style="width:97%;">Para el caso de Invalidez:</td>
								</tr>
								<tr>
									<td style="width:3%;">&nbsp;</td>
									<td style="width:97%;">
										<table border="0" cellspacing="0" cellpadding="0" style="<?=$cssTable1;?> <?=$cssTextExclusion?> <?=$fuenteDet;?>">
											<tr>
												<td align="left" style="width:3%;" valign="top">&bull;</td>
												<td style="width:97%;">
													Dictamen de Invalidez emitido por un m&eacute;dico calificador con registro en la Autoridad de Fiscalizaci&oacute;n y Control de Pensiones y Seguro (APS).
												</td>
											</tr>
											<tr>
												<td style="width:3%;" valign="top">&bull;</td>
												<td style="width:97%;">
													Historial Cl&iacute;nico en base al cual se emiti&oacute; el Dictamen correspondiente.
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td style="width:3%;" valign="top">&bull;</td>
									<td style="width:97%;">
										Para Sepelio, Formulario de Declaraci&oacute;n de Beneficiarios o en ausencia de &eacute;ste la Declaratoria de Herederos.
									</td>
								</tr>
							</table>
							
							<b style="<?=$cssMarginTop;?>">Nota.</b>&nbsp;
							La p&oacute;liza establece que el beneficio de sepelio ser&aacute; pagado por la Compa&ntilde;&iacute;a directamente a ECOFUTURO FONDO FINANCIERO PRIVADO quien ser&aacute; responsable del pago ante el beneficiario del asegurado fallecido.
							
							<br />
							<b style="<?=$cssMarginTop;?>">PAGO DE SINIESTRO EN CASO DE DESEMPLEO:</b> &nbsp;
							Producida la cesant&iacute;a involuntaria el asegurado deber&aacute; comunicar por escrito a la compa&ntilde;&iacute;a dentro de un plazo de m&aacute;ximo de treinta (30) d&iacute;as  contados desde su conocimiento. El cumplimiento extempor&aacute;neo de esta obligaci&oacute;n har&aacute; perder el derecho a la indemnizaci&oacute;n establecida en la presente cl&aacute;usula de Cobertura Adicional, salvo caso de fuerza mayor o impedimento justificado.
							
							<br />
							<b style="<?=$cssMarginTop;?>">DEFINICION DE DESEMPLEO:</b>&nbsp;
							P&eacute;rdida involuntaria del empleo como concecuencia de despido por parte del empleador por alguna causa que no se encuentre espec&iacute;ficamente excluida. El asegurado debera contar por lo menos con 12 meses de antig&uuml;edad comprobada en el empleo.
							
							<br />
							<span style="<?=$cssMarginTop;?>">Los documentos que se deben presentar para la liquidaci&oacute;n del reclamo son los siguientes:
							</span>
							<table border="0" cellspacing="0" cellpadding="0" style="<?=$cssTable1;?> <?=$cssTextExclusion?> <?=$fuenteDet;?>">
								<tr>
									<td style="width:3%;" align="left" valign="top"><br />&bull;</td>
									<td style="width:97%;"><br />Copia del Contrato de Trabajo</td>
								</tr>
								<tr>
									<td style="width:3%;" align="left" valign="top">&bull;</td>
									<td style="width:97%;">Carta de Despido</td>
								</tr>
								<tr>
									<td style="width:3%;" align="left" valign="top">&bull;</td>
									<td style="width:97%;">
										Original de Finiquito visado por el Ministerio de Trabajo (Para asegurados dependientes del sector p&uacute;blico, cuando corresponda, se aceptar&aacute; el original del Memorandum de retiro en reemplazo del Finiquito)
									</td>
								</tr>
								<tr>
									<td style="width:3%;" align="left" valign="top">&bull;</td>
									<td style="width:97%;">Plan de Pagos del Cr&eacute;dito</td>
								</tr>
								<tr>
									<td style="width:3%;" align="left" valign="top">&bull;</td>
									<td style="width:97%;">Fotocopia del Carnet de Identidad.</td>
								</tr>
								<tr>
									<td style="width:3%;" align="left" valign="top">&bull;</td>
									<td style="width:97%;">Copia del contrato de pr&eacute;stamo</td>
								</tr>
							</table>
							
							<div style="<?=$cssMarginTop;?>"></div>
							Todo lo que no est&eacute; previsto por el presente Certificado de Cobertura Individual, se sujetar&aacute; a lo establecido en las Condiciones Particulares, Condiciones Generales y dem&aacute;s documentos Anexos a la presente P&oacute;liza de Seguro de Desgravamen Hipotecario en Grupo, el C&oacute;digo de Comercio, la Ley de Seguros y por las disposiciones legales vigentes en la materia.
						</td>
					</tr>
			</table>
		</div>
        
        <?php
          if($row['verifica_vida'] == 0){
		?>
            <div style="<?=$cssContent2;?> <?=$fuenteDet;?>">
				<h1 style="<?=$cssH1;?> margin-top: 10px;">
					<span style="text-decoration: underline;">SEGURO DE VIDA EN GRUPO - ANUAL RENOVABLE<br />
					CERTIFICADO DE COBERTURA INDIVIDUAL</span><br /><br />
					COD. 205-934607-2004 01 005 - 4001<br/>
					RESOLUCIÓN ADMINISTRATIVA DE LA SPVS: IS No. 075, 19/03/04<br/>
					
					<span style="text-decoration: underline;">PÓLIZA NRO. LP-0001113</span>
				</h1>
				<table style="<?=$cssTable1;?> margin-top: 5px; <?=$fuenteDet;?>" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td style="<?=$cssTextPage;?> <?=$fuenteDet;?>">
							El presente Certificado de Cobertura Individual, tan solo representa un documento referencial. En caso que el Asegurado desee conocer inextenso las normas, t&eacute;rminos y condiciones del seguro, &eacute;ste deber&aacute; referirse &uacute;nicamente a la P&oacute;liza original, la misma que se encuentra en posesi&oacute;n del Tomador.
							
							<table style="<?=$cssTable1;?> <?=$cssMarginTop;?> <?=$fuenteDet;?>" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td style="width: 30%; vertical-align: top;" align="left" valign="top"><b >TOMADOR:</b></td>
									<td style="width: 50%; vertical-align: top;">ECOFUTURO S.A. FFP</td>
								</tr>
								<tr>
									<td style="width: 30%; vertical-align: top;"><b >ASEGURADO:</b></td>
									<td style="width: 50%; vertical-align: top;">
										Datos que figuran en Titular 1 y/o Titular 2 en la declaraci&oacute;n de salud (reverso) y/o Cotitulares adicionales
									</td>
								</tr>
								<tr>
									<td style="width: 30%; vertical-align: top;"><b >GRUPO ASEGURADO:</b></td>
									<td style="width: 50%; vertical-align: top;">Clientes del Tomador</td>
								</tr>
								<tr>
									<td style="width: 30%; vertical-align: top;"><b >ACTIVIDAD GENERAL:</b></td>
									<td style="width: 50%; vertical-align: top;">Varias y Sin Limitaciones</td>
								</tr>
							</table>
							
							<b style="<?=$cssMarginTop;?>">COBERTURAS:</b>
							<br/>
							<b >A)</b>&nbsp;Muerte por cualquier Causa del Asegurado, no excluida en las Condiciones Generales de la P&oacute;liza.
							<br/>
							<b >B)</b>&nbsp;Invalidez Total y Permanente del Asegurado, no excluida en la Cobertura Complementaria respectiva.
							
							<br />
							<b style="<?=$cssMarginTop;?>">EXCLUSIONES:</b><br />
							Sin Exclusiones
							
							<br />
							<b style="<?=$cssMarginTop;?>">EDAD DE ADMISI&Oacute;N Y PERMANENCIA:</b>
							<br />
							Para Muerte e Invalidez<br/>
							M&iacute;nima 18 A&ntilde;os<br/>
							M&aacute;xima 72 a&ntilde;os (Hasta cumplir los 73 a&ntilde;os) al momento de inicio de la cobertura.
							<br/>
							Renovaci&oacute;n garantizada en funci&oacute;n de la duraci&oacute;n del cr&eacute;dito hasta los 76 a&ntilde;os  (Hasta cumplir los 77 A&ntilde;os)
							
							<br />
							<b style="<?=$cssMarginTop;?>">MONEDA:</b>&nbsp;&nbsp;&nbsp;&nbsp;
							D&oacute;lares Americanos y/o Bolivianos
							
							<br />
							<b style="<?=$cssMarginTop;?>">SUMAS ASEGURADAS:</b>
							
							<br/>
							<b>Para las Coberturas de Muerte e Invalidez.</b>&nbsp;
							El Capital Asegurado Corresponde al monto que resulte de la diferencia entre el monto desembolsado y la indemnizaci&oacute;n pagada a Eco Futuro por la cobertura de la p&oacute;liza Desgravamen con N&deg; '.$reg15[2].' emitida por SEGUROS PROVIDA S.A. &Eacute;ste beneficio ser&aacute; desembolsado a favor de los beneficiarios declarados por el asegurado &oacute; en ausencia de esta declaraci&oacute;n a favor de los Herederos Legales en caso de muerte y a favor del propio asegurado en caso de invalidez total.
							
							<br />
							<b style="<?=$cssMarginTop;?>">PRIMA ANUAL:</b>
							
							<br/>
							<b>Para las Coberturas de Muerte e Invalidez.</b>&nbsp;
							La Tasa a aplicarse ser&aacute; de 0.83%o por mil mensual sobre el monto que resulte de la diferencia entre el total desembolsado y el saldo a capital mensual declarado para efectos de la cobertura de desgravamen.
							<br/> 
							En caso de cr&eacute;ditos mancomunados, la Tasa a aplicarse ser&aacute; del 0.83%o por mil mensual sobre el monto que resulte de la diferencia entre el total desembolsado y el saldo a capital mensual declarado por el tomador por ambos asegurados, para efectos de la cobertura de desgravamen.
							
							<br />
							<b style="<?=$cssMarginTop;?>">FORMA DE PAGO miguel:</b>
							
							<br/>Las Primas de cada prestatario, as&iacute; como del total del grupo, ser&aacute;n pagadas por &nbsp;
							<b>ECOFUTURO FONDO FINANCIERO PRIVADO</b>, en forma mensual por mes vencido hasta el d&iacute;a 30 del mes siguiente, sujeta a la declaraci&oacute;n por parte del contratante, donde se especificar&aacute; el nombre, C.I. y fecha de nacimiento del prestatario y en caso de los codeudores, el n&uacute;mero de operaci&oacute;n, el capital inicial, el saldo actualizado y el porcentaje de deuda de cada codeudor.
							
							<br />
							<b style="<?=$cssMarginTop;?>">BENEFICIARIOS:</b>
							
							<br/>
							Los Declarados por el Asegurado o los Herederos Legales en caso de muerte y el propio asegurado en caso de invalidez total.
							
							<table border="0" cellspsacing="0" cellpadding="0" style="<?=$cssTable1;?> <?=$cssMarginTop;?> <?=$fuenteDet;?>">
								<tr>
									<td style="width:20%;" align="left">
										<b>INTERMEDIARIO:<br/>
											DIRECCION:<br/>TELEFONO</b>
									</td>
									<td style="width:80%;" align="left">
										SUDAMERICANA SRL<br/>
										Prolongaci&oacute;n Cordero N&deg; 163, San Jorge, La Paz
										<br/>2433500&nbsp;&nbsp;&nbsp;
										<b>FAX:</b> 2128329
									</td>
								</tr>
							</table>
						</td>
						<td style="<?=$cssTextPage;?> <?=$fuenteDet;?>">
							<b style="<?=$cssMarginTop;?>">ADHESION VOLUNTARIA AL SEGURO:</b>
							<br />
							Se deja expresamente establecido que el Asegurado que figura en el presente Certificado de Cobertura Individual, ha aceptado y consentido  voluntariamente su inclusi&oacute;n en la P&oacute;liza, cuyo n&uacute;mero figura en el t&iacute;tulo del Certificado.
							
							<br />
							<b style="<?=$cssMarginTop;?>">SINIESTRO:</b>
							<br/>
							En caso de fallecimiento &oacute; invalidez total y permanente; el Tomador, tan pronto y a m&aacute;s tardar dentro de los 30 d&iacute;as calendario siguientes de tener conocimiento del siniestro, debe comunicar el mismo a la Compa&ntilde;&iacute;a, salvo fuerza mayor o impedimento justificado, caso contrario la Compa&ntilde;&iacute;a se libera de cualquier responsabilidad indemnizatoria por extemporaneidad.
							
							<br />
							<span style="<?=$cssMarginTop;?>">En caso de muerte &oacute; invalidez del Asegurado, la indemnizaci&oacute;n del capital asegurado ser&aacute; pagada a los beneficiarios declarados o en ausencia de &eacute;stos a los Herederos Legales, en caso de muerte y al propio asegurado en caso de invalidez total; como m&aacute;ximo a los 15 d&iacute;as despu&eacute;s de haber presentado los documentos probatorios de la muerte o invalidez total del Asegurado, de acuerdo al siguiente detalle:
							</span>
							
							<br />
							<b style="<?=$cssMarginTop;?>">MUERTE POR CUALQUIER CAUSA</b><br/>
							<b>Para siniestros con desembolso hasta $us3,500.00 o Bs24,500.00</b>
							
							<table border="0" cellspacing="0" cellpadding="0" style="<?=$cssTable1;?> <?=$cssMarginTop;?> <?=$fuenteDet;?>">
								<tr>
									<td style="width:3%;" align="left" valign="top">&bull;</td>
									<td style="width:97%;">
										Certificado de Defunci&oacute;n o excepcionalmente Certificado de la persona de jerarqu&iacute;a en la comunidad del asegurado.
									</td>
								</tr>
								<tr>
									<td style="width:3%;" align="left" valign="top">&bull;</td>
									<td style="width:97%;">
										Fotocopia de C. I. y/o Fotocopia de certificado de nacimiento. y/o Fotocopia del Carnet de identidad RUN y/o Fotocopia de la libreta del servicio militar del asegurado debidamente visado por el Jefe de Agencia.
									</td>
								</tr>
								<tr>
									<td style="width:3%;" align="left" valign="top">&bull;</td>
									<td style="width:97%;">Liquidaci&oacute;n de cartera con el monto indemnizable.</td>
								</tr>
								<tr>
									<td style="width:3%;" align="left" valign="top">&bull;</td>
									<td style="width:97%;">
										Formulario de Declaraci&oacute;n de Beneficiarios o en ausencia de &eacute;ste la Declaratoria de Herederos.
									</td>
								</tr>
								<tr>
									<td style="width:3%;" align="left" valign="top">&bull;</td>
									<td style="width:97%;">Fotocopia(s) de(los) documentos de identidad del(los) beneficiario(s).</td>
								</tr>
							</table>
							
							<b style="<?=$cssMarginTop;?>">
								Para siniestros con desembolso desde $us3,501.00 hasta $us5,000.00 o desde Bs24,501.00 hasta Bs35,000.00
							</b>
										
							<table border="0" cellspacing="0" cellpadding="0" style="<?=$cssTable1;?> <?=$fuenteDet;?>">
								<tr>
									<td style="width:3%;" align="left" valign="top">&bull;</td>
									<td style="width:97%;">Certificado de Defunci&oacute;n y uno de los 3 siguientes documentos:</td>
								</tr>
								<tr>
									<td style="width:3%;">&nbsp;</td>
									<td style="width:97%;">
										<table border="0" cellspacing="0" cellpadding="0" style="<?=$cssTable1;?> <?=$fuenteDet;?>">
											<tr>
												<td style="width:3%;" align="left" valign="top">&bull;</td>
												<td style="width:97%;">
													Certificado Forense, si el fallecimiento ocurriese de manera violenta, accidental o en ejecuci&oacute;n de un hecho delictivo.
												</td>
											</tr>
											<tr>
												<td style="width:3%;" valign="top">&bull;</td>
												<td style="width:97%;">
													Certificado &Uacute;nico de defunci&oacute;n si la muerte sucede en circunstancias distintas a las descritas en el anterior punto
												</td>
											</tr>
											<tr>
												<td style="width:3%;" valign="top">&bull;</td>
												<td style="width:97%;">
													Declaracion del fallecimiento emitida por la autoridad comunal reconocida, cuando el fallecimiento ocurra en localizaciones donde no se hallen disponibles servicios m&eacute;dicos
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td style="width:3%;" valign="top">&bull;</td>
									<td style="width:97%;">
										Fotocopia de C. I. y/o Fotocopia de certificado de nacimiento. y/o Fotocopia del Carnet de identidad RUN y/o Fotocopia de la libreta del servicio militar del asegurado debidamente visado por el Jefe de Agencia.
									</td>
								</tr>
								<tr>
									<td style="width:3%;" valign="top">&bull;</td>
									<td style="width:97%;">Liquidaci&oacute;n de cartera con el monto indemnizable.</td>
								</tr>
								<tr>
									<td style="width:3%;" valign="top">&bull;</td>
									<td>
										Formulario de Declaraci&oacute;n de Beneficiarios o en ausencia de &eacute;ste la Declaratoria de Herederos.
									</td style="width:97%;">
								</tr>
								<tr>
									<td style="width:3%;" valign="top">&bull;</td>
									<td style="width:97%;">Fotocopia(s) de(los) documentos de identidad del(los) beneficiario(s)</td>
								</tr>
							</table>
							
							<b style="<?=$cssMarginTop;?>">INVALIDEZ TOTAL Y PERMANENTE</b>
							
							<table border="0" cellspacing="0" cellpadding="0" style="<?=$cssTable1;?> <?=$cssMarginTop;?> <?=$fuenteDet;?>">
								<tr>
									<td style="width:3%;" align="left" valign="top">&bull;</td>
									<td style="width:97%;">
										Fotocopia de C. I. y/o Fotocopia de certificado de nacimiento. y/o Fotocopia del Carnet de identidad RUN y/o Fotocopia de la libreta del servicio militar del asegurado debidamente visado por el Jefe de Agencia.
									</td>
								</tr>
								<tr>
									<td style="width:3%;" align="left" valign="top">&bull;</td>
									<td style="width:97%;">Liquidaci&oacute;n de cartera con el monto indemnizable.</td>
								</tr>
								<tr>
									<td style="width:3%;" align="left" valign="top">&bull;</td>
									<td style="width:97%;">
										Dictamen de un m&eacute;dico calificador con registro en la APS(Autoridad de Fiscalizaci&oacute;n y Control de Pensiones y Seguros), el cual determine el grado de invalidez.
									</td>
								</tr>
							</table>	
							
							<b style="<?=$cssMarginTop;?>">PLAZO PARA PAGO DE SINIESTROS</b>
							
							<br/>
							El plazo para el pago de siniestros ser&aacute; realizado dentro de los 15 d&iacute;as de recibidos todos los documentos requeridos por la compa&ntilde;&iacute;a. Dicho pago se realizar&aacute; a trav&eacute;s de la emisi&oacute;n de cheques a nombre de los beneficiarios declarados &oacute; en ausencia a nombre de los Herederos Legales en caso de muerte y a nombre del propio asegurado en caso de invalidez.
							<br />
							<span style="<?=$cssMarginTop;?>">Todo lo que no est&eacute; previsto por el presente Certificado de Cobertura Individual, se sujetar&aacute; a lo establecido en las Condiciones Particulares, Condiciones Generales y dem&aacute;s documentos Anexos a la presente P&oacute;liza de Seguro de Vida en Grupo, el C&oacute;digo de Comercio, la Ley de Seguros y por las disposiciones legales vigentes en la materia.
							</span>
						</td>
					</tr>
				</table>
			</div>
        <?php
		  }
		?>
        
          <div style="<?=$cssContent2;?>">
              <h1 style="<?=$cssH1;?> margin-top: 15px;">
                  SEGUROS PRO VIDA S.A.<br />
                  FIRMAS AUTORIZADAS
              </h1>
              <table style="<?=$cssTable1;?> <?=$fuenteDet;?>" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                      <td style="width: 20%;"></td>
                      <td style="width: 20%; text-align:center;">
                          <img src="img/firma-ds.jpg" width="84" height="97" />
                      </td>
                      <td style="width: 20%;"></td>
                      <td style="width: 20%; text-align:center;">
                          <img src="img/firma-rs.jpg" width="46" height="97" />
                      </td>
                      <td style="width: 20%;"></td>
                  </tr>
                  <tr>
                      <td style="width: 20%;"></td>
                      <td style="width: 20%; text-align: center;">Dario Soraide Jimenez<br/>Jefe de Producción</td>
                      <td style="width: 20%;"></td>
                      <td style="width: 20%; text-align: center;">Ramiro Salinas Soruco<br/>Gerente General</td>
                      <td style="width: 20%;"></td>
                  </tr>
              </table>
          </div>
<?php
	if ($fac === TRUE) {
		$url .= 'index.php?ms='.md5('MS_DE').'&page='.md5('P_fac').'&ide='.base64_encode($row['id_emision']).'';
?>
<br>
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

	if ($implant === TRUE) {
		$url .= 'index.php?ms='.md5('MS_DE').'&page='.md5('P_app_imp').'&ide='.base64_encode($row['id_emision']).'';
?>
<br>
<div style="width:500px; height:auto; padding:10px 15px; border:1px solid #00FFFF; background:#9FE0FF; color:#303030; font-size:10px; font-weight:bold; text-align:justify;">
	Solicitud de Aprobaci&oacute;n: P&oacute;liza: <?=$row['prefijo'].'-'.$row['no_emision'];?><br><br>
</div>
<div style="width:500px; height:auto; padding:10px 15px; font-size:11px; font-weight:bold; text-align:left;">
	Para procesar la solicitud ingrese al siguiente link con sus credenciales de usuario:<br>
	<a href="<?=$url;?>" target="_blank">Procesar Solicitud de Aprobaci&oacute;n</a>
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

function formatCheck($data){
	return explode('-', $data);
}
?>