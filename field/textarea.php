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
 * Création d'une balise textarea
 *
 * @author      Jean Molliné <jmolline@jem-dev.com>
 * @package     jemdev
 * @subpackage  form
 */
class textarea extends field
{
    /**
     * Constructeur.
     *
     * Définit une zone de saisie de texte libre. Les paramètres attendus sont
     * sensiblement les mêmes que pour un champ input, le doctype en moins.<br />
     * Notez que l'attribut value est invalide pour un textarea. Cependant on laisse ici
     * la possibilité de l'utiliser et le cas échéant, ce sera intercepté et transféré
     * vers le contenu de la balise.<br />
     *
     * D'autres attributs sont possibles sous réserve qu'ils soient définis
     * dans le constructeur ou les classes parentes. Par exemple, l'attribut
     * style est générique et peut être alimenté avec la méthode setAttribute().
     *
     * @param array  $props             Voir détail ci-dessous
     * @param string $props[0] = id     Identifiant du champ
     * @param string $props[1] = name   Attribut name du champ (Facultatif, sera remplacé par
     *                                  la valeur de l'id si absent)
     * @param string $props[2] = value  Valeur du champ (Facultatif)
     * @param string $props[3] = label  Label pour le champ si nécessaire (Facultatif)
     * @param form   $oForm             Instance du formulaire en cours de construction
     */
    public function __construct(array $props, form $oForm)
    {
        parent::__construct($oForm);
        $this->_tag = 'textarea';
        $this->_oForm = $oForm;
        $this->_aAttributs['id'] = $props[0];
        $this->_aAttributs['name']  = (isset($props[1])) ? $props[1] : $props[0];
        $value  = (isset($props[2])) ? $props[2] : null;
        $this->_contenu = $value;
        if(isset($props[3]))
        {
            $this->_label = $props[3];
        }
    }

    /**
     * Pré-remplit le textarea en ajoutant une valeur dans la zone de saisie.
     *
     * @param   string  $contenu
     * @return  string
     */
    public function setContenu(string $contenu): textarea
    {
        $this->_contenu = $contenu;
        $this->_valChamp = $contenu;
        return $this;
    }

    public function __toString(): string
    {
        if(isset($this->_aSentDatas[$this->_aAttributs['name']]))
        {
            $this->setContenu($this->_aSentDatas[$this->_aAttributs['name']]);
        }
        $sTextarea = '';
        $textAttrs = '';
        if(!empty($this->_aAttributs['rows']) && !empty($this->_aAttributs['cols']))
        {
            /**
             * Ajout des autres attributs
             */
            foreach ($this->_aAttributs as $k => $v)
            {
                if(!empty($v) && $k != 'rows' && $k != 'cols')
                {
                    $textAttrs .= ' '. $k .'="'. $v .'"';
                }
            }
            /**
             * Ajout des attributs obligatoires
             */
            $textAttrs .= ' rows="'. $this->_aAttributs['rows'] .'" cols="'. $this->_aAttributs['cols'] .'"';

            $sTextarea = '<textarea'. $textAttrs .'>'. $this->_contenu .'</textarea>';
        }
        else
        {
            $msg = sprintf($this->_aExceptionErreurs['attr_textarea_manquant'], $this->_aAttributs['id']);
            $sTextarea = '<span class="erreur">'. $msg .'</span>';
        }
        return $sTextarea;
    }
}