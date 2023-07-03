<?php


namespace App\Models;

class Admin
{

    private $conn;

    public function __construct()
    {
        $host = "";
        $dbName = "";
        $username = "";
        $password = "";

        $this->conn = new \mysqli($host, $username, $password, $dbName);

        if ($this->conn->connect_error) {
            die('Erro na conexão com o banco de dados: ' . $this->conn->connect_error);
        }
    }

    public function getInfor()
    {
        $sql_query = "SELECT * FROM information";
        $query = $this->conn->query($sql_query);
        $num_rows = $query->num_rows;
        if ($num_rows > 0) {
            $data = $query->fetch_assoc();
            $fillable = [
                'title' => $data['title'],
                'content' => $data['content'],
            ];
            return $fillable;
        } else {
            return false;
        }
    }
    public function getValuesFromCheckout()
    {
        $sql_query = "SELECT * FROM checkout";
        $query = $this->conn->query($sql_query);
        $data = $query->fetch_assoc();
        $fillable = [
            'price' => $data['price'],
            'day' => $data['day'],
        ];
        return $fillable;
    }
}