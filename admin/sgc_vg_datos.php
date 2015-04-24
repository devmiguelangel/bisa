<?php
include('sgc_funciones.php');
include('sgc_funciones_entorno.php');
include('main_menu.php');
require_once('config.class.php');
$conexion = new SibasDB();

//TENGO Q VER SI EL USUARIO HA INICIADO SESION
if(isset($_SESSION['usuario_sesion']) && isset($_SESSION['tipo_sesion'])) {
	//SI EL USUARIO HA INICIADO SESION, MOSTRAMOS LA PAGINA
	mostrar_pagina($_SESSION['id_usuario_sesion'], $_SESSION['tipo_sesion'], $_SESSION['usuario_sesion'], $_SESSION['id_ef_sesion'], $conexion, $lugar);
	
} else {
	//SI EL USUARIO NO HA INICIADO SESION, VEMOS SI HA HECHO CLICK EN EL FORMULARIO DE LOGIN
	if(isset($_POST['username'])) {
		//SI HA HECHO CLICK EN EL FORM DE LOGIN, VALIDAMOS LOS DATOS Q HA INGRESADO
		if(validar_login($conexion)) {
			//SI LOS DATOS DEL FORM SON CORRECTOS, MOSTRAMOS LA PAGINA
			header('Location: index.php?l=des_datos&var=de');
			exit;
		} else {
			mostrar_login_form(2);
		}
	} else {
		mostrar_login_form(1);
	}
}


//FUNCION PARA MOSTRAR EL SGC PARA ADMINISTRACION DE USUARIOS
function mostrar_pagina($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion, $lugar) {			
?>
       
	<!-- Main Wrapper. Set this to 'fixed' for fixed layout and 'fluid' for fluid layout' -->
	<div id="da-wrapper" class="fluid">
    
        <!-- Header -->
        <div id="da-header">
        
        	<div id="da-header-top">
                
                <!-- Container -->
                <div class="da-container clearfix">
                    
                    <!-- Logo Container. All images put here will be vertically centere -->
                    <div id="da-logo-wrap">
                        <?php logo_container($tipo_sesion,$id_ef_sesion,$id_usuario_sesion,$conexion);?>
                    </div>
                                      
                    <!-- Header Toolbar Menu -->
                    <div id="da-header-toolbar" class="clearfix">
                        <?php header_toolbar_menu($id_usuario_sesion,$tipo_sesion,$usuario_sesion,$conexion);?>
                    </div>
                                    
                </div>
            </div>
            
            <div id="da-header-bottom">
                <?php header_bottom('i',$_GET['var'],1);?>
            </div>
        </div>
    
        <!-- Content -->
        <div id="da-content">
            
            <!-- Container -->
            <div class="da-container clearfix">
            
                <!-- Sidebar -->
                <div id="da-sidebar-separator"></div>
                <div id="da-sidebar">
                
                    <!-- Main Navigation -->
                    <div id="da-main-nav" class="da-button-container">
                        <?php main_navegation($lugar,$id_usuario_sesion,$tipo_sesion,$usuario_sesion,$conexion);?>
                    </div>
                    
                </div>
                
                <!-- Main Content Wrapper -->
                <div id="da-content-wrap" class="clearfix">
                
                	<!-- Content Area -->
                	<div id="da-content-area">
                    
                    	<div class="grid_4">
                           <?php
                            //NECESITO SABER SI DEBO CREAR UN NUEVO USUARIO
							if(isset($_GET['crear'])) {
						
								agregar_nuevos_montos($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
								
							} else {
								//VEMOS SI NOS PASAN UN ID DE USUARIO
								if(isset($_GET['idhome'])) {
						
									if(isset($_GET['darbaja'])) {
										
										desactivar_compania($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
										
									}elseif(isset($_GET['daralta'])){ 
									
									    activar_compania($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
								    
									}elseif(isset($_GET['editar'])) {
										//SI NO ME PASAN 'CPASS' NI 'ELIMINAR', MUESTRO EL FORM PARA EDITAR USUARIO
										editar_datos_admin($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
										
									} 
								} else {
									//SI NO ME PASAN UN ID DE USUARIO, MUESTRO LA LISTA DE FORMULARIOS EXISTENTES
									mostrar_lista_datos_admin($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
								}
							}
							
						   ?>
                        </div>
                                                  
                    </div>
                    
                </div>
                
            </div>
            
        </div>
        
        <!-- Footer -->
        <div id="da-footer">
        	<?php footer();?>
        </div>
        
    </div>

<?php
}

//FUNCION QUE PERMITE LISTAR LOS FORMULARIOS
function mostrar_lista_datos_admin($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
?>
<script type="text/javascript" src="plugins/ambience/jquery.ambiance.js"></script>
<script type="text/javascript">
  <?php 
    $op = $_GET["op"];
    $msg = $_GET["msg"];
	$var = $_GET["var"];
	$producto = $_GET["producto"];
	if($op==1){$valor='success';}elseif($op==2){$valor='error';}
  ?>
  $(document).ready(function() {
	//PLUGIN AMBIENCE
    <?php if($msg!=''){ ?>
		 $.ambiance({message: "<?=base64_decode($msg);?>", 
				title: "Notificacion",
				type: "<?=$valor;?>",
				timeout: 5
				});
		 //location.load("sgc.php?l=usuarios&idhome=1");
		 //$(location).attr('href', 'sgc.php?l=crearusuario&idhome=1');		
		 setTimeout( "$(location).attr('href', 'index.php?l=vg_datos&var=<?=$var;?>&producto=<?=$producto;?>');",5000 );
	<?php }?>
	 
  });
</script>

<?php
//SACAMOS LAS ENTIDADES FINANCIERAS EXISTENTES Y POSTERIOR ESTEN ACTIVADAS
if($tipo_sesion=='ROOT'){
	  $selectEf="select 
					ef.id_ef, ef.nombre, ef.logo, ef.activado
				from
					s_entidad_financiera as ef
				where
					ef.activado = 1
						and exists( select 
							sh.id_ef
						from
							s_sgc_home as sh
						where
							sh.id_ef = ef.id_ef and sh.producto='".base64_decode($_GET['producto'])."');";
}else{
	 $selectEf="select 
					  ef.id_ef, ef.nombre, ef.logo, ef.activado
				  from
					  s_entidad_financiera as ef
				  where
					  ef.activado = 1 
						and exists( select 
							sh.id_ef
						from
							s_sgc_home as sh
						where
							sh.id_ef = ef.id_ef and sh.producto='".base64_decode($_GET['producto'])."')
						  and ef.id_ef = '".$id_ef_sesion."';";
}
  if($resef=$conexion->query($selectEf,MYSQLI_STORE_RESULT)){
	  $num_regi_ef = $resef->num_rows;
	  if($num_regi_ef>0){
			  /*echo'<div class="da-panel collapsible">
					  <div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
						  <ul class="action_user">
							  <li style="margin-right:6px;">
								 <a href="?l=des_datos&var='.$_GET['var'].'&crear=v" class="da-tooltip-s various" title="Añadir nuevo registro">
								 <img src="images/add_new.png" width="32" height="32"></a>
							  </li>
						  </ul>
					  </div>
				   </div>';*/
			   while($regief = $resef->fetch_array(MYSQLI_ASSOC)){		
					  $selectFor="select
									 id_home,
									 producto,
									 edad_max,
									 edad_min,
									 id_ef,
									 data 	   	    
								  from
									s_sgc_home
								  where
									producto='".base64_decode($_GET['producto'])."' and id_ef='".$regief['id_ef']."';";
					  if($res = $conexion->query($selectFor,MYSQLI_STORE_RESULT)){		  
					        
							  echo'
							  <div class="da-panel collapsible">
								  <div class="da-panel-header">
									  <span class="da-panel-title" style="font-size:11.5px;">
										  <img src="images/icons/black/16/list.png" alt="" />
										  <b>'.$regief['nombre'].'</b> - <span lang="es">Administrar parametros Vida en Grupo</span>
									  </span>
								  </div>
								  <div class="da-panel-content">
									  <table class="da-table">
										  <thead>
											  <tr style="font-size:11.5px;">
												  <th style="text-align:center;"><b><span lang="es">Edad Mínima</span></b></th>
												  <th style="text-align:center;"><b><span lang="es">Edad Máxima</span></b></th>
												  <th style="text-align:center;"><b><span lang="es">Tasa %</span></b></th>
												  <th style="text-align:center;"><b><span lang="es">Monto mínimo</span></b></th>
												  <th style="text-align:center;"><b><span lang="es">Monto máximo</span></b></th>
												  <th style="text-align:center;"><b><span lang="es">Modalidad</span></b></th>';
											 echo'<th></th>
											  </tr>
										  </thead>
										  <tbody>';
											$num = $res->num_rows;
											if($num>0){
												  while($regi = $res->fetch_array(MYSQLI_ASSOC)){
													  $jsondata = $regi['data'];
													  $datavg = json_decode($jsondata, true);
													  echo'<tr style="font-size:11.5px;">
															  <td style="text-align:center;">'.$regi['edad_min'].'</td>
															  <td style="text-align:center;">'.$regi['edad_max'].'</td>
															  <td style="text-align:center;">'.$datavg['tasa'].'</td>
															  <td style="text-align:center;">'.$datavg['amount_min'].'</td>
															  <td style="text-align:center;">'.$datavg['amount_max'].'</td>';
															  if($datavg['modality'][1]['active']===true)
															     echo'<td style="text-align:center;">'.$datavg['modality'][1]['name'].'</td>';
															  elseif($datavg['modality'][2]['active']===true)
															     echo'<td style="text-align:center;">'.$datavg['modality'][2]['name'].'</td>';
															  else
															     echo'<td></td>';	 	 
														 echo'<td>
																 <ul class="action_user">
																	<li style="margin-right:5px;"><a href="?l=vg_datos&idhome='.base64_encode($regi['id_home']).'&id_ef='.base64_encode($regief['id_ef']).'&editar=v&var='.$_GET['var'].'&producto='.$_GET['producto'].'" class="edit da-tooltip-s" title="<span lang=\'es\'>Editar</span>"></a></li>';																	 
															echo'</ul>	
															  </td>
														  </tr>';
												  }
												  $res->free();			
											}else{
											   echo'<tr><td colspan="8">
														<div class="da-message info">
															 No existe registros alguno, ingrese nuevos registros
														</div>
													</td></tr>';
											}
									 echo'</tbody>
									  </table>
								  </div>
							  </div>';
					  }else{
						  echo"<div style='font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;'>
				Error en la consulta: "."\n ".$conexion->errno . ": " .$conexion->error
							 ."</div>";	
					  }
			   }
			   $resef->free();
	  }else{
		  echo'<div class="da-message warning">
					 <span lang="es">No existe ningun registro, probablemente se debe a</span>:
					 <ul>
						<li lang="es">La Entidad Financiera no tiene asignado el producto Desgravamen</li>
						<li lang="es">La Entidad Financiera no esta activado</li>
						<li lang="es">La Entidad Financiera no esta creada</li>
					  </ul>
				</div>'; 
	  }
  }else{
	  echo"<div style='font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;'>
		  Error en la consulta: "."\n ".$conexion->errno . ": " .$conexion->error
		   ."</div>";
  }
}

//FUNCION QUE PERMITE VISUALIZAR EL FORMULARIO NUEVO USUARIO
function agregar_nuevos_montos($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
	
	//VEO SI SE HA HECHO CLICK EN EL BOTON GUARDAR
	if(isset($_POST['accionGuardar'])) {
				
			//SEGURIDAD
			$caducidadcotiz = mysql_real_escape_string($_POST['txtLimitCotiza']);
			$montocotizusd = mysql_real_escape_string($_POST['txtMaxCotiUsd']);
			$montocotizbs = mysql_real_escape_string($_POST['txtMaxCotiBs']);
			$montoemiusd = mysql_real_escape_string($_POST['txtMaxEmiUsd']);
			$montoemibs = mysql_real_escape_string($_POST['txtMaxEmiBs']);
			$edadmax = mysql_real_escape_string($_POST['txtEdadMax']);
			$edadmin = mysql_real_escape_string($_POST['txtEdadMin']);
			$idefin = mysql_real_escape_string($_POST['idefin']);
			$numtitulares = mysql_real_escape_string($_POST['txtNumTitulares']);
			$implante = mysql_real_escape_string($_POST['implante']);
			
			//GENERA ID CODIFICADO UNICO
			$id_new_home = generar_id_codificado('@S#1$2013');					
			//METEMOS LOS DATOS A LA BASE DE DATOS
			$insert ="INSERT INTO s_sgc_home(id_home, id_ef, producto, limite_cotizacion, max_cotizacion_usd, max_cotizacion_bs, max_emision_usd, max_emision_bs, edad_max, edad_min, max_detalle, implante, id_usuario) "
				    ."VALUES('".$id_new_home."', '".$_POST['idefin']."', 'DE', ".$caducidadcotiz.", ".$montocotizusd.", ".$montocotizbs.", ".$montoemiusd.", ".$montoemibs.", ".$edadmax.", ".$edadmin.", ".$numtitulares.", ".$implante.", '".$id_usuario_sesion."')";
			$rs = $conexion->query($insert, MYSQLI_STORE_RESULT);
			
			//VERIFICAMOS SI HUBO ERROR EN EL INGRESO DEL REGISTRO
			if(mysql_errno($conexion)==0){
								
				$mensaje="Se registro correctamente los datos del formulario";
			    header('Location: index.php?l=des_datos&var='.$_GET['var'].'&op=1&msg='.base64_encode($mensaje));
			    exit;
			} else {
				$mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".$conexion->errno. ": " . $conexion->error;
			    header('Location: index.php?l=des_datos&var='.$_GET['var'].'&op=2&msg='.base64_encode($mensaje));
				exit;
			}
		
	} else {
		//MUESTRO EL FORM PARA CREAR UNA CATEGORIA
		mostrar_crear_montos($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
	}
}

//VISUALIZAMOS EL FORMULARIO CREA USUARIO
function mostrar_crear_montos($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
  ?>
<script type="text/javascript">
  $(function(){
	  $('#frmDatosCrea').submit(function(e){
		  //alert('Guardar');
		  var caducidad_cotiza = $('#txtLimitCotiza').prop('value');
		  var monto_cotiza_usd = $("#txtMaxCotiUsd").prop('value');
		  var monto_emite_usd = $("#txtMaxEmiUsd").prop('value');
		  var selectEdadMax = $("#txtEdadMax option:selected").prop('value'); 
		  var selectEdadMin = $("#txtEdadMin option:selected").prop('value'); 
		  var idefin = $('#idefin option:selected').prop('value');
		  var numtitular = $('#txtNumTitulares').prop('value');
		  var sum=0;
		  var edad=0;
		  $(this).find('.required').each(function(){
			  if(idefin!=''){
				  $('#errorentidad').hide('slow');
			  }else{
				  sum++;
				  $('#errorentidad').show('slow');
				  $('#errorentidad').html('seleccione entidad financiera');
			  }
			  
			  if(caducidad_cotiza!=''){
				  if(caducidad_cotiza.match(/^[0-9]+$/)){
					 $('#errorcaducidad').hide('slow');
				  }else{
					 sum++;
					  $('#errorcaducidad').show('slow'); 
					 $('#errorcaducidad').html('ingrese solo numeros'); 
				  }
			  }else{
				 sum++;  
				 $('#errorcaducidad').html('ingrese caducidad cotizacion'); 
			  }
			  if(monto_cotiza_usd!=''){
				  if(monto_cotiza_usd.match(/^[0-9]+$/)){
					 $('#errorarmaxcotiusd').hide('slow');
				  }else{
					 sum++;
					 $('#errorarmaxcotiusd').show('slow');
					 $('#errorarmaxcotiusd').html('ingrese solo numeros'); 
				  }
			  }else{
				 sum++;  
				 $('#errorarmaxcotiusd').show('slow');
				 $('#errorarmaxcotiusd').html('ingrese monto maximo usd');   
			  }
			  if(monto_emite_usd!=''){
				  if(monto_emite_usd.match(/^[0-9]+$/)){
					  $('#errorarmaxemiusd').hide('slow'); 
				  }else{
					 sum++;
				     $('#errorarmaxemiusd').show('slow'); 
					 $('#errorarmaxemiusd').html('ingrese solo numeros'); 
				  } 
			  }else{
				 sum++;
				 $('#errorarmaxemiusd').show('slow'); 
				 $('#errorarmaxemiusd').html('ingrese monto maximo usd'); 
			  }
			  if(numtitular!=''){
				  if(numtitular.match(/^[0-9]+$/)){
					 $('#errornumtitular').hide('slow');
				  }else{
					 sum++;
					 $('#errornumtitular').show('slow');
					 $('#errornumtitular').html('ingrese solo numeros');  
				  }
			  }else{
				  sum++
				  $('#errornumtitular').show('slow'); 
				  $('#errornumtitular').html('ingrese numero de titulares');
			  }
			  //SELECCIONAR IMPLANTE
			  if( $("#frmDatosCrea input[name='implante']:radio").is(':checked')) {
				  $('#errorimplante').hide('slow');
			  }else{
				  sum++;
				  $('#errorimplante').show('slow');
				  $('#errorimplante').html('seleccione implante'); 
			  }
			  
			  if(selectEdadMin!=''){
				 $('#errorminedad').hide('slow'); 
			  }else{
				 sum++;
				 edad++;
				 $('#errorminedad').show('slow');
				 $('#errorminedad').html('seleccione edad minima');
			  }
			  if(selectEdadMax!=''){
				 $('#errormaxedad').hide('slow');
			  }else{
				 sum++;
				 edad++;
				 $('#errormaxedad').show('slow');
				 $('#errormaxedad').html('seleccione edad maxima');   
			  }
			  if(edad==0){
				  if(selectEdadMin<selectEdadMax){
					  $('#errorminedad').hide('slow');
				  }else{
					  sum++;
					  $('#errorminedad').show('slow').stop(true,false);
					  $('#errorminedad').html('edad minima debe ser menor a la edad maxima');
				  }
			  }
			  
		  });
		  if(sum==0){
			  //$("#btnUsuario").removeAttr("disabled");
		  }else{
			 e.preventDefault(); 
			 //$('#btnUsuario').attr('disabled', true);
			 
		  }
		  
	  });
	  
	  //CAMBIO DOLAR A BOLIVIANOS COTIZACION
	  $('#txtMaxCotiUsd').blur(function(e){
		  var val_cotiza_dolar = $(this).val();
		  //VERIFICAMOS SI LA CASILLA ESTA VACIA
		   e.preventDefault();
		  if (val_cotiza_dolar == "") {
		     $('#errorarmaxcotiusd').show('slow');
			 $('#errorarmaxcotiusd').html('ingrese monto maximo usd');
			 $('#errorarmaxcotiusd').focus();
		  }else if(val_cotiza_dolar.match(/^[0-9]+$/)){//VERIFICAMOS SI EL VALOR ES NUMERO ENTERO
			  $('#errorarmaxcotiusd').hide('slow');
			  var equiv = parseInt(val_cotiza_dolar)*7;
			  $('#txtMaxCotiBs').prop('value',equiv).hide().show("slow");
		  }else{
			 $('#errorarmaxcotiusd').show('slow');
			 $('#errorarmaxcotiusd').html("ingrese solo numeros enteros").fadeIn("slow");
			 $('#errorarmaxcotiusd').focus();
		  }
      });
	  
	  //CAMBIO DOLARES A BOLIVIANOS EMISION
	  $('#txtMaxEmiUsd').blur(function(e){
		  var val_emite_dolar = $(this).val();
		  //VERIFICAMOS SI LA CASILLA ESTA VACIA
		  e.preventDefault();
		  if (val_emite_dolar == "") {
		     $('#errorarmaxemiusd').show('slow');
			 $('#errorarmaxemiusd').html('ingrese monto maximo usd');
			 $('#errorarmaxemiusd').focus();
		  }else if(val_emite_dolar.match(/^[0-9]+$/)){//VERIFICAMOS SI EL VALOR ES NUMERO ENTERO
			  $('#errorarmaxemiusd').hide('slow');
			  var equiv = parseInt(val_emite_dolar)*7;
			  $("#txtMaxEmiBs").prop('value',equiv).hide().show("slow");
			  
		  }else{
			 $('#errorarmaxemiusd').show('slow');
			 $('#errorarmaxemiusd').html('ingrese solo numeros');
			 $('#errorarmaxemiusd').focus(); 
		  }
     });
	 
	  $('#btnCancelar').click(function(e){
		  var variable=$('#var').prop('value');
		  $(location).attr('href', 'index.php?l=des_datos&var='+variable); 
	  });  
  });
</script>
<?php    
		//SACAMOS LAS ENTIDADES EXISTENTES
		if($tipo_sesion=='ROOT'){
				$select1="select 
								ef.id_ef, ef.nombre, ef.logo, ef.activado
							from
								s_entidad_financiera as ef
							where
								ef.activado = 1
									and not exists( select 
										sh.id_ef
									from
										s_sgc_home as sh
									where
										sh.id_ef = ef.id_ef and sh.producto='DE');";
		}else{
			 $select1="select 
								ef.id_ef, ef.nombre, ef.logo, ef.activado
							from
								s_entidad_financiera as ef
							where
								ef.activado = 1
									and not exists( select 
										sh.id_ef
									from
										s_sgc_home as sh
									where
										sh.id_ef = ef.id_ef and sh.producto='DE')
									and ef.id_ef='".$id_ef_sesion."';";
			
		 }
		 $res1 = $conexion->query($select1,MYSQLI_STORE_RESULT);
		 $num_reg = $res1->num_rows;	 							
		  echo'<div class="da-panel" style="width:600px;">
				<div class="da-panel-header">
					<span class="da-panel-title">
						<img src="images/icons/black/16/pencil.png" alt="" />
						Crear montos desgravamen
					</span>
				</div>
				<div class="da-panel-content">
					<form class="da-form" name="frmDatosCrea" action="" method="post" id="frmDatosCrea">
						<div class="da-form-row">
						   <label style="width:190px; text-align:right; margin-right:10px;"><b>Entidad Financiera</b></label>
						   <div class="da-form-item large">
							   <select id="idefin" name="idefin" class="required" style="width:160px;">';
								  echo'<option value="">seleccione...</option>';
								  while($regi1 = $res1->fetch_array(MYSQLI_ASSOC)){
									  if($idefin==$regi1['id_ef']){ 
									   echo'<option value="'.$regi1['id_ef'].'" selected>'.$regi1['nombre'].'</option>';  
									  }else{
										  echo'<option value="'.$regi1['id_ef'].'">'.$regi1['nombre'].'</option>';  
									  }
								  }
								  $res1->free();
						  echo'</select>
							   <span class="errorMessage" id="errorentidad"></span>
						   </div>	 
					  </div>			
						<div class="da-form-row">
							<label style="width:190px; text-align:right; margin-right:10px;"><b>Caducidad cotizacion (días)</b></label>
							<div class="da-form-item large">
								<input class="textbox required" type="text" name="txtLimitCotiza" id="txtLimitCotiza" style="width: 200px;" value="" autocomoplete="off"/>
								<span class="errorMessage" id="errorcaducidad"></span>
							</div>
						</div>
						<div class="da-form-row">
							<label style="width:190px; text-align:right; margin-right:10px;"><b>Monto Maximo Cotizacion (USD)</b></label>
							<div class="da-form-item large">
								<input class="textbox required" type="text" name="txtMaxCotiUsd" id="txtMaxCotiUsd" style="width: 200px;" value="" autocomoplete="off"/>
								<span class="errorMessage" id="errorarmaxcotiusd"></span>
							</div>
						</div>
						<div class="da-form-row">
							<label style="width:190px; text-align:right; margin-right:10px;"><b>Monto Maximo Cotizacion (Bs)</b></label>
							<div class="da-form-item large">
								<input class="textbox required" type="text" name="txtMaxCotiBs" id="txtMaxCotiBs" style="width: 200px;" value="" readonly="readonly"/>
								<span class="errorMessage" id="errorarmaxcotibs"></span>
							</div>
						</div>
						<div class="da-form-row">
							<label style="width:190px; text-align:right; margin-right:10px;"><b>Monto Maximo Emision (USD)</b></label>
							<div class="da-form-item large">
								<input class="textbox required" type="text" name="txtMaxEmiUsd" id="txtMaxEmiUsd" style="width: 200px;" value=""/>
								<span class="errorMessage" id="errorarmaxemiusd"></span>
							</div>
						</div>
						<div class="da-form-row">
							<label style="width:190px; text-align:right; margin-right:10px;"><b>Monto Maximo Emision (Bs)</b></label>
							<div class="da-form-item large">
								<input class="textbox required" type="text" name="txtMaxEmiBs" id="txtMaxEmiBs" style="width: 200px;" value="" readonly="readonly"/>
								<span class="errorMessage" id="max_emision_bs"></span>
							</div>
						</div>
						<div class="da-form-row">
							<label style="width:190px; text-align:right; margin-right:10px;"><b>Numero de Titulares</b></label>
							<div class="da-form-item large">
								<input class="textbox required" type="text" name="txtNumTitulares" id="txtNumTitulares" style="width: 200px;" value=""/>
								<span class="errorMessage" id="errornumtitular"></span>
							</div>
						</div>
						<div class="da-form-row">
							<label style="width:190px; text-align:right; margin-right:10px;"><b>Implante</b></label>
							<div class="da-form-item">
								<ul class="da-form-list inline">
									<li><input type="radio" name="implante" id="rd-1" value="1" class="required"/> <label>Si</label></li>
									<li><input type="radio" name="implante" id="rd-2" value="0" class="required"/> <label>No</label></li>
								</ul>
								<span class="errorMessage" id="errorimplante"></span>
							</div>
						</div>
						<div class="da-form-row">
							<label style="width:190px; text-align:right; margin-right:10px;"><b>Edad Minima</b></label>
							<div class="da-form-item large">';
								$j=18;
							  echo'<select id="txtEdadMin" name="txtEdadMin" style="width:120px;" class="required">';
									  echo'<option value="">Seleccione...</option>';
									  while($j<=85){
										 echo'<option value="'.$j.'">'.$j.'</option>';  
										 $j++;   
									  }
							  echo'</select>
							      <span class="errorMessage" id="errorminedad"></span>'; 	
					   echo'</div>
						</div>		
						<div class="da-form-row">
							<label style="width:190px; text-align:right; margin-right:10px;"><b>Edad Maxima</b></label>
							<div class="da-form-item large">';
								 $i=18;
							  echo'<select id="txtEdadMax" name="txtEdadMax" style="width:120px;" class="required">';
									  echo'<option value="">Seleccione...</option>';
									  while($i<=85){
										 echo'<option value="'.$i.'">'.$i.'</option>'; 
										 $i++;   
									  }
							  echo'</select>
							      <span class="errorMessage" id="errormaxedad"></span>';
								 	
					   echo'</div>
						</div>												
						<div class="da-button-row">
							<input type="button" value="Cancelar" class="da-button gray left" name="btnCancelar" id="btnCancelar"/>';
							if($num_reg>0){
							   echo'<input type="submit" value="Guardar" class="da-button green" name="btnUsuario" id="btnUsuario"/>';
							}else{
					           echo'<input type="submit" value="Guardar" class="da-button green" name="btnUsuario" id="btnUsuario" disabled/>';			
							}
							echo'<input type="hidden" name="accionGuardar" value="guardar"/>
							<input type="hidden" name="var" id="var" value="'.$_GET['var'].'"/>
						</div>
					</form>
				</div>
			</div>';
	
}

//FUNCION PARA EDITAR UN USUARIO
function editar_datos_admin($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion) {
    $idhome = base64_decode($_GET['idhome']);
	$id_ef = base64_decode($_GET['id_ef']);
	//$idusuario = strtolower($idusuario);

	//VEO SI SE HA HECHO CLICK EN EL BOTON GUARDAR
	if(isset($_POST['btnUpdate'])) {
       
        
            //SEGURIDAD
			$edadmin = $conexion->real_escape_string($_POST['txtEdadMin']);
			$edadmax = $conexion->real_escape_string($_POST['txtEdadMax']);
			$tasa = $conexion->real_escape_string($_POST['txtTasa']);
			$max_bs = $conexion->real_escape_string($_POST['txtMaxBs']);
			$min_bs = $conexion->real_escape_string($_POST['txtMinBs']);
			$modalidad = $conexion->real_escape_string($_POST['modalidad']);
			$monto_va = $conexion->real_escape_string($_POST['txtMontoVA']);
			$monto_aux = $conexion->real_escape_string($_POST['txtMontoAux']);
			if($modalidad==1){
			  $active_one=true;
			  $active_two=false;
			  $monto_md1 = $monto_va;
			  $monto_md2 = $monto_aux; 
			}elseif($modalidad==2){	
			  $active_one=false;
			  $active_two=true;
			  $monto_md2 = $monto_va;
			  $monto_md1 = $monto_aux;
			}
			$data = array('tasa'=>(float)$tasa,
						  'modality'=>array(
								'1'=>array( 
									'slug'=>'VS',
									'name'=>'Modalidad Valor Estatico',
									'active'=>$active_one,
									'amount'=>(int)$monto_md1
									),
								"2"=>array(
									'slug'=>'VA',
									'name'=>'Modalidad Valor Asegurado',
									'active'=>$active_two,
									'amount'=>(int)$monto_md2
									)
						   ),
						  "amount_min"=>(int)$min_bs,
						  "amount_max"=>(int)$max_bs
						  );	
			
            //CARGAMOS LOS DATOS A LA BASE DE DATOS
            $update = "UPDATE s_sgc_home SET edad_max=".$edadmax.", edad_min=".$edadmin.", data='".$conexion->real_escape_string(json_encode($data))."' WHERE id_home='".$idhome."' and id_ef='".$id_ef."' LIMIT 1;";
            //echo $update;
            
            
            if($conexion->query($update)===TRUE){
                $mensaje="Se actualizo correctamente los datos del formulario";
			    header('Location: index.php?l=vg_datos&var='.$_GET['var'].'&producto='.$_GET['producto'].'&op=1&msg='.base64_encode($mensaje));
			    exit;
            } else{
                $mensaje="Hubo un error al ingresar los datos, consulte con su administrador ".$conexion->errno. ": ".$conexion->error;
				header('Location: index.php?l=vg_datos&var='.$_GET['var'].'&producto='.$_GET['producto'].'&op=2&msg='.base64_encode($mensaje));
				exit;
            }
			
	}else {
	  //MUESTRO FORM PARA EDITAR UNA CATEGORIA
	  mostrar_editar_datos_admin($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
	}
	
}

//VISUALIZAMOS EL FORMULARIO PARA EDITAR EL FORMULARIO
function mostrar_editar_datos_admin($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
?>
<script type="text/javascript">
  $(function(){
	  $('#frmDatosAdmin').submit(function(e){
		  //alert('Guardar');
		  var tasa = $('#txtTasa').prop('value');
		  var monto_max = $("#txtMaxBs").prop('value');
		  var monto_min = $("#txtMinBs").prop('value');
		  var selectEdadMax = $("#txtEdadMax option:selected").prop('value'); 
		  var selectEdadMin = $("#txtEdadMin option:selected").prop('value');
		  var modalidad = $("input[name='modalidad']:checked").prop('value');
		  var monto_va = $('#txtMontoVA').prop('value');
		  
		  var sum=0;
		  var edad=0;
		  $(this).find('.required').each(function(){
			  if(tasa!=''){
				  if(tasa.match(/^[0-9\.]+$/)){
					 $('#error_tasa').hide('slow');
				  }else{
					 sum++;
					 $('#error_tasa').show('slow'); 
					 $('#error_tasa').html('ingrese solo numeros'); 
				  }
			  }else{
				 sum++; 
				 $('#error_tasa').slideDown('slow'); 
				 $('#error_tasa').html('campo requerido'); 
			  }
			  if(monto_max!=''){
				  if(monto_max.match(/^[0-9]+$/)){
					 $('#error_max').hide('slow');
				  }else{
					 sum++;
					 $('#error_max').show('slow');
					 $('#error_max').html('ingrese solo numeros'); 
				  }
			  }else{
				 sum++;  
				 $('#error_max').show('slow');
				 $('#error_max').html('campo requerido');   
			  }
			  if(monto_min!=''){
				  if(monto_min.match(/^[0-9]+$/)){
					  $('#error_min').hide('slow'); 
				  }else{
					 sum++;
				     $('#error_min').show('slow'); 
					 $('#error_min').html('ingrese solo numeros'); 
				  } 
			  }else{
				 sum++;
				 $('#error_min').show('slow'); 
				 $('#error_min').html('campo requerido'); 
			  }
			  if((modalidad==1) || (modalidad==2)){
				  if(monto_va!=''){
					  if(monto_va.match(/^[0-9]+$/)){
						 $('#error_monto').slideUp('slow'); 
					  }else{
						 sum++;
						 $('#error_monto').slideDown('slow');
					     $('#error_monto').html('ingrese numeros enteros');
					  }
				  }else{
					  sum++;
					  $('#error_monto').slideDown('slow');
					  $('#error_monto').html('campo requerido');
				  }
			  }
			  if($("#frmDatosAdmin input[name='modalidad']:radio").is(':checked')) {
				  $('#error_modalidad').hide('slow');
			  }else{
				  sum++;
				  $('#error_modalidad').show('slow');
				  $('#error_modalidad').html('seleccione modalidad'); 
			  }
			  if(selectEdadMin!=''){
				 $('#errorminedad').hide('slow'); 
			  }else{
				 sum++;
				 edad++;
				 $('#errorminedad').show('slow');
				 $('#errorminedad').html('seleccione edad minima');
			  }
			  if(selectEdadMax!=''){
				 $('#errormaxedad').hide('slow');
			  }else{
				 sum++;
				 edad++;
				 $('#errormaxedad').show('slow');
				 $('#errormaxedad').html('seleccione edad maxima');   
			  }
			  if(edad==0){
				  if(selectEdadMin<selectEdadMax){
					  $('#errorminedad').hide('slow');
				  }else{
					  sum++;
					  $('#errorminedad').show('slow').stop(true,false);
					  $('#errorminedad').html('edad minima debe ser menor a la edad maxima');
				  }
			  }
			  
		  });
		  if(sum==0){
			  //$("#frmDatosAdmin :submit").attr("disabled", true);
			  //e.preventDefault();
			  //var FormCadena = $(this).serialize();
			  //alert(FormCadena);
			  //$("#btnUsuario").removeAttr("disabled");
		  }else{
			 e.preventDefault(); 
			 //$('#btnUsuario').attr('disabled', true);
			 
		  }
		  
	  });
	  
	  //MODALIDAD
	  $('.rdve').click(function(){
		   var op_mod = $(this).attr('value');
		   var idhome = $('#idhome').prop('value');
		   var producto = $('#producto').prop('value');
		   var id_ef = $('#idenfin').prop('value');
		   $.post("accion_registro.php",
				  {opmod:op_mod,opcion:"quest_amount",id_ef:id_ef,producto:producto},
				  function(data, textStatus, jqXHR)
				  {     //alert(data)
						var vec = data.split('|');
						$('#txtMontoVA').prop('value',vec[0]);
						$('#txtMontoAux').prop('value',vec[1]);  
				  }); 
	  });
	  
	  //CAMBIO BOLIVIANOS A DOLARES COTIZACION
	  $('#txtMaxCotiBs').blur(function(e){
		  var val_cotiza_bs = $(this).val();
		  var tipo_cambio = $('#tipo_cambio').prop('value');
		  //VERIFICAMOS SI LA CASILLA ESTA VACIA
		   e.preventDefault();
		  if (val_cotiza_bs == "") {
		     $('#errorarmaxcotibs').show('slow');
			 $('#errorarmaxcotibs').html('ingrese monto maximo bs');
			 $('#errorarmaxcotibs').focus();
		  }else if(val_cotiza_bs.match(/^[0-9]+$/)){//VERIFICAMOS SI EL VALOR ES NUMERO ENTERO
			  $('#errorarmaxcotibs').hide('slow');
			  var equiv = parseInt(val_cotiza_bs/tipo_cambio);
			  $('#txtMaxCotiUsd').prop('value',equiv).hide().show("slow");
		  }else{
			 $('#errorarmaxcotibs').show('slow');
			 $('#errorarmaxcotibs').html("ingrese solo numeros enteros").fadeIn("slow");
			 $('#errorarmaxcotibs').focus();
		  }
      });
	  //-----------------------------------------
	  //CAMBIO DOLAR A BOLIVIANOS COTIZACION
	  $('#txtMaxCotiUsd').blur(function(e){
		  var val_cotiza_dolar = $(this).val();
		  var tipo_cambio = $('#tipo_cambio').prop('value');
		  //VERIFICAMOS SI LA CASILLA ESTA VACIA
		   e.preventDefault();
		  if (val_cotiza_dolar == "") {
		     $('#errorarmaxcotiusd').show('slow');
			 $('#errorarmaxcotiusd').html('ingrese monto maximo usd');
			 $('#errorarmaxcotiusd').focus();
		  }else if(val_cotiza_dolar.match(/^[0-9]+$/)){//VERIFICAMOS SI EL VALOR ES NUMERO ENTERO
			  $('#errorarmaxcotiusd').hide('slow');
			  var equiv = parseInt(val_cotiza_dolar*tipo_cambio);
			  $('#txtMaxCotiBs').prop('value',equiv).hide().show("slow");
		  }else{
			 $('#errorarmaxcotiusd').show('slow');
			 $('#errorarmaxcotiusd').html("ingrese solo numeros enteros").fadeIn("slow");
			 $('#errorarmaxcotiusd').focus();
		  }
      });
	  
	  
	  //CAMBIO BOLIVIANOS A DOLARES EMISION	  
	  $('#txtMaxEmiBs').blur(function(e){
		  var val_emite_bs = $(this).val();
		  var tipo_cambio = $('#tipo_cambio').prop('value');
		  //VERIFICAMOS SI LA CASILLA ESTA VACIA
		  e.preventDefault();
		  if (val_emite_bs == "") {
		     $('#errormaxemibs').show('slow');
			 $('#errormaxemibs').html('ingrese monto maximo bs');
			 $('#errormaxemibs').focus();
		  }else if(val_emite_bs.match(/^[0-9]+$/)){//VERIFICAMOS SI EL VALOR ES NUMERO ENTERO
			  $('#errorarmaxemiusd').hide('slow');
			  var equiv = parseInt(val_emite_bs/tipo_cambio);
			  $("#txtMaxEmiUsd").prop('value',equiv).hide().show("slow");
			  
		  }else{
			 $('#errormaxemibs').show('slow');
			 $('#errormaxemibs').html('ingrese solo numeros');
			 $('#errormaxemibs').focus(); 
		  }
      });
	 //--------------------------------------
	 //CAMBIO DOLARES A BOLIVIANOS EMISION
	  $('#txtMaxEmiUsd').blur(function(e){
		  var val_emite_dolar = $(this).val();
		  var tipo_cambio = $('#tipo_cambio').prop('value');
		  //VERIFICAMOS SI LA CASILLA ESTA VACIA
		  e.preventDefault();
		  if (val_emite_dolar == "") {
		     $('#errorarmaxemiusd').show('slow');
			 $('#errorarmaxemiusd').html('ingrese monto maximo usd');
			 $('#errorarmaxemiusd').focus();
		  }else if(val_emite_dolar.match(/^[0-9]+$/)){//VERIFICAMOS SI EL VALOR ES NUMERO ENTERO
			  $('#errorarmaxemiusd').hide('slow');
			  var equiv = parseInt(val_emite_dolar*tipo_cambio);
			  $("#txtMaxEmiBs").prop('value',equiv).hide().show("slow");
			  
		  }else{
			 $('#errorarmaxemiusd').show('slow');
			 $('#errorarmaxemiusd').html('ingrese solo numeros');
			 $('#errorarmaxemiusd').focus(); 
		  }
     });
	 
	  $('#btnCancelar').click(function(e){
		  var variable=$('#var').prop('value');
		  var producto=$('#producto').prop('value');
		 
		  $(location).attr('href', 'index.php?l=vg_datos&var='+variable+'&producto='+producto); 
	  });  
  });
</script>
<?php    
	$idhome = base64_decode($_GET['idhome']);
    $id_ef = base64_decode($_GET['id_ef']);
	//SACAMOS LOS DATOS DE LA BASE DE DATOS
	$select = "select 
					sh.id_home,
					sh.producto,
					sh.edad_max,
					sh.edad_min,
					sh.data,
					ef.nombre,
					sh.id_ef,
					stc.valor_boliviano
				from
					s_sgc_home as sh
					inner join s_entidad_financiera as ef on (ef.id_ef=sh.id_ef)
					inner join s_tipo_cambio as stc on (stc.id_ef=ef.id_ef) 
				where
					sh.producto = '".base64_decode($_GET['producto'])."' and sh.id_home = '".$idhome."'
						and sh.id_ef = '".$id_ef."' 
						and stc.activado = true;";
	
	if($rs = $conexion->query($select, MYSQLI_STORE_RESULT)){
			$num = $rs->num_rows;
			
			//SI EXISTE EL USUARIO DADO EN LA BASE DE DATOS, LO EDITAMOS
			if($num>0) {
				$fila = $rs->fetch_array(MYSQLI_ASSOC);	
				$jsondata = $fila['data'];
				$datavg = json_decode($jsondata, true);					
				  echo'<div class="da-panel" style="width:600px;">
						<div class="da-panel-header">
							<span class="da-panel-title">
								<img src="images/icons/black/16/pencil.png" alt="" />
								<span lang="es">Editar Datos Vida en Grupo</span>
							</span>
						</div>
						<div class="da-panel-content">
							<form class="da-form" name="frmDatosAdmin" action="" method="post" id="frmDatosAdmin">
								<div class="da-form-row">
									 <label style="width:190px; text-align:right; margin-right:10px;"><b><span lang="es">Entidad Financiera</span></b></label>
									 <div class="da-form-item large">
										 '.$fila['nombre'].'
										 <input type="hidden" name="idenfin" id="idenfin" value="'.$fila['id_ef'].'"/>								 
									 </div>	 
								</div>
								<div class="da-form-row">
									<label style="width:190px; text-align:right; margin-right:10px;"><b><span lang="es">Edad Mínima</span></b></label>
									<div class="da-form-item large" style="margin-left:200px;">';
										$j=15;
									  echo'<select id="txtEdadMin" name="txtEdadMin" style="width:120px;" class="required">';
											  echo'<option value="">seleccione</option>';
											  while($j<=85){
												 if($fila['edad_min']==$j){  
													echo'<option value="'.$j.'" selected>'.$j.'</option>'; 
												 }else{
													echo'<option value="'.$j.'">'.$j.'</option>';  
												 }
												 $j++;   
											  }
									  echo'</select>
										  <span class="errorMessage" id="errorminedad" lang="es"></span>'; 	
							   echo'</div>
								</div>
								<div class="da-form-row">
									<label style="width:190px; text-align:right; margin-right:10px;"><b><span lang="es">Edad Máxima</span></b></label>
									<div class="da-form-item large" style="margin-left:200px;">';
										 $i=15;
									  echo'<select id="txtEdadMax" name="txtEdadMax" style="width:120px;" class="required">';
											  echo'<option value="">seleccione</option>';
											  while($i<=85){
												 if($fila['edad_max']==$i){ 
													echo'<option value="'.$i.'" selected>'.$i.'</option>'; 
												 }else{
													echo'<option value="'.$i.'">'.$i.'</option>'; 
												 }
												 $i++;   
											  }
									  echo'</select>
										  <span class="errorMessage" id="errormaxedad" lang="es"></span>';	
							   echo'</div>
								</div>				
								<div class="da-form-row">
									<label style="width:190px; text-align:right; margin-right:10px;"><b><span lang="es">Tasa (%)</span></b></label>
									<div class="da-form-item large">
										<input class="textbox required" type="text" name="txtTasa" id="txtTasa" style="width: 80px;" value="'.$datavg['tasa'].'" autocomplete="off"/>
										<span class="errorMessage" id="error_tasa" style="margin-top:0px;"></span>
									</div>
								</div>
								<div class="da-form-row">
									<label style="width:190px; text-align:right; margin-right:10px;"><b><span lang="es">Monto Mínimo (Bs)</span></b></label>
									<div class="da-form-item large">
										<input class="textbox required" type="text" name="txtMinBs" id="txtMinBs" style="width: 125px;" value="'.$datavg['amount_min'].'" autocomplete="off"/>
										<span class="errorMessage" id="error_min" style="margin-top:0px;"></span>
									</div>
								</div>
								<div class="da-form-row">
									<label style="width:190px; text-align:right; margin-right:10px;"><b><span lang="es">Monto Máximo (Bs)</span></b></label>
									<div class="da-form-item large">
										<input class="textbox required" type="text" name="txtMaxBs" id="txtMaxBs" style="width: 125px;" value="'.$datavg['amount_max'].'" autocomplete="off"/>
										<span class="errorMessage" id="error_max" style="margin-top:0px;"></span>
									</div>
								</div>
								<div class="da-form-row">
									<label style="width:190px; text-align:right; margin-right:10px;"><b><span lang="es">Modalidad</span></b></label>
									<div class="da-form-item">
										<ul class="da-form-list inline">';
										   if(is_array($datavg)){
											   if($datavg['modality'][1]['active']===true){
												  $display='';
												  $monto = $datavg['modality'][1]['amount'];
											      $monto_aux = $datavg['modality'][2]['amount']; 	
												  echo'<li style="font-size:80%;"><input type="radio" name="modalidad" id="mod-1" value="1" class="required rdve" checked/> <label>'.$datavg['modality'][1]['name'].'</label></li>
													   <li style="font-size:80%;"><input type="radio" name="modalidad" id="mod-2" value="2" class="required rdve"/> <label>'.$datavg['modality'][2]['name'].'</label></li>';
												  
											   }elseif($datavg['modality'][2]['active']===true){
												  $display='';
												  $monto = $datavg['modality'][2]['amount'];
											      $monto_aux = $datavg['modality'][1]['amount'];
												  echo'<li style="font-size:80%;"><input type="radio" name="modalidad" id="mod-1" value="1" class="required rdve"/> <label>'.$datavg['modality'][1]['name'].'</label></li>
													   <li style="font-size:80%;"><input type="radio" name="modalidad" id="mod-2" value="2" class="required rdve" checked/> <label>'.$datavg['modality'][2]['name'].'</label></li>';
											   }
			                               }else{
											   $display='display:none;';
											   echo'<li style="font-size:80%;"><input type="radio" name="modalidad" id="mod-1" value="1" class="required rdve"/> <label>Modalidad Valor Estatico</label></li>
													<li style="font-size:80%;"><input type="radio" name="modalidad" id="mod-2" value="2" class="required rdve"/> <label>Modalidad Valor Asegurado</label></li>';
										   }
								   echo'</ul>
										<span class="errorMessage" id="error_modalidad"></span>
									</div>
								</div>
								<div class="da-form-row vs_modality" style="'.$display.'">
									<label style="width:190px; text-align:right; margin-right:10px;"><b><span lang="es">Monto Valor Estático (Bs)</span></b></label>
									<div class="da-form-item large">
										<input class="required" type="text" name="txtMontoVA" id="txtMontoVA" style="width: 125px; " value="'.$monto.'" autocomplete="off"/>
								        <input type="hidden" name="txtMontoAux" id="txtMontoAux" style="width: 125px; " value="'.$monto_aux.'" autocomplete="off"/>
										<span class="errorMessage" id="error_monto" style="margin-top:0px;"></span>
									</div>
								</div>
																			
								<div class="da-button-row">
									<input type="button" value="Cancelar" class="da-button gray left" name="btnCancelar" id="btnCancelar" lang="es"/>
									<input type="submit" value="Guardar" class="da-button green" name="btnUpdate" id="btnUpdate" lang="es"/>
									<input type="hidden" name="var" id="var" value="'.$_GET['var'].'"/>
									<input type="hidden" id="producto" value="'.$_GET['producto'].'"/>
								</div>
							</form>
						</div>
					</div>';
			
			} else {
				//SI NO EXISTE EL USUARIO DADO EN LA BASE DE DATOS, VOLVEMOS A LA LISTA DE USUARIOS
				echo'<div class="da-message info">
					   Verifique que el tipo de cambio tenga un valor y este activado para la Entidad Financiera, esto para realizar las operaciones al momento de ingresar los nuevos montos
					</div>';
			}
	}else{
		echo"<div style='font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;'>
		  Error en la consulta: "."\n ".$conexion->errno . ": " .$conexion->error
		   ."</div>";
	}
}

?>