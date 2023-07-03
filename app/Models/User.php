<?php

namespace App\Models;

class User
{
    private $conn;

    public function __construct()
    {
        $host = "";
        $dbName = "";
        $username = "";
        $password = "";

        $this->conn = new \mysqli($host, $username, $password, $dbName, 7999);

        if ($this->conn->connect_error) {
            die('Erro na conexão com o banco de dados: ' . $this->conn->connect_error);
        }
    }

    public function bouquets($username)
    {
        $sql_query = "SELECT * FROM users WHERE username = '$username'";
        $query = $this->conn->query($sql_query);
        $row = $query->fetch_assoc();
        $numeros = json_decode($row['bouquet']);
        $numeroProcurado = 6;
        if (in_array($numeroProcurado, $numeros)) {
            return "PACOTE COMPLETO COM ADULTOS";
        } else {
            return "PACOTE COMPLETE SEM ADULTOS";
        }
    }
    public function fillTable($username)
    {
        $sql_query = "SELECT * FROM users WHERE username = '$username'";
        $query = $this->conn->query($sql_query);
        $num_rows = $query->num_rows;

        if ($num_rows > 0) {
            $data = $query->fetch_assoc();
            $fillable = [
                'id' => $data['id'],
                'username' => $data['username'],
                'member_id' => $data['member_id'],
                'bouquet' => $data['bouquet'],
                'password' => $data['password'],
                'exp_date' => $data['exp_date'],
                'status' => $data['enabled'],
                'max_connections' => $data['max_connections'],
                'last_connection' => $data['last_activity'],
                'bouquets' => $this->bouquets($username)
            ];
            return $fillable;
        } else {
            return false;
        }
    }
    public function getUser()
    {
        $user_id = $_SESSION['user'];
        $sql_query = "SELECT * FROM users WHERE id = '$user_id'";
        $query = $this->conn->query($sql_query);
        $num_rows = $query->num_rows;
        if ($num_rows > 0) {
            $data = $query->fetch_assoc();
            $fillable = [
                'username' => $data['username'],
                'password' => $data['password'],
            ];
            return $fillable;
        } else {
            return false;
        }
    }


    public function userActivityNow()
    {
        $user_id = $_SESSION['user'];
        $sql_query = "SELECT * FROM user_activity_now WHERE user_id = '$user_id'";
        $query = $this->conn->query($sql_query);
        $num_rows = $query->num_rows;
        if ($num_rows > 0) {
            $data = $query->fetch_assoc();
            $id = $data['stream_id'];

            $sql_query = "SELECT * FROM streams WHERE id = '$id'";
            $query_stream = $this->conn->query($sql_query);
            $stream_date = $query_stream->fetch_assoc();
            return $stream_date['stream_display_name'];
        } else {
            return 0;
        }
    }
    public function updatePassword($password)
    {
        $user_id = $_SESSION['user'];
        $sql_query = "UPDATE users SET password = '$password' WHERE id = '$user_id'";
        $query = $this->conn->query($sql_query);
        if (mysqli_affected_rows($this->conn)) {
            return true;
        } else {
            return false;
        }
    }
}