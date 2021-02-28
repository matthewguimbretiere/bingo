<?php
//== la page d'accueil ========================================
//$route->addRoute('GET', '/', 'FrontController@index');
//$route->addRoute(['GET','POST'],'/backoffice/formules/update/{id:[0-9]+}', 'FormulaController@update');

// Page d'accueil =======================================

use App\Controllers\AccueilController;
use App\Models\Accueil;

$route->addRoute('GET', '/', 'AccueilController@index');
$route->addRoute('POST', '/bingo-connexion', 'AccueilController@connexion');
// Page choix cartons ===================================
$route->addRoute('GET', '/choix-cartons', 'AccueilController@choixCartons');
$route->addRoute('POST', '/choix-cartons-save', 'AccueilController@choixCartonsSave');
$route->addRoute('POST', '/choix-cartons-supp', 'AccueilController@choixCartonsSupp');
// Page de jeux côté admin ==============================
$route->addRoute('GET', '/admin', 'AccueilController@admin');
$route->addRoute('GET', '/ligne/{nbr}', 'AccueilController@ligne');
$route->addRoute('GET', '/numeros/{num}', 'AccueilController@numeros');
$route->addRoute('GET', '/retrait/{id}', 'AccueilController@retrait');
$route->addRoute('GET', '/load_classement', 'AccueilController@load_classement');
$route->addRoute('GET', '/load_infogagnant', 'AccueilController@load_infogagnant');
$route->addRoute('GET', '/continue', 'AccueilController@continue');
// Page de jeux coté joueur =============================
$route->addRoute('GET', '/partie', 'AccueilController@partie');
$route->addRoute('GET', '/load_ligne', 'AccueilController@load_ligne');
$route->addRoute('GET', '/load_tableau', 'AccueilController@load_tableau');
$route->addRoute('GET', '/load_numeros', 'AccueilController@load_numeros');
$route->addRoute('GET', '/win/{ligne}', 'AccueilController@win');
// Classement mister
$route->addRoute('GET', '/classement', 'ClassementController@index');
$route->addRoute('GET', '/classement/add', 'ClassementController@add');
$route->addRoute('POST', '/classement/save', 'ClassementController@save');
$route->addRoute('GET', '/classement/edit/{id}', 'ClassementController@edit');
$route->addRoute(['GET', 'POST'], '/classement/update/{id}', 'ClassementController@update');
$route->addRoute('GET', '/classement/delete/{id}', 'ClassementController@delete');
$route->addRoute('GET','/classement/recherche-pseudo/{pseudo}','ClassementController@recherchePseudo');
?>