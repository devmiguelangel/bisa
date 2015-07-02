<?php

function getSubAgencyUser($link, &$data_subsidiary, &$data_agency, &$data_user) {
	if (($rowUser = $link->verify_type_user($_SESSION['idUser'], $_SESSION['idEF'])) !== false) {
		$user = $rowUser['u_id'];
		$type = $rowUser['u_tipo_codigo'];

		switch ($type) {
		case 'LOG':
			$data_user[] = array(
				'id' 	=> $rowUser['u_id'], 
				'user' 	=> $rowUser['u_usuario'],
				'name' 	=> $rowUser['u_nombre'],
				);

			$data_subsidiary[] = array(
				'id'	=> base64_encode($rowUser['u_depto']),
				'depto'	=> $rowUser['u_nombre_depto'],
				);

			$data_agency[] = array(
				'id' 	 => base64_encode($rowUser['id_agencia']), 
				'agency' => $rowUser['agencia'], 
				);
			break;
		case 'PA':
			RepFull:
			$data_user[] = array(
				'id' 	=> '', 
				'user' 	=> '',
				'name' 	=> 'Todos',
				);

			if (is_null($rowUser['u_depto']) === true) {
				$data_subsidiary[] = array(
					'id'	=> '',
					'depto'	=> 'Todos',
					);

				if (($rsSb = $link->get_depto()) !== false) {
					while ($rowSb = $rsSb->fetch_array(MYSQLI_ASSOC)) {
						if ((boolean)$rowSb['tipo_dp'] === true) {
							$data_subsidiary[] = array(
								'id'	=> base64_encode($rowSb['id_depto']),
								'depto'	=> $rowSb['departamento'],
								);
						}
					}
				}

				if (($rsAgency = $link->getAgencySubsidiary(
					$_SESSION['idEF'], '')) !== false) {
					$data_agency[] = array(
							'id' 	 => '', 
							'agency' => 'Todos', 
							);
					while ($rowAgency = $rsAgency->fetch_array(MYSQLI_ASSOC)) {
						$data_agency[] = array(
							'id' 	 => base64_encode($rowAgency['id_agencia']), 
							'agency' => $rowAgency['agencia'], 
							);
					}
				}

				if (($rsUss = $link->getUserSubsidiary($_SESSION['idEF'], '')) !== false) {
					while ($rowUss = $rsUss->fetch_array(MYSQLI_ASSOC)) {
						$data_user[] = array(
							'id' 	=> $rowUss['id_usuario'], 
							'user' 	=> $rowUss['usuario'],
							'name' 	=> $rowUss['nombre'],
							);
					}
				}

			} else {
				$data_subsidiary[] = array(
					'id'	=> base64_encode($rowUser['u_depto']),
					'depto'	=> $rowUser['u_nombre_depto'],
					);

				if (is_null($rowUser['id_agencia']) === true) {
					if (($rsAgency = $link->getAgencySubsidiary(
						$_SESSION['idEF'], $rowUser['u_depto'])) !== false) {
						$data_agency[] = array(
								'id' 	 => '', 
								'agency' => 'Todos', 
								);
						while ($rowAgency = $rsAgency->fetch_array(MYSQLI_ASSOC)) {
							$data_agency[] = array(
								'id' 	 => base64_encode($rowAgency['id_agencia']), 
								'agency' => $rowAgency['agencia'], 
								);
						}
					}

					if (($rsUss = $link->getUserSubsidiary($_SESSION['idEF'], $rowUser['u_depto'])) !== false) {
						while ($rowUss = $rsUss->fetch_array(MYSQLI_ASSOC)) {
							$data_user[] = array(
								'id' 	=> $rowUss['id_usuario'], 
								'user' 	=> $rowUss['usuario'],
								'name' 	=> $rowUss['nombre'],
								);
						}
					}
				} else {
					$data_agency[] = array(
						'id' 	 => base64_encode($rowUser['id_agencia']), 
						'agency' => $rowUser['agencia'], 
						);

					if (($rsUss = $link->getUserSubsidiary(
						$_SESSION['idEF'], $rowUser['u_depto'], $rowUser['id_agencia'], $rowUser['u_id'])) !== false) {
						while ($rowUss = $rsUss->fetch_array(MYSQLI_ASSOC)) {
							$data_user[] = array(
								'id' 	=> $rowUss['id_usuario'], 
								'user' 	=> $rowUss['usuario'],
								'name' 	=> $rowUss['nombre'],
								);
						}
					}
				}
			}
			break;
		case 'REP':
			goto RepFull;
			break;
		case 'FAC':
			goto RepFull;
			break;
		}
	}
}

function getDeptoCodePolicy($link, $id_user) {
	$sql = 'select 
		su.id_usuario,
		sdep.id_depto 
	from 
		s_usuario as su
			inner join
		s_departamento as sdep ON (sdep.id_depto = su.id_depto)
	where
		su.id_usuario = "' . base64_decode($id_user) . '"
	limit 0, 1
	;';

	if (($rs = $link->query($sql, MYSQLI_STORE_RESULT)) !== false) {
		if ($rs->num_rows === 1) {
			$row = $rs->fetch_array(MYSQLI_ASSOC);
			$rs->free();

			switch ($row['id_depto']) {
			case 1:			// La Paz
				return 1;
				break;
			case 7:			// Santa Cruz
				return 2;
				break;
			case 4:			// Cochabamba
				return 3;
				break;
			case 5:			// Chuquisaca
				return 4;
				break;
			case 6:			// Tarija
				return 5;
				break;
			case 2:			// Oruro
				return 6;
				break;
			case 3:			// Potosi
				return 7;
				break;
			case 8:			// Beni
				return 8;
				break;
			case 9:			// Pando
				return 9;
				break;
			}
		}

		return 0;
	}
}

?>