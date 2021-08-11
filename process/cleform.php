<?php
namespace jemdev\form\process;
/**
 * @package     mje
 *
 * Ce code est fourni tel quel sans garantie.
 * Vous avez la liberté de l'utiliser et d'y apporter les modifications
 * que vous souhaitez. Vous devrez néanmoins respecter les termes
 * de la licence CeCILL dont le fichier est joint à cette librairie.
 * {@see http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html}
 */
/**
 * Classe de gestion d'une clé de sécurité pour créer
 * ou valider une clé d'identification unique pour un formulaire.
 *
 * @author      Jean Molliné <jmolline@gmail.com>
 * @package     mje
 * @subpackage  Form
 */
class cleform
{
    private $_cleUnique;
    private $_ancienneCle;
    /**
     * Constructeur.
     *
     */
    public function __construct()
    {
        /**
         * Si le formulaire est posté, on récupère la clé précédente
         * qui permettra de comparer avec la valeur du formulaire.
         */
        if(isset($_SESSION['secure_key']))
        {
            $this->_ancienneCle = $_SESSION['secure_key'];
        }
    }

    public function  getCleUnique()
    {
        /**
         * on génère la clé et on l’enregistre dans la classe
         */
        $this->_genereCle() ;
        /**
         * enregistrement de la clé dans la session
         */
        $_SESSION['secure_key'] = sha1($this->_cleUnique);
        /**
         * envoi de la clé dans le formulaire
         */
        return $this->_cleUnique;
    }

    /**
     * Validation de la clé d'identification du formulaire.
     *
     * @param   String  $clePostee
     * @return  Boolean
     */
    public function validationCle($clePostee)
    {
        $retour = (sha1($clePostee) == $this->_ancienneCle) ? true : false;
        return $retour;
    }
    private function _genereCle()
    {
        /**
         * Récupération de L'Adresse IP de l'utilisateur.
         */
        $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
        /**
         * on utlilise mt_rand() pour avoir une valeur plus aléatoire qu’avec
         * la fonction rand(), et on passe true en paramètre à uniqid() pour
         * lui dire qu’on veut une longue chaine de caractère.
         */
        $uniqid = uniqid(mt_rand(), true);
        /**
         * création et renvoi du hash
         */
        $this->_cleUnique = md5($ip . $uniqid);
    }

}