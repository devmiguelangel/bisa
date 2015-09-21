<?php

require 'reports/Report.php';

if ($_REQUEST) {
    $data = $_REQUEST;

    $report = new Report($data);

    $report->getReport();
}

?>