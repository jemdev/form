<?php
namespace jemdev\form\tags;

/**
 * @author Cyrano
 *
 */
interface attributesInterface
{
    /**
     * Liste des attributs valides pour une balise donnée.
     *
     * La méthode est statique, on a en effet nul besoin d'un objet
     *
     * @param   String  $balise     Nom de la balise
     * @return  Array
     */
    public static function getAttrParBalise($balise);
}

?>