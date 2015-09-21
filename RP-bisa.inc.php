<?php

require_once 'sibas-db.class.php';

$cx = new SibasDB();

$subsidiaries = array();

if (($data = $cx->get_depto(null, true)) !== false) {
  $subsidiaries = $data;
}

$insurances = $cx->getInsurance();

?>
<h3 class="h3">Reportes Bisa Seguros</h3>

<div>
  <form id="br-form" class="form-quote form-customer rep-form" method="post" action="reports.php">
    <label>Sucursal: </label>
    <select class="fbin" name="br-subsidiary">
      <option value="">Todos</option>
      <?php foreach ($subsidiaries as $key => $subsidiary): ?>
        <?php if ((boolean)$subsidiary['tipo_dp']): ?>
          <option value="<?= $subsidiary['id_depto'] ;?>"><?= $subsidiary['departamento'] ;?></option>
        <?php endif ?>
      <?php endforeach ?>
    </select>
    <br>

    <label>Seguro: </label>
    <select class="fbin" name="br-insurance">
      <option value="">Todos</option>
      <?php foreach ($insurances as $key => $insurance): ?>
        <option value="<?= $key ;?>"><?= $insurance ;?></option>
      <?php endforeach ?>
    </select>
    <br>

    <label>Fecha: </label>
    <label style="width: auto;">Desde:</label>
    <input type="text" class="fbin date" style="width: 80px;" name="br-date-begin" value="<?= date('Y-m-d') ;?>" readonly>
    <label style="width: auto;">Hasta:</label>
    <input type="text" class="fbin date" style="width: 80px;" name="br-date-end" value="<?= date('Y-m-d') ;?>" readonly>
    <br>

    <input type="hidden" id="br-category" name="br-category" value="">
    <input type="hidden" id="br-type" name="br-type" value="">
  </form>

  <table class="rep-bisa">
    <tr>
      <td style="width: 70%;">1. Reporte de Producción</td>
      <td style="width: 15%;" class="rep-img">
        <a href="reports.php" id="link01" title="Reporte Producción" class="br-button" data-type="print" data-category="P">
          <img src="img/view-icon.png" height="50">
        </a>
      </td>
      <td style="width: 15%;" class="rep-img">
        <a href="#" title="Reporte Producción" class="br-button" data-type="xls" data-category="P">
          <img src="img/xls-icon.png" height="50">
        </a>
      </td>
    </tr>
    <tr>
      <td>2. Reporte de Cobranzas</td>
      <td class="rep-img">
        <a href="#" title="Reporte Cobranzas" class="br-button" data-type="print" data-category="C">
          <img src="img/view-icon.png" height="50">
        </a>
      </td>
      <td class="rep-img">
        <a href="#" title="Reporte Cobranzas" class="br-button" data-type="xls" data-category="C">
          <img src="img/xls-icon.png" height="50">
        </a>
      </td>
    </tr>
  </table>

</div>

<script type="text/javascript">
  $(document).ready(function () {

    $('.br-button').click(function (e) {
      e.preventDefault();

      var type      = $(this).data('type');
      var category  = $(this).data('category');
      
      $('#br-type').prop('value', type);
      $('#br-category').prop('value', category);

      var data = $('#br-form').serialize();
      
      if (type == 'xls') {
        $('#br-form').submit();
      } else {
        $.fancybox(
          'reports.php?' + data,
          {
            'autoDimensions'  : false,
            'width'           : 800,
            'height'          : 'auto',
            'transitionIn'    : 'none',
            'transitionOut'   : 'none',
            'type'            : 'ajax'
          }
        );
      }
      
      // console.log(data);
    });

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