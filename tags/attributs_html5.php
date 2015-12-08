<?php
/**
 * Ce code est fourni tel quel sans garantie.
 * Vous avez la liberté de l'utiliser et d'y apporter les modifications
 * que vous souhaitez. Vous devrez néanmoins respecter les termes
 * de la licence CeCILL dont le fichier est joint à cette librairie.
 *
 * @package     jemdev
 * @see http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html
 */
namespace jemdev\form\tags;
use jemdev\form\form;
/**
 * Gestion des attributs html5 de balise d'éléments de formulaire.
 *
 * @author      Jean Molliné <jmolline@gmail.com>
 * @package     jemdev
 * @subpackage  Form
 * @version     0.1 alpha
 */
class attributs_html5 implements attributesInterface
{
    /**
     * Liste des types de champs de formulaire valides en HTML5.
     * @var array
     */
    public static $aFormTags = array(
        'textarea',
        'select',
        'datalist',
        'input' => array(
            'hidden',
            'text',
            'search',
            'tel',
            'url',
            'email',
            'password',
            'date',
            'time',
            'number',
            'range',
            'color',
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
     * Liste des attributs html de balise des éléments composant un formulaire selon le standard HTML5
     *
     * @var Array
     */
    private static $_aAttrs = array(
        'global'    => array(
            'accesskey',
            'class',
            'contenteditable',
            'contextmenu',
            'dir',
            'hidden',
            'id',
            'lang',
            'spellcheck',
            'style',
            'tabindex',
            'title',
            'translate'
        ),
        'events'    => array(
            'onabort',
            'onblur',
            'oncancel',
            'oncanplay',
            'oncanplaythrough',
            'onchange',
            'onclick',
            'oncuechange',
            'ondblclick',
            'ondurationchange',
            'onemptied',
            'onended',
            'onerror',
            'onfocus',
            'oninput',
            'oninvalid',
            'onkeydown',
            'onkeypress',
            'onkeyup',
            'onload',
            'onloadeddata',
            'onloadedmetadata',
            'onloadstart',
            'onmousedown',
            'onmouseenter',
            'onmouseleave',
            'onmousemove',
            'onmouseout',
            'onmouseover',
            'onmouseup',
            'onmousewheel',
            'onpause',
            'onplay',
            'onplaying',
            'onprogress',
            'onratechange',
            'onreset',
            'onresize',
            'onscroll',
            'onseeked',
            'onseeking',
            'onselect',
            'onshow',
            'onstalled',
            'onsubmit',
            'onsuspend',
            'ontimeupdate',
            'ontoggle',
            'onvolumechange',
            'onwaiting'
        ),
        'form'      => array(
            'accept-charset',
            'action',
            'autocomplete',
            'enctype',
            'method',
            'name',
            'novalidate',
            'target'
        ),
        'label'     => array(
            'form',
            'for'
        ),
        'input'     => array(
            'accept',
            'alt',
            'autocomplete',
            'autofocus',
            'checked',
            'dirname',
            'disabled',
            'form',
            'formaction',
            'formenctype',
            'formmethod',
            'formnovalidate',
            'formtarget',
            'height',
            'list',
            'max',
            'maxlength',
            'min',
            'minlength',
            'multiple',
            'name',
            'pattern',
            'placeholder',
            'readonly',
            'required',
            'size',
            'src',
            'step',
            'type',
            'value',
            'width'
        ),
        'button'    => array(
            'autofocus',
            'disabled',
            'form',
            'formaction',
            'formenctype',
            'formmethod',
            'formnovalidate',
            'formtarget',
            'name',
            'type',
            'value'
        ),
        'select'    => array(
            'autofocus',
            'disabled',
            'form',
            'multiple',
            'name',
            'required',
            'size'
        ),
        'datalist'  => array(),
        'optgroup'  => array(
            'disabled',
            'label'
        ),
        'option'    => array(
            'disabled',
            'label',
            'selected',
            'value'
        ),
        'textarea'    => array(
            'autofocus',
            'cols',
            'dirname',
            'disabled',
            'form',
            'maxlength',
            'minlength',
            'name',
            'placeholder',
            'readonly',
            'required',
            'rows',
            'wrap'
        )
    );
    /**
     * Constructeur.
     * Aucune action particulière à effectuer ici.
     */
    function __construct(){}
    /**
     * Liste des attributs html5 valides pour une balise donnée.
     *
     * La méthode est statique, on a en effet nul besoin d'un objet
     *
     * @param   String  $balise     Nom de la balise
     * @return  Array
     */
    public static function getAttrParBalise($balise)
    {
        $aAttrs = array();
        switch ($balise)
        {
            case 'form':
                $aAttrs = array_merge(
                    self::$_aAttrs['global'],
                    self::$_aAttrs['events'],
                    self::$_aAttrs['form']
                );
                break;
            case 'label':
                $aAttrs = array_merge(
                    self::$_aAttrs['global'],
                    self::$_aAttrs['events'],
                    self::$_aAttrs['label']
                );
                break;
            case 'input':
                $aAttrs = array_merge(
                    self::$_aAttrs['global'],
                    self::$_aAttrs['events'],
                    self::$_aAttrs['input']
                );
                break;
            case 'select':
                $aAttrs = array_merge(
                    self::$_aAttrs['global'],
                    self::$_aAttrs['events'],
                    self::$_aAttrs['select']
                );
                break;
            case 'optgroup':
                $aAttrs = array_merge(
                    self::$_aAttrs['global'],
                    self::$_aAttrs['events'],
                    self::$_aAttrs['optgroup']
                );
                break;
            case 'option':
                $aAttrs = array_merge(
                    self::$_aAttrs['global'],
                    self::$_aAttrs['events'],
                    self::$_aAttrs['option']
                );
                break;
            case 'textarea':
                $aAttrs = array_merge(
                    self::$_aAttrs['global'],
                    self::$_aAttrs['events'],
                    self::$_aAttrs['textarea']
                );
                break;
            case 'datalist':
                $aAttrs = array_merge(
                    self::$_aAttrs['global'],
                    self::$_aAttrs['events'],
                    self::$_aAttrs['datalist']
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

