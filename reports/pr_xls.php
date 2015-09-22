<div style="width: auto; height: auto; padding: 5px; border: 1px solid #999; font-size: 80%;">
  <div style="width: auto;">
    <div style="width: 100%; font-size: 90%; margin: 10px 0; text-align: right;">
      Fecha: <?= date('d', $current_date) ;?> de <?= date('m', $current_date) ;?> de <?= date('Y', $current_date) ;?>
      <br>
      Hora: <?= date('H:i:s') ;?>
    </div>
    <div style="font-weight: bold; font-size: 110%; width: 100%; text-align: center;">
      REPORTE DE PRODUCCION
      <br>
      (<?= htmlentities('Expresado en Dólares', ENT_QUOTES, 'UTF-8') ;?>)
      <br>
      Del <?= date('d/m/Y', strtotime($request['date_begin'])) ;?> al <?= date('d/m/Y', strtotime($request['date_end'])) ;?>
    </div>
    
    <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 90%; margin: 10px 0;">
      <tr>
        <td style="border: 1px solid #999; font-weight: bold; text-align: center;"><?= htmlentities('N° Póliza', ENT_QUOTES, 'UTF-8') ;?></td>
        <td style="border: 1px solid #999; font-weight: bold; text-align: center;"><?= htmlentities('Fecha emisión', ENT_QUOTES, 'UTF-8') ;?></td>
        <td style="border: 1px solid #999; font-weight: bold; text-align: center;">Fecha Inicio de Vigencia</td>
        <td style="border: 1px solid #999; font-weight: bold; text-align: center;"><?= htmlentities('Fecha Término de Vigencia', ENT_QUOTES, 'UTF-8') ;?></td>
        <td style="border: 1px solid #999; font-weight: bold; text-align: center;">Prima Total</td>
        <td style="border: 1px solid #999; font-weight: bold; text-align: center;">Capital Asegurado</td>
        <td style="border: 1px solid #999; font-weight: bold; text-align: center;"><?= htmlentities('Código Cliente', ENT_QUOTES, 'UTF-8') ;?></td>
        <td style="border: 1px solid #999; font-weight: bold; text-align: center;">Sucursal</td>
        <td style="border: 1px solid #999; font-weight: bold; text-align: center;"><?= htmlentities('N° Cuenta', ENT_QUOTES, 'UTF-8') ;?></td>
        <td style="border: 1px solid #999; font-weight: bold; text-align: center;">Agencia Banco</td>
        <td style="border: 1px solid #999; font-weight: bold; text-align: center;"><?= htmlentities('Producción', ENT_QUOTES, 'UTF-8') ;?></td>
        <td style="border: 1px solid #999; font-weight: bold; text-align: center;">Tipo</td>
        <td style="border: 1px solid #999; font-weight: bold; text-align: center;">Ap. Paterno Asegurado</td>
        <td style="border: 1px solid #999; font-weight: bold; text-align: center;">Ap. Materno Asegurado</td>
        <td style="border: 1px solid #999; font-weight: bold; text-align: center;">Nombre Asegurado</td>
        <td style="border: 1px solid #999; font-weight: bold; text-align: center;">C.I. Asegurado</td>
        <td style="border: 1px solid #999; font-weight: bold; text-align: center;">Pagador</td>
        <td style="border: 1px solid #999; font-weight: bold; text-align: center;">IVA</td>
        <td style="border: 1px solid #999; font-weight: bold; text-align: center;">IT</td>
        <td style="border: 1px solid #999; font-weight: bold; text-align: center;">CSPVS</td>
        <td style="border: 1px solid #999; font-weight: bold; text-align: center;">Prima Neta</td>
        <td style="border: 1px solid #999; font-weight: bold; text-align: center;">Prima Adicional</td>
        <td style="border: 1px solid #999; font-weight: bold; text-align: center;">Ramo</td>
        <td style="border: 1px solid #999; font-weight: bold; text-align: center;">Nombre de Emisor</td>
      </tr>
    <?php foreach ($data as $kd => $d): ?>
      <?php
          $sub_valor_asegurado  = 0;
          $sub_prima_neta       = 0;
          $sub_prima_adicional  = 0;
          $sub_iva              = 0;
          $sub_prima_total      = 0;
      ?>
      <?php foreach ($d['product'] as $kp => $product):
          $valor_asegurado  = 0;
          $prima_neta       = 0;
          $prima_adicional  = 0;
          $iva              = 0;
          $prima_total      = 0;
      ?>
        <?php if (count($product['data']) > 0): ?>
          <?php foreach ($product['data'] as $row):
              $prima_neta       += $row['prima_neta'];
              $prima_adicional  += $row['prima_adicional'];
              $iva              += $row['iva'];
              $prima_total      += $row['prima_total'];
          ?>
            <?php foreach ($row['collections'] as $kc => $collection): 
                $valor_asegurado  += $row['valor_asegurado'];
            ?>
              <tr>
                <td style="border: 1px solid #999; font-size: 80%; text-align: center;">
                  <?= $row['no_poliza'] ;?>
                </td>
                <td style="border: 1px solid #999; font-size: 80%; text-align: center;">
                  <?= date('d/m/Y', strtotime($row['fecha_emision'])) ;?>
                </td>
                <td style="border: 1px solid #999; font-size: 80%; text-align: center;">
                  <?= date('d/m/Y', strtotime($collection['ini_vigencia'])) ;?>
                </td>
                <td style="border: 1px solid #999; font-size: 80%; text-align: center;">
                  <?= date('d/m/Y', strtotime($collection['fin_vigencia'])) ;?>
                </td>
                <td style="border: 1px solid #999; font-size: 80%; text-align: center;">
                  <?= $collection['prima_total'] ;?>
                </td>
                <td style="border: 1px solid #999; font-size: 80%; text-align: center;">
                  <?= $row['valor_asegurado'] ;?>
                </td>
                <td style="border: 1px solid #999; font-size: 80%; text-align: center;">
                  <?= $row['codigo_bb'] ;?>
                </td>
                <td style="border: 1px solid #999; font-size: 80%; text-align: center;">
                  <?= $d['departamento'] ;?>
                </td>
                <td style="border: 1px solid #999; font-size: 80%; text-align: center;">
                  <?= $row['no_cuenta'] ;?>
                </td>
                <td style="border: 1px solid #999; font-size: 80%; text-align: center;">
                  <?= $row['agencia'] ;?>
                </td>
                <td style="border: 1px solid #999; font-size: 80%; text-align: center;">
                  <?= $product['title'] ;?>
                </td>
                <td style="border: 1px solid #999; font-size: 80%; text-align: center;">
                  <?= $row['type'] ;?>
                </td>
                <td style="border: 1px solid #999; font-size: 80%; text-align: center;">
                  <?= $row['cl_paterno'] ;?>
                </td>
                <td style="border: 1px solid #999; font-size: 80%; text-align: center;">
                  <?= $row['cl_materno'] ;?>
                </td>
                <td style="border: 1px solid #999; font-size: 80%; text-align: center;">
                  <?= $row['cl_nombre'] ;?>
                </td>
                <td style="border: 1px solid #999; font-size: 80%; text-align: center;">
                  <?= $row['ci'] . ' ' . $row['extension'] ;?>
                </td>
                <td style="border: 1px solid #999; font-size: 80%; text-align: center;">
                  <?= $row['pagador'] ;?>
                </td>
                <td style="border: 1px solid #999; font-size: 80%; text-align: center;">
                  <?= $collection['iva'] ;?>
                </td>
                <td style="border: 1px solid #999; font-size: 80%; text-align: center;">
                  <?= $collection['it'] ;?>
                </td>
                <td style="border: 1px solid #999; font-size: 80%; text-align: center;">
                  <?= $collection['pvs'] ;?>
                </td>
                <td style="border: 1px solid #999; font-size: 80%; text-align: center;">
                  <?= $collection['prima_neta'] ;?>
                </td>
                <td style="border: 1px solid #999; font-size: 80%; text-align: center;">
                  <?= $collection['prima_adicional'] ;?>
                </td>
                <td style="border: 1px solid #999; font-size: 80%; text-align: center;">
                  <?= $row['ramo'] ;?>
                </td>
                <td style="border: 1px solid #999; font-size: 80%; text-align: center;">
                  <?= $row['usuario'] ;?>
                </td>
              </tr>
            <?php endforeach ?>
          <?php endforeach ?>
        <?php endif ?>
        <?php
          $sub_valor_asegurado  += $valor_asegurado;
          $sub_prima_neta       += $prima_neta;
          $sub_prima_adicional  += $prima_adicional;
          $sub_iva              += $iva;
          $sub_prima_total      += $prima_total;
        ?>
      <?php endforeach ?>
    <?php endforeach ?>
    </table>


  </div>
</div>