<?php

namespace App\Controllers;

use App\Models\Accueil;

class AccueilController extends Controller {

  /**
   * Page d'accueil
   */
  public function index() {
    $this->display(
      'index.html.twig',
      [
          "msgErreur" => ""
      ]
    );
  }

  /**
   * Connexion
   */
  public function connexion() {
    Accueil::getInstance()->connexionSave( $_POST );
  }

  /**
   * Page choix cartons
   */
  public function choixCartons()
  {
    $this->display(
        'choix-cartons.html.twig'
    );
  }

  /**
   * Sauvegarde du carton
   */
  public function choixCartonsSave()
  {
    Accueil::getInstance()->choixCartonsSave( $_POST );
  }

  /**
   * Suppression du carton
   */
  public function choixCartonsSupp()
  {
    Accueil::getInstance()->choixCartonsSupp( );
  }

  /**
   * Page de jeux coté admin
   */
  public function admin()
  {
    $this->display(
      'admin.html.twig',
      [
        "cartons" => Accueil::getInstance()->getAllCartons(),
        "classement" => Accueil::getInstance()->getClassement()
      ]
    );
  }

  /**
   * Page de jeux coté joueur
   */
  public function partie()
  {
    $this->display(
      'partie.html.twig',
      [
        "carton" => Accueil::getInstance()->getCarton(),
        "classement" => Accueil::getInstance()->getClassement()
      ]
    );
  }

  /**
   * Update nombre lignes
   */
  public function ligne( $nbr )
  {
    Accueil::getInstance()->ligne( $nbr );
  }

  /**
   * Ajout du num dans la bdd
   */
  public function numeros($num)
  {
    Accueil::getInstance()->numeros($num);
  }

  /**
   * Retire d'un classement
   */
  public function retrait($id)
  {
    Accueil::getInstance()->retrait($id);
  }

  /**
   * Load classement
   */
  public function load_classement()
  {
    Accueil::getInstance()->getClassementt();
  }

  /**
   * Load infos gagnant
   */
  public function load_infogagnant()
  {
    Accueil::getInstance()->getInfoGagnant();
  }

  /**
   * Continue de la partie
   */
  public function continue()
  {
    Accueil::getInstance()->continue();
  }

  /**
   * Load ligne
   */
  public function load_ligne()
  {
    Accueil::getInstance()->load_ligne();
  }

  /**
   * Load tableau
   */
  public function load_tableau()
  {
    Accueil::getInstance()->load_tableau();
  }

  /**
   * Load tableau
   */
  public function load_numeros()
  {
    Accueil::getInstance()->load_numeros();
  }

  /**
   * win
   */
  public function win( $ligne )
  {
    Accueil::getInstance()->win( $ligne );
  }
}

?>