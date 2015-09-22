<?php

require 'BaseRepository.php';

class DataRepository extends BaseRepository
{

    protected function getProductRecords()
    {
        $this->getSubsidiaries();

        foreach ($this->subsidiaries as $key => &$subsidiary) {

            $subsidiary['product'] = array(
                'new'       => array(
                    'title' => 'NUEVA',
                    'data'  => array()
                ),
                'renovated' => array(
                    'title' => 'RENOVADA',
                    'data'  => array()
                ),
                'canceled'  => array(
                    'title' => 'ANULADA',
                    'data'  => array()
                )
            );

            $nReg = 0;

            foreach ($this->products as $kp => $product) {
                $this->getPRRecords($subsidiary['id_depto'], $kp);

                $nReg += count($this->records);

                foreach ($this->records as &$record) {
                    $record['num_cuota']    = (int)$record['num_cuota'];
                    $record['producto']     = (int)$record['producto'];

                    $account                = json_decode($record['cuenta']);
                    $record['no_cuenta']    = '';

                    if (is_object($account)) {
                        $record['no_cuenta'] = $account->numero;
                    } else {
                        $record['no_cuenta'] = $record['cuenta'];
                    }

                    $record['pagador']      = 'BANCO BISA .S.A';
                    $record['ramo']         = 'TARJETA DEBITO';

                    $record['type'] = '';
                    switch ($record['garantia']) {
                        case 0:
                            $record['type'] = 'No Subrogado';
                            break;
                        case 1:
                            $record['type'] = 'Subrogado';
                            break;
                    }

                    $record['full_name'] =
                        $record['cl_nombre'] . ' ' .
                        $record['cl_paterno'] . ' ' .
                        $record['cl_materno'];

                    $this->getCollections($record, $kp);

                    $this->mapCollections($record);

                    $record['collections']  = $this->collections;

                    if ($record['producto'] === 0) {
                        $record['valor_asegurado'] = $record['valor_asegurado'] * -1;

                        array_push($subsidiary['product']['canceled']['data'], $record);
                    } elseif ($record['num_cuota'] === 1) {
                        array_push($subsidiary['product']['new']['data'], $record);
                    } elseif ($record['num_cuota'] > 1) {
                        array_push($subsidiary['product']['renovated']['data'], $record);
                    }
                }
            }

            if ($nReg === 0) {
                unset($this->subsidiaries[$key]);
            }
        }

        return $this->subsidiaries;
    }

    protected function getCollectionRecords()
    {
        $this->getSubsidiaries();

        foreach ($this->subsidiaries as $key => &$subsidiary) {
            $subsidiary['data'] = array();

            $nReg = 0;

            foreach ($this->products as $kp => $product) {
                $this->getCRRecords($subsidiary['id_depto'], $kp);

                $nReg += count($this->records);

                foreach ($this->records as &$record) {
                    $record['commission'] = 0;
                    switch ($kp) {
                        case 'AU':
                            $record['commission'] = $record['prima_neta'] * 0.12;
                            break;
                        case 'TRD':
                            $record['commission'] = $record['prima_neta'] * 0.15;
                            break;
                    }

                    $this->mapRecords($record);

                    array_push($subsidiary['data'], $record);
                }
            }

            if ($nReg === 0) {
                unset($this->subsidiaries[$key]);
            }
        }

        return $this->subsidiaries;
    }

    private function mapCollections(&$record) {
        $record['prima_total']      = 0;
        $record['prima_neta']       = 0;
        $record['prima_adicional']  = 0;
        $record['iva']              = 0;
        $record['it']               = 0;
        $record['pvs']              = 0;

        $record['valor_asegurado']  = round($record['valor_asegurado'], 2);

        foreach ($this->collections as &$collection) {
            $collection['prima_total']      = round($collection['prima_total'], 2);
            $collection['prima_neta']       = round($collection['prima_neta'], 2);
            $collection['prima_adicional']  = round($collection['prima_adicional'], 2);
            $collection['iva']              = round($collection['iva'], 2);
            $collection['it']               = round($collection['it'], 2);
            $collection['pvs']              = round($collection['pvs'], 2);

            if ($record['producto'] === 0) {
                $collection['prima_total']      = $collection['prima_total'] * -1;
                $collection['prima_neta']       = $collection['prima_neta'] * -1;
                $collection['prima_adicional']  = $collection['prima_adicional'] * -1;
                $collection['iva']              = $collection['iva'] * -1;
                $collection['it']               = $collection['it'] * -1;
                $collection['pvs']              = $collection['pvs'] * -1;
            }

            $record['prima_total']      += $collection['prima_total'];
            $record['prima_neta']       += $collection['prima_neta'];
            $record['prima_adicional']  += $collection['prima_adicional'];
            $record['iva']              += $collection['iva'];
            $record['it']               += $collection['it'];
            $record['pvs']              += $collection['pvs'];
        }
    }

    private function mapRecords(&$record)
    {
        $record['prima_total']  = round($record['prima_total'], 2);
        $record['prima_neta']   = round($record['prima_neta'], 2);
        $record['commission']   = round($record['commission'], 2);

        $record['full_name'] =
            $record['cl_nombre'] . ' ' .
            $record['cl_paterno'] . ' ' .
            $record['cl_materno'];
    }
}

?>