# jemdev\form
Package de gestion de formulaires.

Ce package permet de créer des formulaires HTML et de gérer la validation des données. Malgré le nombre de fichiers inclus dans ce package, la seule classe qui va nous intéresser est la classe jemdev\form, toutes les opérations passant par là.

Notez par ailleurs que ce package ne gère absolument pas la mise en page des formulaires, cette tâche étant à prendre en charge dans les gabarits au moment de l'intégration. Ce package permet cependant de créer les différents champs qui seront inclus dans le code (X)HTML et permettra de définir des règles de validation pour chaque champ si nécessaire.
Il sera en outre possible d'étendre le système de validation en ajoutant une classe utilisateur comportant des méthodes de validation spécifiques qui ne seraient pas couvertes par les méthodes natives du package.

#### Note for english speaking users : English is not my native language, if you're interested to use jemdev\form in your project, if your native language is english, if you understand french speaking and if you want to make a translation, feel free to do so.
## Installation
Via composer, ajouter simplement ceci dans le require de votre fichier composer.json:

```
"require" : {
    "jemdev/form": "dev-master"
}
```


## Mise en œuvre
### Création de l'instance de formulaire
La création d'une instance va initialiser le formulaire lui-même. Cependant, on ne créera pas la balise `<form>` qui sera automatiquement créée assortie des attributs appropriés. On précisera ces éléments lors de la création de l'instance.

```php
/**  
 * Création de l'instance de formulaire  
 */  
$oForm = new jemdev\form('form_id', 'post');  
```


Notez ici qu'on précise uniquement l'identifiant du formulaire et la méthode utilisée. Ces paramètres sont tous facultatifs, cependant, il y aura automatiquement un identifiant d'inséré, *« form »* et par défaut la méthode sera *« get »*. On peut en outre préciser l'attribut action : par défaut, ce sera l'adresse de la page courante, mais on peut en indiquer une autre.
On peut ensuite indiquer l'attribut *enctype* : par défaut, la valeur sera *« application/x-www-form-urlencoded »* mais sera automatiquement changée en *« multipart/form-data »* si un champ input de type file est créé. Il est recommandé de ne rien indiquer du tout.
Enfin, on peut indiquer une ou plusieurs classes de validations spécifiques, ce point particulier sera vu plus loin.
### Quel DOCTYPE
Par défaut, les champs seront créés selon la syntaxe XHTML 1.0. Cependant, il est possible d'indiquer une autre DTD. Par exemple, pour construire un formulaire HTML, on assignera la valeur à une propriété publique de la classe jemdev\form :

```php
/* Changer le DOCTYPE */  
$oForm->_sDoctype = 'HTML5';  
```

## Méthodes publiques du packages jemdev\form
Ce package et inspiré à l'origine de *PEAR/HTML_QuickForm*. Cependant, à l'époque où la première version de jemdev\form a été conçu, l'idée de base consistait à disposer d'un outil en PHP5.

Ce package est présenté aujourd'hui dans une version 2 intégrant les *namespaces* qui n'existaient pas encore en PHP. L'autre point essentiel à prendre en considération est que les packages que proposaient les frameworks majeurs du moment avaient la fâcheuse tendance à mélanger les genres, à savoir la gestion des données du formulaire et l'affichage de ce même formulaire.
jemdev\form gère la création des champs et la validation des données saisies, mais l'aspect visuel du formulaire relève d'un processus à part qui n'est pas traité ici. Les seuls aspects visuels tiennent dans les balises (X)HTML des champs de formulaire, pas à leur intégration dans le formulaire lui-même.
### Les méthodes de la classe jemdev\form\form
Le principe de création d'un champ de formulaire a été aussi simplifié que possible. À partir de l'objet jemdev\form\form, on appelle simplement la méthode correspondant au type de champs qu'on souhaite créer.
Par exemple, pour un champ de type *textarea* :

```php
/* Création de l'instance de formulaire */
$oForm = new jemdev\form\form('identifiant_form', 'post');
/* Création d'un champ de saisie */
$zone_texte = $oForm->textarea('id_zone_texte');
```

Ce n'est pas plus compliqué que ça, le champ est prêt à être utilisé.

On peut cependant ajouter des attributs à ce champ, nous verrons ce point  un peu plus loin.
Voyons d'abord la liste des champs qu'on peut créer.
#### champs communs à tous les DOCTYPES
-    hidden
-    select
-    textarea
-    groupe
-    text
-    radio
-    checkbox
-    submit
-    reset
-    file
-    image
-    button

#### champs propre à HTML5
-    datalist
-    color
-    date
-    email
-    number
-    range
-    search
-    tel
-    time
-    url

Notez l'absence du champ *input* : c'est voulu pour des raisons de simplification. On peut en revanche observer la présence de champs qui correspondent aux types possible d'un champ *input* : *hidden*, *text*, *radio*, etc...
Quel que soit le champ que l'on souhaite créer, la méthode est la même que montrée plus haut avec un *textarea*.

### Les méthodes sur les objets créés à partir de jemdev\form\form
Lorsqu'on crée un champ, la méthode retourne un objet comportant des méthodes qui deviennent utilisables pour compléter certaines propriétés de ce champ.
#### méthodes communes pour tous les champs
-    setLabel($label) : Permet d'indiquer le texte d'un label qui sera rattaché au champ. Notez cependant que la balise *label* ne sera pas créée, seule la propriété du champ sera stockée et disponible.
-    setAttribute($attr [, $value]) : 
Pour ajouter un attribut à un champ donné, on appelle simplement la méthode en indiquant en paramètre le nom de l'attribut et la valeur à y assigner. Notez que la valeur est optionnelle.
Reprenons l'exemple montré plus haut :

```php
$zone_texte->setAttribute('cols', 50);
$zone_texte->setAttribute('rows', 10);
```

Le résultat correspondra alors à la balise html suivante :

```html
<textarea id="id_zone_texte" name="id_zone_texte" cols="50" rows="10"></textarea>
```

L'exemple utilisé ici permet de souligner un cas particulier : cette balise ne comporte pas d'attribut *value*, la valeur étant affichée entre les deux balises. Cependant, il est possible d'indiquer le contenu de cette zone de saisie de la même manière que pour n'importe quel autre champ :

```php
$zone_texte->setAttribute('value', "Contenu de la zone de saisie");
```

Avec le résultat suivant : 

```html
<textarea id="id_zone_texte" name="id_zone_texte" cols="50" rows="10">Contenu de la zone de saisie</textarea>
```

Alors qu'avec un champ *input* de type *text*, le résultat sera conforme à ce qui est attendu :

```php
/* Création d'un champ de saisie */
$zone_texte = $oForm->text('id_zone_texte');
$zone_texte->setAttribute('value', "Contenu de la zone de saisie");
```

Résultat :

```html
<input type="text" id="id_zone_texte" name="id_zone_texte" value="Contenu de la zone de saisie" />
```

-    setRule($rule, $msg[, $val]) :
On peut indiquer des règles de validation. Deux paramètres sont obligatoires, le nom de la règle, et le message à afficher si la règle n'est pas respectée. En option, certaines règles requièrent un troisième paramètre qui sera généralement une valeur de contrôle.
Une liste des règles de validation pré-existantes sera indiquée plus loin, ainsi que la manière d'ajouter des règles personnalisées.
#### méthodes propres à chaque objet

* select
    * addOption
    * addGroup
* textarea
* datalist
    * addOption

### Les règles de validation

Il existe déjà des règles génériques disponibles pour valider les données d'un formulaire :

* *required* :
    * Vérifie qu'une valeur requise n'est pas vide.
    * Utilisation : $objet->setRule('required', "message");
* *email* :
    * Vérifie la validité d'une adresse de courrier électronique.
    * Utilisation : $objet->setRule('email', "message");
* *url* :
    * Vérifie la validité de la syntaxe d'une URL
    * Utilisation : $objet->setRule('url', "message");
* *alpha* :
    * Vérifie la validité de la syntaxe d'une chaîne alphabétique. N'accepte que des caractères alphabétiques qui peuvent être accentués.
    * Utilisation : $objet->setRule('alpha', "message");
* *word* :
    * Vérifie la validité de la syntaxe d'un ou plusieurs mots. Accepte des caractères alphabétiques plus guillemets, apostrophes, tirets et espaces.
    * Utilisation : $objet->setRule('word', "message");
* *alnum* :
    * Vérifie la validité de la syntaxe d'une chaîne alphanumérique, accepte des mots et des chiffres ainsi que des caractères de ponctuation.
    * Utilisation : $objet->setRule('alnum', "message");
* *num* :
    * Vérifie la validité de la syntaxe d'une chaîne de chiffres, n'accepte que des chiffres.
    * Utilisation : $objet->setRule('num', "message");
* *float* :
  * Vérifie la validité de la syntaxe d'un nombre flottant. Accepte des chiffres avec ou sans décimales et une séparation sous la forme d'un point ou d'une virgule.
  * Utilisation : $objet->setRule('float', "message");
* *minlength* :
  * Vérifie que la chaîne comporte au moins un certain nombre de caractères. On doit indiquer en troisième paramètre un entier correspondant au nombre minimum de caractères attendus.
  * Utilisation : $objet->setRule('minlength', "message", 10);
* *maxlength* :
  * Vérifie que la chaîne comporte au maximum un certain nombre de caractères. On doit indiquer en troisième paramètre un entier correspondant au nombre maximum de caractères attendus.
  * Utilisation : $objet->setRule('maxlength', "message", 50);
* *rangelength* :
  * Vérifie que la chaine comporte un nombre de caractères compris entre un minimum et un maximum donné. On doit indiquer en troisième paramètre tableau avec deux entiers correspondant au nombres minimum et maximum de caractères attendus.
  * Utilisation : $objet->setRule('rangelength', "message", array(10, 50));
* *minval* :
  * Vérifie que la valeur est supérieure ou égale à une valeur minimum. On doit indiquer en troisième paramètre un entier ou flottant correspondant au nombre minimum attendu : cette règle pourra avantageusement être utilisée en parallèle avec la règle *num* ou *float*.
  * Utilisation : $objet->setRule('minval', "message", 1);
* *maxval* :
  * Vérifie que la valeur est inférieure ou égale à une valeur maximum. On doit indiquer en troisième paramètre un entier ou flottant correspondant au nombre maximum attendu : cette règle pourra avantageusement être utilisée en parallèle avec la règle *num* ou *float*.
  * Utilisation : $objet->setRule('maxval', "message", 12);
* *rangeval* :
  * Vérifie que la valeur est comprise entre une valeur maximum et une valeur minimum. On doit indiquer en troisième paramètre un tableau de deux entiers ou flottants correspondant aux limites minimum et maximum attendu : cette règle pourra avantageusement être utilisée en parallèle avec la règle *num* ou *float*.
  * Utilisation : $objet->setRule('rangeval', "message", array(1, 12));
* *inferieur* :
  * Vérifie que la valeur est strictement inférieure à une valeur maximum. On indique en troisième paramètre la valeur à laquelle le nombre saisi doit être strictement inférieur. Cette règle pourra être utilisée en parallèle avec la règle *num* ou *float*.
  * Utilisation : $objet->setRule('inferieur', "message", 100);
* *superieur* :
  * Vérifie que la valeur est strictement supérieure à une valeur minimum. On indique en troisième paramètre la valeur à laquelle le nombre saisi doit être strictement supérieur. Cette règle pourra être utilisée en parallèle avec la règle *num* ou *float*.
  * Utilisation : $objet->setRule('superieur', "message", 1);
* *regex* :
  * Vérifie que la saisie correspond à une expression régulière définie. Règle fort pratique qui dispensera souvent d'écrire d'autres règles spécifiques. Si la saisie doit correspondre à un masque PCRE particulier, on indiquera ce masque en troisième paramètre.
  * Utilisation : $objet->setRule('regex', "message", "#^[A-Z]{1}[0-9]{4}$#");
* *formatdateheure* :
  * Validation du format d'une date. On utilisera les mêmes règles de formats de date que pour la fonction PHP native *date()*, voir [la documentation][].
  * Utilisation : $objet->setRule('formatdateheure', "message", 'd/m/Y');
* *validedate* :
  * Validation d'une date à partir d'un format donné. Le format de la date sera vérifié selon le troisième paramètre, mais l'existence de la date elle-même sera vérifié, interdisant par exemple de saisir un 30 février.
  * Utilisation : $objet->setRule('validedate', "message", 'd/m/Y');
* *comparer* :
  * Compare deux valeurs et retourne vrai si les deux chaines sont identiques. Utile lors de la création d'un mot de passe avec un champ de confirmation.
  * Utilisation : $objet->setRule('comparer', "message", $valeur\_de\_controle);
* *differentDe* :
  * Vérifie que la valeur saisie est différente de l'argument. Par exemple, pour une liste de sélection dont le premier item affiche « Sélectionnez une valeur » et dont l'attribut *value* vaut *« -1 »*, on indique cette valeur comme ne pouvant être sélectionnée. Ça équivaut à rendre un choix obligatoire dans une liste.
  * Utilisation : $objet->setRule('differentDe', "message", '-1');

Notez que si un champ n'est pas défini comme requis mais que vous définissez une règles de validation sur le contenu, la validation ne retournera de message d'erreur que si une saisie a été effectuée. Par exemple, un champ où doit être saisie une date ne retournera d'erreur que si une date a été saisie incorrectement, mais la règle sera ignorée si le champ est resté vide et que la règle *required* n'a pas été définie.

#### En résumé :
Le principe est simple, la méthode attend les paramètres suivant :

1.  le nom de la règle;  
2.  Le message a afficher le la règle n'est pas respectée;  
3.  pour certains règles, une valeur de contrôle (*minlength*, *maxlength*, *minval*, *maxval*, *rangeval*, *inferieur*, *superieur*, *regex*, *validedate*, *comparer*, *differentDe*);  
4.  pour certaines autres règles un seconde valeur de contrôle (*rangelength*, *rangeval*) : dans ce cas, le troisième paramètre sera un tableau indexé contenant les deux valeurs à utiliser comme éléments de contrôle.

### Le chaînage des méthodes
Dès la création d'un objet pour un champ de formulaire donné, on peut chaîner les méthodes et s'affranchir ainsi de la nécessité de ré-écrire la variable pour chaque appel d'une nouvelle méthode.

Exemple :

```php
$nom = $oForm->text('nom', 'nom')
             ->setLabel('Nom du client')
             ->setAttribute('class', 'classe_css_quelconque')
             ->setAttribute('value', $infosClient['nom'])
             ->setRule('required', "Le nom du client est requis");
```

### Définir des règles de validation personnalisées.
Pour les cas où il ne sera pas possible de définir une expression régulière pour valider un champ donné, il reste possible d'ajouter des méthodes de validation supplémentaires au système de gestion des formulaires.
Généralement, ce sera indispensable lorsqu'il s'agira de collecter des informations de contrôle dans une base de données par exemple ou d'une source externe quelconque.
Pour ce faire, on définira une classe étendant jemdev\form\process\validation. Pour simplifier, voici un modèle qui pourra vous servir de base :

```php
class helpers_validationsupplementaire extends jemdev\form\process\validation
{
    public static $aMethodesSupplementaires = array(
        'autreMethodeValidation'
    );
    /**
     * Constructeur.
     *
     * Crée l'instance parente et valide les données du formulaire.
     *
     */
    public function __construct($aDatas, $oRules, $aExceptionErreurs)
    {
        parent::__construct($aDatas, $oRules, $aExceptionErreurs);
        foreach(self::$aMethodesSupplementaires as $methode)
        {
            self::$methodesValidation[] = $methode;
        }
    }
    public function __get($cle)
    {
        $authorise = array('aMethodesSupplementaires');
        if(in_array($cle, $authorise))
        {
            return $this->${$cle};
        }
    }

    /**
     * Vérifie la validité d'une donnée saisie.
     *
     * @param   mixed     $valeur
     * @return  bool    Retourne TRUE si la valeur est falide, FALSE dans le cas contraire
     */
    public function _autreMethodeValidation($valeur)
    {
        /* Code validant la valeur saisie dans le formulaire et retournant un booléen */
        //... $bValide = false; // <== Votre propre code définissant la valeur du retour
        /* On retourne le résultat */
        return $bValide;
    }
}
```

Le nom de cette classe et la manière dont elle sera chargée dans votre application dépend de vous même et de l'architecture de votre application.

La partie qui nous intéresse essentiellement est la propriété de classe « `$aMethodesSupplementaires` » où vous indiquerez le nom de la règle qui correspond au nom de la méthode. Ensuite, la méthode de validation : elle reçoit en paramètre au minimum la valeur de la saisie. Le code qu'elle contient dépend de vos propres besoins et devra impérativement retourner un BOOLÉEN. Si vous devez indiquer un autre paramètre, par exemple une valeur de contrôle, vous devrez l'ajouter dans la définition de la méthode ainsi que lors de l'appel de la méthode lorsque vous définirez la règle de validation sur le champ concerné.

### Mise en œuvre des méthodes de validation supplémentaires
Pour que vos règles de validation personnalisées soient prises en compte, il convient d'abord de les définir avant la création de l'instance de formulaire. Ensuite, vous devrez préciser lors de la création de cette instance la liste des classes où ces règles supplémentaires devront être trouvées.

```php
/* Le formulaire nécessitera des méthodes spécifiques de validation : on le chemin vers la classe appropriée */
$aClassValidExterne = array(
    "chemin/vers/fichier/classe/specifique.php",
    'nom_de_la_classe'
);

/**
 * Création de l'instance de formulaire
 */
$oForm = new jemdev\form\form('test', 'post', null, $aClassValidExterne);
```

Notez que la classe supplémentaire est indiquée en quatrième paramètre lors de la création de l'instance du formulaire. On peut mettre le troisième paramètre à `NULL` dans la mesure où une valeur sera mise par défaut et automatiquement modifiée si nécessaire lors de l'ajout d'un champ input de type FILE.
Vous pouvez maintenant appliquer la règle personnalisée sur le champ approprié comme vu ci-dessus.

#### NOTE : il n'est pas exclus que le code de la partie validation soit simplifié dans un avenir rapproché de façon à rendre la création de classe de validations supplémentaires moins compliqué même si en pratique ça ne présente actuellement que peu de difficultés.
## Intégrer le formulaire dans un gabarit (template)
Il ne reste plus qu'à faire afficher le formulaire, pour ça, on va préparer un gabarit.
### Préparer un gabarit (template)
Pour vous simplifier au maximum le travail, préparez un gabarit dont vous pourrez ajuster la mise en forme en HTML. Exemple basique de formulaire tel qu'il sera finalement affiché :

```html
<form id="template_form" action="#" method="post">
  <fieldset>
    <legend>Les champs de mon formulaire</legend>
    <p>
      <label for="nom">Votre nom</label>
      <input type="text" name="nom" id="nom" value="" />
    </p>
    <input type="submit" id="envoi" name="envoi" value="Envoyer les données" />
  </fieldset>
</form>
```

À ce stade, on a besoin que de la partie `fieldset`, n'utilisez pas la balise `FORM` qui sera de toutes façons intégrée automatiquement. On va mettre le contenu tel quel dans une variable PHP, ici en utilisant la syntaxe HEREDOC :

```php
<?php
$sForm = <<<CODE_HTML
      <fieldset>
        <legend>Les champs de mon formulaire</legend>
        <p>
          <label for="nom">Votre nom</label>
          <input type="text" name="nom" id="nom" value="" />
        </p>
        <input type="submit" id="envoi" name="envoi" value="Envoyer les données" />
      </fieldset>

CODE_HTML;
```

### Intégrer les champs dynamiques
Maintenant, on va remplacer certaines parties *« en dur »* par des variables PHP. Le code final ressemblera à ceci :

```php
<?php
$sForm = <<<CODE_HTML
      <fieldset>
        <legend>Les champs de mon formulaire</legend>
        <p>
          <label for="{$nom->id}">{$nom->label}</label>
          {$nom}
        </p>
        {$envoi}
      </fieldset>

CODE_HTML;
```

Observez les lignes où se trouvent les champs : vous pouvez voir qu'on indique les variables entre accolades « { » et « } » et qu'on peut appeler deux propriétés. Ces variables sont en effet des objets et comportent donc des propriété : id et label que vous aurez définis lors de la création des champs. La variable mise seule entre accolade fera appel à la méthode __toString de l'instance qui retournera le code HTML de la balise.

Cette façon de procéder vous laisse toute latitude pour structurer vos formulaires de la manière qui vous convient le mieux, rien n'est imposé à ce niveau et le package jemdev\form ne traite pas du tout l'affichage.
### Ajouter le contenu et récupérer le code complet du formulaire.
Une fois votre gabarit défini et vos variables mises en place, et une fois que tous vos champs ont été créés, il ne reste qu'à récupérer le tout prêt à afficher dans votre page.

```php
/* On définit le chemin vers le gabarit */
$form_tpl       = "chemin/vers/le/fichier/form.phtml";
```

Notez que le fichier a ici une extension « .phtml » qui est relativement classique, mais rien ne vous empêche d'utiliser une autre extension, du genre « .tpl.php » par exemple. Néanmoins, je recommande « .phtml ».

```php
/* On crée tous les champs de notre formulaire */
/* ... ... ... ... */
/* On inclut le fichier gabarit */
include($form_tpl);
/**
 * On indique à l'instance de formulaire que son contenu est la
 * variable définie dans le gabarit, ici $sForm
 */
$oForm->contenu = $sForm;
/* On récupère le code complet du formulaire */
$sFormulaire = sprintf('%s', $oForm);
```

Important : vous n'avez pas vu l'intégration des champs caché : normal, c'est ajouté automatiquement lors de la dernière opération.
## Conclusion
J'ai créé ce package aux environ de 2007 ou 2008 et je m'en sers quotidiennement. Cette version est une refonte intégrant les espaces de nom et *composer* pour simplifier son utilisation. Si besoin est, faites-moi part de vos observations et/ou questions, je m'efforcerai de trouver le temps nécessaire pour y donner suite.

[la documentation]: http://php.net/date "Documentation PHP pour la fonction date"
