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
     * @param   string  $balise     Nom de la balise
     * @return  array
     */
    public static function getAttrParBalise(string $balise): array;
}

?>