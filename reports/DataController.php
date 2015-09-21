<?php

require 'DataRepository.php';

class DataController extends DataRepository
{
    private $data;
    private $title;

    public function __construct($cx, $request)
    {
        $this->cx = $cx;
        $this->request = $request;

        $this->data = array(
            'data' => array(),
            'request' => $request,
            'current_date' => strtotime(date('Y-m-d'))
        );
    }

    public function getData()
    {
        switch ($this->request['category']) {
            case 'P':
                $this->title = 'REPORTE-PRODUCION';
                $this->data['data'] = $this->getProductRecords();

                return $this->data;
                break;
            case 'C':
                $this->title = 'REPORTE-COBRANZA';
                # code...
                break;
        }
    }

    public function getTitle()
    {
        switch ($this->request['category']) {
            case 'P':
                $this->title = 'REPORTE-PRODUCION';
                break;
            case 'C':
                $this->title = 'REPORTE-COBRANZA';
                break;
        }

        return $this->title;
    }

}


?>