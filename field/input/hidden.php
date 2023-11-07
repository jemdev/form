<?php
namespace jemdev\form\field\input;
use jemdev\form\field\input;
use jemdev\form\form;
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
 * Construction d'une balise input de type hidden.
 *
 * @author      Jean Molliné <jmolline@jem-dev.com>
 * @package     jemdev
 * @subpackage  form
 */
class hidden extends input
{
    protected $_oForm;
    /**
     * Constructeur.
     *
     * Cette classe étant appelée via la méthode jemdev\form::__call, les
     * paramètres envoyés individuellement arrivent ici sous la forme
     * d'un tableau indexé.
     * Les paramètres réellement attendus sont les attributs de base :
     * @param string $id        Identifiant du champ
     * @param string $name      Attribut name du champ (Facultatif, sera remplacé par la valeur de l'id si absent)
     * @param string $value     Valeur du champ (Facultatif)
     * @param string $label     Label pour le champ si nécessaire (Facultatif)
     *
     * D'autres attributs sont possibles sous réserve qu'ils soient définis
     * dans le constructeur ou les classes parentes. Par exemple, l'attribut
     * style est générique et peut être alimenté avec la méthode setAttribute().
     *
     * @param   array   $props      Voir ci-dessus
     * @param   string  $doctype    Standard à utiliser (HTML ou XHTML par défaut)
     * @param   form    $oForm      Instance du formulaire en cours de construction
     */
    public function __construct(array $props, string $doctype, form $oForm)
    {
        /**
         * Initialisation des propriétés de base.
         */
        $this->_sDoctype = $doctype;
        $id     = $props[0];
        $name   = (isset($props[1])) ? $props[1] : $id;
        $value  = (isset($props[2])) ? $props[2] : null;
        $this->_oForm  = $oForm;
        parent::__construct($id, 'hidden', $oForm);

        $this->_aAttributs['id']    = $id;
        $name = (!is_null($name) && !empty($name))
            ? $name
            : $id;
        $this->_aAttributs['name']  = $name;
        if(!is_null($value))
        {
            $this->_aAttributs['value'] = $value;
        }
        $this->_oForm->setHidden($id, sprintf('%s', $this));
    }
    /**
     * Surcharge de la méthode pour ajouter un enregistrement de la nouvelle
     * chaine dans les champs cachés du formulaire.
     *
     * @param   string $attr    Attribut de la balise
     * @param   string $value   Valeur à affecter à la balise.
     * @return  hidden
     */
    public function setAttribute(string $attr, string $value = null): hidden
    {
        if(array_key_exists($attr, $this->_aAttributs))
        {
            $this->_aAttributs[$attr] = $value;
            $this->_oForm->setHidden($this->_aAttributs['id'], sprintf('%s', $this));
        }
        else
        {
            $msg = sprintf($this->_aExceptionErreurs['attr_input_invalide'], $attr, $this->_type);
            trigger_error($msg, E_USER_WARNING);
        }
        return $this;
    }
}