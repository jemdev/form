<?php
namespace jemdev\form\locale;
/**
 * @package     mje
 *
 * Ce code est fourni tel quel sans garantie.
 * Vous avez la liberté de l'utiliser et d'y apporter les modifications
 * que vous souhaitez. Vous devrez néanmoins respecter les termes
 * de la licence CeCILL dont le fichier est joint à cette librairie.
 * {@see http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html}
 */
if(!class_exists('fr'))
{
    class fr
    {
        /**
         * Messages d'erreur / Error messages
         * @version  Français
         */
        public static $msgs_exceptions = array(
            'methode_inexistante'       => "La méthode %s est inexistante pour la classe %s",
            'element_inexistant'        => "Élément %s inexistant",
            'attr_interdit'             => "Ce type de champ n'accepte pas l'attribut %s",
            'contenu_interdit'          => "Ce type de champ n'accepte pas de contenu",
            'attr_input_invalide'       => "L'attribut «%s» n'est pas valide pour un champ input de type «%s».",
            'prop_inaccessible'         => "Propriété %s de l'objet %s inaccessible",
            'balise_hors_form'          => "La balise %s n'est pas traitée dans cette classe pour les formulaires",
            'attr_textarea_manquant'    => "Les attributs «rows» et «cols» sont obligatoires pour le textarea «%s»",
            'liste_options_vide'        => "Erreur : la liste «%s» ne contient aucun choix possible.",
            'method_valid_manquante'    => "Un paramètre de valeur est requis pour la méthode de validation %s",
            'method_valid_inexistante'  => "Règle de validation %s inexistante",
            'attribut_champ_manquant'   => "La valeur de l'attribut %s du champ %s est obligatoire",
            'regle_valid_inexistante'   => "La règle %s n'a pas été définie dans la classe étendue du code de cette application: sonnez les cloches du développeur distrait",
            'name_radio_manquant'       => "L'attribut « name » requiert une valeur pour le bouton radio %s",
            'balise_html5'              => "La balise %s est exclusive à HTML5 et ne sera pas valide en %s",
            'doctype_inexistant'        => "Le DOCTYPE « %s » indiqué n'existe pas, la valeur XHTML sera appliquée par défaut"
        );

        /**
         * Titre des messages d'erreur par défaut (validation)
         * @todo Messages à définir
         */
        public static $msgs_erreur_validation = array(
            'titremessages'             => 'Des erreurs ont été relevées ou des données sont manquantes'
        );
    }
}
