<?php
namespace jemdev\form\field;
use jemdev\form\form;
use jemdev\form\field;
use jemdev\form\field\option;

/**
 * @author Cyrano
 *
 */
class datalist extends field
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
     * Constructeur.
     */
    function __construct(array $props, form $oForm)
    {
        $this->_tag                 = 'datalist';
        $this->_type                = 'datalist';
        $this->_oForm               = $oForm;
        parent::__construct($oForm);
        $this->_aAttributs['id']    = $props[0];
        $this->_contenu = '';
        $this->_nbOptions = 0;
    }


    /**
     * Ajoute un item dans la liste de sélection.
     *
     * @param   string  $valeur     Contenu de l'attribut value
     * @param   string  $affiche    Valeur affichée dans la liste de sélection
     * @return  Object
     */
    public function addOption(string $valeur, string $affiche, bool $selected = false, ?array $options = null): datalist
    {
        if(!isset($this->_oOption))
        {
            $this->_oOption = new option($this->_oForm);
        }
        $this->_oOption->addValue($valeur, $affiche, $selected, null, $options);
        $this->_nbOptions++;
        return $this;
    }

    public function __toString(): string
    {
        $sListeOptions  = sprintf('%s', $this->_oOption);
        $sListeOpt      = $sListeOptions;

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
