<?php

require_once(dirname(__FILE__) . '/config.php');

class Database
{

    public static function query($sql)
    {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DB);
        $res = $conn->query($sql);
        $conn->close();
        if ($res->num_rows == 0) {
            $answer = array('result' => 'error', "message" => "No existen contratos para esta matricula");
            echo json_encode($answer);
        } else {
            Database::formatResults($res);
        }

    }

    public static function querySave($sql)
    {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DB);
        $res = $conn->query($sql);
        $conn->close();
        if ($res->num_rows == 0) {
            $answer = array('result' => 'error', "message" => "Usuario ya existe");
            echo json_encode($answer);
        } else {
            $answer = array('result' => 'ok', "message" => "Usuario creado");
            echo json_encode($answer);
        }

    }

    public static function queryAddOrUpdate($sql)
    {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DB);
        $res = $conn->query($sql);
        $conn->close();
        if ($res->num_rows == 0) {
            return 1;
        } else {
            return 0;
        }

    }

    public static function queryCount($sql)
    {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DB);
        $res = $conn->query($sql);
        $conn->close();
        if ($res->num_rows == 0) {
            return 0;
        } else {
            return 1;
        }

    }

    public static function formatResults($res)
    {

//		var_dump($res);
        $results = array('result' => 'ok', 'service' => '');
        foreach ($res as $k => $r) {
            $results['service'] = $r;
        }
        $answer = $results;
//		var_dump($answer);
        echo json_encode($answer);
    }

}

//SEARCH by MATRICULA
if (isset($_GET['p'])) {
    $data = $_GET['p'];
    Database::query("SELECT * FROM users WHERE users.`matricula` LIKE '$data'");
}


//ADD NEW CLIENT
if (isset($_GET['save'])) {
    if (isset($_GET['token'])) {
        if ($_GET['token'] != TOKEN) {
            $answer = array('result' => 'error', "message" => "TOKEN ERRONEO");
            echo json_encode($answer);
        }
        if (isset($_GET['username'])) {
            $username = $_GET['username'];
        } else {
            $username = 'Name not provided';
        }

        if (isset($_GET['matricula'])) {
            $matricula = $_GET['matricula'];
        } else {
            $matricula = 'Name not provided';
        }

        if (isset($_GET['email'])) {
            $email = $_GET['email'];
        } else {
            $email = 'Email not provided';
        }

        if (isset($_GET['contrato'])) {
            $contrato = $_GET['contrato'];
        } else {
            $contrato = 'Contrato not provided';
        }

        if (Database::queryCount("SELECT * FROM users WHERE users.`matricula` LIKE '$data'") > 0) {
            $answer = array('result' => 'error', "message" => "Usuario ya existe");
            echo json_encode($answer);
        } else {
            Database::querySave("INSERT INTO users (username, email, matricula, contrato, telefono) VALUES ('$username','$email','$matricula','$contrato','$telefono')");
        }

    } else {
        $answer = array('result' => 'error', "message" => "Sin token no puedes escribir");
        echo json_encode($answer);
    }
}


//ADD NEW CLIENT
if (isset($_GET['update'])) {
    if (isset($_GET['token'])) {
        if ($_GET['token'] != TOKEN) {
            $answer = array('result' => 'error', "message" => "TOKEN ERRONEO");
            echo json_encode($answer);
        }
        if (isset($_GET['username'])) {
            $username = $_GET['username'];
        } else {
            $username = 'Name not provided';
        }

        if (isset($_GET['matricula'])) {
            $matricula = $_GET['matricula'];
        } else {
            $matricula = 'Name not provided';
        }

        if (isset($_GET['email'])) {
            $email = $_GET['email'];
        } else {
            $email = 'Email not provided';
        }

        if (isset($_GET['contrato'])) {
            $contrato = $_GET['contrato'];
        } else {
            $contrato = 'Contrato not provided';
        }

        if (Database::queryCount("SELECT * FROM users WHERE users.`matricula` LIKE '$data'") > 0) {
            $answer = array('result' => 'error', "message" => "Usuario ya existe");
            echo json_encode($answer);
        } else {
            Database::querySave("INSERT INTO users (matricula, username, email, contrato, telefono) VALUES ('$matricula','$username','$email','$contrato','$telefono') ON DUPLICATE KEY UPDATE
username='$username', email='$email', contrato='$contrato', telefono='$telefono'");
        }

    } else {
        $answer = array('result' => 'error', "message" => "Sin token no puedes escribir");
        echo json_encode($answer);
    }
}
?>