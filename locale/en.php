<?php
namespace jemdev\form\locale;
/**
 * @package     jemdev
 *
 * Ce code est fourni tel quel sans garantie.
 * Vous avez la liberté de l'utiliser et d'y apporter les modifications
 * que vous souhaitez. Vous devrez néanmoins respecter les termes
 * de la licence CeCILL dont le fichier est joint à cette librairie.
 * {@see http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html}
 */
class en
{
    /**
     * Messages d'erreur / Error messages
     * @version  English
     */
    public static $msgs_exceptions = array(
        'methode_inexistante'       => "The %s method doesn't exist in the class %s",
        'element_inexistant'        => "Element %s doesn't exist",
        'attr_interdit'             => "The attribute %s is not allowed in this kind of field",
        'contenu_interdit'          => "Content is not allowed in this kind of field",
        'attr_input_invalide'       => "Attribute «%s» is not allowed for a «%s» input field.",
        'prop_inaccessible'         => "Property %s of %s is unreachable or doesn't exist.",
        'balise_hors_form'          => "The tag %s is not prerocessed in this form's class",
        'attr_textarea_manquant'    => "Attributs «rows» and «cols» are required for the textarea «%s»",
        'liste_options_vide'        => "Error : they're no option defined in the «%s» select list.",
        'method_valid_manquante'    => "A value parameter is required for the validation method %s",
        'method_valid_inexistante'  => "Validation rule %s doesn't exist",
        'attribut_champ_manquant'   => "A value for %s attribute of the the %s field is required",
        'regle_valid_inexistante'   => "The rule %s has not been defined in your extended validation class : ring a bell to the developer",
        'name_radio_manquant'       => "The « name » attribute is required with a value for the radio button %s",
        'balise_html5'              => "The %s tag is HTML5 only and won't be valid in %s",
        'doctype_inexistant'        => "DOCTYPE « %s » doesn't exist, XHTML will be applied by default"
    );

    public static $msgs_erreur_validation = array(
        'titremessages'             => 'Some errors have occurred or some datas are missing'
    );
}
