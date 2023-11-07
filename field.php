<?php
namespace jemdev\form;
use jemdev\form\form;
use jemdev\form\process\validation;
use jemdev\form\tags\attributs_html5;
use jemdev\form\tags\attributs_xhtml;
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
 * Classe abstraite définissant les règles de création de
 * champs de formulaires.
 *
 * @author      Jean Molliné <jmolline@jem-dev.com>
 * @package     jemdev
 * @subpackage  form
 */
abstract class field
{
    /**
     * Classe définissant les attributs valides pour une balise données dans un formulaire.
     * @var jemdev\form\tags\attributesInterface
     */
    private $_oAttributes;
    private $_aDoctypes     = array('HTML','XHTML','HTML5');
    /**
     * Sorte de balise html
     *
     * @var string
     */
    protected $_tag;
    /**
     * Type pour les balise input
     *
     * @var string
     */
    protected $_type        = null;
    /**
     * Standard devant être utilisé.
     * Deux valeurs sont possibles, HTML ou XHTML, par défaut, ce
     * sera XHTML
     *
     * @var string
     */
    protected $_sDoctype    = 'XHTML';
    /**
     * Contenu de la balise lorsqu'il ne s'agit pas d'une balide vide.
     * Ne concerne ici que les balsies select et textarea.
     *
     * @var string
     */
    protected $_contenu     = null;
    /**
     * Attributs HTML de la balise
     *
     * @var array
     */
    protected $_aAttributs  = array();
    /**
     * Label du champ de formulaire
     *
     * @var string
     */
    protected $_label;
    /**
     * Valeur du champ.
     *
     * @var string
     */
    protected $_valChamp    = null;
    /**
     * Option sélectionnée dans le cas d'un champ de type select
     *
     * @var string
     */
    protected $_optionSelected = null;
    /**
     * Instance du formulaire en cours de construction.
     *
     * Sera utilisée par les champs input de type hidden pour le regrouppement
     * de tous les champs cachés du formulaire.
     * Sera utilisé par tous les champs de formulaire devant stocker des règles
     * de validation.
     *
     * @var jemdev\form\form
     */
    protected $_oForm;
    /**
     * Stockage des valeurs d'une saisie précédente pour ré-affichage
     * si des erreurs ont été relevées.
     *
     * @var array
     */
    protected $_aSentDatas  = array();
    /**
     * Stockage des règles définies pour le champ en cours de construction
     *
     * @var array
     */
    protected $_aRules      = array();
    /**
     * Indique si le champ a déjà été défini comme obligatoire.
     *
     * @var bool
     */
    protected $_bRequis     = false;
    protected $_locale      = 'fr';
    public    $methodesValidation;

    /**
     * Constructeur.
     *
     * @param   string  $locale
     * @param   form    $oForm
     */
    public function __construct(form $oForm)
    {
        $this->_oForm = $oForm;
        $this->_aExceptionErreurs = $oForm->_aExceptionErreurs;
        if($oForm instanceof form)
        {
            $rm      = strtoupper($oForm->_formMethod);
        }
        else
        {
            trigger_error("Erreur de transmission de l'objet Form.", E_USER_ERROR);
        }
        $this->_sDoctype = $oForm->doctype;
        $this->setListeAttributs($this->_tag, $this->_type);
        $aDatas = null;
        $srm = (isset($_SERVER['REQUEST_METHOD'])) ? $_SERVER['REQUEST_METHOD'] : 'GET';
        if($srm == $rm)
        {
            switch ($rm)
            {
                case 'POST':
                    $aDatas = $_POST;
                    break;
                case 'GET':
                    $aDatas = $_GET;
                    break;
                default:
                    $aDatas = null;
                    break;
            }
        }
        $this->_aSentDatas = $aDatas;
        /**
         * Méthodes de validation définies
         */
        $this->methodesValidation = validation::$methodesValidation;
        // Ajout des méthodes de classe de validatione externe s'il y a lieu
        $this->_setMethodesSuppl();
    }

    /**
     * Établissement de la liste des balises valides selon le DOCTYPE utilisé.
     * 
     * @param string|null $tag
     * @param string|null $type
     * 
     * @return void
     */
    public function setListeAttributs(?string $tag = null, ?string $type = null):void
    {
        if(!is_null($this->_tag))
        {
            /**
             * Liste des attributs pour le type de balise créée
             */
            switch ($this->_sDoctype)
            {
                case 'HTML5':
                    $attrs = attributs_html5::getAttrParBalise($this->_tag, $type);
                    break;
                case'HTML':
                case'XHTML':
                default:
                    $attrs = attributs_xhtml::getAttrParBalise($this->_tag, $type);
                    break;
            }
            foreach ($attrs as $attr)
            {
                if(isset($this->_aAttributs[$attr]))
                {
                    $this->_aAttributs[$attr] = null;
                }
            }
        }
    }

    /**
     * Ajout ou modification d'un attribut du champ
     *
     * La valeur de $attr devra être impérativement existante pour
     * ce type de balise.
     * Pour un textarea, on acceptera toutefois l'utilisation de l'attribut
     * value dont la valeur sera transférée vers le contenu de la balise.
     * Le blocage se limitera toutefois aux DOCTYPES définis dans le package. Si
     * un DOCTYPE non répertorié est mis en oeuvre, n'importe quel attribut pourra
     * être utilisé, conforme ou non.
     *
     * @param   string $attr    Attribut de la balise
     * @param   string $value   Valeur à affecter à la balise.
     * @return  form
     */
    public function setAttribute(string $attr, ?string $value = null)
    {
        if($this->_tag == 'textarea' && $attr == 'value')
        {
            $this->_contenu = $value;
        }
        elseif($this->_tag == 'select' && $attr == 'value')
        {
            $this->_optionSelected = $value;
        }
        elseif(false === $this->_oForm->bStrict || array_key_exists($attr, $this->_aAttributs))
        {
            $this->_aAttributs[$attr] = $value;
        }
        else
        {
            if($this->_tag == 'input')
            {
                $msg = sprintf($this->_oForm->_aExceptionErreurs['attr_input_invalide'], $attr, $this->_type);
            }
            else
            {
                $msg = sprintf($this->_oForm->_aExceptionErreurs['attr_interdit'], $attr);
            }
            trigger_error($msg, E_USER_WARNING);
        }
        return $this;
    }

    /**
     * Ajout d'un label pour le champ de formulaire
     *
     * @param   string $label
     * @return  form
     */
    public function setLabel(string $label): form
    {
        if(true === $this->_bRequis)
        {
            $label .= ' <span class="elementrequis">(*)</span>';
        }
        $this->_label = $label;
        return $this;
    }

    /**
     * Récupération de la valeur du label
     *
     * @return string
     */
    public function getLabel(): string
    {
        return $this->_label;
    }

    /**
     * Récupération de la valeur de propriété de l'instance.
     *
     * @param   string $prop
     * @return  string
     */
    public function __get(string $prop): string
    {
        if($prop == 'label')
        {
            return $this->_label;
        }
        elseif($prop == '_aExceptionErreurs')
        {
            return $this->_oForm->_aExceptionErreurs;
        }
        elseif($prop == 'value')
        {
            if(array_key_exists($this->name, $this->_oForm->aDatas))
            {
                return($this->_oForm->aDatas[$this->name]);
            }
            elseif($this->_type == 'select')
            {
                return $this->_optionSelected;
            }
            elseif($this->_type == 'textarea')
            {
                return $this->_valChamp;
            }
            elseif(!array_key_exists('value', $this->_aAttributs))
            {
                return(null);
            }
            else
            {
                return($this->_aAttributs['value']);
            }
        }
        elseif (array_key_exists($prop, $this->_aAttributs))
        {
            return $this->_aAttributs[$prop];
        }
        elseif(($this->_type == 'checkbox' || $this->_type == 'radio') && $prop == 'checked')
        {
            $retour = (array_key_exists($prop, $this->_aAttributs) && $this->_aAttributs[$prop] == 'checked') ? true : false;
            return($retour);
        }
        else
        {
            $msg = sprintf($this->_oForm->_aExceptionErreurs['prop_inaccessible'], $prop, __CLASS__);
            trigger_error($msg, E_USER_WARNING);
        }
    }

    /**
     * Insertion du contenu dans la balise.
     * Si toutefois il s'agit d'une balise de type vide, on retournera
     * une exception.
     *
     * Cette méthode devra être surchargée dans la classe de la balise
     * dont on veut pouvoir modifier le contenu, par exemple la balise
     * textarea peut avoir un contenu.
     *
     * @param   string $contenu
     */
    public function setContenu(string $contenu): void
    {
        trigger_error($this->_oForm->_aExceptionErreurs['contenu_interdit'], E_USER_NOTICE);
    }

    /**
     * Enregistre une règle de validation pour le champ créé.
     *
     * On peut enregistrer autant de règles que nécessaire pour le champ traité.
     * Le premier paramètre sera l'une des regles définies dans jemdev\form\process\validation
     *
     * Ce second paramètre sera toujours un tableau indexé avec une à n valeurs.
     * le second paramètre sera indispensable pour les règles suivantes :
     * - minlength      = 0 => valeur minimum
     * - maxlength      = 0 => valeur maximum
     * - rangelength    = 0 => valeur minimum, 1 => valeur maximum
     * - minval         = 0 => valeur minimum
     * - maxval         = 0 => valeur maximum
     * - rangeval       = 0 => valeur minimum, 1 => valeur maximum
     * - superieur      = 0 => valeur minimum
     * - inferieur      = 0 => valeur maximum
     * - regex          = 0 => expression régulière
     * - comparer       = 0 => attribut name du champ à comparer
     * - callback       = 0 => instance de la classe externe, 1 => méthode à invoquer
     *
     * @param   string  $rule       Nom de la règle à appliquer
     * @param   string  $msg        Message à afficher si règle non vérifiée.
     * @param   array   $val        Valeur à vérifier (Optionnel selon la règle à appliquer)
     * @see     jemdev\form\process\validation
     * 
     * @TODO    Tenter de simplifier le passage de paramètre pour permettre indifféremment l'envoi
     *          de tableaux que de valeurs scalaires.
     */
    public function setRule(string $rule, string $msg, ?array $val = null): form
    {
        if($rule == 'required' || $rule == 'differentDe')
        {
            $this->_bRequis = true;
        }
        $this->_aRules[] = $rule;
        if(is_null($val))
        {
            if(in_array($rule, $this->methodesValidation))
            {
                $this->_oForm->setRegleValidation($this->_aAttributs['name'], array($rule), $msg);
            }
            else
            {
                $msg = sprintf($this->_aExceptionErreurs['method_valid_inexistante'], $rule);
                trigger_error($msg, E_USER_NOTICE);
            }
        }
        else
        {
            /**
             * Méthodes attendant 1 ou plusieurs paramètres en plus du message d'erreur.
             */
            if(is_array($val))
            {
                $this->_oForm->setRegleValidation($this->_aAttributs['name'], array($rule, $val), $msg);
            }
            else
            {
                $this->_oForm->setRegleValidation($this->_aAttributs['name'], array($rule, $val), $msg);
            }
        }
        $strRequis = ' <span class="elementrequis">(*)</span>';
        if(true == $this->_bRequis && !empty($this->_label) && !strstr($this->_label, $strRequis))
        {
            $this->_label .= $strRequis;
        }
        return $this;
    }

    /**
     * Récupération de la valeur postée lorsque le champ est un tableau.
     *
     * En récupérant l'attribut name, on va isoler les index de tableau et
     * reconstruire dynamiquement la structure de la variable pour récupérer
     * la valeur dans les données postées.
     * Exemple : un champ de type texte qui a pour attribut name monchamp[1]
     * ou encore monchamp[1][2] (deux à 7 niveaux, index numériques ou chaines.)
     *
     * @param   string  $aField
     * @param   array   $aDatas
     * 
     * @return string|null
     */
    public static function getValueFromArrayData(string $name, array $aDatas): string|null
    {
        $masque = "#^[^[]+(\[[^]]+\])+#";
        if(preg_match($masque, $name))
        {
            $sName = str_replace("]", '', $name);
            $sName = str_replace("'", '', $sName);
            $indexes = explode('[', $sName);
            $index = '$aDatas';
            foreach($indexes as $idx)
            {
                $index .= (is_int($idx)) ? '['. $idx .']' : "['". $idx ."']";
            }
            eval( '$val = isset('. $index .') ? '. $index .' : null;');
        }
        else
        {
            $val = (isset($aDatas[$name])) ? $aDatas[$name] : null;
        }
        return($val);
    }

    /**
     * Ajoute les méthodes spécifiques de validation du helper
     * à la collection de méthodes génériques de base.
     */
    private function _setMethodesSuppl(): void
    {
        if(count($this->_oForm->_aClasseValidationExterne) > 0)
        {
            $helper = $this->_oForm->_aClasseValidationExterne['classe'];
            $reflection = new \ReflectionClass($helper);
            $aProps = $reflection->getProperties();
            $aMethodesSupplementaires = array();
            foreach($aProps as $prop)
            {
                if($prop->name == 'aMethodesSupplementaires')
                {
                    $aMethodesSupplementaires = $prop->getValue();
                }
            }
            foreach($aMethodesSupplementaires as $methode)
            {
                $this->methodesValidation[] = $methode;
            }
        }
   }

    /**
     * Retourne la chaine correspondant à l'objet instancié.
     */
    public function __toString(): string
    {
        return('');
    }
}
