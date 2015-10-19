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
 * Classe abstraite de création de champs de formulaire input.
 *
 * @author      Jean Molliné <jmolline@gmail.com>
 * @package     mje
 * @subpackage  Form
 */
abstract class input extends field
{
    /**
     * Attributs de la balise.
     *
     * @var Array
     */
    protected $_aAttributs = array(
        'id'    => null,
        'name'  => null,
        'value' => null,
        'class' => null,
        'style' => null
    );
    /**
     * Constructeur.
     *
     */
    public function __construct($id, $type, form $oForm)
    {
        $this->_tag = 'input';
        $this->_type = $type;
        parent::__construct($oForm);
    }

    public function __toString()
    {
        $getVal = parent::getValueFromArrayData($this->_aAttributs['name'], $this->_aSentDatas);
        $valeur = (!empty($this->_aAttributs['value']))
            ? $this->_aAttributs['value']
            : $getVal;
        if(isset($valeur) && $this->_type != 'password')
        {
            $this->_aAttributs['value'] = $valeur;
        }
        if(($this->_type == 'radio' || $this->_type == 'checkbox') && !is_null($this->_aAttributs['value']) && !is_null($this->_aAttributs['checked']) && $getVal == $this->_aAttributs['value'])
        {
            $this->_aAttributs['checked'] = 'checked';
        }
        $sChamp  = '<input type="'. $this->_type .'"';
        foreach ($this->_aAttributs as $k => $v)
        {
            if((!empty($v) && !is_null($v)) || $k == 'value')
            {
                $sChamp .= ' '. $k .'="'. $v .'"';
            }
        }
        $sChamp .= ($this->_sDoctype == 'HTML') ? '>' : ' />';
        return $sChamp;
    }
}