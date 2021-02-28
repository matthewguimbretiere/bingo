<?php

namespace App\Models;

function affErreur( $erreur, $redirection ) {
    global $twig;
    $twig->display(
    	$redirection,
    	[
    		'erreur' => $erreur
    	]
    );
}

class Classement extends Base
{
    protected $tableName = 'classementmister';

    protected static $instance;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Obtenir les infos des joueurs
     */
    public function infosJoueurs() {
        $sql = "SELECT * FROM classementmister ORDER BY total DESC";
        $sth = self::$dbh->prepare( $sql );
        $sth->execute();
        return $sth->fetchAll();
    }

    /**
     * Infos pour un joueur
     */
    public function infoJoueur( $id ) {
        $sql = "SELECT * FROM classementmister WHERE id = :id";
        $sth = self::$dbh->prepare( $sql );
        $sth->execute([
            ":id" => $id
        ]);
        return $sth->fetch();
    }

    /**
     * Save nouveau joueur
     */
    public function save( $datas ) {
        $sql = "SELECT * FROM classementmister WHERE pseudo = :pseudo";
        $sth = self::$dbh->prepare($sql);
        $sth->execute([
            ":pseudo" => $datas["pseudo"]
        ]);
        $infos = $sth->fetch();
        $count = $sth->rowCount();

        if($count == 0) {
            $sql = "INSERT INTO classementmister (pseudo, points, kills, total) VALUES (:pseudo, :points, :kills, :total)";
            $sth = self::$dbh->prepare($sql);
            $sth->execute([
                ":pseudo" => $datas["pseudo"],
                ":points" => $datas["points"],
                ":kills" => $datas["kills"],
                ":total" => $datas["total"]
            ]);
            redirect('/classement');
        } else {
            $sql = "UPDATE classementmister SET points = points + :points, kills = kills + :kills, total = total + :total WHERE id = :id";
            $sth = self::$dbh->prepare($sql);
            $sth->execute([
                ":points" => $datas["points"],
                ":kills" => $datas["kills"],
                ":total" => $datas["total"],
                ":id" => $infos['id']
            ]);
            redirect('/classement');
        }


        
    }

    /**
     * Save update joueur
     */
    public function update( $id, $datas ) {
        $sql = "UPDATE classementmister SET pseudo = :pseudo, points = :points, kills = :kills, total = :total WHERE id = :id";
        $sth = self::$dbh->prepare($sql);
        $sth->execute([
            ":pseudo" => $datas["pseudo"],
            ":points" => $datas["points"],
            ":kills" => $datas["kills"],
            ":total" => $datas["total"],
            ":id" => $id
        ]);
        redirect('/classement');
    }

    /**
     * Suppression d'un joueur
     */
    public function delete( $id ) {
        $sql = "DELETE FROM classementmister WHERE id = :id";
        $sth = self::$dbh->prepare($sql);
        $sth->execute([":id" => $id]);
        redirect('/classement');
    }

    public function rechercheMembre( $pseudo ) {
		$sql = "SELECT * FROM classementmister WHERE pseudo LIKE '$pseudo%'";
		$membres = self::$dbh->query( $sql )->fetchAll();
		header( 'Content-Type: application/json' );
		echo json_encode([
		 'membres' => $membres
		]);
   	}
    
}
?>