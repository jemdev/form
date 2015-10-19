<?php
namespace mje\form\locale;

$loc = (isset($loc)) ? $loc : 'fr';
switch($loc)
{
    case 'en':
        $oLang = new en();
        break;
    case 'fr':
    default:
        $oLang = new fr();
}

