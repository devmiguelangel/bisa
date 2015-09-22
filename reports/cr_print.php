<div style="width: 785px; height: auto; padding: 5px; border: 0px solid #999; font-size: 80%;">
  <?php if ($request['type'] === 'print'): ?>
    <div style="text-align: left;">
      <a target="_blank" href="reports.php?br-subsidiary=<?=
        $request['subsidiary'] ;?>&br-insurance=<?= 
        $request['insurance'] ;?>&br-date-begin=<?= 
        $request['date_begin'] ;?>&br-date-end=<?= 
        $request['date_end'] ;?>&br-category=<?= 
        $request['category'] ;?>&br-type=pdf">PDF</a>
    </div>
  <?php endif ?>
  
  <div style="width: 785px;">
    <div style="width: 100%; font-size: 90%; margin: 10px 0; text-align: left;">
      BISA SEGUROS Y REASEGUROS
    </div>
    <div style="width: 100%; font-size: 90%; margin: 10px 0; text-align: right;">
      Fecha: <?= date('d', $current_date) ;?> de <?= date('m', $current_date) ;?> de <?= date('Y', $current_date) ;?>
      <br>
      Hora: <?= date('H:i:s') ;?>
    </div>
    <div style="font-weight: bold; font-size: 110%; width: 100%; text-align: center;">
      REPORTE DE INGRESOS
      <br>
      Del <?= date('d/m/Y', strtotime($request['date_begin'])) ;?> al <?= date('d/m/Y', strtotime($request['date_end'])) ;?>
    </div>
    
    <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 90%; margin: 10px 0;">
    <?php
        $prima_total  = 0;
        $prima_neta   = 0;
        $comission    = 0;
    ?>
    <?php foreach ($data as $kd => $d): ?>
      <?php
          $sub_prima_total  = 0;
          $sub_prima_neta   = 0;
          $sub_comission    = 0;
          $k                = 0;
      ?>
      <tr>
        <td colspan="6" style="font-weight: bold; font-size: 110%;">
          Sucursal: <?= $d['departamento'] ;?>
          <br>
          Moneda: Dólares
        </td>
      </tr>
      <tr>
        <td style="width: 5%; border: 1px solid #999; font-weight: bold; text-align: center;">N°</td>
        <td style="width: 15%; border: 1px solid #999; font-weight: bold; text-align: center;">Código Cliente</td>
        <td style="width: 30%; border: 1px solid #999; font-weight: bold; text-align: center;">Nombre Asegurado</td>
        <td style="width: 15%; border: 1px solid #999; font-weight: bold; text-align: center;">Prima Total</td>
        <td style="width: 15%; border: 1px solid #999; font-weight: bold; text-align: center;">Prima Neta</td>
        <td style="width: 20%; border: 1px solid #999; font-weight: bold; text-align: center;">Cobro por Comisión</td>
      </tr>
      <?php foreach ($d['data'] as $row):
        $k += 1;

        $sub_prima_total  += $row['prima_total'];
        $sub_prima_neta   += $row['prima_neta'];
        $sub_comission    += $row['commission'];
      ?>
        <tr>
          <td style="width: 5%; border: 1px solid #999; font-weight: bold; text-align: center;">
            <?= $k ;?>
          </td>
          <td style="width: 15%; border: 1px solid #999; font-size: 80%; text-align: center;">
            <?= $row['codigo_bb'] ;?>
          </td>
          <td style="width: 30%; border: 1px solid #999; font-size: 80%; text-align: center;">
            <?= $row['full_name'] ;?>
          </td>
          <td style="width: 15%; border: 1px solid #999; font-size: 80%; text-align: center;">
            <?= $row['prima_total'] ;?>
          </td>
          <td style="width: 15%; border: 1px solid #999; font-size: 80%; text-align: center;">
            <?= $row['prima_neta'] ;?>
          </td>
          <td style="width: 20%; border: 1px solid #999; font-size: 80%; text-align: center;">
            <?= $row['commission'] ;?>
          </td>
        </tr>
      <?php endforeach ?>
      <?php
          $prima_total  += $sub_prima_total;
          $prima_neta   += $sub_prima_neta;
          $comission    += $sub_comission;
      ?>
      <tr>
        <td colspan="6" style="width: 100%; font-weight: bold;">Resumen por sucursal: </td>
      </tr>
      <tr>
        <td colspan="3" style="font-weight: bold;">TOTAL USD: </td>
        <td style="width: 15%; border: 1px solid #999; font-size: 80%; font-weight: bold; text-align: center;">
          <?= $sub_prima_total ;?>
        </td>
        <td style="width: 15%; border: 1px solid #999; font-size: 80%; font-weight: bold; text-align: center;">
          <?= $sub_prima_neta ;?>
        </td>
        <td style="width: 20%; border: 1px solid #999; font-size: 80%; font-weight: bold; text-align: center;">
          <?= $sub_comission ;?>
        </td>
      </tr>
      <tr>
        <td colspan="6" style="width: 100%; height: 7px;"></td>
      </tr>
    <?php endforeach ?>
      <tr>
        <td colspan="6" style="width: 100%; font-weight: bold;">RESUMEN GENERAL: </td>
      </tr>
      <tr>
        <td colspan="3" style="font-weight: bold;">TOTAL USD: </td>
        <td style="width: 15%; border: 1px solid #999; font-size: 80%; font-weight: bold; text-align: center;">
          <?= $prima_total ;?>
        </td>
        <td style="width: 15%; border: 1px solid #999; font-size: 80%; font-weight: bold; text-align: center;">
          <?= $prima_neta ;?>
        </td>
        <td style="width: 20%; border: 1px solid #999; font-size: 80%; font-weight: bold; text-align: center;">
          <?= $comission ;?>
        </td>
      </tr>
    </table>


  </div>
</div>