<?php

header("Expires: Tue, 01 Jan 2000 06:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require('sibas-db.class.php');
$s_ef = array();
$s_nc = '';
$s_user = '';
$s_client = '';
$s_dni = '';
$s_comp = '';
$s_ext = '';
$s_date_begin = '2000-01-01';
$s_date_end = '3000-01-01';

$token = 0;
$bg = '';
$nMt = 0;

if(isset($_GET['token'])){
	switch($_GET['token']){
		case md5('1'):
			$token = 0;
			break;
		case md5('2'):
			$token = 1;
			break;
	}
}
?>
<script type="text/javascript">
$(document).ready(function(e) {
	$(".row").reportCxt({
		product: 'TRD'
	});
});
</script>
<input type="hidden" id="ms" name="ms" value="<?=@$_GET['ms'];?>">
<input type="hidden" id="page" name="page" value="<?=@$_GET['page'];?>">
<table class="result-list">
	<thead>
    	<tr>
<?php
if($token === 1){
	echo '<td>Leido / No Leido</td>';
}
?>
        	<td>No. Póliza</td>
            <td>Entidad Financiera</td>
            <td>Cliente</td>
            <td>CI</td>
            <td>Complemento</td>
            <td>Extensión</td>
            <td>Ciudad</td>
            <td>Teléfono</td>
            <td>Celular</td>
            <td>Email</td>
            <td>Departamento</td>
            <td>Zona</td>
            <td>Localidad</td>
            <td>Tipo</td>
            <td>Dirección</td>
            <td>Uso</td>
            <td>Valor Asegurado</td>
            <td>Valor Muebles y/o contenido</td>
            <td>Creado Por</td>
            <td>Sucursal Registro</td>
            <td>Agencia</td>
            <td>Fecha de Ingreso</td>
            <td>Días en Proceso</td>
            <td>Estado</td>
            <td>Días de Ultima Modificación</td>
            <td>Observaciones Realizadas</td>
            <!--<td>Acción</td>-->
        </tr>
    </thead>
<?php
if(isset($_GET['fde']) && isset($_GET['fde-id-user'])){
	$link = new SibasDB();
	
	if(isset($_GET['fde-ef'])){
		$s_ef = $_GET['fde-ef'];
	}else {
		$s_ef[0] = base64_encode('%%');
	}
	
	$s_nc = $link->real_escape_string(trim($_GET['fde-nc']));
	$s_user = $link->real_escape_string(trim($_GET['fde-user']));
	$s_client = $link->real_escape_string(trim($_GET['fde-client']));
	$s_dni = $link->real_escape_string(trim($_GET['fde-dni']));
	$s_comp = $link->real_escape_string(trim($_GET['fde-comp']));
	$s_ext = $link->real_escape_string(trim($_GET['fde-ext']));
	$s_date_begin = $link->real_escape_string(trim($_GET['fde-date-b']));
	$s_date_end = $link->real_escape_string(trim($_GET['fde-date-e']));
	
	$idUSer = $link->real_escape_string(trim(base64_decode($_GET['fde-id-user'])));
	$type_user = $link->real_escape_string(trim(base64_decode($_GET['fde-type-user'])));
	
	if($s_date_begin === '' && $s_date_end === ''){
		$s_date_begin = '2000-01-01';	$s_date_end = '3000-01-01';
	}elseif($s_date_begin !== '' && $s_date_end === ''){
		$s_date_end = '3000-01-01';
	}elseif($s_date_begin === '' && $s_date_end !== ''){
		$s_date_end = '3000-01-01';
	}
	
	$_IMP = FALSE;
	if($token === 1){
		$s_user = $idUSer;
	}
	
	$_SW_EM = FALSE;
	
	$nEF = count($s_ef);
	
	$sql = "select 
	    stre.id_emision as ide,
	    stre.id_cotizacion as idc,
	    stre.id_compania,
	    count(strd.id_emision) as noMt,
	    if(strf.aprobado is null,
	        if(strp.id_pendiente is not null,
	            case strp.respuesta
	                when 1 then 'S'
	                when 0 then 'O'
	            end,
	            if((stre.emitir = 0 and stre.aprobado = 0)
	                    or stre.facultativo = 1,
	                'P',
	                'F')),
	        case strf.aprobado
	            when 'SI' then 'A'
	            when 'NO' then 'R'
	        end) as estado,
	    case
	        when sds.codigo = 'ED' then 'E'
	        when sds.codigo != 'ED' then 'NE'
	        else null
	    end as observacion,
	    sds.id_estado,
	    sds.estado as estado_pendiente,
		sds.codigo as estado_codigo,
	    stre.prefijo,
	    stre.no_emision,
	    stre.no_poliza,
	    (case scl.tipo
	        when 0 then 'NATURAL'
	        when 1 then 'JURIDICO'
	    end) cl_tipo,
	    (case scl.tipo
	        when
	            0
	        then
	            concat(scl.nombre,
	                    ' ',
	                    scl.paterno,
	                    ' ',
	                    scl.materno)
	        when 1 then scl.razon_social
	    end) as cl_nombre,
	    scl.ci as cl_ci,
	    scl.complemento as cl_complemento,
	    sdep.codigo as cl_extension,
	    sdep.departamento as cl_ciudad,
	    (case scl.genero
	        when 'M' then 'Hombre'
	        when 'F' then 'Mujer'
	    end) as cl_genero,
	    (case scl.tipo
	        when 0 then scl.telefono_domicilio
	        when 1 then scl.telefono_oficina
	    end) as cl_telefono,
	    scl.telefono_celular as cl_celular,
	    scl.email as cl_email,
	    su.nombre as u_nombre,
	    sdepu.departamento as u_sucursal,
	    sag.agencia as u_agencia,
	    date_format(stre.fecha_creacion, '%d/%m/%Y') as fecha_ingreso,
	    sef.nombre as ef_nombre,
	    sef.logo as ef_logo,
	    datediff(curdate(), stre.fecha_creacion) as dias_proceso,
	    @fum:=(if(strf.aprobado is null,
			datediff(curdate(), strp.fecha_creacion),
			datediff(curdate(), strf.fecha_creacion))) as fum,
		if(@fum is not null, @fum, 0) as dias_ultima_modificacion,
	    stre.leido
	from
	    s_trd_em_cabecera as stre
	        inner join
	    s_trd_em_detalle as strd ON (strd.id_emision = stre.id_emision)
	        left join
	    s_trd_facultativo as strf ON (strf.id_emision = stre.id_emision)
			left join
		s_trd_pendiente as strp ON (strp.id_emision = stre.id_emision)
		    left join
		s_estado as sds ON (sds.id_estado = strp.id_estado)
	        inner join
	    s_cliente as scl ON (scl.id_cliente = stre.id_cliente)
	        inner join
	    s_entidad_financiera as sef ON (sef.id_ef = stre.id_ef)
	        inner join
	    s_departamento as sdep ON (sdep.id_depto = scl.extension)
	        inner join
	    s_usuario as su ON (su.id_usuario = stre.id_usuario)
	        inner join
	    s_departamento as sdepu ON (sdepu.id_depto = su.id_depto)
	        left join
	    s_agencia as sag ON (sag.id_agencia = su.id_agencia)
	where
	    stre.facultativo = true
	        and stre.emitir = false
	        and stre.anulado = false
	        and sef.activado = true
	        and stre.aprobado = true
	        and stre.rechazado = false
	        and (";
	if($nEF > 0) {
		$sql .= "sef.id_ef like '".base64_decode($s_ef[0])."'";
		for($i = 1; $i < $nEF; $i++){
			$sql .= " or sef.id_ef like '".base64_decode($s_ef[$i])."' ";
		}
	} else {
		$sql .= "sef.id_ef like '%%'";
	}
	$sql .= ") and stre.no_emision like '%".$s_nc."%' ";
	
	$sqlAg = '';
	if ($_IMP === FALSE) {
		$_SW_EM = TRUE;
		user_log:
		$sql .= " and (su.usuario like '%".$s_user."%'
	        or su.id_usuario = '".$s_user."')";
	}
	
	if($token === 0){
		$sql .= " and not exists( select 
				strf2.id_emision
			from
				s_trd_facultativo as strf2
			where
				strf2.id_emision = stre.id_emision)";
	}
	
	$sql .= $sqlAg." and (case scl.tipo
				when
					0
				then
					concat(scl.nombre,
							' ',
							scl.paterno,
							' ',
							scl.materno)
				when 1 then scl.razon_social
			end) like '%".$s_client."%'
	        and scl.ci like '%".$s_dni."%'
	        and scl.complemento like '%".$s_comp."%'
	        and scl.extension like '%".$s_ext."%'
	        and stre.fecha_creacion between '".$s_date_begin."' and '".$s_date_end."'
	group by stre.id_emision
	order by stre.id_emision desc
	;";
	// echo $sql;
	
	$rs = $link->query($sql, MYSQLI_STORE_RESULT);
	if($rs->num_rows > 0){
?>
	<tbody>
<?php
		$swBG = FALSE;
		$arr_state = array('txt' => '', 'action' => '', 'obs' => '', 'link' => '', 'bg' => '');
		$bgCheck = '';
		$unread = '';
		
		while($row = $rs->fetch_array(MYSQLI_ASSOC)){
			$rowSpanBgOld = '';

			$nMt = (int)$row['noMt'];
			
			if($swBG === FALSE){
				$bg = 'background: #EEF9F8;';
			}elseif($swBG === TRUE){
				$bg = 'background: #D1EDEA;';
			}
			
			$rowSpan = FALSE;
			if($nMt > 0) {
				$rowSpan = TRUE;
			}
			
			$sqlMt = "select 
			    stre.id_emision as ide,
			    strd.id_inmueble as idPr,
			    strd.tipo_in as pr_tipo, 
			    strd.uso as pr_uso, 
			    sdep.departamento as pr_depto, 
			    strd.zona as pr_zona, 
			    strd.localidad as pr_localidad, 
			    strd.direccion as pr_direccion,
			    strd.valor_asegurado as pr_valor_asegurado,
			    strd.valor_contenido as pr_valor_contenido
			from
			    s_trd_em_cabecera as stre
			        inner join
			    s_trd_em_detalle as strd ON (strd.id_emision = stre.id_emision)
			    	inner join
			    s_departamento as sdep ON (sdep.id_depto = strd.departamento)
			where
			    strd.id_emision = '".$row['ide']."'
			order by strd.id_inmueble asc
			;";
			// echo $sqlMt;
			$rsMt = $link->query($sqlMt, MYSQLI_STORE_RESULT);
			if($rsMt->num_rows <= $nMt){
				while($rowMt = $rsMt->fetch_array(MYSQLI_ASSOC)){
					$_EM = 0;
					/*if ($token === 0) {
						$nMt = $_PEN;
					} else {
						$nMt = $rsMt->num_rows;
					}*/
					
					if($token === 1){
						if(1 === (int)$row['leido']){
							$bgCheck = 'background-position:0 0;';
							$unread = '';
						}elseif(0 === (int)$row['leido']){
							$bgCheck = 'background-position:0 -24px;';
							$unread = 'unread';
						}

						$bgOld = 'color: #ffffff; background: ';
                		$dayOld = (int)$row['dias_ultima_modificacion'];
                		
                		if ($dayOld >= 0 && $dayOld <= 2) {
		                    $bgOld .= '#18b745;';
		                } elseif ($dayOld >= 3 && $dayOld <= 10) {
		                    $bgOld = 'color: #000000; background: #f4ec1c;';
		                } elseif ($dayOld > 10) {
		                    $bgOld .= '#f31d1d;';
		                }

		                if ($token === 1) {
		                    $rowSpanBgOld .= ' style="' . $bgOld . '"';
		                }
						
						if ($row['estado'] === 'A' && $_SW_EM === TRUE) {
							$_EM = 1;
						}
					}
					
					$arr_state['txt'] = '';		$arr_state['action'] = '';
					$arr_state['obs'] = '';		$arr_state['link'] = '';	$arr_state['bg'];
					
					$link->get_state($arr_state, $row, $token, 'TRM', FALSE);
?>
		<tr style=" <?=$bg;?> " class="row <?=$unread;?>" rel="0" data-nc="<?=base64_encode($row['ide']);?>" data-token="<?=$token;?>" data-vh="" data-issue="<?=base64_encode($_EM);?>" >
<?php
				if($rowSpan === TRUE){
					$rowSpan = 'rowspan="'.$nMt.'"';
				}elseif($rowSpan === FALSE){
					$rowSpan = '';
				}elseif($rowSpan === 'rowspan="'.$nMt.'"'){
					$rowSpan = 'style="display:none;"';
				}
				
				if($token === 1){
?>
			<td <?=$rowSpan;?>><label class="check-label" style=" <?=$bgCheck;?> " data-read="<?=$row['leido'];?>"></label></td>
<?php
				}
?>
			
            <td <?=$rowSpan . $rowSpanBgOld;?>><?= $row['no_poliza'] ;?></td>
            <td <?=$rowSpan;?>><?=mb_strtoupper($row['ef_nombre']);?></td>
            <td <?=$rowSpan;?>><?=mb_strtoupper($row['cl_nombre']);?></td>
            <td <?=$rowSpan;?>><?=$row['cl_ci'];?></td>
            <td <?=$rowSpan;?>><?=$row['cl_complemento'];?></td>
            <td <?=$rowSpan;?>><?=$row['cl_extension'];?></td>
            <td <?=$rowSpan;?>><?=$row['cl_ciudad'];?></td>
            <td <?=$rowSpan;?>><?=$row['cl_telefono'];?></td>
            <td <?=$rowSpan;?>><?=$row['cl_celular'];?></td>
            <td <?=$rowSpan;?>><?=$row['cl_email'];?></td>
            <td><?= $rowMt['pr_depto'] ;?></td>
            <td><?= $rowMt['pr_zona'] ;?></td>
            <td><?= $rowMt['pr_localidad'] ;?></td>
            <td><?= $link->typeProperty[$rowMt['pr_tipo']] ;?></td>
            <td><?= $rowMt['pr_direccion'] ;?></td>
            <td><?= $link->useProperty[$rowMt['pr_uso']] ;?></td>
            <td><?=number_format($rowMt['pr_valor_asegurado'], 2, '.', ',');?> USD.</td>
            <td><?=number_format($rowMt['pr_valor_contenido'], 2, '.', ',');?> USD.</td>
            <td><?=$row['u_nombre'];?></td>
            <td><?=$row['u_sucursal'];?></td>
            <td><?=htmlentities($row['u_agencia'], ENT_QUOTES, 'UTF-8');?></td>
            <td><?=$row['fecha_ingreso'];?></td>
            <td><?=$row['dias_proceso'];?></td>
            <td <?=$rowSpan;?> style=" <?=$arr_state['bg'];?> "><?=$arr_state['txt'];?></td>
            <td <?=$rowSpan;?>><?=$row['dias_ultima_modificacion'];?></td>
            <td <?=$rowSpan;?>><?=$arr_state['obs'];?></td>
            <!--<td>Acción</td>-->
        </tr>
<?php
				}
			}
			if($swBG === FALSE)
				$swBG = TRUE;
			elseif($swBG === TRUE)
				$swBG = FALSE;
		}
?>
	</tbody>
<?php
	}
}
?>
</table>