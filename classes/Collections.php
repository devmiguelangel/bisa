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
		$no_transaction = 0,
		$fecha_trans 	= '',
		$monto_trans 	= 0,
		$cashed 		= 0
		;

	public
		$mess = '';

	public function __construct($cx, $row, $pr)
	{
		$this->cx 	= $cx;
		$this->row 	= $row;
		$this->pr	= strtolower($pr);
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
		switch ($this->period) {
		case 'M':
			$nc = 12;
			$this->prima = $this->row['prima_total'] / $nc;
			break;
		case 'Y':
			$nc = 1;
			$this->prima = $this->row['prima_total'];
			break;
		}

		$idc = date('U');

		$fecha 			= $this->fecha_emision;
		$fecha_cuota 	= strtotime('+0 day', strtotime($fecha));
		$fecha_cuota 	= date('Y-m-d', $fecha_cuota);
		
		for ($i = 1; $i <= $nc ; $i++) {
			$idc += $i;

			if ($i !== 1) {
				$this->no_transaction	= 0;
				$this->fecha_trans 		= '';
				$this->monto_trans 		= 0;
				$this->cashed 			= 0;
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