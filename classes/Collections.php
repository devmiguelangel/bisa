<?php

class Collection
{
	private
		$cx,
		$row,
		$pr,
		$fecha_emision,
		$payment 		= '',
		$period 		= '',
		$prima			= 0,
		$depreciation	= 10,
		$no_transaction = 0,
		$fecha_trans 	= '',
		$monto_trans 	= 0,
		$cashed 		= 0,
		$ws_token		= false
		;

	public
		$mess = '';

	public function __construct($cx, $row, $pr)
	{
		$this->cx 	= $cx;
		$this->row 	= $row;
		$this->pr	= strtolower($pr);
		$this->depreciation = $this->depreciation / 100;
	}

	public function putPolicy($ide, $idef)
	{	
		$this->fecha_emision = date('Y-m-d');

		$sql = 'update 
		s_' . $this->pr . '_em_cabecera as sae
			inner join 
		s_entidad_financiera as sef ON (sef.id_ef = sae.id_ef)
		set 
			sae.emitir = true, 
			sae.fecha_emision = "' . $this->fecha_emision . '",
			sae.estado = "V", 
			sae.leido = false
		where 
			sae.id_emision = "' . $ide . '" 
				and sef.id_ef = "' . base64_decode($idef) . '"
				and sef.activado = true 
		;';

		if ($this->cx->query($sql)) {
			if ($this->postCollection($ide)) {
				return true;
			} else {
				$this->mess = 'Error. No se pudo registrar las cuotas .';
			}
		} else {
			$this->mess = 'La Póliza no pudo ser Emitida';
		}

		return false;
	}

	private function postCollection($ide)
	{
		$days_linkup 		= 0;			// Dias transcurridos hasta la vinculacion
		$prima_linkup 		= 0;			// Prima transcurrido hasta la vinculacion
		$diff_rounding 		= 0;			// Cobro pro diferencia de redondeo
		$prima_month	 	= 0;			// Prima Mensual
		$prima_year			= 0;			// Prima Anual
		$value_insured		= 0;			// Valor Asegurado

		switch ($this->row['forma_pago']) {
		case 'CO':
			$this->period = 'Y';
			break;
		case 'CR':
			$this->period = 'M';
			break;
		}

		$queryset = 'insert into s_' . $this->pr . '_cobranza
		(id, id_emision, numero_cuota, fecha_cuota, 
			monto_cuota, numero_transaccion, fecha_transaccion, 
			monto_transaccion, cobrado)
		values
		';

		$nc = 0;
		$this->prima = 0;
		$prima_year = $this->row['prima_total'];
		switch ($this->period) {
		case 'M':
			$nc = 12;
			$this->prima = $this->row['prima_total'] / $nc;
			$prima_month = round($this->prima, 2);
			// $prima_month = round(($this->row['prima_total'] / 12), 2);
			break;
		case 'Y':
			$nc = 1;
			$this->prima = $this->row['prima_total'];
			break;
		}
		
		if ((boolean)$this->row['garantia']) {
			if ($this->row['ws_db']) {
				$this->ws_token = true;
				
				$nc = count($this->row['data']);
			}

			$datetime1 = new DateTime($this->row['fecha_creacion']);
			$datetime2 = new DateTime($this->fecha_emision);
			$interval = $datetime1->diff($datetime2);
			$days_linkup = $interval->d;

			$prima_linkup 	= ($prima_month / 30) * $days_linkup;
			$diff_rounding 	= ($prima_month * 12) - $prima_year;
			$value_insured 	= $this->row['valor_asegurado'];
		}

		$idc = date('U');

		$fecha 			= $this->fecha_emision;
		$fecha_cuota 	= strtotime('+0 day', strtotime($fecha));
		$fecha_cuota 	= date('Y-m-d', $fecha_cuota);
		
		$count_nc = 0;

		for ($i = 1; $i <= $nc ; $i++) {
			$idc += $i;
			$count_nc += 1;

			if ($i !== 1) {
				$this->no_transaction	= 0;
				$this->fecha_trans 		= '';
				$this->monto_trans 		= 0;
				$this->cashed 			= 0;
			}

			if ((boolean)$this->row['garantia']) {
				if ($this->ws_token) {
					$fecha_cuota = $this->row['data'][$i - 1]['fecVctoCuota'];
				}

				if ($i === 1) {
					$this->prima = $prima_month + $diff_rounding + $prima_linkup;
				} else {
					if ($count_nc > 12) {
						$value_insured 	= $value_insured - ($this->row['valor_asegurado'] * $this->depreciation);
						$prima_year 	= $value_insured * $this->row['tasa'];
						$prima_month	= round(($prima_year / 12), 2);
						$diff_rounding 	= ($prima_month * 12) - $prima_year;

						$this->prima	= $prima_month + $diff_rounding;
						
						$count_nc = 1;
					} elseif ($count_nc <= 12) {
						$this->prima = $prima_month;
					}
				}
			} else {
				$this->prima = $prima_month;

				if (($this->period === 'M' && $i === 1) && (($prima_month * 12) < $prima_year)) {
					$this->prima += $prima_year - ($prima_month * 12);
				}
			}

			$queryset .= '
			("' . $idc . '", "' . $ide . '", "' . $i . '", 
				"' . $fecha_cuota . '", 
				"' . $this->prima . '", 
				"' . $this->no_transaction . '", 
				"' . $this->fecha_trans . '" , 
				"' . $this->monto_trans . '", 
				"' . $this->cashed . '"),';
			
			$fecha	= strtotime($fecha_cuota);
			$month 	= date('n', $fecha);
			$year 	= date('Y', $fecha);
			$days 	= cal_days_in_month(CAL_GREGORIAN, $month, $year);

			$fecha_cuota = strtotime('+' . $days . ' day', $fecha);
			$fecha_cuota = date('Y-m-d', $fecha_cuota);
		}

		$queryset = trim($queryset, ',') . ';';

		if ($this->cx->query($queryset)) {
			return true;
		}

		return false;
	}

}

?>