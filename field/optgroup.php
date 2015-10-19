<?php
namespace jemdev\form\field;
use jemdev\form\form;
use jemdev\form\field;
use jemdev\form\field\option;
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
 * Enter description here...
 *
 * @author      Jean Molliné <jmolline@gmail.com>
 * @package     mje
 * @subpackage  form
 */
class optgroup extends field
{
    private $_oOption;
    private $_nbOptions;
    /**
     * Constructeur.
     *
     */
    public function __construct($label, form $oForm)
    {
        parent::__construct($oForm);
        $this->_aAttributs['label'] = $label;
        $this->_oForm       = $oForm;
        $this->_tag         = 'optgroup';
        $this->_contenu     = '';
        $this->_nbOptions   = 0;
    }

    /**
     * Ajoute un item dans la liste de sélection.
     *
     * @param   String  $valeur     Contenu de l'attribut value
     * @param   String  $affiche    Valeur affichée dans la liste de sélection
     * @param   Boolean $selected   Optionnel, option sélectionnée, par défaut false.
     * @return  Object
     */
    public function addOption($valeur, $affiche, $selected = false, $parentName, $options = null)
    {
        if(!isset($this->_oOption))
        {
            $this->_oOption = new option($this->_oForm);
        }
        $this->_oOption->addValue($valeur, $affiche, $selected, $parentName, $options);
        $this->_nbOptions++;
        return $this;
    }

    /**
     * Retourne le nombre d'options de la liste de sélection.
     *
     * @return Int
     */
    public function getNbOptions()
    {
        return($this->_nbOptions);
    }

    public function getSelected()
    {
        return $this->_oOption->getSelected();
    }

    public function __toString()
    {
        $sListeOptions = sprintf('%s', $this->_oOption);
        if(!empty($sListeOptions))
        {
            $sAttrs = '';
            foreach ($this->_aAttributs as $k => $v)
            {
                if(!empty($v))
                {
                    $sAttrs .= ' '. $k .'="'. $v .'"';
                }
            }
            $sListe  = '<'. $this->_tag . $sAttrs .'>'."\n" . $sListeOptions ."\n" .'</'. $this->_tag .'>';
        }
        else
        {
            $msg = sprintf($this->_aExceptionErreurs['liste_options_vide'], $this->_aAttributs['label']);
            $sListe = '<span class="erreur">'. $msg .'</span>';
        }
        return($sListe);
    }
}