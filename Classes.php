<?php

require_once "./BaseDatabaseClass.php";



class Incident extends BaseDatabaseClass
{
    public function __construct($db)
    {
        parent::__construct($db);
    }
    public function createIncident($incidentData)
    {
        // var_dump($incidentData);
        $incident = $this->simplified_db->insert("incidents", $incidentData);

        if ($incident) {
            return array("id" => $incident);
        } else {
            return array("message" => $this->simplified_db->getLastError());
        }
    }
}
