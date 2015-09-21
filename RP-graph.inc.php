<?php
if (isset($_SESSION['idUser']) && isset($_SESSION['idEF'])) {
?>

<script type="text/javascript">
$(document).ready(function(e) {
    $(".date").datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'yy-mm-dd',
        yearRange: "c-100:c+100"
    });

    $(".date").datepicker($.datepicker.regional[ "es" ]);

});
</script>

<?php
    if (($rsSb = $link->get_depto()) !== false) {
        while ($rowSb = $rsSb->fetch_array(MYSQLI_ASSOC)) {
            if ((boolean)$rowSb['tipo_dp'] === true) {
               if($rowSb['id_depto'] == "7") $selected='selected'; else  $selected='';
                $data_subsidiary[] = array(
               'id'    => base64_encode($rowSb['id_depto']),
               'depto' => $rowSb['departamento'],
               'selected' => $selected,
                );
            }
        }
    }
?>
<h3 class="h3">Reporte Gráfico</h3>

<form class="f-records" id="f-graph">

	  <div align="left">

        <label>Tipo de Reporte: </label>
        <select id="frp-type" name="frp-type">

            <option value="production">Producción</option>
            <option value="users">Usuarios</option>

        </select>

        <label>Sucursal: </label>
        <select id="frp-subsidiary" name="frp-subsidiary">
<?php
            foreach ($data_subsidiary as $key => $value) {
                echo '<option value="' . $value['id'] . '" ' . $value['selected'] . '>' . $value['depto'] . '</option>';
            }

            echo '<option value="' . base64_encode(12) . '" >Sin Sucursal</option>';
?>
        </select>


        <label>Desde: </label>
        <input type="text" id="frp-date-b" name="frp-date-b" value="<?= date('Y-m-d', strtotime('-31 day'));?>" autocomplete="off"  class="date" readonly>

        <label style="width:auto;">Hasta: </label>
        <input type="text" id="frp-date-e" name="frp-date-e" value="<?= date('Y-m-d');?>" autocomplete="off" class="date" readonly>


      </div>

      <div style="width:1000px;">

        <div style="display:inline-block; width:100%;">
            <div id="container" style="width: 100%; height: 400px; margin: 0 auto"></div>

            <div style="height: 40px;">
                <div id="reporting" class="plan-detail"></div>
            </div>
        </div>

      </div>

      <input type="hidden" id="idef" name="idef" value="<?=$_SESSION['idEF'];?>">
      <input type="hidden" id="usertype" name="usertype" value="<?=$user_type;?>">
      <input type="hidden" id="iduser" name="iduser" value="<?=$_SESSION['idUser'];?>">

</form>

    <script type="text/javascript">

    $(document).ready(function() {
    var $reporting = $('#reporting');
    $reporting.hide();

        Highcharts.setOptions({
         colors: ['#9e413e', '#40699c', '#7f9a48', '#695185', '#3c8da3', '#cc7b38', '#4f81bd',      '#9bbb59', '#8064a2', '#4bacc6', '#f79646', '#aabad7']
        });
        Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function (color) {
            return {
                radialGradient: { cx: 0.5, cy: 0.3, r: 0.7 },
                stops: [
                    [0, color],
                    [1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
                ]
            };
        });

        var rep = {

            chart: {
                renderTo: 'container',
                type: 'column'
            },
            title: {
                text: 'Cantidad de Pólizas Emitidas por Agencia',
                x: -20 //center
            },
            xAxis: {
                //enabled: false;
                categories: ['Automotores - Todo Riesgo']
            },
            yAxis: {
                title: {
                    text: 'Nro. Polizas'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },

            legend: {
                layout: 'horizontal',
                align: 'center',
                verticalAlign: 'bottom',
                borderWidth: 0
            },
            tooltip : {
                //enabled: false
                formatter: function() {
                    var tooltip;
                    if (this.key == 'last') {
                        tooltip = '<b>Final result is </b> ' + this.y;
                    }
                    else {
                        tooltip =  '<span style="color:' + this.series.color + '">Polizas en ' + this.series.name + '</span>: <b>' + this.y + '</b><br/>';
                    }
                    return tooltip;
                }
            },
            plotOptions: {
                series: {
                 cursor: 'pointer',
                    point: {
                        events: {
                            click: function () {

                                var agency = this.series.name;
                                var type = $("#frp-type").val();
                                var data_det = $('#f-graph').serialize();
                                data_det = data_det + '&frp-agency=' + agency + '&frp-type=' + type;

                                $.fancybox({
                                    'width': '80%',
                                    'height': '90%',
                                    'autoScale': true,
                                    'transitionIn': 'fade',
                                    'transitionOut': 'fade',
                                    'type': 'iframe',
                                    'href': 'get-data-report-au-trd.php?' + data_det
                                });

                            },
                            mouseOver: function () {
                            var agency = this.series.name;
                            var type = $("#frp-type").val();
                            var data_det = $('#f-graph').serialize();
                            data_det = data_det + '&frp-agency=' + agency ;

                                $.get("get-data-graph.php?gr-flag=4", data_det, function(ap) {
                                    $.get("get-data-graph.php?gr-flag=5", data_det, function(vi) {
                                        $reporting.fadeIn();
                                        $reporting.html('Nro. Automotores: ' + ap + ' - Nro. Todo Riesgo ' + vi);
                                    });
                                });
                            },
                            mouseOut: function () {
                                var agency = this.series.name;
                                $reporting.fadeOut();

                            }

                        }
                    }
                }
            }
        }


      var repuser = {

            chart: {
                renderTo: 'container',
                type: 'column'
            },
            title: {
                text: 'Cantidad de Usuarios por Agencia',
                x: -20 //center
            },
            xAxis: {
                //enabled: false;
                categories: ['Usuarios']
            },
            yAxis: {
                title: {
                    text: 'Nro. Usuarios'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },

            legend: {
                layout: 'horizontal',
                align: 'center',
                verticalAlign: 'bottom',
                borderWidth: 0
            },
            tooltip : {
                //enabled: false
                formatter: function() {
                    var tooltip;
                    if (this.key == 'last') {
                        tooltip = '<b>Final result is </b> ' + this.y;
                    }
                    else {
                        tooltip =  '<span style="color:' + this.series.color + '">Usuarios en ' + this.series.name + '</span>: <b>' + this.y + '</b><br/>';
                    }
                    return tooltip;
                }
            },
            plotOptions: {
                series: {
                 cursor: 'pointer',
                    point: {
                        events: {
                            click: function () {

                                var agency = this.series.name;
                                var type = $("#frp-type").val();
                                var data_det = $('#f-graph').serialize();
                                data_det = data_det + '&frp-agency=' + agency + '&frp-type=' + type;

                                $.fancybox({
                                    'width': '80%',
                                    'height': '90%',
                                    'autoScale': true,
                                    'transitionIn': 'fade',
                                    'transitionOut': 'fade',
                                    'type': 'iframe',
                                    'href': 'get-data-report-au-trd.php?' + data_det
                                });

                            },


                        }
                    }
                }
            }
        }


        router();

        function router(){
            var frp_type= $("#frp-type").val();

            if(frp_type == 'production'){
                generarRep();
            }
            else{
                generarRepUsers();
            }
        }

        $("#frp-type").change(function(e){
            router();
        });

        $("#frp-subsidiary").change(function(e){
            router();
        });

        $("#frp-date-b").change(function(e){
             var frp_type= $("#frp-type").val();
          if(frp_type == 'production'){
                generarRep();
            }
        });

        $("#frp-date-e").change(function(e){
        var frp_type= $("#frp-type").val();
            if(frp_type == 'production'){
                generarRep();
            }
        });


        function generarRep(){
        var data = $('#f-graph').serialize();

            $.getJSON("get-data-graph.php?gr-flag=1", data, function(json) {
//alert(json);
               /*$.each(json, function() {
                  $.each(this, function(name, value) {
                    /// do stuff
                    alert(name + '=' + value);
                    rep.series.name[] = json;
                    rep.series.id = json;
                    rep.series = json;
                  });
                });*/

               rep.series = json;
               $.get("get-data-graph.php?gr-flag=2", data, function(ap) {
                    $.get("get-data-graph.php?gr-flag=3", data, function(vi) {

                    rep.xAxis.categories = ['Automotores ' + ap + ' - Todo Riesgo ' + vi]
                    //rep.subtitle.text = ['Accidentes Personales ' + ap + ' - Vida Individual ' + vi]
                    rep.series = json;
                    chart = new Highcharts.Chart(rep);
                    });
               });

            });
        }
        function generarRepUsers(){
        var data = $('#f-graph').serialize();

            $.getJSON("get-data-graph.php?gr-flag=6", data, function(json) {

                $.get("get-data-graph.php?gr-flag=7", data, function(cant) {

                     repuser.xAxis.categories = ['Usuarios ' + cant]
                    //rep.subtitle.text = ['Accidentes Personales ' + ap + ' - Vida Individual ' + vi]
                     repuser.series = json;
                     chart = new Highcharts.Chart(repuser);
                });

            });

            $reporting.hide();
        }

    });
    </script>

<?php
}
?>