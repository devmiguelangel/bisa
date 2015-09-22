<?php

require __DIR__ . '/../sibas-db.class.php';
require 'DataController.php';

class Report
{
    /**
     * @var MySQLi
     */
    private $cx;
    private $subsidiary;
    private $insurance;
    private $date_begin;
    private $date_end;
    private $category;
    private $type;

    private $controller;

    public function __construct(array $data)
    {
        $this->cx   = new SibasDB();
        $this->type = 0;

        $this->subsidiary = $this->cx->real_escape_string(trim($data['br-subsidiary']));
        $this->insurance  = $this->cx->real_escape_string(trim($data['br-insurance']));
        $this->date_begin = $this->cx->real_escape_string(trim($data['br-date-begin']));
        $this->date_end   = $this->cx->real_escape_string(trim($data['br-date-end']));
        $this->category   = $this->cx->real_escape_string(trim($data['br-category']));
        $this->type       = $this->cx->real_escape_string(trim($data['br-type']));

        extract(get_object_vars($this));
        $request = compact(
           'subsidiary', 'insurance', 'date_begin', 'date_end', 'category', 'type');
        
        $this->controller = new DataController($this->cx, $request);
    }

    public function getReport()
    {
        switch ($this->type) {
            case 'xls':
                header("Content-Type:   application/vnd.ms-excel; charset=iso-8859-1");
                header("Content-Disposition: attachment; filename=" . $this->controller->getTitle() . ".xls");
                header("Pragma: no-cache");
                header("Expires: 0");

                $this->getRecords();
                break;
            case 'pdf':
                set_time_limit(0);
                ob_start();
                $this->getRecords();
                $content = ob_get_clean();

                require_once(dirname(__FILE__) . '/../html2pdf/html2pdf.class.php');
                try {
                    $html2pdf = new HTML2PDF('P', 'Letter', 'en', true, 'UTF-8', 2);
                    $html2pdf->WriteHTML($content);
                    $html2pdf->Output('Reporte.pdf');
                } catch (HTML2PDF_exception $e) {
                    return false;
                }
                break;
            case 'print':
                $this->getRecords();
                break;
            default:
                exit();
                break;
        }
    }

    private function getRecords()
    {
        $result = $this->controller->getData();
        extract($result);

        switch ($this->category) {
        case 'P':
            switch ($this->type) {
            case 'xls':
                require_once 'pr_xls.php';
                break;
            default:
                require_once 'pr_print.php';
                break;
            }
            break;
        case 'C':
            switch ($this->type) {
                case 'xls':
                    require_once 'cr_xls.php';
                    break;
                default:
                    require_once 'cr_print.php';
                    break;
            }
            break;
        }
    }


}