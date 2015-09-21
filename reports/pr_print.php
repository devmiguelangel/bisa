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
      REPORTE DE PRODUCCION
      <br>
      (Expresado en Dólares)
      <br>
      Del <?= date('d/m/Y', strtotime($request['date_begin'])) ;?> al <?= date('d/m/Y', strtotime($request['date_end'])) ;?>
    </div>
    
    <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 90%; margin: 10px 0;">
    <?php foreach ($data as $kd => $d): ?>
      <?php
          $sub_valor_asegurado  = 0;
          $sub_prima_neta       = 0;
          $sub_prima_adicional  = 0;
          $sub_iva              = 0;
          $sub_prima_total      = 0;
      ?>
      <tr>
        <td colspan="12" style="font-weight: bold; font-size: 110%;">
          Sucursal: <?= $d['departamento'] ;?>
        </td>
      </tr>
      <?php foreach ($d['product'] as $kp => $product):
          $valor_asegurado  = 0;
          $prima_neta       = 0;
          $prima_adicional  = 0;
          $iva              = 0;
          $prima_total      = 0;
      ?>
        <?php if (count($product['data']) > 0): $k = 0; ?>
            <tr>
              <td colspan="12" style="font-weight: bold; font-size: 110%;">
                Producto: PRIMA <?= $product['title'] ;?>
              </td>
            </tr>
            <tr>
              <td style="width: 3%; border: 1px solid #999; font-weight: bold; text-align: center;">N°</td>
              <td style="width: 8%; border: 1px solid #999; font-weight: bold; text-align: center;">N° Póliza</td>
              <td style="width: 7%; border: 1px solid #999; font-weight: bold; text-align: center;">Fecha emisión</td>
              <td style="width: 14%; border: 1px solid #999; font-weight: bold; text-align: center;">
                Vigencia
                <br>
                <table cellpadding="0" cellspacing="0" border="0" style="width: 100%;">
                  <tr>
                    <td style="width: 50%; border-top: 1px solid #999; border-right: 1px solid #999;">Desde</td>
                    <td style="width: 50%; border-top: 1px solid #999;">Hasta</td>
                  </tr>
                </table>
              </td>
              <td style="width: 7%; border: 1px solid #999; font-weight: bold; text-align: center;">Tipo</td>
              <td style="width: 8%; border: 1px solid #999; font-weight: bold; text-align: center;">Código Cliente</td>
              <td style="width: 15%; border: 1px solid #999; font-weight: bold; text-align: center;">Nombre Cliente</td>
              <td style="width: 10%; border: 1px solid #999; font-weight: bold; text-align: center;">Capital Asegurado</td>
              <td style="width: 7%; border: 1px solid #999; font-weight: bold; text-align: center;">Prima Neta</td>
              <td style="width: 8%; border: 1px solid #999; font-weight: bold; text-align: center;">Prima Adicional</td>
              <td style="width: 5%; border: 1px solid #999; font-weight: bold; text-align: center;">IVA</td>
              <td style="width: 8%; border: 1px solid #999; font-weight: bold; text-align: center;">Prima Total</td>
            </tr>
          <?php foreach ($product['data'] as $row):
              $prima_neta       += $row['prima_neta'];
              $prima_adicional  += $row['prima_adicional'];
              $iva              += $row['iva'];
              $prima_total      += $row['prima_total'];
          ?>
            <?php foreach ($row['collections'] as $kc => $collection): 
                $k++;
                $valor_asegurado  += $row['valor_asegurado'];
            ?>
              <tr>
                <td style="width: 3%; border: 1px solid #999; font-size: 80%; text-align: center;">
                  <?= $k ;?>
                </td>
                <td style="width: 8%; border: 1px solid #999; font-size: 80%; text-align: center;">
                  <?= $row['no_poliza'] ;?>
                </td>
                <td style="width: 7%; border: 1px solid #999; font-size: 80%; text-align: center;">
                  <?= date('d/m/Y', strtotime($row['fecha_emision'])) ;?>
                </td>
                <td style="width: 14%; border: 1px solid #999; font-size: 80%; text-align: center;">
                  <table cellpadding="0" cellspacing="0" border="0" style="width: 100%;">
                    <tr>
                      <td style="width: 50%; border-right: 1px solid #999; text-align: center;">
                        <?= date('d/m/Y', strtotime($collection['ini_vigencia'])) ;?>
                      </td>
                      <td style="width: 50%; text-align: center;">
                        <?= date('d/m/Y', strtotime($collection['fin_vigencia'])) ;?>
                      </td>
                    </tr>
                  </table>
                </td>
                <td style="width: 7%; border: 1px solid #999; font-size: 80%; text-align: center;">
                  <?= $row['type'] ;?>
                </td>
                <td style="width: 8%; border: 1px solid #999; font-size: 80%; text-align: center;">
                  <?= $row['codigo_bb'] ;?>
                </td>
                <td style="width: 15%; border: 1px solid #999; font-size: 80%; text-align: center;">
                  <?= $row['full_name'] ;?>
                </td>
                <td style="width: 10%; border: 1px solid #999; font-size: 80%; text-align: center;">
                  <?= $row['valor_asegurado'] ;?>
                </td>
                <td style="width: 7%; border: 1px solid #999; font-size: 80%; text-align: center;">
                  <?= $collection['prima_neta'] ;?>
                </td>
                <td style="width: 8%; border: 1px solid #999; font-size: 80%; text-align: center;">
                  <?= $collection['prima_adicional'] ;?>
                </td>
                <td style="width: 5%; border: 1px solid #999; font-size: 80%; text-align: center;">
                  <?= $collection['iva'] ;?>
                </td>
                <td style="width: 8%; border: 1px solid #999; font-size: 80%; text-align: center;">
                  <?= $collection['prima_total'] ;?>
                </td>
              </tr>
            <?php endforeach ?>
          <?php endforeach ?>
            <tr>
              <td colspan="6" style="font-weight: bold;">Totales por tipo de póliza PRIMA <?= $product['title'] ;?></td>
              <td style="width: 15%; font-size: 80%; font-weight: bold; text-align: center;">Cantidad: </td>
              <td style="width: 10%; border: 1px solid #999; font-size: 80%; font-weight: bold; text-align: center;">
                <?= $valor_asegurado ;?>
              </td>
              <td style="width: 7%; border: 1px solid #999; font-size: 80%; font-weight: bold; text-align: center;">
                <?= $prima_neta ;?>
              </td>
              <td style="width: 8%; border: 1px solid #999; font-size: 80%; font-weight: bold; text-align: center;">
                <?= $prima_adicional ;?>
              </td>
              <td style="width: 5%; border: 1px solid #999; font-size: 80%; font-weight: bold; text-align: center;">
                <?= $iva ;?>
              </td>
              <td style="width: 8%; border: 1px solid #999; font-size: 80%; font-weight: bold; text-align: center;">
                <?= $prima_total ;?>
              </td>
            </tr>
            <tr>
              <td colspan="12" style="width: 100%; height: 7px;"></td>
            </tr>
        <?php endif ?>
        <?php
          $sub_valor_asegurado  += $valor_asegurado;
          $sub_prima_neta       += $prima_neta;
          $sub_prima_adicional  += $prima_adicional;
          $sub_iva              += $iva;
          $sub_prima_total      += $prima_total;
        ?>
      <?php endforeach ?>
          <tr>
            <td colspan="6" style="font-weight: bold; text-align: right;">Total <?= $d['departamento'] ;?></td>
            <td style="width: 15%; font-size: 80%; font-weight: bold; text-align: center;">Cantidad: </td>
            <td style="width: 10%; border: 1px solid #999; font-size: 80%; font-weight: bold; text-align: center;">
              <?= $sub_valor_asegurado ;?>
            </td>
            <td style="width: 7%; border: 1px solid #999; font-size: 80%; font-weight: bold; text-align: center;">
              <?= $sub_prima_neta ;?>
            </td>
            <td style="width: 8%; border: 1px solid #999; font-size: 80%; font-weight: bold; text-align: center;">
              <?= $sub_prima_adicional ;?>
            </td>
            <td style="width: 5%; border: 1px solid #999; font-size: 80%; font-weight: bold; text-align: center;">
              <?= $sub_iva ;?>
            </td>
            <td style="width: 8%; border: 1px solid #999; font-size: 80%; font-weight: bold; text-align: center;">
              <?= $sub_prima_total ;?>
            </td>
          </tr>
    <?php endforeach ?>
    </table>


  </div>
</div>