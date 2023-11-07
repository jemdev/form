<?php
namespace jemdev\form\field;
use jemdev\form\form;
use jemdev\form\field;
use jemdev\form\field\optgroup;
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
 * Création d'une balise select de liste de sélection.
 *
 * @author      Jean Molliné <jmolline@jem-dev.com>
 * @package     jemdev
 * @subpackage  form
 */
class select extends field
{
    /**
     * Instance de la classe jemdev\form\field\option
     *
     * @var Object
     */
    private $_oOption;
    /**
     * Nombre d'options de la liste de sélection.
     *
     * @var Int
     */
    private $_nbOptions;
    /**
     * Instance de la classe optgroup
     *
     * @var Object
     */
    private $_oOptgroup;
    /**
     * Liste des groupes d'options
     *
     * @var array
     */
    private $_aOptGroups = array();
    /**
     * Nombre de groupes d'options
     *
     * @var Int
     */
    private $_nbGroupes;

    /**
     * Constructeur.
     *
     * Définit une liste de sélection.
     * Contenu du paramètre $props :
     * \@param string $props[0] = id    Identifiant du champ
     * \@param string $props[1] = name  Attribut name du champ (Facultatif, sera remplacé par
     *                                  la valeur de l'id si absent)
     * \@param string $props[2] = value Valeur du champ (Facultatif)
     * \@param string $props[3] = label Label pour le champ si nécessaire (Facultatif)
     *
     * @param   array   $props  Voir ci-dessus
     * @param   Object  $oForm  Instance du formulaire en cours de construction.
     */
    public function __construct(array $props, form $oForm)
    {
        parent::__construct($oForm);
        $this->_tag                 = 'select';
        $this->_type                = 'select';
        $this->_aAttributs['id']    = $props[0];
        $this->_aAttributs['name']  = (isset($props[1])) ? $props[1] : $props[0];
        $this->_contenu = '';
        $this->_nbOptions = 0;
        $this->_nbGroupes = 0;
    }

    /**
     * Ajoute un item dans la liste de sélection.
     *
     * @param   string      $valeur     Contenu de l'attribut value
     * @param   string      $affiche    Valeur affichée dans la liste de sélection
     * @param   bool        $selected   Optionnel, option sélectionnée, par défaut : false.
     * @param   array|null  $options
     * 
     * @return select
     */
    public function addOption(string $valeur, string $affiche, bool $selected = false, ?array $options = null): select
    {
        if($this->_oOptgroup instanceof optgroup )
        {
            $this->_oOptgroup->addOption($valeur, $affiche, $selected, $this->_aAttributs['name'], $options);
        }
        else
        {
            if(!isset($this->_oOption))
            {
                $this->_oOption = new option($this->_oForm);
            }
            $this->_oOption->addValue($valeur, $affiche, $selected, $this->_aAttributs['name'], $options);
            $this->_nbOptions++;
        }
        return $this;
    }

    public function addGroup(string $label): select
    {
        $this->_oOptgroup = new optgroup($label, $this->_oForm);
        $this->_aOptGroups[] = $this->_oOptgroup;
        $this->_nbGroupes++;
        return $this;
    }

    /**
     * Retourne le nombre d'options de la liste de sélection.
     *
     * @return Int
     */
    public function getNbOptions(): int
    {
        return($this->_nbOptions);
    }

    public function getSelected(): string
    {
        return $this->_oOption->getSelected();
    }

    /**
     * Sortie HTML
     *
     * @return string
     */
    public function __toString(): string
    {
        $sListeOptions  = sprintf('%s', $this->_oOption);
        $sListeGroupes = '';
        if($this->_nbGroupes > 0)
        {
            foreach($this->_aOptGroups as $oOptGroup)
            {
                $sListeGroupes .= sprintf('%s', $oOptGroup);
            }
        }
        $sListeOpt      = $sListeOptions . $sListeGroupes;

        if(!empty($sListeOpt))
        {
            $sAttrs = '';
            foreach ($this->_aAttributs as $k => $v)
            {
                if(!empty($v))
                {
                    $sAttrs .= ' '. $k .'="'. $v .'"';
                }
            }
            $sListe  = '<'. $this->_tag . $sAttrs .'>'."\n" . $sListeOpt ."\n" .'</'. $this->_tag .'>';
        }
        else
        {
            $msg = sprintf($this->_aExceptionErreurs['liste_options_vide'], $this->_aAttributs['id']);
            $sListe = '<span class="erreur">'. $msg .'</span>';
        }
        return($sListe);
    }
}