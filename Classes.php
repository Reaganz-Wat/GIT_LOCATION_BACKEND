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

    public function login($email, $password)
    {
        // Hash the password using MD5
        $hashedPassword = md5($password);

        var_dump($email);

        // Add a where condition to check email and hashed password
        $this->simplified_db->where("email", $email);
        $this->simplified_db->where("password", $hashedPassword);

        // Get user data
        $user = $this->simplified_db->getOne("users", "email, username, contact");


        // Check if user exists and return response
        if ($user) {
            return json_encode(array(
                "user" => $user,
                "message" => "success",
                "isAuthenticated" => "authenticated"
            ));
        } else {
            return json_encode(array(
                "message" => "Invalid email or password", "error" => $email
            ));
        }
    }

    public function registeruser($userdata) {
        $user = $this->simplified_db->insert("users", $userdata);

        if ($user) {
            return json_encode(array("message" => "true"));
        } else {
            return json_encode(array("message" => "registration failed", "error" => $this->simplified_db->getLastError()));
        }
    }
}
