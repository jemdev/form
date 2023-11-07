<?php
namespace jemdev\form\field;
use jemdev\form\form;
use jemdev\form\field;
/**
 * @package     jemdev
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
 * @author      Jean Molliné <jmolline@jem-dev.com>
 * @package     jemdev
 * @subpackage  form
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
     * @param   string  $value      Valeur de l'attribut value
     * @param   string  $affiche    Valeur affichée dans la liste
     * @param   bool $selected   Optionnel, définit si une valeur doit être sélectionnée par défaut.
     * @param   array|null $selectParentName
     * @param   array   $options    Paires attribut/valeur supplémentaires (Optionnel)
     * 
     * @return void
     */
    public function addValue($value, $affiche, $selected = false, $selectParentName = null, ?array $options = null): void
    {
        $this->_value   = $value;
        $this->_affiche = $affiche;
        if(false == $selected && (isset($this->_aSentDatas) && !is_null($selectParentName) && array_key_exists($selectParentName, $this->_aSentDatas) && $this->_aSentDatas[$selectParentName] == $value))
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

    public function getSelected(): string
    {
        return $this->_valSelected;
    }

    public function __toString(): string
    {
        $sListeOptions = implode("\n", $this->_listeOptions);
        return $sListeOptions;
    }
}
