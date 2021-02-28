<?php

namespace App\Controllers;

use App\Models\Classement;

class ClassementController extends Controller {

  /**
   * Page d'accueil
   */
  public function index() {
    $this->display(
      '/classement/index.html.twig',
      [
        "infosJoueurs" => Classement::getInstance()->infosJoueurs()
      ]
    );
  }

    /**
    * Page d'ajout d'un joueur
    */
    public function add() {
        $this->display(
            '/classement/add.html.twig'
        );
    }
    /**
    * Page d'edition d'un jour
    */
    public function edit( $id ) {
        $this->display(
            '/classement/edit.html.twig',
            [
                "infoJoueur" => Classement::getInstance()->infoJoueur( $id )
            ]
        );
    }

    /**
    * Sauvegarde d'add d'un joueur
    */
    public function save() {
        Classement::getInstance()->save( $_POST );
    }

    /**
    * Sauvegarde d'edit d'un joueur
    */
    public function update( $id ) {
        Classement::getInstance()->update( $id, $_POST );
    }

    /**
    * Suppression d'un joueur
    */
    public function delete( $id ) {
        Classement::getInstance()->delete( $id );
    }

    /**
	* Recherche de pseudo
	**/
	public function recherchePseudo( $pseudo ) {
		Classement::getInstance()->rechercheMembre( $pseudo );
	}
}

?>