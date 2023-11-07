<?php
namespace jemdev\form\tags;
use jemdev\form\form;
/**
 * Ce code est fourni tel quel sans garantie.
 * Vous avez la liberté de l'utiliser et d'y apporter les modifications
 * que vous souhaitez. Vous devrez néanmoins respecter les termes
 * de la licence CeCILL dont le fichier est joint à cette librairie.
 *
 * @package     jemdev
 * @see http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html
 */
/**
 * Gestion des attributs xhtml de balise d'éléments de formulaire.
 *
 * @author      Jean Molliné <jmolline@jem-dev.com>
 * @package     jemdev
 * @subpackage  form
 */
class attributs_xhtml implements attributesInterface
{
    public static $aFormTags = array(
        'textarea',
        'select',
        'input' => array(
            'hidden',
            'text',
            'password',
            'checkbox',
            'radio',
            'file',
            'submit',
            'image',
            'reset',
            'button'
        )
    );

    /**
     * Liste des attributs xhtml de balise des éléments composant un formulaire selon le standard XHTML
     *
     * @var array
     */
    private static $_aAttrs = array(
        'core'      => array(
            'id',
            'class',
            'style',
            'title'
        ),
        'i18n'      => array(
            'lang',
            'xml:lang',
            'dir'
        ),
        'events'    => array(
            'onclick',
            'ondblclick',
            'onmousedown',
            'onmouseup',
            'onmouseover',
            'onmousemove',
            'onmouseout',
            'onkeypress',
            'onkeydown',
            'onkeyup'
        ),
        'focus'     => array(
            'accesskey',
            'tabindex',
            'onfocus',
            'onblur'
        ),
        'form'      => array(
            'action',
            'method',
            'enctype',
            'onsubmit',
            'onreset',
            'accept',
            'accept-charset'
        ),
        'label'     => array(
            'for',
            'accesskey',
            'onfocus',
            'onblur'
        ),
        'input'     => array(
            'type',
            'name',
            'value',
            'checked',
            'disabled',
            'readonly',
            'size',
            'maxlength',
            'src',
            'alt',
            'usemap',
            'onselect',
            'onchange',
            'accept',
            'autocomplete'
        ),
        'select'    => array(
            'name',
            'size',
            'multiple',
            'disabled',
            'tabindex',
            'onfocus',
            'onblur',
            'onchange',
            'autocomplete'
        ),
        'optgroup'  => array(
            'disabled',
            'label'
        ),
        'option'    => array(
            'selected',
            'disabled',
            'label',
            'value'
        ),
        'textarea'    => array(
            'name',
            'rows',
            'cols',
            'disabled',
            'readonly',
            'onselect',
            'onchange',
            'autocomplete'
        )
    );
    /**
     * Constructeur.
     *
     */
    public function __construct()
    {}
    /**
     * Liste des attributs xhtml valides pour une balise donnée.
     *
     * La méthode est statique, on a en effet nul besoin d'un objet
     *
     * @param   string  $balise     Nom de la balise
     * @return  array
     */
    public static function getAttrParBalise(string $balise): array
    {
        $aAttrs = array();
        switch ($balise)
        {
            case 'form':
                $aAttrs = array_merge(
                    self::$_aAttrs['core'],
                    self::$_aAttrs['i18n'],
                    self::$_aAttrs['events'],
                    self::$_aAttrs['form']
                );
                break;
            case 'label':
                $aAttrs = array_merge(
                    self::$_aAttrs['core'],
                    self::$_aAttrs['i18n'],
                    self::$_aAttrs['events'],
                    self::$_aAttrs['label']
                );
                break;
            case 'input':
                $aAttrs = array_merge(
                    self::$_aAttrs['core'],
                    self::$_aAttrs['i18n'],
                    self::$_aAttrs['events'],
                    self::$_aAttrs['focus'],
                    self::$_aAttrs['input']
                );
                break;
            case 'select':
                $aAttrs = array_merge(
                    self::$_aAttrs['core'],
                    self::$_aAttrs['i18n'],
                    self::$_aAttrs['events'],
                    self::$_aAttrs['select']
                );
                break;
            case 'optgroup':
                $aAttrs = array_merge(
                    self::$_aAttrs['core'],
                    self::$_aAttrs['i18n'],
                    self::$_aAttrs['events'],
                    self::$_aAttrs['optgroup']
                );
                break;
            case 'option':
                $aAttrs = array_merge(
                    self::$_aAttrs['core'],
                    self::$_aAttrs['i18n'],
                    self::$_aAttrs['events'],
                    self::$_aAttrs['option']
                );
                break;
            case 'textarea':
                $aAttrs = array_merge(
                    self::$_aAttrs['core'],
                    self::$_aAttrs['i18n'],
                    self::$_aAttrs['events'],
                    self::$_aAttrs['focus'],
                    self::$_aAttrs['textarea']
                );
                break;
            default:
                $loc = (defined('LOCALE_LANG')) ? LOCALE_LANG : 'fr';
                $fichier_locale = realpath(dirname(__FILE__)) . DS .'locale'. DIRECTORY_SEPARATOR . $loc .'.php';
                include($fichier_locale);
                $msg  = sprintf(form::$_aExceptionErreurs['balise_hors_form'], $balise);
                trigger_error($msg, E_USER_WARNING);
        }
        return $aAttrs;
    }
}