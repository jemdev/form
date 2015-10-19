<?php
namespace jemdev\form;
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
 * Gestion des exceptions du package Form
 *
 * @author      Jean Molliné <jmolline@gmail.com>
 * @package     mje
 * @subpackage  Form
 */
class exception extends \Exception
{
    /**
     * Constructeur.
     *
     */
    public function __construct($msg, $code)
    {
        parent::__construct($msg, $code);
    }

    /**
     * Affichage des exceptions levées.
     *
     * @return String
     */
    public function __toString()
    {
        $retour  = '<pre>Erreur applicative :' . "\n";
        $retour  = 'Message : '. $this->getMessage() .';' . "\n";
        $retour  = 'Trace :'. $this->getTraceAsString() . "\n";
        $retour .= '</pre>';
        return $retour;
    }
}