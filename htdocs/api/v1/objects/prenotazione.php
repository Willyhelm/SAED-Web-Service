<?php
class Prenotazione
{
    private $conn;
    private $table_name = 'prenotazione';
    // proprietà di una prenotazione
    public $id;
    public $email_cliente;
    public $email_avvocato;
    public $data;
    public $descrizione;
    // costruttore
    public function __construct($db)
    {
        $this->conn = $db;
    }
    // READ PRENOTAZIONE
    public function read()
    {
        // select all
        $query = 'SELECT `id`, `email_avvocato`, `email_cliente`, DATE_FORMAT(`data`,' . "'%d/%m/%Y'" . ') `data`, `descrizione` FROM ' . $this->table_name . ' ORDER BY `data` DESC;';
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        return $stmt;
    }

    public function read_email_cliente($email_cliente)
    {
        $query = 'SELECT `id`, `email_avvocato`, `email_cliente`, DATE_FORMAT(`data`,' . "'%d/%m/%Y'" . ') `data`, `descrizione` FROM ' . $this->table_name . ' p WHERE p.email_cliente = :email_cliente ORDER BY `data` DESC;';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email_cliente", $email_cliente, PDO::PARAM_STR);
        // execute query
        $stmt->execute();
        return $stmt;
    }

    public function read_email_avvocato($email_avvocato)
    {
        $query = 'SELECT `id`, `email_avvocato`, `email_cliente`, DATE_FORMAT(`data`,' . "'%d/%m/%Y'" . ') `data`, `descrizione` FROM ' . $this->table_name . ' p WHERE p.email_avvocato = :email_avvocato ORDER BY `data` DESC;';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email_avvocato", $email_avvocato, PDO::PARAM_STR);
        // execute query
        $stmt->execute();
        return $stmt;
    }

    public function read_id($id)
    {
        $query = 'SELECT `id`, `email_avvocato`, `email_cliente`, DATE_FORMAT(`data`,' . "'%d/%m/%Y'" . ') `data`, `descrizione` FROM ' . $this->table_name . ' p WHERE p.id = :id ORDER BY `data` DESC;';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        // execute query
        $stmt->execute();
        return $stmt;
    }
    // CREARE PRENOTAZIONE
    public function create()
    {
        $query = 'INSERT INTO ' . $this->table_name . ' (`email_cliente`, `email_avvocato`, `data`, `descrizione`) VALUES (:email_cliente, :email_avvocato, :data, :descrizione);';
        $stmt = $this->conn->prepare($query);
        // disinfetta le variabili
        $this->email_cliente = htmlspecialchars(strip_tags($this->email_cliente));
        $this->email_avvocato = htmlspecialchars(strip_tags($this->email_avvocato));
        $this->data = htmlspecialchars(strip_tags($this->data));
        $this->descrizione = htmlspecialchars(strip_tags($this->descrizione));
        // binding parametri
        $stmt->bindParam(":email_cliente", $this->email_cliente, PDO::PARAM_STR);
        $stmt->bindParam(":email_avvocato", $this->email_avvocato, PDO::PARAM_STR);
        $stmt->bindParam(":data", $this->data, PDO::PARAM_STR);
        $stmt->bindParam(":descrizione", $this->descrizione, PDO::PARAM_STR);
        // execute query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    // AGGIORNARE PRENOTAZIONE
    // CANCELLARE PRENOTAZIONE
    // delete the product
    public function delete($id)
    {

        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE `id` = :id";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // bind id of record to delete
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        // execute query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>