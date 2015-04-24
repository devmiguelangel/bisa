<?php
require_once('config.class.php');
$conexion = new SibasDB();
?>
<style type="text/css">
 .errorMessage{
	display:block;
	color:#d44d24;
	font-size:11px;
	width:100% !important;
	margin-top:1px;
	padding:0 !important;
 }
 .loading-fac{
	background: #FFFFFF url(images/loading30x30.gif) top center no-repeat;
	height: 0px;
	margin: 10px 0;
	text-align: center;
	font-size: 90%;
	font-weight: bold;
	color: #0075AA;
}
</style>
<script type="text/javascript">
   $(document).ready(function(){
	    //CREAMOS NUEVO CERTIFICADO
		$('#formCreaCertificado').submit(function(e){
			var selectTipo = $("#tipocertificado option:selected").prop('value');
			var titulo = $('#txtTitulo').val();
			var id_ef_cia = $('#id_ef_cia').prop('value');
			var sum=0;
			$(this).find('.required').each(function(){
			    if(selectTipo!=''){
				    $('#errortipocert').hide('slow');
			    }else{
				   sum++; 
				   $('#errortipocert').show('slow');
				   $('#errortipocert').html('seleccione tipo certificado');  
			    }
				
				if(titulo!=''){
					$('#errortitulo').hide('slow');
			    }else{
					sum++;
					$('#errortitulo').show('slow');
					$('#errortitulo').html('Ingrese el titulo');
				}	
			});
			if(sum==0){
			     $("#formCreaCertificado :submit").attr("disabled", true);
		         e.preventDefault();
				 var dataString = 'tipocertificado='+selectTipo+'&id_ef_cia='+id_ef_cia+'&titulo='+titulo+'&opcion=registrodatos';
				 //alert (dataString);
				 //ejecutando ajax 
				 $.ajax({
					   async: true,
				       cache: false,
					   type: "POST",
					   url: "accion_registro.php",
					   data: dataString,
					   beforeSend: function(){
							$("#response-loading").css({
								'height': '30px'
							});
					   },
					   complete: function(){
							$("#response-loading").css({
								"background": "transparent"
							});
					   },
					   success: function(datareturn) {
							  //alert(datareturn);
							  if(datareturn==1){
								 $('#response-loading').html("Se agrego correctamente el registro");
								  window.setTimeout('location.reload()', 3000); 
							  }else if(datareturn==2){
								 $('#response-loading').html("Hubo un error al ingresar el dato, consulte con su administrador");
								 e.preventDefault();
							  }
							  
					   }
				 });
		    }else{
			   e.preventDefault();  
		    }
			//e.preventDefault();
		});
		
		//EDITAR CERTIFICADO
		$('#formEditarCertificado').submit(function(e){
			var tipocertificado = $("#tipocertificado option:selected").prop('value');
			var idcm = $('#idcm').prop('value');
			var titulo = $('#txtTitulo').val();
			var id_ef_cia = $('#id_ef_cia').prop('value');
			var sum=0;
			$(this).find('.required').each(function(){
			    if(tipocertificado!=''){
				    $('#errortipocert').hide('slow');
			    }else{
				   sum++; 
				   $('#errortipocert').show('slow');
				   $('#errortipocert').html('seleccione tipo certificado');  
			    }
								
				if(titulo!=''){
					$('#errortitulo').hide('slow');
			    }else{
					sum++;
					$('#errortitulo').show('slow');
					$('#errortitulo').html('Ingrese el titulo');
			    }	
			});
			if(sum==0){
			     $("#formEditarCertificado :submit").attr("disabled", true);
		         e.preventDefault();
				 var dataString = 'tipocertificado='+tipocertificado+'&id_ef_cia='+id_ef_cia+'&idcm='+idcm+'&titulo='+titulo+'&opcion=editardatos';
				 //alert (dataString);
				 //ejecutando ajax 
				 $.ajax({
					   async: true,
				       cache: false,
					   type: "POST",
					   url: "accion_registro.php",
					   data: dataString,
					   beforeSend: function(){
							$("#response-loading").css({
								'height': '30px'
							});
					   },
					   complete: function(){
							$("#response-loading").css({
								"background": "transparent"
							});
					   },
					   success: function(datareturn) {
							  //alert(datareturn);
							  if(datareturn==1){
								 $('#response-loading').html("Se edito correctamente el registro");
								  window.setTimeout('location.reload()', 3000); 
							  }else if(datareturn==2){
								 $('#response-loading').html("Hubo un error al ingresar el dato, consulte con su administrador");
								 e.preventDefault();
							  }
							  
					   }
				 });
		    }else{
			   e.preventDefault();  
		    }
		});
		
		//CREAMOS NUEVO CUESTIONARIO
		$('#formCreaCuestionario').submit(function(e){
			var titulo=$('#txtTitCuestionario').prop('value');
			var sum=0;
			$(this).find('.required').each(function(){
			     if(titulo!=''){
					 if(titulo.match(/^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ\s]+$/)){
						 $('#errortitulo').hide('slow');
					 }else{
						 sum++;
						 $('#errortitulo').show('slow');
						 $('#errortitulo').html('ingrese solo caracteres');
					 }
				 }else{
					 sum++;
					 $('#errortitulo').show('slow');
					 $('#errortitulo').html('ingrese titulo del cuestionario');
				 }	
			});
			if(sum==0){
			     $("#formCreaCuestionario :submit").attr("disabled", true);
		         e.preventDefault();
				 var dataString = 'titulo='+titulo+'&opcion=registrocuestionario';
				 //alert (dataString);
				 //ejecutando ajax 
				 $.ajax({
					   async: true,
				       cache: false,
					   type: "POST",
					   url: "accion_registro.php",
					   data: dataString,
					   beforeSend: function(){
							$("#response-loading").css({
								'height': '30px'
							});
					   },
					   complete: function(){
							$("#response-loading").css({
								"background": "transparent"
							});
					   },
					   success: function(datareturn) {
							  //alert(datareturn);
							  if(datareturn==1){
								 $('#response-loading').html("Se agrego correctamente el registro");
								  window.setTimeout('location.reload()', 3000); 
							  }else if(datareturn==2){
								 $('#response-loading').html("Hubo un error al ingresar el dato, consulte con su administrador");
								 e.preventDefault();
							  }
							  
					   }
				 });
		    }else{
			   e.preventDefault();  
		    }
	    });
		
		//EDITAMOS EL REGISTRO CREADO
		$('#formEditaCuestionario').submit(function(e){
			var titulo=$('#txtTitCuestionario').prop('value');
			var idcuestionario=$('#idcuestionario').prop('value');
			var sum=0;
			$(this).find('.required').each(function(){
			     if(titulo!=''){
					 if(titulo.match(/^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ\s]+$/)){
						 $('#errortitulo').hide('slow');
					 }else{
						 sum++;
						 $('#errortitulo').show('slow');
						 $('#errortitulo').html('ingrese solo caracteres');
					 }
				 }else{
					 sum++;
					 $('#errortitulo').show('slow');
					 $('#errortitulo').html('ingrese titulo del cuestionario');
				 }	
			});
			if(sum==0){
			     $("#formEditaCuestionario :submit").attr("disabled", true);
		         e.preventDefault();
				 var dataString = 'titulo='+titulo+'&idcuestionario='+idcuestionario+'&opcion=registroedita';
				 //alert (dataString);
				 //ejecutando ajax 
				 $.ajax({
					   async: true,
				       cache: false,
					   type: "POST",
					   url: "accion_registro.php",
					   data: dataString,
					   beforeSend: function(){
							$("#response-loading").css({
								'height': '30px'
							});
					   },
					   complete: function(){
							$("#response-loading").css({
								"background": "transparent"
							});
					   },
					   success: function(datareturn) {
							  //alert(datareturn);
							  if(datareturn==1){
								 $('#response-loading').html("Se agrego correctamente el registro");
								  window.setTimeout('location.reload()', 3000); 
							  }else if(datareturn==2){
								 $('#response-loading').html("Hubo un error al ingresar el dato, consulte con su administrador");
								 e.preventDefault();
							  }
							  
					   }
				 });
		    }else{
			   e.preventDefault();  
		    }
	    });
		
		//CREA PREGUNTA
		$('#formCreaPregunta').submit(function(e){
		    var pregunta = $('#txtPregunta').prop('value');
			var selectTipo = $("#tipopregunta option:selected").prop('value');
			var sum=0;
			$(this).find('.required').each(function(){
					 if(pregunta!=''){
						 if(pregunta.match(/^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ\s\D\d]+$/)){
							 $('#errorpregunta').hide('slow');
						 }else{
							 sum++;
							 $('#errorpregunta').show('slow');
							 $('#errorpregunta').html('ingrese solo caracteres');
						 }
					 }else{
						 sum++;
						 $('#errorpregunta').show('slow');
						 $('#errorpregunta').html('ingrese la pregunta');
					 }
					 
					 if(selectTipo!=''){
						 $('#errortipopreg').hide('slow');
				     }else{
						 sum++;
						 $('#errortipopreg').show('slow');
						 $('#errortipopreg').html('seleccione tipo pregunta');
				     }
			});
			if(sum==0){
			     $("#formCreaPregunta :submit").attr("disabled", true);
		         e.preventDefault();
				 var dataString = 'pregunta='+pregunta+'&tipopregunta='+selectTipo+'&opcion=creapregunta';
				 //alert (dataString);
				 //ejecutando ajax 
				 $.ajax({
					   async: true,
				       cache: false,
					   type: "POST",
					   url: "accion_registro.php",
					   data: dataString,
					   beforeSend: function(){
							$("#response-loading").css({
								'height': '30px'
							});
					   },
					   complete: function(){
							$("#response-loading").css({
								"background": "transparent"
							});
					   },
					   success: function(datareturn) {
							  //alert(datareturn);
							  if(datareturn==1){
								 $('#response-loading').html("Se agrego correctamente el registro");
								  window.setTimeout('location.reload()', 3000); 
							  }else if(datareturn==2){
								 $('#response-loading').html("Hubo un error al ingresar el dato, consulte con su administrador");
								 e.preventDefault();
							  }
							  
					   }
				 });
		    }else{
			   e.preventDefault();  
		    }								   
	    });
		
		//EDITA PREGUnTA
		$('#formEditaPregunta').submit(function(e){
		    var pregunta = $('#txtPregunta').prop('value');
			var selectTipo = $("#tipopregunta option:selected").prop('value');
			var idpregunta = $('#idpregunta').prop('value');
			var sum=0;
			$(this).find('.required').each(function(){
					 if(pregunta!=''){
						 if(pregunta.match(/^[0-9a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ\s\D\S]+$/)){
							 $('#errorpregunta').hide('slow');
						 }else{
							 sum++;
							 $('#errorpregunta').show('slow');
							 $('#errorpregunta').html('ingrese solo caracteres');
						 }
					 }else{
						 sum++;
						 $('#errorpregunta').show('slow');
						 $('#errorpregunta').html('ingrese la pregunta');
					 }
					 
					 if(selectTipo!=''){
						 $('#errortipopreg').hide('slow');
				     }else{
						 sum++;
						 $('#errortipopreg').show('slow');
						 $('#errortipopreg').html('seleccione tipo pregunta');
				     }
			});
			if(sum==0){
			     $("#formCreaPregunta :submit").attr("disabled", true);
		         e.preventDefault();
				 var dataString = 'pregunta='+pregunta+'&tipopregunta='+selectTipo+'&idpregunta='+idpregunta+'&opcion=editapregunta';
				 //alert (dataString);
				 //ejecutando ajax 
				 $.ajax({
					   async: true,
				       cache: false,
					   type: "POST",
					   url: "accion_registro.php",
					   data: dataString,
					   beforeSend: function(){
							$("#response-loading").css({
								'height': '30px'
							});
					   },
					   complete: function(){
							$("#response-loading").css({
								"background": "transparent"
							});
					   },
					   success: function(datareturn) {
							  //alert(datareturn);
							  if(datareturn==1){
								 $('#response-loading').html("Se edito correctamente el registro");
								  window.setTimeout('location.reload()', 3000); 
							  }else if(datareturn==2){
								 $('#response-loading').html("Hubo un error al ingresar el dato, consulte con su administrador");
								 e.preventDefault();
							  }
							  
					   }
				 });
		    }else{
			   e.preventDefault();  
		    }												
		});
		
		//ADICIONAR CUESTIONARIO A CERTIFICADO
		$('#formAddCuest').submit(function(e){
			var idcuestionario = $("#idcuestionario option:selected").prop('value');
			var idcm = $('#idcm').prop('value');
			var sum=0;
			$(this).find('.required').each(function(){					 
					 if(idcuestionario!=''){
						 $('#errorcuest').hide('slow');
				     }else{
						 sum++;
						 $('#errorcuest').show('slow');
						 $('#errorcuest').html('seleccione tipo de cuestionario');
				     }
			});
			if(sum==0){
			     $("#formAddCuest :submit").attr("disabled", true);
		         e.preventDefault();
				 var dataString = 'idcuestionario='+idcuestionario+'&idcm='+idcm+'&opcion=adicionacuest';
				 //alert (dataString);
				 //ejecutando ajax 
				 $.ajax({
					   async: true,
				       cache: false,
					   type: "POST",
					   url: "accion_registro.php",
					   data: dataString,
					   beforeSend: function(){
							$("#response-loading").css({
								'height': '30px'
							});
					   },
					   complete: function(){
							$("#response-loading").css({
								"background": "transparent"
							});
					   },
					   success: function(datareturn) {
							  //alert(datareturn);
							  if(datareturn==1){
								 $('#response-loading').html("Se adiciono correctamente el registro");
								  window.setTimeout('location.reload()', 3000); 
							  }else if(datareturn==2){
								 $('#response-loading').html("Hubo un error al ingresar el dato, consulte con su administrador");
								 e.preventDefault();
							  }
							  
					   }
				 });
		    }else{
			   e.preventDefault();  
		    }								
	    });
		
		//AGREGAR PREGUNTA A CUESTIONARIO
		$('#formAddPreguntaCuest').submit(function(e){
			var idpregunta = $("#idpregunta option:selected").prop('value');
			var idcc = $('#idcc').prop('value');
			var sum=0;
			$(this).find('.required').each(function(){					 
					 if(idpregunta!=''){
						 $('#erroraddpregcuest').hide('slow');
				     }else{
						 sum++;
						 $('#erroraddpregcuest').show('slow');
						 $('#erroraddpregcuest').html('seleccione pregunta');
				     }
			});
			if(sum==0){
			     $("#formAddPreguntaCuest :submit").attr("disabled", true);
		         e.preventDefault();
				 var dataString = 'idpregunta='+idpregunta+'&idcc='+idcc+'&opcion=adicionapregcuest';
				 //alert (dataString);
				 //ejecutando ajax 
				 $.ajax({
					   async: true,
				       cache: false,
					   type: "POST",
					   url: "accion_registro.php",
					   data: dataString,
					   beforeSend: function(){
							$("#response-loading").css({
								'height': '30px'
							});
					   },
					   complete: function(){
							$("#response-loading").css({
								"background": "transparent"
							});
					   },
					   success: function(datareturn) {
							  //alert(datareturn);
							  if(datareturn==1){
								 $('#response-loading').html("Se adiciono correctamente el registro");
								  window.setTimeout('location.reload()', 3000); 
							  }else if(datareturn==2){
								 $('#response-loading').html("Hubo un error al ingresar el dato, consulte con su administrador");
								 e.preventDefault();
							  }
							  
					   }
				 });
		    }else{
			   e.preventDefault();  
		    }						
		});
		
		//CONVERSOR MAYUSCULAS 
		$('#txtTitCuestionario').keyup(function() {
           $(this).val($(this).val().toUpperCase());
        });
   });
</script>
<?php
    
   if($_GET['opcion']=='crear_cert'){//CREAR CERTIFICADOS MEDICOS
	   echo'<div class="da-panel">
			  <div class="da-panel-header">
				  <span class="da-panel-title">
					  <img src="images/icons/black/16/pencil.png" alt="" />
					  Crear Nuevo Certificado Médico
				  </span>
			  </div>
			  <div class="da-panel-content">
				  <form class="da-form" action="" method="POST" name="formCreaCertificado" id="formCreaCertificado">						
					  <div class="da-form-row">
					      <label style="text-align:right;"><b>Entidad Financiera:</b></label>
						  <div class="da-form-item large">
						    '.base64_decode($_GET['entidad']).'
						  </div>	 
					  </div>
					  <div class="da-form-row">
					      <label style="text-align:right;"><b>Compañía de Seguros:</b></label>
						  <div class="da-form-item large">
						     '.base64_decode($_GET['compania']).'
						  </div>	  
					  </div>
					  <div class="da-form-row">
						  <label style="text-align:right;"><b>Tipo Certificado:</b></label>
						  <div class="da-form-item large">
							  <select id="tipocertificado" class="required">
								 <option value="">Seleccione...</option>
								 <option value="EDITOR">EDITOR</option>
								 <option value="CUESTIONARIO">CUESTIONARIO</option>
							  </select>
							  <span class="errorMessage" id="errortipocert"></span>
						  </div>	  
					  </div>
					  <div class="da-form-row">
					      <label style="text-align:right;"><b>Titulo:</b></label>
						  <div class="da-form-item large">
							  <textarea rows="auto" cols="auto" id="txtTitulo"></textarea>
							  <span class="errorMessage" id="errortitulo"></span>
						  </div>	  
					  </div>    
					  <div class="da-button-row">
					      <input type="submit" value="Guardar" class="da-button green"/>
						  <input type="hidden" id="id_ef_cia" value="'.base64_decode($_GET['id_ef_cia']).'"/>   
						  <div id="response-loading" class="loading-fac"></div>
					  </div>
				  </form>
			  </div>
			</div>';   
   }elseif($_GET['opcion']=='editar_cert'){
       $select="select
	              id_compania,
				  nombre
			    from
				  s_compania AS tbc
			    where
				  activado=1";
	   $res = $conexion->query($select,MYSQLI_STORE_RESULT);
	   
	   //SACAMOS LOS DATOS DE LA DB
	   $busca="select
				  id_cm,
				  tipo,
				  titulo
				from
				  s_cm_certificado
				where
				  id_cm=".base64_decode($_GET['idcm'])."
				limit
				  0,1;";
	   $resbusca = $conexion->query($busca,MYSQLI_STORE_RESULT);			  
	   $regibusca = $resbusca->fetch_array(MYSQLI_ASSOC);
	   $resbusca->free();		   
	   echo'<div class="da-panel">
			  <div class="da-panel-header">
				  <span class="da-panel-title">
					  <img src="images/icons/black/16/pencil.png" alt="" />
					  Editar Certificado Médico
				  </span>
			  </div>
			  <div class="da-panel-content">
				  <form class="da-form" action="" method="POST" name="formEditarCertificado" id="formEditarCertificado">						
					  <div class="da-form-row">
					      <label style="text-align:right;"><b>Entidad Financiera:</b></label>
						  <div class="da-form-item large">
						  	'.base64_decode($_GET['entidad']).' 
						  </div>	
					  </div>
					  <div class="da-form-row">
					      <label style="text-align:right;"><b>Compañía de Seguros:</b></label>
						  <div class="da-form-item large">
						  	'.base64_decode($_GET['compania']).' 
						  </div>
					  </div>
					  <div class="da-form-row">
						  <label style="text-align:right;"><b>Tipo Certificado:</b></label>
						  <div class="da-form-item large">
							  <select id="tipocertificado" class="required">
								  <option value=""';
									if($regibusca['tipo']==""){
									  echo"selected";
									}
								 echo'>Seleccione...</option>
								  <option value="EDITOR"';
									if($regibusca['tipo']=="EDITOR"){
									  echo"selected";
									} 
								 echo'>EDITOR</option>';
								 echo'<option value="CUESTIONARIO"';
									if($regibusca['tipo']=="CUESTIONARIO"){
									  echo"selected";
									} 
								 echo'>CUESTIONARIO</option>';
						 echo'</select>
							  <span class="errorMessage" id="errortipocert"></span>
						  </div>	  
					  </div>
					  <div class="da-form-row">
					      <label style="text-align:right;"><b>Titulo:</b></label>
						  <div class="da-form-item large">
							  <textarea rows="auto" cols="auto" id="txtTitulo">'.$regibusca['titulo'].'</textarea>
							  <span class="errorMessage" id="errortitulo"></span>
						  </div>	  
					  </div>  
					  <div class="da-button-row">
					      <input type="submit" value="Guardar" class="da-button green"/>   
						  <div id="response-loading" class="loading-fac"></div>
						  <input type="hidden" id="idcm" value="'.base64_decode($_GET['idcm']).'"/>
						  <input type="hidden" id="id_ef_cia" value="'.base64_decode($_GET['id_ef_cia']).'"/>
					  </div>
				  </form>
			  </div>
			</div>';   
   }elseif($_GET['opcion']=='crear_question'){ //CREAR CUESTIONARIOS TITULOS
	    echo'<div class="da-panel">
			  <div class="da-panel-header">
				  <span class="da-panel-title">
					  <img src="images/icons/black/16/pencil.png" alt="" />
					  Crear nuevo Cuestionario
				  </span>
			  </div>
			  <div class="da-panel-content">
				  <form class="da-form" action="" method="POST" name="formCreaCuestionario" id="formCreaCuestionario">						
					  <div class="da-form-row">
						 <label style="width:190px;"><b>Titulo Cuestionario</b></label>
						 
						   <input class="textbox required" type="text" id="txtTitCuestionario" value="" autocomplete="off"/>
						   <span class="errorMessage" id="errortitulo"></span>
						 
					  </div>
					  					  
					  <div class="da-button-row">
					      <input type="submit" value="Guardar" class="da-button green"/>   
						  <div id="response-loading" class="loading-fac"></div>
					  </div>
				  </form>
			  </div>
			</div>';
   }elseif($_GET['opcion']=='editar_question'){ //EDITAR TITULOS CUESTIONARIOS
	   $selectQu="select titulo from s_cm_cuestionario where id_cuestionario=".$_GET['idcuestionario']." limit 0,1;";
	   $resqu = $conexion->query($selectQu,MYSQLI_STORE_RESULT);
	   $regiqu = $resqu->fetch_array(MYSQLI_ASSOC);
	   echo'<div class="da-panel">
			  <div class="da-panel-header">
				  <span class="da-panel-title">
					  <img src="images/icons/black/16/pencil.png" alt="" />
					  Editar Registro
				  </span>
			  </div>
			  <div class="da-panel-content">
				  <form class="da-form" action="" method="POST" name="formEditaCuestionario" id="formEditaCuestionario">						
					  <div class="da-form-row">
						 <label style="width:190px;"><b>Titulo Cuestionario</b></label>
						 
						   <input class="textbox required" type="text" id="txtTitCuestionario" value="'.$regiqu['titulo'].'" autocomplete="off"/>
						   <span class="errorMessage" id="errortitulo"></span>
						 
					  </div>
					  					  
					  <div class="da-button-row">
					      <input type="submit" value="Guardar" class="da-button green"/>   
						  <div id="response-loading" class="loading-fac"></div>
						  <input type="hidden" id="idcuestionario" value="'.$_GET['idcuestionario'].'"/>
					  </div>
				  </form>
			  </div>
			</div>';   
   }elseif($_GET['opcion']=='crear_pregunta'){
	   echo'<div class="da-panel">
			  <div class="da-panel-header">
				  <span class="da-panel-title">
					  <img src="images/icons/black/16/pencil.png" alt="" />
					  Crear nueva Pregunta
				  </span>
			  </div>
			  <div class="da-panel-content">
				  <form class="da-form" action="" method="POST" name="formCreaPregunta" id="formCreaPregunta">						
					  <div class="da-form-row">
						   <label style="width:190px;"><b>Titulo Pregunta</b></label>
						   <input class="textbox required" type="text" id="txtPregunta" value="" autocomplete="off"/>
						   <span class="errorMessage" id="errorpregunta"></span>
					  </div>
					  <div class="da-form-row">
						   <label style="width:200px;"><b>Tipo pregunta:</b></label>
							<select id="tipopregunta" class="required">
							   <option value="">Seleccione...</option>
							   <option value="cb">CHECKBOX</option>
							   <option value="rd">RADIO</option>
							   <option value="text">TEXTO</option>
							   <option value="txtarea">AREA DE TEXTO</option>
							</select>
							<span class="errorMessage" id="errortipopreg"></span>  	
					  </div>   					  
					  <div class="da-button-row">
					      <input type="submit" value="Guardar" class="da-button green"/>   
						  <div id="response-loading" class="loading-fac"></div>
					  </div>
				  </form>
			  </div>
			</div>';  
   }elseif($_GET['opcion']=='editar_pregunta'){
	   $select="select
				  id_pregunta,
				  pregunta,
				  tipo
				from
				  s_cm_pregunta
				where
				  id_pregunta = ".$_GET['idpregunta'].";";
	   $resi = $conexion->query($select,MYSQLI_STORE_RESULT);			  
	   $regi = $resi->fetch_array(MYSQLI_ASSOC);
	   echo'<div class="da-panel">
			  <div class="da-panel-header">
				  <span class="da-panel-title">
					  <img src="images/icons/black/16/pencil.png" alt="" />
					  Editar Pregunta
				  </span>
			  </div>
			  <div class="da-panel-content">
				  <form class="da-form" action="" method="POST" name="formEditaPregunta" id="formEditaPregunta">						
					  <div class="da-form-row">
						   <label style="width:190px;"><b>Titulo Pregunta</b></label>
						   <input class="textbox required" type="text" id="txtPregunta" value="'.$regi['pregunta'].'" autocomplete="off"/>
						   <span class="errorMessage" id="errorpregunta"></span>
					  </div>
					  <div class="da-form-row">
						   <label style="width:200px;"><b>Tipo pregunta:</b></label>
							<select id="tipopregunta">
								  <option value=""';
									if($regi['tipo']==""){
									  echo"selected";
									}
								 echo'>Seleccione...</option>
								  <option value="cb"';
									if($regi['tipo']=="cb"){
									  echo"selected";
									} 
								 echo'>checkbox</option>';
								 echo'<option value="rd"';
									if($regi['tipo']=="rd"){
									  echo"selected";
									} 
								 echo'>radio</option>';
								 echo'<option value="text"';
									if($regi['tipo']=="text"){
									  echo"selected";
									} 
								 echo'>texto</option>';
								 echo'<option value="txtarea"';
									if($regi['tipo']=="txtarea"){
									  echo"selected";
									} 
								 echo'>Area de Texto</option>';
						echo'</select>
							<span class="errorMessage" id="errortipopreg"></span>  	
					  </div>   					  
					  <div class="da-button-row">
					      <input type="submit" value="Guardar" class="da-button green"/>   
						  <div id="response-loading" class="loading-fac"></div>
						  <input type="hidden" id="idpregunta" value="'.$_GET['idpregunta'].'"/>
					  </div>
				  </form>
			  </div>
			</div>';  
   }elseif($_GET['opcion']=='adicionar_cuestionario_cert'){
	   $select="select 
					scc.id_cuestionario, scc.titulo
				from
					s_cm_cuestionario as scc
				where
					not exists( select 
							sctc.id_cuestionario
						from
							s_cm_cert_cues as sctc
						where
							sctc.id_cuestionario = scc.id_cuestionario and sctc.id_cm=".base64_decode($_GET['idcm']).");";
	   $query = $conexion->query($select,MYSQLI_STORE_RESULT);				
	   echo'<div class="da-panel">
			  <div class="da-panel-header">
				  <span class="da-panel-title">
					  <img src="images/icons/black/16/pencil.png" alt="" />
					  Agregar cuestionario a certificado
				  </span>
			  </div>
			  <div class="da-panel-content">
				  <form class="da-form" action="" method="POST" name="formAddCuest" id="formAddCuest">						
					  <div class="da-form-row">
						   <label style="text-align:right;"><b>Entidad Financiera</b></label>
						   <div class="da-form-item large">'.base64_decode($_GET['entidad']).'</div>	 	
					  </div>
					  <div class="da-form-row">
						   <label  style="text-align:right;"><b>Compañía de Seguros</b></label>
						   <div class="da-form-item large">'.base64_decode($_GET['compania']).'</div>	 	
					  </div>
					  <div class="da-form-row">
						   <label  style="text-align:right;"><b>Tipo de certificado</b></label>
						   <div class="da-form-item large">'.base64_decode($_GET['tipocert']).'</div>
					  </div> 
					  <div class="da-form-row">
						   <label  style="text-align:right;"><b>Cuestionario</b></label>
						   <div class="da-form-item large">
							   <select id="idcuestionario" class="required">';
								 echo'<option value="">Seleccione...</option>';
								 while($regi = $query->fetch_array(MYSQLI_ASSOC)){
									echo'<option value="'.$regi['id_cuestionario'].'">'.$regi['titulo'].'</option>';  	
								 }
								 $query->free();	
						   echo'</select>   
							   <span class="errorMessage" id="errorcuest"></span>
						   </div>	   
					  </div>  					  
					  <div class="da-button-row">
					      <input type="submit" value="Guardar" class="da-button green"/>   
						  <div id="response-loading" class="loading-fac"></div>
						  <input type="hidden" id="idcm" value="'.base64_decode($_GET['idcm']).'"/>
					  </div>
				  </form>
			  </div>
			</div>';  
   }elseif($_GET['opcion']=='adicionar_pregunta_cuest'){
	   $select="select 
					scp.id_pregunta, scp.pregunta
				from
					s_cm_pregunta as scp
				where
					not exists( select 
							scg.id_pregunta
						from
							s_cm_grupo as scg
							inner join s_cm_cert_cues as cert on (cert.id_cc=scg.id_cc) 
						where
							scg.id_pregunta = scp.id_pregunta and cert.id_cm=".base64_decode($_GET['id_cm']).");";
	   $query = $conexion->query($select,MYSQLI_STORE_RESULT);
	   $num_reg = $query->num_rows;				
	   echo'<div class="da-panel">
			  <div class="da-panel-header">
				  <span class="da-panel-title">
					  <img src="images/icons/black/16/pencil.png" alt="" />
					  Agregar pregunta a cuestionario
				  </span>
			  </div>
			  <div class="da-panel-content">
				  <form class="da-form" action="" method="POST" name="formAddPreguntaCuest" id="formAddPreguntaCuest">						
					  <div class="da-form-row">
						   <label style="text-align:right;"><b>Entidad Financiera</b></label>
						   <div class="da-form-item large">'.base64_decode($_GET['entidad']).'</div>	 	
					  </div>
					  <div class="da-form-row">
						   <label style="text-align:right;"><b>Compañía de Seguros</b></label>
						   <div class="da-form-item large">'.base64_decode($_GET['compania']).'</div>	 	
					  </div>
					  <div class="da-form-row">
						   <label style="text-align:right;"><b>Titulo Cuestionario</b></label>
						   <div class="da-form-item large">'.base64_decode($_GET['cuestionario']).'</div>
					  </div>
					  <div class="da-form-row">
						   <label style="text-align:right;"><b>Pregunta</b></label>
						   <div class="da-form-item large">
							   <select id="idpregunta" class="required">';
								 echo'<option value="">Seleccione...</option>';
								 while($regi = $query->fetch_array(MYSQLI_ASSOC)){
									echo'<option value="'.$regi['id_pregunta'].'">'.$regi['pregunta'].'</option>';  	
								 }
								 $query->free();	
						   echo'</select>   
							   <span class="errorMessage" id="erroraddpregcuest"></span>
						    </div>   
					  </div>   					  
					  <div class="da-button-row">';
					      if($num_reg>0){
					        echo'<input type="submit" value="Guardar" class="da-button green"/>';
						  }else{
							echo'<input type="submit" value="Guardar" class="da-button green" disabled/>';  
						  }
					 echo'<div id="response-loading" class="loading-fac"></div>
						  <input type="hidden" id="idcc" value="'.base64_decode($_GET['id_cc']).'"/>
					  </div>
				  </form>
			  </div>
			</div>';  
   }
?>