<?php

/**
 * Handle MySQL Connection with PDO.
 * Class DB
 */
class DB
{
    private string $server = 'localhost';
    private string $db = 'exo194';
    private string $user = 'dev';
    private string $pwd = 'dev';

    private static PDO $dbInstance;

    /**
     * DbStatic constructor.
     */
    public function __construct() {
        try {
            self::$dbInstance = new PDO("mysql:host=$this->server;dbname=$this->db;charset=utf8", $this->user, $this->pwd);
            self::$dbInstance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$dbInstance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
        catch(PDOException $exception) {
            echo $exception->getMessage();
        }
    }

    /**
     * Return PDO instance.
     */
    public static function getInstance(): ?PDO {
        if( is_null(self::$dbInstance) ) {
            new self();
        }
        return self::$dbInstance;
    }

    /**
     * Avoid instance to be cloned.
     */
    public function __clone() {}

    /**
     * delete the last line of table user
     */
    public function delLastID(){

        $bdd = self::getInstance();
        $stat = $bdd->prepare("SELECT MAX(id) FROM user");
        $state = $stat->execute();
        if ($state) {
            $result = $stat->fetch();            ;
            $sql = "DELETE FROM user WHERE id = ".$result['MAX(id)'];
            if ($bdd->exec($sql) !== false){
                echo "ligne avec id ".$result['MAX(id)']." est supprimé";
            }
        }
    }

    public function truncate(){
        $bdd = self::getInstance();
        $stat = $bdd->prepare("TRUNCATE TABLE user");
        $state = $stat->execute();
        if ($state) {
            echo "truncate ok";
        }
    }

    public function requestUser($nom,$prenom,$rue,$numero,$cp,$ville,$pays,$mail){
        $request = self::$dbInstance->prepare("
        INSERT INTO exo194.user (nom,prenom,rue,numero,code_postal,ville,pays,mail)
        VALUES (:nom,:prenom,:rue,:numero,:code_postal,:ville,:pays,:mail)                        
    ");


        $request->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':rue' => $rue,
            ':numero' => $numero,
            ':code_postal' => $cp,
            ':ville' => $ville,
            ':pays' => $pays,
            ':mail' => $mail,
        ]);
    }

    public function modData($table, $column , $value, $id){
        $request = self::$dbInstance->prepare("
        UPDATE exo194.$table  SET $column = '$value' WHERE id = $id
    ");
        $request->execute();
    }

    public function delTable($table){

        $bdd = self::getInstance();
        $stat = $bdd->prepare("DROP TABLE $table");
        $state = $stat->execute();
        if ($state) {
            echo "drop table de $table ok";
        }
    }

    public function delBDD($data){

        $bdd = self::getInstance();
        $stat = $bdd->prepare("DROP DATABASE ".$data);
        $state = $stat->execute();
        if ($state) {
            echo "drop BDD de $data ok";
        }
    }

    public function userDisplay(){
        $bdd = self::getInstance();
        $stat = $bdd->prepare("SELECT * FROM user");
        $state = $stat->execute();
        if ($state) {
            foreach ($stat->fetchAll() as $user)
            echo "
            <div class='utilisateur'>utilisateur ".$user['id']." nom : ".$user['nom']." prenom : ".$user['prenom']." adresse : ".$user['numero']." ".$user['rue']." ".$user['code_postal']." ".$user['ville']." ".$user['pays']." email : ".$user['mail']."</div>";

        }

    }
    public function userDisplaySort($column){
        $bdd = self::getInstance();
        $stat = $bdd->prepare("SELECT * FROM user ORDER BY $column DESC");
        $state = $stat->execute();
        if ($state) {
            foreach ($stat->fetchAll() as $user)
                echo "
            <div class='utilisateur2'>utilisateur ".$user['id']." nom : ".$user['nom']." prenom : ".$user['prenom']." adresse : ".$user['numero']." ".$user['rue']." ".$user['code_postal']." ".$user['ville']." ".$user['pays']." email : ".$user['mail']."</div>";

        }
    }

    public function userDisplaySortName($column){
        $bdd = self::getInstance();
        $stat = $bdd->prepare("SELECT nom,prenom FROM user ORDER BY $column DESC");
        $state = $stat->execute();
        if ($state) {
            foreach ($stat->fetchAll() as $user)
                echo "
            <div class='utilisateur3'>utilisateur ".$user['nom']." prenom : ".$user['prenom']."</div>";

        }
    }
}
