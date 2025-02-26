<?php

/**
 * Classe d'accès aux données.
 *
 * PHP Version 8
 *
 * @category  PPE
 * @package   GSB
 * @author    Cheri Bibi - Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL - CNED <jgil@ac-nice.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.php.net/manual/fr/book.pdo.php PHP Data Objects sur php.net
 */

/**
 * Classe d'accès aux données.
 *
 * Utilise les services de la classe PDO
 * pour l'application GSB
 * Les attributs sont tous statiques,
 * les 4 premiers pour la connexion
 * $connexion de type PDO
 * $instance qui contiendra l'unique instance de la classe
 *
 * PHP Version 8
 *
 * @category  PPE
 * @package   GSB
 * @author    Cheri Bibi - Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL <jgil@ac-nice.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   Release: 1.0
 * @link      http://www.php.net/manual/fr/book.pdo.php PHP Data Objects sur php.net
 */

namespace Modeles;

use PDO;
use Outils\Utilitaires;

require '../config/bdd.php';

class PdoGsb
{
    protected $connexion;
    private static $instance = null;

    /**
     * Constructeur privé, crée l'instance de PDO qui sera sollicitée
     * pour toutes les méthodes de la classe
     */
    private function __construct()
    {
        $this->connexion = new PDO(DB_DSN, DB_USER, DB_PWD);
        $this->connexion->query('SET CHARACTER SET utf8');
    }

    /**
     * Méthode destructeur appelée dès qu'il n'y a plus de référence sur un
     * objet donné, ou dans n'importe quel ordre pendant la séquence d'arrêt.
     */
    public function __destruct()
    {
        $this->connexion = null;
    }

    /**
     * Fonction statique qui crée l'unique instance de la classe
     * Appel : $instancePdoGsb = PdoGsb::getPdoGsb();
     *
     * @return l'unique objet de la classe PdoGsb
     */
    public static function getPdoGsb(): PdoGsb
    {
        if (self::$instance == null) {
            self::$instance = new PdoGsb();
        }
        return self::$instance;
    }

    /**
     * Retourne les informations d'un visiteur
     *
     * @param String $login Login du visiteur
     * @param String $mdp   Mot de passe du visiteur
     *
     * @return l'id, le nom, le prénom, l'email sous la forme d'un tableau associatif
     */
    public function getInfosVisiteur($login): array
    {
        $requetePrepare = $this->connexion->prepare(
            'SELECT visiteur.id AS id, visiteur.nom AS nom, '
            . 'visiteur.prenom AS prenom, visiteur.email AS email '
            . 'FROM visiteur '
            . 'WHERE visiteur.login = :unLogin'
        );
        $requetePrepare->bindParam(':unLogin', $login, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetch();
    }

    /**
     * Retourne les informations d'un comptable
     *
     * @param String $login Login du comptable
     * @param String $mdp   Mot de passe du comptable
     *
     * @return l'id, le nom, le prénom, l'email sous la forme d'un tableau associatif
     */
    public function getInfosComptable($login): array
    {
        $requetePrepare = $this->connexion->prepare(
            'SELECT comptable.id AS id, comptable.nom AS nom, '
            . 'comptable.prenom AS prenom, comptable.email AS email '
            . 'FROM comptable '
            . 'WHERE comptable.login = :unLogin'
        );
        $requetePrepare->bindParam(':unLogin', $login, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetch();
    }
    
    public function getMdpUtilisateur($login) {
        $requetePrepare = $this->connexion->prepare(
            'SELECT mdp '
            . 'FROM utilisateur '
            . 'WHERE utilisateur.login = :unLogin'
        );
        $requetePrepare->bindParam(':unLogin', $login, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetch(PDO::FETCH_OBJ)->mdp;
    }
    


    public function estComptable($login): bool
    {
        $requetePrepare = $this->connexion->prepare(
            'SELECT comptable.login AS login '
            . 'FROM comptable '
            . 'WHERE comptable.login = :unlogin'
        );
        $requetePrepare->bindParam(':unlogin', $login, PDO::PARAM_STR);
        $requetePrepare->execute();

        if ($requetePrepare->fetch() == null) {
            return false;
        } else {
            return true;
        }
    }
    
    /**
     * Hashe tous les mots de passe des utilisateurs de la base de donnée
     *
     * @return Rien
     */
    public function hashAllPwd(): void
    {
        $requetePrepare = $this->connexion->prepare(
            'SELECT comptable.mdp AS mdp, comptable.id as id '
            . 'FROM comptable'
        );
        $requetePrepare->execute();

        $users = $requetePrepare->fetchAll(PDO::FETCH_ASSOC);

        foreach ($users as $user) {
            $mdp = $user['mdp'];
            $id = $user['id'];
            $hashMdp = password_hash($mdp, PASSWORD_DEFAULT);
            $req = $this->connexion->prepare('UPDATE comptable SET mdp= :hashMdp WHERE id= :unId');
            $req->bindParam(':hashMdp', $hashMdp, PDO::PARAM_STR);
            $req->bindParam(':unId', $id, PDO::PARAM_STR);
            $req->execute();
        }
        
        $requetePrepare = $this->connexion->prepare(
            'SELECT visiteur.mdp AS mdp, visiteur.id as id '
            . 'FROM visiteur'
        );
        $requetePrepare->execute();

        $users = $requetePrepare->fetchAll(PDO::FETCH_ASSOC);

        foreach ($users as $user) {
            $mdp = $user['mdp'];
            $id = $user['id'];
            $hashMdp = password_hash($mdp, PASSWORD_DEFAULT);
            $req = $this->connexion->prepare('UPDATE visiteur SET mdp= :hashMdp WHERE id= :unId');
            $req->bindParam(':hashMdp', $hashMdp, PDO::PARAM_STR);
            $req->bindParam(':unId', $id, PDO::PARAM_STR);
            $req->execute();
        }
    }

    /**
     * Retourne sous forme d'un tableau associatif toutes les lignes de frais
     * hors forfait concernées par les deux arguments.
     * La boucle foreach ne peut être utilisée ici car on procède
     * à une modification de la structure itérée - transformation du champ date-
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return tous les champs des lignes de frais hors forfait sous la forme
     * d'un tableau associatif
     */
    public function getLesFraisHorsForfait($idVisiteur, $mois): array
    {
        $requetePrepare = $this->connexion->prepare(
            'SELECT * FROM lignefraishorsforfait '
            . 'WHERE lignefraishorsforfait.idvisiteur = :unIdVisiteur '
            . 'AND lignefraishorsforfait.mois = :unMois'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $lesLignes = $requetePrepare->fetchAll();
        $nbLignes = count($lesLignes);
        for ($i = 0; $i < $nbLignes; $i++) {
            $date = $lesLignes[$i]['date'];
            $lesLignes[$i]['date'] = Utilitaires::dateAnglaisVersFrancais($date);
        }
        return $lesLignes;
    }

    /**
     * Retourne sous forme d'un tableau associatif toutes les lignes de frais
     * hors forfait concernées par les deux arguments déstiné à la validation par comptable.
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return tous les champs des lignes de frais hors forfait sous la forme
     * d'un tableau associatif
     */
    public function getLesFraisHorsForfaitValidation($idVisiteur, $mois): array
    {
        $requetePrepare = $this->connexion->prepare(
            'SELECT * FROM lignefraishorsforfait '
            . 'WHERE lignefraishorsforfait.idvisiteur = :unIdVisiteur '
            . 'AND lignefraishorsforfait.mois = :unMois '
            . 'AND libelle NOT LIKE "REFUSE :%"'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $lesLignes = $requetePrepare->fetchAll();

        return $lesLignes;
    }

    /**
     * Retourne le nombre de justificatif d'un visiteur pour un mois donné
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return le nombre entier de justificatifs
     */
    public function getNbjustificatifs($idVisiteur, $mois): int
    { 
        $requetePrepare = $this->connexion->prepare(
            'SELECT fichefrais.nbjustificatifs as nb FROM fichefrais '
            . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
            . 'AND fichefrais.mois = :unMois'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $laLigne = $requetePrepare->fetch();

        return $laLigne['nb'];
    }

    /**
     * Retourne sous forme d'un tableau associatif toutes les lignes de frais
     * au forfait concernées par les deux arguments
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return l'id, le libelle et la quantité sous la forme d'un tableau
     * associatif
     */
    public function getLesFraisForfait($idVisiteur, $mois): array
    {
        $requetePrepare = $this->connexion->prepare(
            'SELECT fraisforfait.id as idfrais, '
            . 'fraisforfait.libelle as libelle, '
            . 'lignefraisforfait.quantite as quantite '
            . 'FROM lignefraisforfait '
            . 'INNER JOIN fraisforfait '
            . 'ON fraisforfait.id = lignefraisforfait.idfraisforfait '
            . 'WHERE lignefraisforfait.idvisiteur = :unIdVisiteur '
            . 'AND lignefraisforfait.mois = :unMois '
            . 'ORDER BY lignefraisforfait.idfraisforfait'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetchAll();
    }

    /**
     * Retourne tous les id de la table FraisForfait
     *
     * @return un tableau associatif
     */
    public function getLesIdFrais(): array
    {
        $requetePrepare = $this->connexion->prepare(
            'SELECT fraisforfait.id as idfrais '
            . 'FROM fraisforfait ORDER BY fraisforfait.id'
        );
        $requetePrepare->execute();
        return $requetePrepare->fetchAll();
    }

    /**
     * Met à jour la table ligneFraisForfait
     * Met à jour la table ligneFraisForfait pour un visiteur et
     * un mois donné en enregistrant les nouveaux montants
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     * @param Array  $lesFrais   tableau associatif de clé idFrais et
     *                           de valeur la quantité pour ce frais
     *
     * @return null
     */
    public function majFraisForfait($idVisiteur, $mois, $lesFrais): void
    {
        $lesCles = array_keys($lesFrais);
        foreach ($lesCles as $unIdFrais) {
            $qte = $lesFrais[$unIdFrais];
            $requetePrepare = $this->connexion->prepare(
                'UPDATE lignefraisforfait '
                . 'SET lignefraisforfait.quantite = :uneQte '
                . 'WHERE lignefraisforfait.idvisiteur = :unIdVisiteur '
                . 'AND lignefraisforfait.mois = :unMois '
                . 'AND lignefraisforfait.idfraisforfait = :idFrais'
            );
            $requetePrepare->bindParam(':uneQte', $qte, PDO::PARAM_INT);
            $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
            $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
            $requetePrepare->bindParam(':idFrais', $unIdFrais, PDO::PARAM_STR);
            
            $requetePrepare->execute();
        }
    }


    /**
     * Met à jour la table ligneFraisHorsForfait
     * Met à jour la table ligneFraisHorsForfait pour un visiteur et
     * un mois donné en enregistrant les nouveaux montants
     *
     * @param Array  $lesFrais   tableau associatif de clé idFrais et
     *                           de valeur la quantité pour ce frais
     *
     * @return null
     */
    public function majFraisHorsForfait($idFraisHorsForfait, $lesFrais): void
    {
        $requetePrepare = $this->connexion->prepare(
            'UPDATE lignefraisHorsforfait '
            . 'SET lignefraisHorsforfait.libelle = :libelle, '
            . 'lignefraisHorsforfait.date = :date, '
            . 'lignefraisHorsforfait.montant = :montant '
            . 'WHERE lignefraisHorsforfait.id = :idFraisHorsForfait '
        );
        $requetePrepare->bindParam(':libelle', $lesFrais['libelle'], PDO::PARAM_STR);
        $requetePrepare->bindParam(':date', $lesFrais['date'], PDO::PARAM_STR);
        $requetePrepare->bindParam(':montant', $lesFrais['montant'], PDO::PARAM_STR);
        $requetePrepare->bindParam(':idFraisHorsForfait', $idFraisHorsForfait, PDO::PARAM_INT);
        
        $requetePrepare->execute();
    }

    /**
     * Met à jour le nombre de justificatifs de la table ficheFrais
     * pour le mois et le visiteur concerné
     *
     * @param String  $idVisiteur      ID du visiteur
     * @param String  $mois            Mois sous la forme aaaamm
     * @param Integer $nbJustificatifs Nombre de justificatifs
     *
     * @return null
     */
    public function majNbJustificatifs($idVisiteur, $mois, $nbJustificatifs): void
    {
        $requetePrepare = $this->connexion->prepare(
            'UPDATE fichefrais '
            . 'SET nbjustificatifs = :unNbJustificatifs '
            . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
            . 'AND fichefrais.mois = :unMois'
        );
        $requetePrepare->bindParam(
            ':unNbJustificatifs',
            $nbJustificatifs,
            PDO::PARAM_INT
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
    }

    /**
     * Teste si un visiteur possède une fiche de frais pour le mois passé en argument
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return vrai ou faux
     */
    public function estPremierFraisMois($idVisiteur, $mois): bool
    {
        $boolReturn = false;
        $requetePrepare = $this->connexion->prepare(
            'SELECT fichefrais.mois FROM fichefrais '
            . 'WHERE fichefrais.mois = :unMois '
            . 'AND fichefrais.idvisiteur = :unIdVisiteur'
        );
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->execute();
        if (!$requetePrepare->fetch()) {
            $boolReturn = true;
        }
        return $boolReturn;
    }

    /**
     * Retourne le dernier mois en cours d'un visiteur
     *
     * @param String $idVisiteur ID du visiteur
     *
     * @return le mois sous la forme aaaamm
     */
    public function dernierMoisSaisi($idVisiteur): string
    {
        $requetePrepare = $this->connexion->prepare(
            'SELECT MAX(mois) as dernierMois '
            . 'FROM fichefrais '
            . 'WHERE fichefrais.idvisiteur = :unIdVisiteur'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->execute();
        $laLigne = $requetePrepare->fetch();
        $dernierMois = $laLigne['dernierMois'];
        return $dernierMois;
    }

    /**
     * Crée une nouvelle fiche de frais et les lignes de frais au forfait
     * pour un visiteur et un mois donnés
     *
     * Récupère le dernier mois en cours de traitement, met à 'CL' son champs
     * idEtat, crée une nouvelle fiche de frais avec un idEtat à 'CR' et crée
     * les lignes de frais forfait de quantités nulles
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return null
     */
    public function creeNouvellesLignesFrais($idVisiteur, $mois): void
    {
        $dernierMois = $this->dernierMoisSaisi($idVisiteur);
        $laDerniereFiche = $this->getLesInfosFicheFrais($idVisiteur, $dernierMois);
        if ($laDerniereFiche['idEtat'] == 'CR') {
            $this->majEtatFicheFrais($idVisiteur, $dernierMois, 'CL');
        }
        $requetePrepare = $this->connexion->prepare(
            'INSERT INTO fichefrais (idvisiteur,mois,nbjustificatifs,'
            . 'montantvalide,datemodif,idetat) '
            . "VALUES (:unIdVisiteur,:unMois,0,0,now(),'CR')"
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $lesIdFrais = $this->getLesIdFrais();
        foreach ($lesIdFrais as $unIdFrais) {
            $requetePrepare = $this->connexion->prepare(
                'INSERT INTO lignefraisforfait (idvisiteur,mois,'
                . 'idfraisforfait,quantite) '
                . 'VALUES(:unIdVisiteur, :unMois, :idFrais, 0)'
            );
            $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
            $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
            $requetePrepare->bindParam(':idFrais', $unIdFrais['idfrais'], PDO::PARAM_STR);
            $requetePrepare->execute();
        }
    }

    /**
     * Crée un nouveau frais hors forfait pour un visiteur un mois donné
     * à partir des informations fournies en paramètre
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     * @param String $libelle    Libellé du frais
     * @param String $date       Date du frais au format français jj//mm/aaaa
     * @param Float  $montant    Montant du frais
     *
     * @return null
     */
    public function creeNouveauFraisHorsForfait($idVisiteur, $mois, $libelle, $date, $montant): void
    {
        $requetePrepare = $this->connexion->prepare(
            'INSERT INTO lignefraishorsforfait '
            . 'VALUES (null, :unIdVisiteur,:unMois, :unLibelle, :uneDate,'
            . ':unMontant) '
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unLibelle', $libelle, PDO::PARAM_STR);
        $requetePrepare->bindParam(':uneDate', $date, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMontant', $montant, PDO::PARAM_INT);
        $requetePrepare->execute();
    }

    /**
     * Supprime le frais hors forfait dont l'id est passé en argument
     *
     * @param String $idFrais ID du frais
     *
     * @return null
     */
    public function supprimerFraisHorsForfait($idFrais): void
    {
        $requetePrepare = $this->connexion->prepare(
            'DELETE FROM lignefraishorsforfait '
            . 'WHERE lignefraishorsforfait.id = :unIdFrais'
        );
        $requetePrepare->bindParam(':unIdFrais', $idFrais, PDO::PARAM_STR);
        $requetePrepare->execute();
    }

    /**
     * Refuser le frais hors forfait dont l'id est passé en argument
     *
     * @param String $idFraisHorsForfait ID du frais
     *
     * @return null
     */
    public function refuserFraisHorsForfait($idFraisHorsForfait): void
    {
        $requetePrepare = $this->connexion->prepare(
            'UPDATE lignefraishorsforfait '
            . 'SET libelle = SUBSTRING(CONCAT("REFUSE : ", libelle), 1, 100) '
            . 'WHERE lignefraishorsforfait.id = :unIdFrais'
        );
        $requetePrepare->bindParam(':unIdFrais', $idFraisHorsForfait, PDO::PARAM_STR);
        $requetePrepare->execute();
    }

    /**
     * Retourne les mois pour lesquel un visiteur a une fiche de frais
     *
     * @param String $idVisiteur ID du visiteur
     *
     * @return un tableau associatif de clé un mois -aaaamm- et de valeurs
     *         l'année et le mois correspondant
     */
    public function getLesMoisDisponibles($idVisiteur): array
    {
        $requetePrepare = $this->connexion->prepare(
            'SELECT fichefrais.mois AS mois FROM fichefrais '
            . 'WHERE fichefrais.idvisiteur = :idVisiteur '
            . 'ORDER BY fichefrais.mois desc'
        );
        $requetePrepare->bindParam(':idVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->execute();
        $lesMois = array();
        while ($laLigne = $requetePrepare->fetch()) {
            $mois = $laLigne['mois'];
            $numAnnee = substr($mois, 0, 4);
            $numMois = substr($mois, 4, 2);
            $lesMois[] = array(
                'mois' => $mois,
                'numAnnee' => $numAnnee,
                'numMois' => $numMois
            );
        }
        return $lesMois;
    }
    
    /**
     * Retourne les fiches de frais d'un visiteur
     *
     * @param String $idVisiteur ID du visiteur
     *
     * @return un tableau associatif de clé un mois -aaaamm- et de valeurs
     *         l'année, le mois correspondant et l'état de la fiche
     */
    public function getLesFichesFrais($idVisiteur): array
    {
        $requetePrepare = $this->connexion->prepare(
            'SELECT fichefrais.mois AS mois, etat.libelle as etat FROM fichefrais '
            . 'INNER JOIN etat ON fichefrais.idetat = etat.id '
            . 'WHERE fichefrais.idvisiteur = :idVisiteur '
            . 'ORDER BY fichefrais.mois desc'
        );
        $requetePrepare->bindParam(':idVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->execute();
        $lesFiches = array();
        while ($laLigne = $requetePrepare->fetch()) {
            $mois = $laLigne['mois'];
            $etat = $laLigne['etat'];
            $numAnnee = substr($mois, 0, 4);
            $numMois = substr($mois, 4, 2);
            $lesFiches[] = array(
                'mois' => $mois,
                'numAnnee' => $numAnnee,
                'numMois' => $numMois,
                'etat' => $etat
            );
        }
        return $lesFiches;
    }
    
    /**
     * Retourne les fiches de frais d'un visiteur
     *
     * @param String $idVisiteur ID du visiteur
     *
     * @return un tableau associatif de clé un mois -aaaamm- et de valeurs
     *         l'année, le mois correspondant et l'état de la fiche
     */
    public function postEnvoyerPaiement($idVisiteur, $mois): void
    {
        echo "<script>console.log('" . addslashes($idVisiteur) . "');</script>";
        echo "<script>console.log('" . addslashes($mois) . "');</script>";
        $requetePrepare = $this->connexion->prepare(
            'UPDATE fichefrais '
            . 'SET idetat = "AR" '
            . 'WHERE idvisiteur = :idVisiteur AND mois = :mois'
        );
        $requetePrepare->bindParam(':idVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':mois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
    }
    
    public function getPrixKilometre(int $idTypeVehicule): float
    {
        $requetePrepare = $this->connexion->prepare(
            'SELECT prix_kilometre '
            . 'FROM type_vehicule '
            . 'WHERE id = :idTypeVehicule'
        );
        $requetePrepare->bindParam(':idTypeVehicule', $idTypeVehicule, PDO::PARAM_INT);
        $requetePrepare->execute();
        $result = $requetePrepare->fetch();

        return $result ? (float)$result['prix_kilometre'] : 0.0;
    }
    
    /**
    * Méthode pour changer le type de véhicule d'un utilisateur
    * 
    * @param string $idVisiteur Identifiant de l'utilisateur
    * @param int $nouveauTypeVehicule ID du nouveau type de véhicule
    * @return void
    */
   function changerTypeVehicule($idVisiteur, $nouveauTypeVehicule) {
       $existeVehicule = $this->connexion->prepare("SELECT COUNT(*) FROM type_vehicule WHERE id = :id");
       $existeVehicule->bindParam(':id', $nouveauTypeVehicule, PDO::PARAM_INT);
       $existeVehicule->execute();

       if ($existeVehicule->fetchColumn() == 0) {
           Utilitaires::ajouterErreur('Le type de véhicule sélectionné est invalide.');
           include PATH_VIEWS . 'v_erreurs.php';
           return;
       }

       $updateVehicule = $this->connexion->prepare(
           "UPDATE visiteur SET id_type_vehicule = :idTypeVehicule WHERE id = :idVisiteur"
       );
       $updateVehicule->bindParam(':idTypeVehicule', $nouveauTypeVehicule, PDO::PARAM_INT);
       $updateVehicule->bindParam(':idVisiteur', $idVisiteur, PDO::PARAM_STR);

       if (!$updateVehicule->execute()) {
           Utilitaires::ajouterErreur('Erreur lors de la mise à jour du type de véhicule.');
           include PATH_VIEWS . 'v_erreurs.php';
       }
   }
   
   public function getTypeVehiculeUtilisateur(string $idVisiteur): int {
        $requete = $this->connexion->prepare(
            "SELECT id_type_vehicule FROM visiteur WHERE id = :idVisiteur"
        );
        $requete->bindParam(':idVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requete->execute();
        return (int) $requete->fetchColumn();
    }

    /**
     * Retourne les informations d'une fiche de frais d'un visiteur pour un
     * mois donné
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return un tableau avec des champs de jointure entre une fiche de frais
     *         et la ligne d'état
     */
    public function getLesInfosFicheFrais($idVisiteur, $mois): array
    {
        $requetePrepare = $this->connexion->prepare(
            'SELECT fichefrais.idetat as idEtat, '
            . 'fichefrais.datemodif as dateModif,'
            . 'fichefrais.nbjustificatifs as nbJustificatifs, '
            . 'fichefrais.montantvalide as montantValide, '
            . 'etat.libelle as libEtat '
            . 'FROM fichefrais '
            . 'INNER JOIN etat ON fichefrais.idetat = etat.id '
            . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
            . 'AND fichefrais.mois = :unMois'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $laLigne = $requetePrepare->fetch();
        return $laLigne;
    }

    /**
     * Modifie l'état et la date de modification d'une fiche de frais.
     * Modifie le champ idEtat et met la date de modif à aujourd'hui.
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     * @param String $etat       Nouvel état de la fiche de frais
     *
     * @return null
     */
    public function majEtatFicheFrais($idVisiteur, $mois, $etat): void
    {
        $requetePrepare = $this->connexion->prepare(
            'UPDATE fichefrais '
            . 'SET idetat = :unEtat, datemodif = now() '
            . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
            . 'AND fichefrais.mois = :unMois'
        );
        $requetePrepare->bindParam(':unEtat', $etat, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
    }

    /**
     * Récupère tous les visiteurs qui ne sont pas des comptables
     * 
     * @return array
     */
    public function getVisiteurs(): array 
    {
        $requetePrepare = $this->connexion->prepare(
            'SELECT visiteur.id as id, visiteur.nom as nom, visiteur.prenom as prenom ' 
            . 'FROM visiteur'
        );
        $requetePrepare->execute();
        return $requetePrepare->fetchAll();
    }

     /**
     * Récupère l'id du visiteur dont le nom et prenom 
     * est passé en paramètre
     * 
     * @param String $nom Nom du visiteur
     * @param String $prenom Prenom du visiteur
     *
     * @return String
     */
    public function getIdVisiteur($nom, $prenom): String 
    {
        $requetePrepare = $this->connexion->prepare(
            'SELECT visiteur.id as id FROM visiteur ' 
            . 'WHERE visiteur.nom = :nom ' 
            . 'AND visiteur.prenom = :prenom '
        );
        $requetePrepare->bindParam(':nom', $nom, PDO::PARAM_STR);
        $requetePrepare->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        $requetePrepare->execute();

        return $requetePrepare->fetchColumn();
    }


    /**
     * Ajoute le codeA2f en BDD 
     * via l'id du visiteur et le code précédemment généré 
     * passés en paramètre
     * 
     * @param int $id id du visiteur
     * @param int $code code A2f du visiteur
     *
     */
    public function setCodeA2fVisiteur($id, $code) {
        $requetePrepare = $this->connexion->prepare(
            'UPDATE visiteur '
          . 'SET codea2f = :unCode '
          . 'WHERE visiteur.id = :unIdVisiteur '
        );
        $requetePrepare->bindParam(':unCode', $code, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unIdVisiteur', $id, PDO::PARAM_STR);
        $requetePrepare->execute();
    }

    /**
     * Ajoute le codeA2f d'un comptable en BDD 
     * via l'id du visiteur et le code précédemment généré 
     * passés en paramètre
     * 
     * @param int $id id du visiteur
     * @param int $code code A2f du visiteur
     *
     */
    public function setCodeA2fComptable($id, $code) {
        $requetePrepare = $this->connexion->prepare(
            'UPDATE comptable '
          . 'SET codea2f = :unCode '
          . 'WHERE comptable.id = :unIdcomptable '
        );
        $requetePrepare->bindParam(':unCode', $code, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unIdcomptable', $id, PDO::PARAM_STR);
        $requetePrepare->execute();
    }



    /**
     * Récupére le code A2f d'un visiteur
     * via l'id passé en paramètre
     * 
     * @param int $id du visiteur
     *
     */
    public function getCodeVisiteur($id) {
        $requetePrepare = $this->connexion->prepare(
            'SELECT visiteur.codea2f AS codea2f '
          . 'FROM visiteur '
          . 'WHERE visiteur.id = :unId'
        );
        $requetePrepare->bindParam(':unId', $id, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetch()['codea2f'];
    }

    /**
     * Récupére le code A2f d'un comptable
     * via l'id passé en paramètre
     * 
     * @param int $id du visiteur
     *
     */
    public function getCodeComptable($id) {
        $requetePrepare = $this->connexion->prepare(
            'SELECT comptable.codea2f AS codea2f '
          . 'FROM comptable '
          . 'WHERE comptable.id = :unId'
        );
        $requetePrepare->bindParam(':unId', $id, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetch()['codea2f'];
    }



    /**
     * Insert le PDF d'un fiche de fras
     * 
     * @param int $idVisiteur du visiteur
     * @param int $leMois de fiche de frais
     * @param int $pdfData le PDF en binaire
     *
     */
    public function insertPdf($idVisiteur, $leMois, $pdfData, $nomPdf)
    {
        $requetePrepare = $this->connexion->prepare(
            'UPDATE fichefrais '
            . 'SET donneespdf = :pdfData, '
            . 'nompdf = :nomPdf '
            . 'WHERE idvisiteur = :idVisiteur '
            . 'AND mois = :leMois'
        );
        $requetePrepare->bindParam(':pdfData', $pdfData, PDO::PARAM_STR);
        $requetePrepare->bindParam(':nomPdf', $nomPdf, PDO::PARAM_STR);
        $requetePrepare->bindParam(':idVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':leMois', $leMois, PDO::PARAM_STR);
        $requetePrepare->execute();
    }


    /**
     * Recupere PDF d'une fiche de fais de la BDD
     * 
     * 
     * @param int $idVisiteur du visiteur
     * @param int $leMois de fiche de frais
     *
     * @return String
     */
    public function getPdf($idVisiteur, $leMois)
    {
        $requetePrepare = $this->connexion->prepare(
            'SELECT donneespdf, nompdf '
          . 'FROM fichefrais '
          . 'WHERE idvisiteur = :idVisiteur '
          . 'AND mois = :leMois'
        );
        $requetePrepare->bindParam(':idVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':leMois', $leMois, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetch(); 
    }
    
    
}

 