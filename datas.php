<?php
namespace mje\form;
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
 * Récupération et restitution des données d'un formulaire.
 * Cette courte classe va récupérer les données éventuellement envoyées
 * depuis le formulaire et alimenter la propriété de l'instance de formulaire
 * avec les valeurs saisies et/ou sélectionnées par l'utilisateur.
 *
 * @author      Jean Molliné <jmolline@gmail.com>
 * @package     mje
 * @subpackage  form
 */
class datas extends form
{
    /**
     * Constructeur.
     *
     */
    public function __construct(form $oForm)
    {
        if($oForm instanceof form)
        {
            $rm      = strtoupper($oForm->_formMethod);
        }
        else
        {
            trigger_error("Erreur de transmission de l'objet Form.", E_USER_ERROR);
        }
        if($_SERVER['REQUEST_METHOD'] == $rm)
        {
            $aDatas = array();
            switch ($rm)
            {
                case 'POST':
                    $aDatas = $_POST;
                    break;
                case 'GET':
                    $aDatas = $_GET;
                    break;
                default:
                    $aDatas = null;
                    break;
            }
            $aDatas = $this->_setValeursNumeriques($aDatas);
            if(isset($_FILES) && count($_FILES) > 0)
            {
                $aDatas['fichiers'] = $_FILES;
            }
            if(isset($aDatas['idformulaire'. $oForm->_formId]) && $oForm->_formId == $aDatas['idformulaire'. $oForm->_formId])
            {
                $oForm->aDatas = $aDatas;
            }
        }
    }

}