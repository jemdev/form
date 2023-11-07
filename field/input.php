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
 * Classe abstraite de création de champs de formulaire input.
 *
 * @author      Jean Molliné <jmolline@jem-dev.com>
 * @package     jemdev
 * @subpackage  form
 */
abstract class input extends field
{
    /**
     * Attributs de la balise.
     *
     * @var array
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
    public function __construct(string $id, string $type, form $oForm)
    {
        parent::__construct($oForm);
        $this->_tag = 'input';
        $this->_type = $type;
    }

    public function __toString(): string
    {
        if(empty($this->_aSentDatas))
        {
            $this->_aSentDatas = [];
        }
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