<?php
namespace jemdev\form;
use jemdev\form\form;
use jemdev\form\field;
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
 * Construction de groupes de champs.
 *
 * Exemple, un groupe de boutons radio. Lors de la validation, on
 * doit pouvoir tester par rapport au nom commun à tous les boutons
 * radios alors qu'ils ont un identifiant individuel unique.
 *
 * Si on crée un groupe de champ, on peut ajouter la règle de validation
 * required et dans ce cas au moins un des champs du groupe devra avoir
 * été alimenté (ou coché dans le cas de boutons radios ou cases à
 * cocher pour que la règle soit validée.
 *
 * @author      Jean Molliné <jmolline@gmail.com>
 * @package     mje
 * @subpackage  Form
 */
class groupe extends field
{
    /**
     * Types de champs composant le groupe
     *
     * @var String
     */
    protected $_typeChamps;
    protected $_groupeName;
    /**
     * Stockage des champs du groupe.
     *
     * @var Array
     */
    protected $_aGroupe = array();

    /**
     * Constructeur.
     *
     * @param Array     $props  Tableau de paramètre ne contenant que le nom du groupe
     * @param form      $oForm  Instance de l'objet formulaire.
     */
    public function __construct($props, form $oForm)
    {
        $this->_groupeName = $props[0];
        $this->_aAttributs['id']  = $props[0];
        $this->_aAttributs['name']  = (isset($props[1])) ? $props[1] : $props[0];
        $this->_aAttributs['value'] = null;
        $this->_label               = null;
        parent::__construct($oForm);
    }


    public function addChamp(field $oChamp)
    {
        $this->_aGroupe[] = $oChamp;
        return $this;
    }
}