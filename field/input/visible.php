<?php
namespace mje\form\field\input;
use mje\form\form;
use mje\form\field\input;
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
 * Construction d'une balise input visible, donc n'importe lequel sauf
 * le type hidden.
 *
 * @author      Jean Molliné <jmolline@gmail.com>
 * @package     mje
 * @subpackage  Form
 */
class visible extends input
{
    /**
     * Constructeur.
     *
     * Cette classe étant appelée via la méthode mje\form::__call, les
     * paramètres envoyés individuellement arrivent ici sous la forme
     * d'un tableau indexé.
     * Les paramètres réellement attendus sont les attributs de base :
     * \@param String $props[0] = id    Identifiant du champ
     * \@param String $props[1] = name  Attribut name du champ (Facultatif, sera remplacé par
     *                                  la valeur de l'id si absent)
     * \@param String $props[2] = value Valeur du champ (Facultatif)
     * \@param String $props[3] = label Label pour le champ si nécessaire (Facultatif)
     *
     * D'autres attributs sont possibles sous réserve qu'ils soient définis
     * dans le constructeur ou les classes parentes. Par exemple, l'attribut
     * style est générique et peut être alimenté avec la méthode setAttribute().
     *
     * @param   string  $type       Type de champ input, ex. text, submit, etc...
     * @param   Array   $props      Voir ci-dessus
     * @param   String  $doctype    Standard à utiliser (HTML ou XHTML par défaut)
     * @param   Object  $oForm      Instance du formulaire en cours de construction
     */
    public function __construct($type, $props, $doctype, form $oForm)
    {
        /**
         * Récupération des paramètres.
         */
        $this->_sDoctype = $doctype;
        if($type == 'radio' && !isset($props[1]))
        {
            $msg = sprintf($oForm->_aExceptionErreurs['name_radio_manquant'], $id);
            trigger_error($msg, E_USER_WARNING);
        }
        $id     = $props[0];
        $name   = (isset($props[1])) ? $props[1] : $id;
        $value  = (isset($props[2])) ? $props[2] : null;
        $label  = (isset($props[3])) ? $props[3] : null;
        $this->_oForm = $oForm;
        parent::__construct($id, $type, $oForm);
        if($type == 'file')
        {
            /**
             * Pour que ce type de champ soit opérationnel, il est indispensable
             * de modifier l'attribut enctype de la balise form : on modifie
             */
            $this->_oForm->_formEnctype = "multipart/form-data";
            /**
             * On ajoute également un champ caché limitant la taille du fichier selon
             * ce qui est défini dans la configuration de PHP
             */
            $ini_size = ini_get('upload_max_filesize');
            $masque = "#([0-9]+)([A-Z])#";
            $nb = preg_replace($masque, "$1", $ini_size);
            $f  = preg_replace($masque, "$2", $ini_size);
            $m  = ($f == 'M') ? 1024 : 1;
            $size = $nb * $m;
            $this->_oForm->hidden('MAX_FILE_SIZE', 'MAX_FILE_SIZE', $size);
        }
        elseif($type == 'radio' || $type == 'checkbox')
        {
            $select = (isset($props[4])) ? $props[4] : null;
            if((!is_null($this->_aSentDatas) && isset($this->_aSentDatas[$name]) && !is_null($value) && $this->_aSentDatas[$name] == $value) || true === $select)
            {
                $this->_aAttributs['checked'] = 'checked';
            }
            else
            {
                $this->_aAttributs['checked'] = null;
            }
        }
        /**
         * Initialisation des propriétés de base.
         */
        $this->_aAttributs['id']    = $id;
        $name = (!is_null($name) && !empty($name))
            ? $name
            : $id;
        $this->_aAttributs['name']  = $name;
        if(!is_null($value))
        {
            $this->_aAttributs['value'] = $value;
        }
        if(!is_null($label))
        {
            $this->_label               = $label;
        }
    }
}
