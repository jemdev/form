<?php
namespace mje\form\field;
use mje\form\form;
use mje\form\field;
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
 * Création d'une balise option pour une liste de sélection
 *
 * @author      Jean Molliné <jmolline@gmail.com>
 * @package     mje
 * @subpackage  Form
 */
class option extends field
{
    private $_value;
    private $_affiche;
    private $_valSelected;
    private $_listeOptions = array();
    /**
     * Constructeur.
     *
     * Définit une liste de sélection. L'objet permet de stocker
     * un certain nombre d'items. La méthode __toString() va
     * retourner l'ensemble des options définies.
     */
    public function __construct(form $oForm)
    {
        parent::__construct($oForm);
        $this->_oForm = $oForm;
        $this->_tag = 'option';
    }

    /**
     * Ajout d'un item dans la liste de sélection.
     *
     * @param   String  $value      Valeur de l'attribut value
     * @param   String  $affiche    Valeur affichée dans la liste
     * @param   Boolean $selected   Optionnel, définit si une valeur doit être sélectionnée par défaut.
     * @param   Array   $options    Paires attribut/valeur supplémentaires (Optionnel)
     */
    public function addValue($value, $affiche, $selected = false, $selectParentName, $options = null)
    {
        $this->_value   = $value;
        $this->_affiche = $affiche;
        if(isset($this->_aSentDatas) && array_key_exists($selectParentName, $this->_aSentDatas) && $this->_aSentDatas[$selectParentName] == $value && false !== $selected)
        {
            $this->_aAttributs['selected'] = 'selected';
            $this->_valSelected == $value;
        }
        elseif(true === $selected)
        {
            $this->_aAttributs['selected'] = 'selected';
            $this->_valSelected == $value;
        }
        else
        {
            $this->_aAttributs['selected'] = null;
        }
        $sAttrs = ' value="'. $this->_value .'"';
        if(!is_null($options))
        {
            foreach ($options as $attr => $val)
            {
                $this->_aAttributs[$attr] = $val;
            }
        }
        foreach ($this->_aAttributs as $k => $v)
        {
            if(!empty($v) || $k == 'value')
            {
                $sAttrs .= ' '. $k .'="'. $v .'"';
            }
        }
        $sOption = '  <'. $this->_tag . $sAttrs .'>'. $this->_affiche .'</'. $this->_tag .'>';
        $this->_listeOptions[] = $sOption;
    }

    public function getSelected()
    {
        return $this->_valSelected;
    }

    public function __toString()
    {
        $sListeOptions = implode("\n", $this->_listeOptions);
        return $sListeOptions;
    }
}