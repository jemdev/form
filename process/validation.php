<?php
namespace jemdev\form\process;
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
 * Validation de valeurs selon des règles établies de façon
 * génériques.
 *
 * Méthodes de validation générique.
 * Chaque méthode retournera true si la vérification est satisfaisante,
 * false dans le cas contraire.
 * L'utilisation de cette classe suppose l'utilisation de mots-clés
 * dans la définition des règles de validation des champs.
 * Ces mots clés sont les suivants :
 * <ul>
 *   <li>required :         Vérifie que la valeur saisie est non vide;<br />
 *     paramètres : aucun;<br />
 *     exemple : <code>$champ->setRule('required', "message d'erreur à afficher en cas de donnée invalide");</code>
 *   </li>
 *   <li>email :            Vérifie la syntaxe d'une adresse de courrier électronique;<br />
 *     paramètres : aucun;<br />
 *     exemple : <code>$champ->setRule('email', "message d'erreur à afficher en cas de donnée invalide");</code>
 *   </li>
 *   <li>url :              Vérifie la syntaxe d'une URL;<br />
 *     paramètres : aucun;<br />
 *     exemple : <code>$champ->setRule('url', "message d'erreur à afficher en cas de donnée invalide");</code>
 *   </li>
 *   <li>alpha :            Vérifie que la valeur saisie est uniquement alphabétique (avec accents);<br />
 *     paramètres : aucun;<br />
 *     exemple : <code>$champ->setRule('alpha', "message d'erreur à afficher en cas de donnée invalide");</code>
 *   </li>
 *   <li>word :             Vérifie que la valeur saisie est alphabétique + espace, apostrophes, guillemets et tirets;<br />
 *     paramètres : aucun;<br />
 *     exemple : <code>$champ->setRule('word', "message d'erreur à afficher en cas de donnée invalide");</code>
 *   </li>
 *   <li>alnum :            Vérifie que la valeur saisie est alphanumérique (word + chiffres);<br />
 *     paramètres : aucun;<br />
 *     exemple : <code>$champ->setRule('alnum', "message d'erreur à afficher en cas de donnée invalide");</code>
 *   </li>
 *   <li>num :              Vérifie que la valeur saisie est numérique (chiffres uniquement);<br />
 *     paramètres : aucun;<br />
 *     exemple : <code>$champ->setRule('num', "message d'erreur à afficher en cas de donnée invalide");</code>
 *   </li>
 *   <li>float :            Vérifie que la valeur saisie est un nombre éventuellement flottant (point ou virgule décimale);<br />
 *     paramètres : aucun;<br />
 *     exemple : <code>$champ->setRule('float', "message d'erreur à afficher en cas de donnée invalide");</code>
 *   </li>
 *   <li>minlength :        Vérifie le nombre de caractère par rapport à un nombre minimum requis;<br />
 *     paramètres : nombre;<br />
 *     exemple : <code>$champ->setRule('minlength', "message d'erreur à afficher en cas de donnée invalide", 6);</code>
 *   </li>
 *   <li>maxlength :        Vérifie le nombre de caractère par rapport à un nombre maximum requis;<br />
 *     paramètres : nombre;<br />
 *     exemple : <code>$champ->setRule('maxlength', "message d'erreur à afficher en cas de donnée invalide", 12);</code>
 *   </li>
 *   <li>rangelength :      Vérifie que le nombre de caractère est compris entre un minimum et un maximum;<br />
 *     paramètres : nombre mini, nombre maxi;<br />
 *     exemple : <code>$champ->setRule('rangelength', "message d'erreur à afficher en cas de donnée invalide", array(6, 12));</code>
 *   </li>
 *   <li>minval :           Vérifie que la valeur (numérique) est supérieure ou égale à un minimum donné;<br />
 *     paramètres : nombre;<br />
 *     exemple : <code>$champ->setRule('minval', "message d'erreur à afficher en cas de donnée invalide", 25);</code>
 *   </li>
 *   <li>maxval :           Vérifie que la valeur (numérique) est inférieure ou égale à un maximum donné;<br />
 *     paramètres : nombre;<br />
 *     exemple : <code>$champ->setRule('maxval', "message d'erreur à afficher en cas de donnée invalide", 99);</code>
 *   </li>
 *   <li>rangeval :         Vérifie que la valeur (numérique) est comprise entre un minimum donné et un maximum donné;<br />
 *     paramètres : nombre mini, nombre maxi;<br />
 *     exemple : <code>$champ->setRule('rangeval', "message d'erreur à afficher en cas de donnée invalide", array(1, 150));</code>
 *   </li>
 *   <li>superieur :        Vérifie que la valeur (numérique) est strictement supérieure à un minimum donné;<br />
 *     paramètres : nombre;<br />
 *     exemple : <code>$champ->setRule('superieur', "message d'erreur à afficher en cas de donnée invalide", 4);</code>
 *   </li>
 *   <li>inferieur :        Vérifie que la valeur (numérique) est strictement inférieure à un maximum donné;<br />
 *     paramètres : nombre;<br />
 *     exemple : <code>$champ->setRule('inferieur', "message d'erreur à afficher en cas de donnée invalide", 11);</code>
 *   </li>
 *   <li>regex :            Teste si la valeur vérifie l'expression régulière fournie;<br />
 *     paramètres : chaine (expression régulière PCRE);<br />
 *     exemple : <code>$champ->setRule('regex', "message d'erreur à afficher en cas de donnée invalide", '#^(?:_[0-9].*)$#');</code>
 *   </li>
 *   <li>formatdateheure :  Teste le format de saisie d'une date en comparant avec un format indiqué en paramètre;<br />
 *     paramètres : chaine (formats compatibles avec la fonction native date());<br />
 *     exemple : <code>$champ->setRule('formatdateheure', "message d'erreur à afficher en cas de donnée invalide", "d/m/Y");</code>
 *   </li>
 *   <li>validedate :       Vérifie la validité d'une date (existence dans le calendrier grégorien);<br />
 *     paramètres : aucun;<br />
 *     exemple : <code>$champ->setRule('validedate', "message d'erreur à afficher en cas de donnée invalide");</code>
 *   </li>
 *   <li>comparer :         Vérifie la parité entre deux valeurs;<br />
 *     paramètres : valeur avec laquelle doit correspondre la valeur du champ testé;<br />
 *     exemple : <code>$champ->setRule('comparer', "message d'erreur à afficher en cas de donnée invalide", $valeur_autre_champ);</code>
 *   </li>
 * </ul>
 *
 * @author      Jean Molliné <jmolline@jem-dev.com>
 * @package     jemdev
 * @subpackage  form
 * @todo        Validation de certaines valeurs avec la méthode filter_var() et les
 *              constantes nativement pré-définies.
 */
class validation
{
    /**#@+
    * Constantes définissant des expressions régulières
    * pour des masques de validation génériques.
    */
    /**
     * Validation d'une chaine en caractères alphabétiques, ni espace ni
     * caractères spéciaux autre que des lettres accentuées.
     */
    const V_ALPHA   = "#^[áàâãäéèêëíìîïóòôõöúùûüñÁÀÂÃÄÉÈÊËÍÌÎÏÓÒÔÕÖÚÙÛÜÑa-zA-ZçÇ]+$#";
    /**
     * Validation de mots en caractères alphabétiques, apostrophe, guillemet, tiret.
     */
    const V_WORD    = "#^['\"áàâãäéèêëíìîïóòôõöúùûüñÁÀÂÃÄÉÈÊËÍÌÎÏÓÒÔÕÖÚÙÛÜÑçÇa-zA-Z\- ]+$#";
    /**
     * Validation d'une chaine alphanumérique, phrase.
     */
    const V_ALNUM   = "#^['\"\.°/áàâãäéèêëíìîïóòôõöúùûüñÁÀÂÃÄÉÈÊËÍÌÎÏÓÒÔÕÖÚÙÛÜÑçÇa-zA-Z0-9\- ]+$#";
    /**
     * Validation d'une chaine de chiffres.
     */
    const V_NUM     = "#^[0-9]+$#";
    /**
     * Validation d'un nombre, éventuellement flottant.
     * Accepte le point décimal ou la virgule.
     */
    const V_FLOAT   = "#^\-?[0-9]+([.,][0-9]+)?$#";
    /**#@-*/

    /**
     * Données postées
     *
     * @var array
     */
    protected $_aDatas;

    /**
     * Règles de validation du formulaire traité
     *
     * @var array
     */
    private $_aRules;

    /**
     * Liste des erreurs relevées lors de la validation des données.
     *
     * @var array
     */
    private $_aErreursRelevees  = array();

    /**
     * Messages affichés lors de la levée d'exception.
     * On utilise un fichier du répertoire /form/locale pour avoir les messages
     * en français ou en anglais. Rien n'interdit de créer d'autres fichiers dans
     * d'autres langues.
     *
     * @var array
     */
    private $_aExceptionmessages;

    /**
     * Liste des méthodes génériques de validation.
     *
     * @var array
     */
    public static $methodesValidation = array(
        "required",
        "email",
        "url",
        "alpha",
        "word",
        "alnum",
        "num",
        "float",
        "minlength",
        "maxlength",
        "rangelength",
        "minval",
        "maxval",
        "rangeval",
        "inferieur",
        "superieur",
        "regex",
        "formatdateheure",
        "validedate",
        "comparer",
        "differentDe"
    );
    /**
     * Constructeur.
     *
     * @param array $aDatas
     * @param array $aRules
     * @param array $msgs_exceptions
     */
    public function __construct(array $aDatas, array $aRules = [], array $msgs_exceptions = [])
    {
        $this->_aDatas = $aDatas;
        $this->_aRules = $aRules;
        $this->_aExceptionmessages = $msgs_exceptions;
    }

    /**
     * Validation des règles de gestion du formulaire.
     *
     * Les règles créées avec le formulaire sont toutes vérifiées, en cas d'erreur,
     * le message correspondant est stocké dans un tableau indexé.
     * On ne vérifiera qu'une règle à la fois par champ : si elle retourne
     * une erreur, on arrêtera la vérification pour passer au champ suivant.
     *
     * @return  array
     */
    public function validerInfos(): array
    {
        foreach($this->_aRules as $champ => $aRegles)
        {
            /**
             * On vérifie si le champ est obligatoirement requis ou non
             */
            $bRequis = false;
            foreach($this->_aRules[$champ] as $r)
            {
                if(in_array('required', $r[0]))
                {
                    $bRequis = true;
                    break;
                }
            }
            $nbr = count($this->_aRules[$champ]);
            for($i = 0; $i < $nbr; $i++)
            {
                $aRegle = $this->_aRules[$champ][$i][0];
                $sRegle = $aRegle[0];
                if(in_array($sRegle, self::$methodesValidation))
                {
                    if(empty($this->_aDatas))
                    {
                        $this->_aDatas = [];
                    }
                    $nbp = count($this->_aRules[$champ][$i][0]);
                    /**
                     * Si la donnée existe :
                     * Attention, une case non cochée sera
                     * absente des index des données.
                     * Attention au type input-file, il n'apparaîtra
                     * pas dans les données POST_DATA ni GET_DATA
                     */
                    $dataChamp = field::getValueFromArrayData($champ, $this->_aDatas);
                    if(is_array($dataChamp))
                    {
                        foreach ($dataChamp as $k => $v)
                        {
                            if(false != strstr($champ, $k))
                            {
                                $dataChamp = $dataChamp[$k];
                            }
                        }
                    }
                    if(empty($dataChamp))
                    {
                        if(isset($this->_aDatas['fichiers'][$champ]))
                        {
                            $dataChamp = $this->_aDatas['fichiers'][$champ]['name'];
                        }
                    }
                    if(isset($dataChamp))
                    {
                        if(is_array($this->_aRules[$champ][$i][0]) && $nbp > 1)
                        {
                            $valeur = array($dataChamp);
                            for($a = 1; $a < $nbp; $a++)
                            {
                                $valeur[] = $this->_aRules[$champ][$i][0][$a];
                            }
                            $msg = $this->_aRules[$champ][$i][1];
                        }
                        else
                        {
                            $valeur = $dataChamp;
                            $msg = $this->_aRules[$champ][$i][1];
                        }
                        $p = $valeur;
                    }
                    else
                    {
                        /**
                         * Donnée absente de l'index, exemple, case non cochée.
                         * On appellera la méthode avec une valeur vide.
                         */
                        $msg = $this->_aRules[$champ][$i][1];
                        $p = '';
                    }
                    $bVerif = ((true == $bRequis || $sRegle == 'required') && ((!is_array($p) && strlen($p) > 0) || (is_array($p) && strlen($p[0]) > 0)));
                    $v   = (true == $bVerif)
                        ? call_user_func(array($this, '_'. $sRegle), $p)
                        : true;
                    $v = false;
                    if($sRegle == 'required')
                    {
                        $v = $this->_required($p);
                    }
                    else
                    {
                        $val = (is_array($p)) ? trim($p[0]) : trim($p);
                        $v = ($val !== "")
                            ? call_user_func(array($this, '_'. $sRegle), $p)
                            : true;
                    }
                    if(false === $v)
                    {
                        $this->_aErreursRelevees[] = $msg;
                        /**
                         * Il y a une erreur dans ce champ, inutile de vérifier les autres règles
                         * qui y sont attachées, on passe au champ suivant. (Sortie de la
                         * boucle for pour le champ suivant dans la boucle foreach)
                         */
                        break;
                    }
                }
                else
                {
                    /**
                     * On lève une exception si la règle utilisée n'existe pas.
                     */
                    $msgerr = sprintf($this->_aExceptionmessages['regle_valid_inexistante'], $sRegle);
                    $this->_aErreursRelevees[] = $msgerr;
                }
            }
        }
        return($this->_aErreursRelevees);
    }

    public function getListeMethodes(): array
    {
        return get_class_methods($this);
    }

    /**
     * Vérifie qu'une valeur requise n'est pas vide.
     * On prendra soin de supprimer les espaces de début et de fin de chaine
     * afin de vérifier que la chaine contient réellement au moins un caractère.
     *
     * @param   string  $valeur
     * @return  bool
     */
    protected function _required(string $valeur): bool
    {
        $val = (is_array($valeur)) ? trim($valeur[0]) : trim($valeur);
        $retour = ($val !== "") ? true : false;
        if(true == $retour)
        {
            $retour = $this->_requiredHtmlTextarea($val);
        }
        return($retour);
    }

    /**
     * Vérifie la validité d'une adresse de courrier électronique.
     *
     * @param   string  $valeur
     * @return  bool
     */
    protected function _email(string $valeur): bool
    {
        return filter_var($valeur, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Vérifie la validité de la syntaxe d'une URL
     *
     * @param   string  $valeur
     * @return  bool
     */
    protected function _url(string $valeur): bool
    {
        return(filter_var($valeur, FILTER_VALIDATE_URL));
    }

    /**
     * Vérifie la validité de la syntaxe d'une chaine alphabétique.
     * N'accepte que des lettres de l'aphabet, éventuellement accentuées,
     * ni espaces ni caractères spéciaux.
     *
     * @param   string  $valeur
     * @return  bool
     */
    protected function _alpha(string $valeur): bool
    {
        return (preg_match(self::V_ALPHA, $valeur)) == 1 ? true : false;
    }

    /**
     * Vérifie la validité de la syntaxe d'un ou plusieurs mots.
     * N'accepte que des lettres de l'aphabet, éventuellement accentuées,
     * plus les espaces, guillemets et apostrophes.
     *
     * @param   string  $valeur
     * @return  bool
     */
    protected function _word(string $valeur): bool
    {
        return (preg_match(self::V_WORD, $valeur)) == 1 ? true : false;
    }

    /**
     * Vérifie la validité de la syntaxe d'une chaine alphanumérique
     * Accepte des lettres de l'aphabet, éventuellement accentuées,
     * plus les chiffres, les espaces, les guillemets et les apostrophes.
     *
     * @param   string  $valeur
     * @return  bool
     */
    protected function _alnum(string $valeur): bool
    {
        $resultat = preg_match(self::V_ALNUM, $valeur) == 1 ? true : false;
        return($resultat);
    }

    /**
     * Vérifie la validité de la syntaxe d'une chaine de chiffres
     * Chiffres exclusivement, ni espace ni ponctuation ni caractères spéciaux.
     *
     * @param   string  $valeur
     * @return  bool
     */
    protected function _num(string $valeur): bool
    {
        return (preg_match(self::V_NUM, $valeur)) == 1 ? true : false;
    }

    /**
     * Vérifie la validité de la syntaxe d'un nombre flottant.
     * Accepte les chiffres et le point décimal ou la virgule.
     * Ni espace ni aucun autre caractère.
     *
     * @param   Float   $valeur
     * @return  bool
     */
    protected function _float(string $valeur): bool
    {
        $val = str_replace(' ', '', $valeur);
        return (preg_match(self::V_FLOAT, $val)) == 1 ? true : false;
    }

    /**
     * Vérifie que la chaine comporte au moins un certain nombre de caractères.
     *
     * @param   array   $valeur valeur du champ $valeur[0] et nombre minimum $valeur[1]
     * @return  bool
     */
    protected function _minlength(array $valeur): bool
    {
        return (strlen($valeur[0]) >= $valeur[1]);
    }

    /**
     * Vérifie que la chaine comporte au maximum un certain nombre de caractères.
     *
     * @param   array   $valeur     valeur du champ $valeur[0] et nombre maximum $valeur[1]
     * @return  bool
     */
    protected function _maxlength(string $valeur): bool
    {
        return(strlen($valeur[0]) <= $valeur[1]);
    }

    /**
     * Vérifie que la chaine comporte un nombre de caractères compris entre
     * un minimum et un maximum donné.
     *
     * @param   array   $valeur     valeur du champ $valeur[0], nombre minimum $valeur[1][0] et nombre maximum $valeur[1][1]
     * @return  bool
     */
    protected function _rangelength(array $valeur)
    {
        $l = strlen($valeur[0]);
        return($l <= $valeur[1][1] && $l >= $valeur[1][0]);
    }

    /**
     * Vérifie que la valeur est supérieure ou égale à une valeur minimum.
     *
     * @param   array   $valeur     valeur du champ $valeur[0] et valeur minimale attendue $valeur[1]
     * @return  bool
     */
    protected function _minval(array $valeur): bool
    {
        $retour = (($valeur[0]) >= $valeur[1]) ? true : false;
        return $retour;
    }

    /**
     * Vérifie que la valeur est inférieure ou égale à une valeur maximum.
     *
     * @param   array   $valeur     valeur du champ $valeur[0] et valeur maximale attendue $valeur[1]
     * @return  bool
     */
    protected function _maxval(array $valeur): bool
    {
        return($valeur[0] <= $valeur[1]);
    }

    /**
     * Vérifie que la valeur est comprise entre une valeur maximum et une valeur minimum.
     *
     * @param   array   $valeur     valeur du champ $valeur[0], valeur minimale attendue $valeur[1][0] et valeur maximale attendue $valeur[1][1]
     * @return  bool
     */
    protected function _rangeval(array $valeur)
    {
        return($valeur[0] >= $valeur[1][0] && $valeur[0] <= $valeur[1][1]);
    }

    /**
     * Vérifie que la valeur est strictement inférieure à une valeur maximum
     *
     * @param   array   $valeur     valeur du champ $valeur[0] et limite inférieure de la valeur attendue $valeur[1]
     * @return  bool
     */
    protected function _inferieur(array $valeur): bool
    {
        return($valeur[0] < $valeur[1]);
    }

    /**
     * Vérifie que la valeur est strictement supérieure à une valeur minimum
     *
     * @param   array   $valeur     valeur du champ $valeur[0] et limite supérieure de la valeur attendue $valeur[1]
     * @return  bool
     */
    protected function _superieur(array $valeur): bool
    {
        return($valeur[0] > $valeur[1]);
    }

    /**
     * Vérifie que la saisie correpond à une expression régulière définie.
     *
     * On se basera sur des expression selon la norme PCRE et non POSIX.
     * L'expression régulière envoyée devra comporter les délimiteurs de
     * début et de fin.
     *
     * @param   array   $valeur     Valeur du champ $valeur[0] et expression régulière à appliquer $valeur[1]
     * @return  bool
     */
    protected function _regex(array $valeur): bool
    {
        $retour = (preg_match($valeur[1], $valeur[0]) == 1) ? true : false;
        return $retour;
    }

    /**
     * Validation du format d'une date.
     *
     * La méthode attend en premier paramètre la date telle que
     * saisie dans le formulaire.
     * Le second paramètre attendu est le format. On utilisera les
     * mêmes caractères clés que ceux utilisés avec la fonction PHP
     * date() à savoir :
     * - y : année sur deux chiffres,    de 00 à 99;
     * - Y : année sur quatre chiffres,  de 1000 à 2999;
     * - m : mois sur deux chiffres,     de 01 à 12;
     * - d : jour sur deux chiffres,     de 01 à 31;
     * - H : heure sur deux chiffres,    de 00 à 23;
     * - i : minutes sur deux chiffres,  de 00 à 59;
     * - s : secondes sur deux chiffres, de 00 à 59;
     * Exemple de valeur attendue :
     * array(2) {
     *   [0]=> string(10) "01/02/2010"
     *   [1]=> string(5) "d/m/Y"
     * }
     *
     * @param   array   $valeur Tableau contenant la date et le format à valider
     * @return  bool
     */
    protected function _formatdateheure(array $valeur): bool
    {
        $retour = false;
        if(is_array($valeur))
        {
            /**
             * Construction dynamique de l'expression régulière de
             * validation du format de date.
             */
            $dateCar = array('y','Y','m','d','H','i','s');
            $dateReg = array(
                '[0-9]{2}',
                '[1-2][0-9]{3}',
                '(?:0[1-9]|1[0-2])',
                '(?:0[1-9]|[1-2][0-9]|3[0-1])',
                '(?:[0-1][0-9]|2[0-3])',
                '[0-5][0-9]',
                '[0-5][0-9]'
            );
            $l = strlen($valeur[1]);
            $c = '';
            for($i = 0; $i < $l; $i++)
            {
                $c .= (!in_array($valeur[1][$i], $dateCar)) ? ")". $valeur[1][$i] ."(" : $valeur[1][$i];
            }
            $masque = "#^(". str_replace($dateCar, $dateReg, $c) .")$#";
            $valide = preg_match($masque, $valeur[0]);
            $retour = ($valide == 1) ? true : false;
        }
        return $retour;
    }

    /**
     * Validation d'une date à partir d'un format donné.
     *
     * On va vérifier que la date saisie est valide et existe au calendrier.
     * Le format sera sera le même que celui défini pour valider le format de
     * la date, @see formatdateheure()
     * Note : il conviendra de valider le format au préalable, on aura
     * ainsi pas besoin de valider la partie horaire si elle existe.
     *
     * On va en outre distinguer le jour de l'année.
     * Pour ce faire, on va construire la même expression régulière correspondant
     * au format indiqué, au détail près qu'on va garder une trace de la position
     * pour le jour, pour le mois et pour l'année.
     * Enfin, si l'année est indiquée sur deux chiffres, on complètera à
     * 4 chiffres en se basant sur un siècle glissant dont le milieu est l'année
     * courante moins 30 ans.
     * Ainsi, en 2010, 30 sera transformé en 2030, 31 en 1931.
     *
     * L'application de la règle comportera trois paramètres sous la forme :
     * $objetChamp->setRule('validedate',"Message d'erreur", 'd/m/Y');
     *
     * @param   array   $valeur Valeur du champ $valeur[0] et format à appliquer $valeur[1]
     * @return  bool
     */
    protected function _validedate(array $valeur): bool
    {
        if(false !== $this->_formatdateheure(array($valeur[0], $valeur[1])))
        {
            $nonnumerique   = "#([^0-9])#";
            $token          = "|";
            $chaine         = preg_replace($nonnumerique, $token, $valeur[0]);
            $aDate          = explode($token, $chaine);
            $dateCar = array('y','Y','m','d','H','i','s');
            $dateReg = array(
                '[0-9]{2}',
                '[1-2][0-9]{3}',
                '(?:0[1-9]|1[0-2])',
                '(?:0[1-9]|[1-2][0-9]|3[0-1])',
                '(?:[0-1][0-9]|2[0-3])',
                '[0-5][0-9]',
                '[0-5][0-9]'
            );
            $l      = strlen($valeur[1]);
            $c      = '';
            $iJour  = null;
            $iMois  = null;
            $iAnnee = null;
            $r      = 0;
            $ba     = 2;
            for($i = 0; $i < $l; $i++)
            {
                $c .= (!in_array($valeur[1][$i], $dateCar))
                ? ")". $valeur[1][$i] ."("
                : $valeur[1][$i];
                if(in_array($valeur[1][$i], $dateCar))
                {
                    $r++;
                }
                if($valeur[1][$i] == 'Y' || $valeur[1][$i] == 'y')
                {
                    $iAnnee = $r;
                    $ba = ($valeur[1][$i] == 'Y') ? 4 : 2;
                }
                elseif($valeur[1][$i] == 'm')
                {
                    $iMois  = $r;
                }
                elseif($valeur[1][$i] == 'd')
                {
                    $iJour  = $r;
                }
            }
            $masque = "#(". str_replace($dateCar, $dateReg, $c) .")#";
            /**
             * On recrée la chaine avec juste l'année, le mois et le jour et
             * un séparateur pour isoler les valeurs dans un tableau
             */
            $repl = '$'. $iMois .'|$'. $iJour .'|$'. $iAnnee;
            $sDate = preg_replace($masque, $repl, $valeur[0]);
            $aDate = explode("|", $sDate);
            /**
             * Si l'année est sur deux chiffres, on la transforme pour la mettre sur
             * 4 chiffres.
             * On calculera sur la base d'une fourchette entre maintenant + 20ans et
             * maintenant - 80ans;
             */
            if($ba == 2)
            {
                $ac = date('Y');
                $as = (int) substr($ac, 0, 2);
                $aa = (int) substr($ac, 2, 2);
                $aDate[2] = ($aDate[2] > ($aa + 20)) ? ($as - 1) . $aDate[2] : $as . $aDate[2];
            }
            return checkdate($aDate[0], $aDate[1], $aDate[2]);
        }
        else
        {
            return false;
        }
    }

    /**
     * Compare deux valeurs et retourne vrai si les deux chaines sont identiques.
     *
     * @param   array   $valeur     Valeur du champ $valeur[0] et valeur attendue $valeur[1]
     * @return  bool
     */
    protected function _comparer(array $valeur): bool
    {
        return ($valeur[0] == $valeur[1]);
    }

    /**
     * Vérifie que la valeur saisie est différente de l'argument
     *
     * @param   array $valeur   $valeur du champ $valeur[0] et valeur de comparaison $valeur[1]
     * @return  bool
     */
    protected function _differentDe(array $valeur): bool
    {
        return ($valeur[0] != $valeur[1]);
    }

    /**
     * Si l'attribut name du champ est un tableau, on isole les éléments.
     *
     * Retourne false s'il n'y a pas d'index.
     *
     * @param  string $champ    Nom du champ à vérifier
     * @return array
     */
    private function _stringToArray(array $champ): array
    {
        $masque = "#([a-zA-Z0-9_]+)(?:\[([0-9]+)\](?:\[([0-9]+)\])?)#i";
        $retour = false;
        if(preg_match($masque, $champ, $aChamp))
        {
            $nomChamp = $aChamp[1];
            $index1   = (isset($aChamp[2])) ? $aChamp[2] : null;
            $retour = array($nomChamp, $index1);
            if(isset($aChamp[3]))
            {
                $index2   = $aChamp[3];
                $retour[] = $index2;
            }
        }
        return $retour;
    }

    private function _requiredHtmlTextarea(string $valeur): bool
    {
        $contenu = strip_tags($valeur);
        $txt = trim($contenu);
        $retour = (!empty($txt)) ? true : false;
        return $retour;
    }
}