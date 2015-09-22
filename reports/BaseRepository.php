<?php


abstract class BaseRepository
{
    /**
     * @var MySQLi
     */
    protected $cx;

    protected $request;

    protected $subsidiaries;

    protected $records;

    protected $collections;

    protected $products = array(
        'AU' => 'au',
        'TRD' => 'trd'
    );

    protected function getSubsidiaries()
    {
        $this->subsidiaries = array();
        $sub = $this->request['subsidiary'];

        if (empty($subsidiary)) {
            $sub = '%' . $this->request['subsidiary'] . '%';
        }

        $sql = "SELECT
            sdep.id_depto,
            sdep.departamento,
            sdep.codigo,
            sdep.codigo_ws
        FROM
            s_departamento AS sdep
        WHERE
            sdep.id_depto LIKE '" . $sub . "'
              AND sdep.tipo_dp = TRUE
        ORDER BY sdep.id_depto
        ;";

        if (($rs = $this->cx->query($sql, MYSQLI_STORE_RESULT)) !== false) {
            if ($rs->num_rows > 0) {
                $this->subsidiaries = $rs->fetch_all(MYSQLI_ASSOC);
            }
        }
    }

    protected function getPRRecords($subsidiary, $product)
    {
        $this->records = array();

        $sql = "SELECT
            sae.id_emision,
            sae.no_poliza,
            sae.fecha_emision,
            sae.garantia,
            scl.codigo_bb,
            scl.nombre AS cl_nombre,
            scl.paterno AS cl_paterno,
            scl.materno AS cl_materno,
            scl.ci,
            sext.codigo AS extension,
            sad.valor_asegurado,
            sae.cuenta,
            sdep.departamento,
            sag.agencia,
            su.nombre AS usuario,
            COUNT(sac.numero_cuota) AS num_cuota,
            SUM(IF(sac.cobrado = TRUE
                    AND sae.anulado = FALSE,
                1,
                0)) AS producto
        FROM
            s_" . $this->products[$product] . "_em_cabecera AS sae
                INNER JOIN
            s_" . $this->products[$product] . "_em_detalle AS sad ON (sad.id_emision = sae.id_emision)
                INNER JOIN
            s_cliente AS scl ON (scl.id_cliente = sae.id_cliente)
                LEFT JOIN
            s_departamento AS sext ON (scl.extension = sext.id_depto)
                INNER JOIN
            s_usuario AS su ON (su.id_usuario = sae.id_usuario)
                INNER JOIN
            s_departamento AS sdep ON (sdep.id_depto = su.id_depto)
                INNER JOIN
            s_agencia AS sag ON (sag.id_agencia = su.id_agencia)
                INNER JOIN
            s_" . $this->products[$product] . "_cobranza AS sac ON (sac.id_emision = sae.id_emision)
        WHERE
            sdep.id_depto = '" . $subsidiary . "'
                AND sac.fecha_cuota BETWEEN '" . $this->request['date_begin'] . "'
                    AND '" . $this->request['date_end'] . "'
                AND cobrado = TRUE
        GROUP BY sae.id_emision
        ORDER BY sae.id_emision ASC
        ;";

        if (($rs = $this->cx->query($sql, MYSQLI_STORE_RESULT)) !== false) {
            if ($rs ->num_rows > 0) {
                $this->records = $rs->fetch_all(MYSQLI_ASSOC);
            }
        }
    }

    protected function getCollections($record, $product)
    {
        $this->collections = array();

        $sql = "SELECT
            sac.id,
            sac.fecha_cuota AS ini_vigencia,
            (CASE sae.forma_pago
                WHEN
                    'CO'
                THEN
                    DATE_SUB(DATE_ADD(sac.fecha_cuota,
                            INTERVAL 1 YEAR),
                        INTERVAL 1 DAY)
                WHEN
                    'CR'
                THEN
                    DATE_SUB(DATE_ADD(sac.fecha_cuota,
                            INTERVAL 1 MONTH),
                        INTERVAL 1 DAY)
            END) AS fin_vigencia,
            sac.monto_cuota AS prima_total,
            (sac.monto_cuota / 1.2675) AS prima_neta,
            (sac.monto_cuota * 0.081) AS prima_adicional,
            (sac.monto_cuota * 0.13) AS iva,
            (sac.monto_cuota * 0.03) AS it,
            (sac.monto_cuota * 0.0155) AS pvs
        FROM
            s_" . $this->products[$product] . "_em_cabecera AS sae
                INNER JOIN
            s_" . $this->products[$product] . "_cobranza AS sac ON (sac.id_emision = sae.id_emision)
        WHERE
            sae.id_emision = '" . $record['id_emision'] . "'
                AND sac.cobrado = TRUE
                AND sac.fecha_cuota BETWEEN '" . $this->request['date_begin'] . "'
                    AND '" . $this->request['date_end'] . "'
        ORDER BY sac.id ASC
        ;";

        if (($rs = $this->cx->query($sql, MYSQLI_STORE_RESULT)) != false) {
            if ($rs->num_rows > 0) {
                $this->collections = $rs->fetch_all(MYSQLI_ASSOC);
            }
        }
    }

    protected function getCRRecords($subsidiary, $product)
    {
        $this->records = array();

        $sql = "SELECT
            sae.id_emision,
            sae.no_poliza,
            sae.fecha_emision,
            sae.garantia,
            scl.codigo_bb,
            scl.nombre AS cl_nombre,
            scl.paterno AS cl_paterno,
            scl.materno AS cl_materno,
            scl.ci,
            scl.extension,
            sad.valor_asegurado,
            sae.cuenta,
            sdep.departamento,
            sag.agencia,
            su.nombre AS usuario,
            sac.numero_cuota,
            sac.fecha_transaccion,
            sac.fecha_cuota AS ini_vigencia,
            (CASE sae.forma_pago
                WHEN
                    'CO'
                THEN
                    DATE_SUB(DATE_ADD(sac.fecha_cuota,
                            INTERVAL 1 YEAR),
                        INTERVAL 1 DAY)
                WHEN
                    'CR'
                THEN
                    DATE_SUB(DATE_ADD(sac.fecha_cuota,
                            INTERVAL 1 MONTH),
                        INTERVAL 1 DAY)
            END) AS fin_vigencia,
            sac.monto_cuota AS prima_total,
            (sac.monto_cuota / 1.2675) AS prima_neta
        FROM
            s_" . $this->products[$product] . "_em_cabecera AS sae
                INNER JOIN
            s_" . $this->products[$product] . "_em_detalle AS sad ON (sad.id_emision = sae.id_emision)
                INNER JOIN
            s_cliente AS scl ON (scl.id_cliente = sae.id_cliente)
                INNER JOIN
            s_usuario AS su ON (su.id_usuario = sae.id_usuario)
                INNER JOIN
            s_departamento AS sdep ON (sdep.id_depto = su.id_depto)
                INNER JOIN
            s_agencia AS sag ON (sag.id_agencia = su.id_agencia)
                INNER JOIN
            s_" . $this->products[$product] . "_cobranza AS sac ON (sac.id_emision = sae.id_emision)
        WHERE
            sdep.id_depto LIKE '" . $subsidiary . "'
                AND sac.fecha_cuota BETWEEN '" . $this->request['date_begin'] . "'
                    AND '" . $this->request['date_end'] . "'
                AND cobrado = TRUE
                AND sae.anulado = FALSE
        ORDER BY sae.id_emision ASC , sac.numero_cuota ASC
        ;";

        if (($rs = $this->cx->query($sql, MYSQLI_STORE_RESULT)) !== false) {
            if ($rs->num_rows > 0) {
                $this->records = $rs->fetch_all(MYSQLI_ASSOC);
            }
        }
    }

}