<div style="width: auto; height: auto; padding: 5px; border: 0px solid #999; font-size: 80%;">
  <div style="width: auto;">
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
      <tr>
        <td style="border: 1px solid #999; font-weight: bold; text-align: center;"><?= htmlentities('N° Póliza', ENT_QUOTES, 'UTF-8') ;?></td>
        <td style="border: 1px solid #999; font-weight: bold; text-align: center;">Fecha</td>
        <td style="border: 1px solid #999; font-weight: bold; text-align: center;">Fecha Inicio de Vigencia</td>
        <td style="border: 1px solid #999; font-weight: bold; text-align: center;"><?= htmlentities('Fecha Término de Vigencia', ENT_QUOTES, 'UTF-8') ;?></td>
        <td style="border: 1px solid #999; font-weight: bold; text-align: center;">Prima Total</td>
        <td style="border: 1px solid #999; font-weight: bold; text-align: center;">Capital Asegurado</td>
        <td style="border: 1px solid #999; font-weight: bold; text-align: center;"><?= htmlentities('Código Cliente', ENT_QUOTES, 'UTF-8') ;?></td>
        <td style="border: 1px solid #999; font-weight: bold; text-align: center;">Sucursal</td>
        <td style="border: 1px solid #999; font-weight: bold; text-align: center;">Ap. Paterno Asegurado</td>
        <td style="border: 1px solid #999; font-weight: bold; text-align: center;">Ap. Materno Asegurado</td>
        <td style="border: 1px solid #999; font-weight: bold; text-align: center;">Nombre Asegurado</td>
        <td style="border: 1px solid #999; font-weight: bold; text-align: center;">C.I. Asegurado</td>
        <td style="border: 1px solid #999; font-weight: bold; text-align: center;">Prima Neta</td>
        <td style="border: 1px solid #999; font-weight: bold; text-align: center;"><?= htmlentities('Cobro por Comisión', ENT_QUOTES, 'UTF-8') ;?></td>
      </tr>
    <?php foreach ($data as $kd => $d): ?>
      <?php foreach ($d['data'] as $row): ?>
        <tr>
          <td style="border: 1px solid #999; font-size: 80%; text-align: center;">
            <?= $row['no_poliza'] ;?>
          </td>
          <td style="border: 1px solid #999; font-size: 80%; text-align: center;">
            <?= date('d/m/Y', strtotime($row['fecha_transaccion'])) ;?>
          </td>
          <td style="border: 1px solid #999; font-size: 80%; text-align: center;">
            <?= date('d/m/Y', strtotime($row['ini_vigencia'])) ;?>
          </td>
          <td style="border: 1px solid #999; font-size: 80%; text-align: center;">
            <?= date('d/m/Y', strtotime($row['fin_vigencia'])) ;?>
          </td>
          <td style="border: 1px solid #999; font-size: 80%; text-align: center;">
            <?= $row['prima_total'] ;?>
          </td>
          <td style="border: 1px solid #999; font-size: 80%; text-align: center;">
            <?= $row['valor_asegurado'] ;?>
          </td>
          <td style="border: 1px solid #999; font-size: 80%; text-align: center;">
            <?= $row['codigo_bb'] ;?>
          </td>
          <td style="border: 1px solid #999; font-size: 80%; text-align: center;">
            <?= htmlentities($row['departamento'], ENT_QUOTES, 'UTF-8') ;?>
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
            <?= $row['ci'] ;?>
          </td>
          <td style="border: 1px solid #999; font-size: 80%; text-align: center;">
            <?= $row['prima_neta'] ;?>
          </td>
          <td style="border: 1px solid #999; font-size: 80%; text-align: center;">
            <?= $row['commission'] ;?>
          </td>
        </tr>
      <?php endforeach ?>
    <?php endforeach ?>
    </table>


  </div>
</div>