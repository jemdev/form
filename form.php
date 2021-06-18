<?php
namespace jemdev\form;
use jemdev\form\field\select;
use jemdev\form\field\textarea;
use jemdev\form\field\input\hidden;
use jemdev\form\field\input\visible;
use jemdev\form\process\validation;
use jemdev\form\process\cleform;
use jemdev\form\field\datalist;

/**
 * @package     mje
 *
 * Ce code est fourni tel quel sans garantie.
 * Vous avez la liberté de l'utiliser et d'y apporter les modifications
 * que vous souhaitez. Vous devrez néanmoins respecter les termes
 * de la licence CeCILL dont le fichier est joint à cette librairie.
 * {@see http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html}
 *
 * Au cas où cette constante ne serait pas déjà définie dans la
 * configuration de l'application, on cale la langue, ici en français.
 * Note pour les anglophones : quand un code commenté en anglais me plait
 * et qu'aucune traduction n'est disponible, je dois me démerder. Merci de
 * bien vouloir me rendre la pareille :-þ
 */

$al = array('fr', 'en');
$l  = (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && in_array($_SERVER['HTTP_ACCEPT_LANGUAGE'], $al)) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : 'fr';
(defined('LOCALE_LANG')) || define('LOCALE_LANG', $l);

/**
 * Classe de gestion de formulaire
 *
 * Cette classe est la seule du paquet qu'il soit nécessaire d'utiliser.
 * Toutes les autres classes seront automatiquement utilisées pour la création
 * et la configuration des différents éléments du formulaire.<br />
 *
 * <b>Attention aux noms des méthodes</b><br />
 * Les méthodes de la classe de validation générique sont protégées, par convention
 * elles sont toutes préfixées par un underscore. Pour des raisons de simplification,
 * les méthodes des classes étendues doivent respecter la même syntaxe.
 * Néanmoins, lors de la définition d'une règle de validation, on ne met pas ce
 * préfixe et on mentionnera ainsi 'required' et non '_required'.<br />
 *
 * @author      Jean Molliné <jmolline@gmail.com>
 * @package     mje
 * @subpackage  Form
 * @version     2.0 Ré-écriture du package intégrant les NAMESPACES
 * @license     CeCILL V2
 * @todo        Messages d'erreur par défaut lors de la validation pour les méthodes génériques utilisées.
 * @todo        Élaboration d'un système de callback de validation permettant l'utilisation de méthodes
 *              autres que dans une classe étendue.
 */
class form
{
    /**
     * DOCTYPE du document à produire.
     * La valeur ne pourra être que HTML ou XHTML et définira
     * si les balises dites «vides» doivent être fermées (<... />)
     * selon la syntaxe XML ou non (<...>) selon la syntaxe HTML4
     *
     * @var String
     */
    private $_sDoctype      = 'HTML5';
    private $_aValidDoctypes = array('HTML','HTML5','XHTML');
    /**
     * Identifiant du formulaire (attribut id)
     *
     * @var String
     */
    protected $_formId;
    /**
     * Url de traitement du formulaire.
     *
     * @var String
     */
    private $_formAction;
    /**
     * Méthode de transfert des données, get ou post.
     * Par défaut, vaudra la même valeur que la valeur par
     * défaut du standard (X)HTML à savoir get.
     *
     * @var String
     */
    protected $_formMethod;
    /**
     * Paramètre enctype de la balise form.
     *
     * @var String
     */
    private $_formEnctype;
    /**
     * Paramètre d'encodage des données.
     * var String
     */
    private $_formCharset = 'utf8';
    /**
     * Liste des champs du formulaire.
     *
     * @var Array
     */
    private $_aElements     = array();
    /**
     * Contenu html qui sera inséré entre les balises <form>
     *
     * @var String
     */
    private $_contenu;
    /**
     * Propriétés de la classe accessible via les méthodes magiques __get() et __set()
     *
     * @var Array
     */
    private $_aProps = array(
        'doctype'   => '_sDoctype',
        'action'    => '_formAction',
        'method'    => '_formMethod',
        'enctype'   => '_formEnctype',
        'contenu'   => '_contenu',
        'charset'   => '_charset'
    );
    /**
     * Liste des messages d'erreurs retournées lors des levées d'exceptions.
     *
     * @var Array
     */
    private $_aExceptionErreurs;
    /**
     * Liste des messages génériques d'erreurs retournées lors de la validation.
     *
     * @var Array
     */
    private $_aValidationErreurs;
    /**
     * Liste des balises de formulaire valide selon le DOCTYPE utilisé.
     * @var array
     */
    private $_aValidTags = array();
    /**
     * @var mjem\form\process\cleform
     */
    private $_oCle;
    /**
     * Liste des champs cachés.
     *
     * On stocke les champs cachés qui seront affichés groupés
     * en début de formulaire.
     *
     * @var Array
     */
    private $_aHiddens;
    /**
     * Les des messages pour les erreurs relevées lors de la validation.
     *
     * @var Array
     */
    private $_aErreursValidation;
    /**
     * Liste des règles de validation.
     *
     * @var Array
     */
    private $_aReglesValidation;
    /**
     * Classe de validation étendue propre au formulaire en cours.
     * Tableau associatif contenant deux valeurs :
     * - Le chemin absolu vers le fichier;
     * - le nom de la classe de validation.
     *
     * @var Array
     */
    private $_aClasseValidationExterne  = array();
    /**
     * Indique que le formulaire a fait l'objet d'un envoi.
     * @var Boolean
     */
    public  $bFormEnvoye = false;
    /**
     * Statut du formulaire.
     * Indique si le formulaire soumis a été validé avec succès ou
     * si des erreurs ont été relevées.
     *
     * @var Boolean
     */
    public  $bFormValide = false;
    /**
     * Données du formulaire récupérables après validation.
     * @var Array
     */
    public  $aDatas = array();
    /**
     * Fichier de messages selon la langue.
     * Ce fichier concerne esentiellement le développeur dans son utilisation du package
     * jemdev\form pour construire ses formulaires, les messages traitant les erreurs qu'il
     * puorrais commettre ponctuellement.
     *
     * @var jemdev\form\locale\langInterface
     */
    public $oLang;
    /**
     * Définit si la construction des champs de formulaires testera la validité
     * des attributs qui pourront être ajoutés ou non selon le DOCTYPE utilisé.
     *
     * Attention, si cette valeur vaut FALSE, rien ne bloquera la génération d'un
     * code HTML de formulaire non conforme et invalide.
     * Cette option ne fonctionne pour l'instant avec TRUE que pour XHTML
     *
     * @var boolean
     */
    public $bStrict = false;
    /**
     * Liste des méthodes de validation disponibles.
     * @var Array
     */
    private $_aMethodesSupp;
    private $_bSetBlocErreursJS = false;
    private $_bFormValidation   = true;
    protected $_masqueNombreFR = '#^([0-9]+),([0-9]+)$#';
    /**
     * Constructeur.
     *
     * @param   String  $id                 Identifiant de la balise <form>
     * @param   String  $method             Méthode (post ou get, optionnel, défaut get)
     * @param   String  $action             URL de traitement, optionnel, par défaut la page elle même.
     * @param   String  $enctype            Attribut enctype de la balise <form>, défaut « application/x-www-form-urlencoded »
     * @param   Array   $aClassValidExterne Si des règles spécifiques de validation ont été définies,
     *                                      chemin et nom de la classe à utiliser.
     */
    public function __construct($id = null, $method = 'get', $action = null, $enctype = null, $aClassValidExterne = null)
    {
        $this->_aHiddens    = array();
        $this->_formId      = (!is_null($id)) ? $id : 'form1';
        $this->_formAction  = $action;
        $this->_formMethod  = $method;
        $this->_formEnctype = (!is_null($enctype)) ? $enctype : 'application/x-www-form-urlencoded';
        $this->_setLang();
        $this->_init($aClassValidExterne);
        $oDatas = new datas($this);
    }

    /**
     * Appel magique de classes pour les éléments du formulaire.
     *
     * En appelant une méthode de classe comme apr exemple text(), l'appel
     * sera transformé en création d'une instance de la classe
     * jemdev\form\field\input\text.
     * Exemple :
     * <code>
     * $login = $oForm->text('login');
     * $login->setLabel('Votre identifiant')
     *       ->setAttribute('class', 'inputsaisie')
     *       ->setAttribute('tabindex', '2');
     * </code>
     * On notera que dans ce code, on appelle une méthode setAttribute
     * qui n'existe pas davantage dans la classe jemdev\form\form : mais on
     * travaille en réalit avec une instance de la classe d'élément, et
     * donc avec ses méthodes.
     *
     * On peut également créer l'instance en envoyant plusieurs paramètres.
     * Ainsi, on aurait pu écrire :
     * <code>
     * $login = $oForm->text('login', '', null, 'Votre identifiant');
     * $login->setAttribute('class', 'inputsaisie')
     *       ->setAttribute('tabindex', '2');
     * </code>
     * La création d'un élément accepte par défaut 4 paramètres de base correspondant
     * aux principaux attributs :
     * - id
     * - name
     * - value
     * - label
     * Pour les autres attributs, on devra passer par la méthode setAttribute().
     * Si la valeur de l'attribut name n'est pas fournie, c'est la même valeur
     * que celle de l'attribut id qui sera utilisée, celle-ci est la seule obligatoire.
     *
     * @param   String  $methode
     * @param   Array   $params
     * @return  field
     */
    public function __call($methode, $params): field
    {
        if(in_array($methode, $this->_aValidTags['input']))
        {
            $input = $methode;
            $balise = 'input';
        }
        else
        {
            $balise = $methode;
        }
        switch ($balise)
        {
            case 'input':
                if($input == 'hidden')
                {
                    $o = new hidden($params, $this->_sDoctype, $this);
                }
                else
                {
                    $o = new visible($input, $params, $this->_sDoctype, $this);
                }
                break;
            case 'textarea':
                $o = new textarea($params, $this);
                break;
            case 'datalist':
                if($this->_sDoctype == 'HTML5')
                {
                    $o = new datalist($params, $this);
                }
                else
                {
                    $msg = sprintf($this->_aExceptionErreurs['balise_html5'], $methode, $this->_sDoctype);
                    trigger_error($msg, E_USER_NOTICE);
                }
                break;
            case 'select':
                $o = new select($params, $this);
                break;
            case 'groupe':
                $o = new groupe($params, $this);
                break;
            default:
                $msg = sprintf($this->_aExceptionErreurs['methode_inexistante'], $methode, __CLASS__);
                trigger_error($msg, E_USER_NOTICE);
        }
        $this->_addElement($methode, $params[0], $o);
        return $o;
    }

    /**
     * Récupération de la valeur d'une propriété de l'instance.
     *
     * @param   String $id
     * @return  String
     */
    public function __get($id)
    {
        if($id == 'attr')
        {
            $aAttrs = array();
            $aAttrs[] = 'id="'. $this->_formId .'"';
            if($this->_sDoctype == 'HTML')
            {
                $aAttrs[] = 'name="'. $this->_formId .'"';
            }
            $aAttrs[] = 'action="'. ((!is_null($this->_formAction)) ? $this->_formAction : $_SERVER['REQUEST_URI']) .'"';
            $aAttrs[] = 'method="'. $this->_formMethod .'"';
            $aAttrs[] = 'enctype="'. $this->_formEnctype .'"';
            $aAttrs[] = 'accept-charset="'. $this->_formCharset .'"';
            $retour = implode(" ", $aAttrs);
            return $retour;
        }
        elseif($id == 'erreurs')
        {
            return $this->_aErreursValidation;
        }
        elseif($id == 'doctype' || $id == 'sDoctype')
        {
            return $this->_sDoctype;
        }
        elseif($id == '_aExceptionErreurs')
        {
            return $this->_aExceptionErreurs;
        }
        elseif($id == '_aHiddens')
        {
            return $this->_aHiddens;
        }
        elseif($id == '_formMethod')
        {
            return $this->_formMethod;
        }
        elseif($id == '_aClasseValidationExterne')
        {
            return $this->_aClasseValidationExterne;
        }
        elseif(array_key_exists($id, $this->_aElements))
        {
            return $this->_aElements[$id]['html'];
        }
        else
        {
            $msg = sprintf($this->_aExceptionErreurs['element_inexistant'], $id);
            trigger_error($msg, E_USER_WARNING);
        }
    }

    /**
     * Méthode magique d'initialisation de propriété de classe.
     *
     * @param   String  $prop
     * @param   String  $val
     * @return  Object
     */
    public function __set($prop, $val)
    {
        if(in_array($prop, $this->_aProps))
        {
            $this->{$prop} = $val;
            if($prop == '_sDoctype')
            {
                if(!in_array($val, $this->_aValidDoctypes))
                {
                    $msg  = sprintf($this->_aExceptionErreurs['doctype_inexistant'], $val);
                    trigger_error($msg, E_USER_WARNING);
                    $val = 'XHTML';
                }
                if($val == 'HTML')
                {
                    $this->_formMethod = strtoupper($this->_formMethod);
                }
                $this->_setValidTags();
            }
        }
        elseif(array_key_exists($prop, $this->_aProps))
        {
            $this->{$this->_aProps[$prop]} = $val;
            if($prop == 'doctype')
            {
                if($val == 'HTML')
                {
                    $this->_formMethod = strtoupper($this->_formMethod);
                }
                $this->_setValidTags();
            }
        }
        else
        {
            $msg = sprintf($this->_aExceptionErreurs['prop_inaccessible'], $prop, __CLASS__);
            trigger_error($msg, E_USER_WARNING);
        }
        return $this;
    }

    /**
     * Ajout d'un champ caché dans le formulaire.
     *
     * @param String    $index  Identifiant du champ
     * @param String    $val    Valeur du champ.
     */
    public function setHidden($index, $val = '')
    {
        $this->_aHiddens[$index] = $val;
    }

    /**
     * Ajout ou modification d'une règle de validation pour un champ donné.
     *
     * @param   String  $nameChamp
     * @param   Array   $aRegles
     * @param   srting  $msg
     */
    public function setRegleValidation($nameChamp, $aRegles, $msg)
    {
        if(!empty($nameChamp) && !is_object($nameChamp))
        {
            $this->_aReglesValidation[$nameChamp][] = array($aRegles, $msg);
        }
        return $this;
    }

    /**
     * Ajoute un bloc container pour les erreurs de validation JavaScript.
     * @param Boolean $afficher
     */
    public function setBlocValidJS($afficher = true)
    {
        $this->_bSetBlocErreursJS = (is_bool($afficher)) ? $afficher : true;
    }

    private function _getValidation()
    {
        $retour = array();
        $m      = strtoupper($this->_formMethod);
        $aDatas = null;
        if($_SERVER['REQUEST_METHOD'] == $m)
        {
            switch ($m)
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
        if(isset($aDatas) && (count($aDatas) > 0) && isset($aDatas['idformulaire'. $this->_formId]) && $this->_formId == $aDatas['idformulaire'. $this->_formId])
        {
            $aDatas = $this->_setValeursNumeriques($aDatas);
            if(isset($_FILES))
            {
                $aDatas['fichiers'] = $_FILES;
            }
            $this->aDatas = $aDatas;
            $this->bFormEnvoye = true;
            $nbErr = 0;
            if(false !== $this->_bFormValidation)
            {
                if(count($this->_aClasseValidationExterne) == 2)
                {
                    try
                    {
                        require_once($this->_aClasseValidationExterne['fichier']);
                    }
                    catch (Exception $e)
                    {
                        trigger_error("Fichier ". $this->_aClasseValidationExterne['fichier'] ." introuvable", E_USER_NOTICE);
                    }
                    $oClasse     = new \ReflectionClass($this->_aClasseValidationExterne['classe']);
                    $args        = array($aDatas, $this->_aReglesValidation, $this->_aExceptionErreurs);
                    $oValidation = $oClasse->newInstanceArgs($args);
                }
                else
                {
                    $oValidation = new validation($aDatas, $this->_aReglesValidation, $this->_aExceptionErreurs);
                }
                $retour = $oValidation->validerInfos();
                if(count($retour) > 0)
                {
                    foreach($retour as $erreur)
                    {
                        $this->_aErreursValidation[] = $erreur;
                    }
                }
                $nbErr = count($this->_aErreursValidation);
            }
            $this->bFormValide = $nbErr > 0 ? false : true;
        }
    }

    /**
     * Enregistrement de l'élément ajouté dans le formulaire.
     *
     * Ce tableau pourra être utilisé ultérieurement.
     * Actuellement ne sert à rien.
     *
     * @param   String  $type
     * @param   String  $id
     * @param   Object  $objet
     * @return  Object
     */
    private function _addElement($type, $id, $objet)
    {
        $this->_aElements[$id] = array(
            'objet' => $objet,
            'html'  => sprintf('%s', $objet),
            'label' => $objet->getLabel()
        );
        return $this;
    }

    /**
     * Ajout d'un champ caché avec une clé d'identification unique propre
     * à ce formulaire.
     */
    private function _addCleSecurite()
    {
        $sCle = $this->_oCle->getCleUnique();
        $oSec = $this->hidden('cleform_'. $this->_formId, 'cleform_'. $this->_formId, $sCle);
        $this->_aHiddens['cleform_'. $this->_formId] = sprintf('%s', $oSec);
    }

    /**
     * Vérification de la clé d'identification du formulaire.
     * En cas de tentative de piratage ou d'utilisation frauduleuse
     * du formulaire par un robot, la validations sera bloquée et
     * rien ne sera considéré comme fiable.
     *
     * @return Boolean
     */
    private function _validerCle()
    {
        /**
         * On envoie par défaut un retour à true : si en effet c'est le
         * premier affichage, inutile d'affoler l'utilisateur en lui disant
         * qu'on vient de détecter sa tentative de piratage.
         */
        $retour = true;
        $method = "_". strtoupper($this->_formMethod);
        if(isset(${$method}['cleform_'. $this->_formId]))
        {
            $retour = $this->_oCle->validationCle(${$method}['cleform_'. $this->_formId]);
        }
        return $retour;
    }

    private function _init($aClassValidExterne = null)
    {
        $this->_setValidTags();
        $this->_aErreursValidation = array();
        if(isset($aClassValidExterne))
        {
            $this->_aClasseValidationExterne['fichier'] = $aClassValidExterne[0];
            $this->_aClasseValidationExterne['classe']  = $aClassValidExterne[1];
        }
        $this->_oCle = new cleform();
        /**
         * On va d'ores et déjà générer une clé d'identification unique de sécurité.
         */
        $this->_addCleSecurite();
        /**
         * Si le formulaire a été envoyé, on récupère la clé pour validation.
         */
        $noHack = $this->_validerCle();
        if(true !== $noHack)
        {
            $this->_aErreursValidation[] = "Tentative d'utilisation frauduleuse du formulaire !";
        }
        /*
         * Pour les cas de formulaires successifs d'étapes multiples,
         * on ajoute un champ caché avec l'identifiant du formulaire.
         * Lors de la création d'un des formulaire, on vérifiera s'il
         * s'agit du même formulaire ou bien s'il s'agit du formulaire
         * suivant....
         */
        $this->hidden('idformulaire'. $this->_formId, null, $this->_formId);
    }

    /**
     * Nettoyage des données numériques.
     * Comme les données sont destinées à être enregistrées en base de données, les valeurs
     * numériques doivent être convenablement formatées. On remplacera donc dans les
     * nombres flottants la virgule par un point décimal.
     *
     * Cette mééthode récursive va faire le tour de toutes les informations qui ont été
     * envoyées et les traiter.
     *
     * @param   Array $datas
     * @return  Array
     */
    protected function _setValeursNumeriques($datas)
    {
        if(is_array($datas))
        {
            foreach($datas as $k => $v)
            {
                if(is_array($v))
                {
                    $datas[$k] = $this->_setValeursNumeriques($v);
                }
                elseif(preg_match($this->_masqueNombreFR, $v))
                {
                    $datas[$k] = preg_replace($this->_masqueNombreFR, "$1.$2", $v);
                }
            }
        }
        return($datas);
    }

    /**
     * Définit si on doit valider les données du formulaire et
     * surtout si on doit ou non masquer le formulaire dans le cas
     * où les données seraient valides.
     * @param Boolean $valider
     */
    public function validerForm($valider = true)
    {
        $this->_bFormValidation = $valider;
    }

    public function addErreur($erreur)
    {
        $this->_aErreursValidation[] = $erreur;
    }

    private function _setLang()
    {
        $loc = (defined('LOCALE_LANG')) ? LOCALE_LANG : 'fr';
        include(realpath(__DIR__ . DIRECTORY_SEPARATOR .'locale'. DIRECTORY_SEPARATOR .'lang.php'));
        $this->oLang = $oLang;
        $this->_aExceptionErreurs  = $oLang::$msgs_exceptions;
        $this->_aValidationErreurs = $oLang::$msgs_erreur_validation;
    }

    private function _setValidTags()
    {
        if(is_null($this->_sDoctype))
        {
            $this->_sDoctype = 'XHTML';
        }
        switch ($this->_sDoctype)
        {
            case 'HTML':
                $this->_aValidTags = tags\attributs_html::$aFormTags;
                break;
            case 'HTML5':
                $this->_aValidTags = tags\attributs_html5::$aFormTags;
                break;
            case 'XHTML':
            default:
                $this->_aValidTags = tags\attributs_xhtml::$aFormTags;
        }
    }

    /**
     * Préparation de la chaine complète du formulaire HTML.
     *
     * @return String
     */
    public function __toString()
    {
        $retour = '';
        /**
         * On récupère le résultat de la validation s'il y a lieu.
         */
        $this->_getValidation();
        if(!is_null($this->_aErreursValidation) && count($this->_aErreursValidation) > 0)
        {
            $aErreurs = array_unique($this->_aErreursValidation);
            $ne = count($aErreurs);
            $vide = $ne == 0 ? '<li>&nbsp;</li>' : null;
            $retour .= <<<CODE_HTML
    <div class="validationerreurs">
      <h4>{$this->_aValidationErreurs['titremessages']}</h4>
      <ul>

CODE_HTML;
            $retour .= $vide;
            foreach ($aErreurs as $erreur):
            $retour .= <<<CODE_HTML
        <li>{$erreur}</li>

CODE_HTML;
            endforeach;
            $retour .= <<<CODE_HTML
      </ul>
    </div>

CODE_HTML;
        }
        $sBlocValidJs = null;
        $sHiddens = null;
        if(true === $this->bFormEnvoye && true === $this->bFormValide && true === $this->_bFormValidation)
        {
            $retour = '';
        }
        elseif(!empty($this->_contenu))
        {
            if(true === $this->_bSetBlocErreursJS)
            {
                $sHiddens .= "\n".'      <div id="erreurs" style="display: none;"><ul><li style="display: none;">&nbsp;</li></ul></div>';
            }
            if(count($this->_aHiddens) > 0)
            {
                $sHiddens .= "\n".'      <p style="display: none;">'. "\n        " . implode("\n        ", $this->_aHiddens) ."\n".'      </p>';
            }
            $retour .= <<<CODE_HTML
    <form {$this->attr}>{$sHiddens}
{$this->_contenu}    </form>

CODE_HTML;
        }
        return $retour;
    }
}
