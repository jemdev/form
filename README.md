#jemdev\form
Package de gestion de formulaires.

Ce package permet de cr�er des formulaires HTML et de g�rer la validation des donn�es. Malgr� le nombre de fichiers inclus dans ce package, la seule classe qui va nous int�resser est la classe jemdev\form, toutes les op�rations passant par l�.

Notez par ailleurs que ce package ne g�re absolument pas la mise en page des formulaires, cette t�che �tant � prendre en charge dans les gabarits au moment de l'int�gration. Ce package permet cependant de cr�er les diff�rents champs qui seront inclus dans le code (X)HTML et permettra de d�finir des r�gles de validation pour chaque champ si n�cessaire.
Il sera en outre possible d'�tendre le syst�me de validation en ajoutant une classe utilisateur comportant des m�thodes de validation sp�cifiques qui ne seraient pas couvertes par les m�thodes natives du package.

####Note for english speaking users : English is not my native language, if you're interested to use jemdev\form in your project, if your native language is english, if you understand french speaking and if you want to make a translation, feel free to do so.
## Installation
Via composer, ajouter simplement ceci dans le require de votre fichier composer.json:

        {  
            "jemdev/form": "dev-master"
        }  


## Mise en �uvre
### Cr�ation de l'instance de formulaire
La cr�ation d'une instance va initialiser le formulaire lui-m�me. Cependant, on ne cr�era pas la balise <form> qui sera automatiquement cr��e assortie des attributs appropri�s. On pr�cisera ces �l�ments lors de la cr�ation de l'instance.

        /**  
         * Cr�ation de l'instance de formulaire  
         */  
        $oForm = new jemdev\form('form_id', 'post');  


Notez ici qu'on pr�cise uniquement l'identifiant du formulaire et la m�thode utilis�e. Ces param�tres sont tous facultatifs, cependant, il y aura automatiquement un identifiant d'ins�r�, � form � et par d�faut la m�thode sera � get �. On peut en outre pr�ciser l'attribut action : par d�faut, ce sera l'adresse de la page courante, mais on peut en indiquer une autre.
On peut ensuite indiquer l'attribut enctype : par d�faut, la valeur sera � application/x-www-form-urlencoded � mais sera automatiquement chang�e en � multipart/form-data � si un champ input de type file est cr��. Il est recommand� de ne rien indiquer du tout.
Enfin, on peut indiquer une ou plusieurs classes de validation sp�cifiques, ce point particulier sera vu plus loin.
### Quel DOCTYPE
Par d�faut, les champs seront cr��s selon la syntaxe XHTML 1.0. Cependant, il est possible d'indiquer une autre DTD. Par exemple, pour construire un formulaire HTML, on assigera la valeur � une propri�t� publique de la classe jemdev\form :

        /* Changer le DOCTYPE */  
        $oForm->_sDoctype = 'HTML5';  


##M�thodes publiques du packages jemdev\form
Ce package et inspir� � l'origine de PEAR/HTML_QuickForm. Cependant, � l'�poque o� la premi�re version de hem\form a �t� con�u, l'id�e de base consistait � disposer d'un outil en PHP5.

Ce package est pr�sent� aujurd'hui dans une version 2 int�grant les *namspaces* qui n'existaient pas encore en PHP. L'autre point essentiel � prendre en consid�ration est que les packages que proposaient les frameworks majeurs du moment avaient la f�cheuse tendance � m�langer les genres, � savoir la gestion des donn�es du formulaire et l'affichage de ce m�me formulaire.
jemdev\form g�re la cr�ation des champs et la validation des donn�es saisies, mais l'aspect visuel du formulaire rel�ve d'un processus � part qui n'est pas trait� ici. Les seuls aspects visuels tiennent dans les balises (X)HTML des champs de formulaire, pas � leur int�gration dans le formulaire lui-m�me.
###Les m�thodes de la classe jemdev\form\form
Le principe de cr�ation d'un champ de formulaire a �t� aussi simplifi� que possible. � partir de l'objet jemdev\form\form, on appelle simplement la m�thode correspondant au type de champs qu'on souhaite cr�er.
Par exemple, pour un champ de type *textarea* :

    /* Cr�ation de l'instance de formulaire */
    $oForm = new jemdev\form\form('identifiant_form', 'post');
    /* Cr�ation d'un champ de saisie */
    $zone_texte = $oForm->textarea('id_zone_texte');

Ce n'est pas plus compliqu� que �a, le champ est pr�t � �tre utilis�.

On peut cependant ajouter des attributs � ce champ, nous verrons ce point  un peu plus loin.
Voyons d'abord la liste des champs qu'on peut cr�er.
####champs communs � tous les DOCTYPES
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

####champs propre � HTML5
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

Notez l'absence du champ *input* : c'est voulu pour des raisons de simplification. On peut en revanche observer la pr�sence de champs qui correspondent aux types possible d'un champ *input* : *hidden*, *text*, *radio*, etc...
Quel que soit le champ que l'on souhaite cr�er, la m�thode est la m�me que montr�e plus haut avec un *textarea*.

###Les m�thodes sur les objets cr��s � partir de jemdev\form\form
Lorsqu'on cr�e un champ, la m�thode retourne un objet qui comporte des m�thodes qui deviennent utilisables pour compl�ter certaines propri�t�s de ce champ.
####m�thodes communes pour tous les champs
-    setLabel($label) : Permet d'indiquer le texte d'un label qui sera rattach� au champ. Notez cependant que la balise *label* ne sera pas cr��e, seule la propri�t� du champ sera stock�e et disponible.
-    setAttribute($attr[, $value]) : 
Pour ajouter un attribut � un champ donn�, on appelle simplement la m�thode en indiquant en param�tre le nom de l'attribut et la valeur � y assigner. Notez que la valeur est optionnelle.
Reprenons l'exemple montr� plus haut :

    $zone_texte->setAttribute('cols', 50);
    $zone_texte->setAttribute('rows', 10);

Le r�sultat correspondra alors � la balise html suivante :

    <textarea id="id_zone_texte" name="id_zone_texte" cols="50" rows="10"></textarea>

L'exemple utilis� ici permet de souligner un cas particulier : cette balise ne comporte pas d'attribut *value*, la valeur �tant affich�e entre les deux balises. Cependant, il est possible d'indiquer le contenu de cette zone de saisie de la m�me mani�re que pour n'importe quel autre champ :

    $zone_texte->setAttribute('value', "Contenu de la zone de saisie");

Avec le r�sultat suivant : 

    <textarea id="id_zone_texte" name="id_zone_texte" cols="50" rows="10">Contenu de la zone de saisie</textarea>

Alors qu'avec un champ *input* de type *text*, le r�sultat sera conforme � ce qui est attendu :

    /* Cr�ation d'un champ de saisie */
    $zone_texte = $oForm->text('id_zone_texte');
    $zone_texte->setAttribute('value', "Contenu de la zone de saisie");

R�sultat :

    <input type="text" id="id_zone_texte" name="id_zone_texte" value="Contenu de la zone de saisie" />


-    setRule($rule, $msg[, $val]) :
On peut indiquer des r�gles de validation. Deux param�tres sont obligatoires, le nom de la r�gle, et le message � afficher si la r�gle n'est pas respect�e. En option, certaines r�gles requi�rent un troisi�me param�tre qui sera g�n�ralement une valeur de contr�le.
Une liste des r�gles de validation pr�-existantes sera indiqu�e plus loin, ainsi que la mani�re d'ajouter des r�gles personnalis�es.
####m�thodes propres � chaque objet
-    select
 - addOption
 - addGroup
-    textarea
-    datalist
 - addOption

###Les r�gles de validation

Il existe d�j� des r�gles g�n�riques disponibles pour valider les donn�es d'un formulaire :

-    *required* :
 - V�rifie qu'une valeur requise n'est pas vide.
 - Utilisation : $objet->setRule('required', "message");
-    *email* :
 - V�rifie la validit� d'une adresse de courrier �lectronique.
 - Utilisation : $objet->setRule('email', "message");
-    *url* :
 - V�rifie la validit� de la syntaxe d'une URL
 - Utilisation : $objet->setRule('url', "message");
-    *alpha* :
 - V�rifie la validit� de la syntaxe d'une chaine alphab�tique. N'accepte que des caract�res alphab�tiques qui peuvent �tre accentu�s.
 - Utilisation : $objet->setRule('alpha', "message");
-    *word* :
 - V�rifie la validit� de la syntaxe d'un ou plusieurs mots. Accepte des caract�res alphab�tiques plus guillemets, apostrophes, tirets et espaces.
 - Utilisation : $objet->setRule('word', "message");
-    *alnum* :
 - V�rifie la validit� de la syntaxe d'une chaine alphanum�rique, accepte des mots et des chiffres ainsi que des caract�res de ponctuation.
 - Utilisation : $objet->setRule('alnum', "message");
-    *num* :
 - V�rifie la validit� de la syntaxe d'une chaine de chiffres, n'accepte que des chiffres.
 - Utilisation : $objet->setRule('num', "message");
-    *float* :
 - V�rifie la validit� de la syntaxe d'un nombre flottant. Accepte des chiffres avec ou sans d�cimales et une s�paration sous la forme d'un point ou d'une virgule.
 - Utilisation : $objet->setRule('float', "message");
-    *minlength* :
 - V�rifie que la chaine comporte au moins un certain nombre de caract�res. On doit indiquer en troisi�me param�tre un entier correspondant au nombre minimum de caract�res attendus.
 - Utilisation : $objet->setRule('minlength', "message", 10);
-    *maxlength* :
 - V�rifie que la chaine comporte au maximum un certain nombre de caract�res. On doit indiquer en troisi�me param�tre un entier correspondant au nombre maximum de caract�res attendus.
 - Utilisation : $objet->setRule('maxlength', "message", 50);
-    *rangelength* :
 - V�rifie que la chaine comporte un nombre de caract�res compris entre un minimum et un maximum donn�. On doit indiquer en troisi�me param�tre tableau avec deux entiers correspondant au nombres minimum et maximum de caract�res attendus.
 - Utilisation : $objet->setRule('rangelength', "message", array(10, 50));
-    *minval* :
 - V�rifie que la valeur est sup�rieure ou �gale � une valeur minimum. On doit indiquer en troisi�me param�tre un entier ou flottant correspondant au nombre minimum attendu : cette r�gle pourra avantageusement �tre utilis�e en parall�le avec la r�gle *num* ou *float*.
 - Utilisation : $objet->setRule('minval', "message", 1);
-    *maxval* :
 - V�rifie que la valeur est inf�rieure ou �gale � une valeur maximum. On doit indiquer en troisi�me param�tre un entier ou flottant correspondant au nombre maximum attendu : cette r�gle pourra avantageusement �tre utilis�e en parall�le avec la r�gle *num* ou *float*.
 - Utilisation : $objet->setRule('maxval', "message", 12);
-    *rangeval* :
 - V�rifie que la valeur est comprise entre une valeur maximum et une valeur minimum. On doit indiquer en troisi�me param�tre un tableau de deux entiers ou flottants correspondant aux limites minimum et maximum attendu : cette r�gle pourra avantageusement �tre utilis�e en parall�le avec la r�gle *num* ou *float*.
 - Utilisation : $objet->setRule('rangeval', "message", array(1, 12));
-    *inferieur* :
 - V�rifie que la valeur est strictement inf�rieure � une valeur maximum. On indique en troisi�me param�tre la valeur � laquelle le nombre saisi doit �tre strictement inf�rieur. Cette r�gle pourra �tre utilis�e en parall�le avec la r�gle *num* ou *float*.
 - Utilisation : $objet->setRule('inferieur', "message", 100);
-    *superieur* :
 - V�rifie que la valeur est strictement sup�rieure � une valeur minimum. On indique en troisi�me param�tre la valeur � laquelle le nombre saisi doit �tre strictement sup�rieur. Cette r�gle pourra �tre utilis�e en parall�le avec la r�gle *num* ou *float*.
 - Utilisation : $objet->setRule('superieur', "message", 1);
-    *regex* :
 - V�rifie que la saisie correspond � une expression r�guli�re d�finie. R�gle fort pratique qui dispensera souvent d'�crire d'autres r�gles sp�cifiques. Si la saisie doit correspondre � un masque PCRE particulier, on indiquera ce masque en troisi�me param�tre.
 - Utilisation : $objet->setRule('regex', "message", "#^[A-Z]{1}[0-9]{4}$#");
-    *formatdateheure* :
 - Validation du format d'une date. On utilisera les m�mes r�gles de formats de date que pour la fonction PHP native *date()*, voir [la documentation][].
 - Utilisation : $objet->setRule('formatdateheure', "message", 'd/m/Y');
-    *validedate* :
 - Validation d'une date � partir d'un format donn�. Le format de la date sera v�rifi� selon le troisi�me param�tre, mais l'existence de la date elle-m�me sera v�rifi�, interdisant par exemple de saisir un 30 f�vrier.
 - Utilisation : $objet->setRule('validedate', "message", 'd/m/Y');
-    *comparer* :
 - Compare deux valeurs et retourne vrai si les deux chaines sont identiques. Utile lors de la cr�ation d'un mot de passe avec un champ de confirmation.
 - Utilisation : $objet->setRule('comparer', "message", $valeur\_de\_controle);
-    *differentDe* :
 - V�rifie que la valeur saisie est diff�rente de l'argument. Par exemple, pour une liste de s�lection dont le premier item affiche � S�lectionnez une valeur � et dont l'attribue value vaut � -1 �, on indique cette valeur comme ne pouvant �tre s�lectionn�e. �a �quivaut � rendre un choix obligatoire dans une liste.
 - Utilisation : $objet->setRule('differentDe', "message", '-1');

Notez que si un champ n'est pas d�fini comme requis mais que vous d�finissez une r�gles de validation sur le contenu, la validation ne retournera de message d'erreur que si une saisie a �t� effectu�e. Par exemple, un champ o� doit �tre saisie une date ne retournera d'erreur que si une date a �t� saisie incorrectement, mais la r�gle sera ignor�e si le champ est rest� vide et que la r�gle *required* n'a pas �t� d�finie.

####En r�sum� :
Le principe est simple, la m�thode attend les param�tres suivant :

1.  le nom de la r�gle;  
2.  Le message a afficher le la r�gle n'est pas respect�e;  
3.  pour certains r�gles, une valeur de contr�le (*minlength*, *maxlength*, *minval*, *maxval*, *rangeval*, *inferieur*, *superieur*, *regex*, *validedate*, *comparer*, *differentDe*);  
4.  pour certaines autres r�gles un seconde valeur de contr�le (*rangelength*, *rangeval*) : dans ce cas, le trois�me param�tre sera un tableau index� contenant les deux valeurs � utiliser comme �l�ments de contr�le.

###Le chainage des m�thodes
D�s la cr�ation d'un objet pour un champ de formulaire donn�, on peut chainer les m�thodes et s'affranchir ainsi de la n�cessit� de r�-�crire la variable pour chaque appel d'une nouvelle m�thode.

Exemple :

    $nom = $oForm->text('nom', 'nom')
                 ->setLabel('Nom du client')
                 ->setAttribute('class', 'classe_css_quelconque')
                 ->setAttribute('value', $infosClient['nom])
                 ->setRule('required', "Le nom du client est requis");

###D�finir des r�gles de validation personnalis�es.
Pour les cas o� il ne sera pas possible de d�finir une expression r�guli�re pour valider un champ donn�, il reste possible d'ajouter des m�thodes de validation suppl�mentaires au syst�me de gestion des formulaires.
G�n�ralement, ce sera indispensable lorsqu'il s'agira de collecter des informations de contr�le dans une base de donn�es par exemple ou d'une source externe quelconque.
Pour ce faire, on d�finira une classe �tendant jemdev\form\process\validation. Pour simplifier, voici un mod�le qui pourra vous servir de base :

        class helpers_validationsupplementaire extends jemdev_form_process_validation
        {
            public static $aMethodesSupplementaires = array(
                'autreMethodeValidation'
            );
            /**
             * Constructeur.
             *
             * Cr�e l'instance parente et valide les donn�es du formulaire.
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
             * V�rifie la validit� d'une donn�e saisie.
             *
             * @param   mixed     $valeur
             * @return  bool    Retourne TRUE si la valeur est falide, FALSE dans le cas contraire
             */
            public function _autreMethodeValidation($valeur)
            {
                /* Code validant la valeur saisie dans le formulaire et retournant un bool�en */
                //... $bValide = false; // <== Votre propre code d�finissant la valeur du retour
                /* On retourne le r�sultat */
                return $bValide;
            }
        }

Le nom de cette classe et la mani�re dont elle sera charg�e dans votre application d�pend de vous m�me et de l'architecture de votre application.

La partie qui nous int�resse essentiellement est la propri�t� de classe � $aMethodesSupplementaires � o� vous indiquerez le nom de la r�gle qui correspond au nom de la m�thode. Ensuite, la m�thode de validation : elle re�oit en param�tre au minimum la valeur de la saisie. Le code qu'elle contient d�pend de vos propres besoins et devra imp�rativement retourner un BOOL�EN. Si vous devez indiquer un autre param�tre, par exemple une valeur de contr�le, vous devrez l'ajouter dans la d�finition de la m�thode ainsi que lors de l'appel de la m�thode lorsque vous d�finirez la r�gle de validation sur le champ concern�.

### Mise en �uvre des m�thodes de validation suppl�mentaires
Pour que vos r�gles de validation personnalis�es soient prises en compte, il convient d'abord de les d�finir avant la cr�ation de l'instance de formulaire. Ensuite, vous devrez pr�ciser lors de la cr�ation de cette instance la liste des classes o� ces r�gles suppl�mentaires devront �tre trouv�es.

        /* Le formulaire n�cessitera des m�thodes sp�cifiques de validation : on le chemin vers la classe appropri�e */
        $aClassValidExterne = array(
            "chemin/vers/fichier/classe/specifique.php",
            'nom_de_la_classe'
        );

        /**
         * Cr�ation de l'instance de formulaire
         */
        $oForm = new jemdev\form\form('test', 'post', null, $aClassValidExterne);

Notez que la classe suppl�mentaire est indiqu�e en quatri�me param�tre lors de la cr�ation de l'instance du formulaire. On peut mettre le troisi�me param�tre � NULL dans la mesure o� une valeur sera mise par d�faut et automatiquement modifi�e si n�cessaire lors de l'ajout d'un champ input de type FILE.
Vous pouvez maintenant appliquer la r�gle personnalis�e sur le champ appropri� comme vu ci-dessus.

####NOTE : il n'est pas exclus que le code de la partie validation soit simplifi� dans un avenir rapproch� de fa�on � rendre la cr�ation de classe de validation suppl�mentaires moins compliqu� m�me si en pratique �a ne pr�sente actuellement que peu de difficult�s.
## Int�grer le formulaire dans un template
Il ne reste plus qu'� faire afficher le formulaire, pour �a, on va pr�parer un gabarit.
###Pr�parer un gabarit (template)
Pour vous simplifier au maximum le travail, pr�parez un gabarit dont vous pourrez ajuster la mise en forme sous forme HTML. Exemple basique de formulaire tel qu'il sera finalement affich� :

        <form id="template_form" action="#" method="post">
          <fieldset>
            <legend>Les champs de mon formulaire</legend>
            <p>
              <label for="nom">Votre nom</label>
              <input type="text" name="nom" id="nom" value="" />
            </p>
            <input type="submit" id="envoi" name="envoi" value="Envoyer les donn�es" />
          </fieldset>
        </form>

� ce stade, on a besoin que de la partie fieldset, n'utilisez pas la balise <FORM> qui sera de toutes fa�ons int�gr�e automatiquement. On va mettre le contenu tel quel dans une variable PHP, ici en utilisant la syntaxe HEREDOC :

        <?php
        $sForm = <<<CODE_HTML
              <fieldset>
                <legend>Les champs de mon formulaire</legend>
                <p>
                  <label for="nom">Votre nom</label>
                  <input type="text" name="nom" id="nom" value="" />
                </p>
                <input type="submit" id="envoi" name="envoi" value="Envoyer les donn�es" />
              </fieldset>

        CODE_HTML;

###Int�grer les champs dynamiques
Maintenant, on va remplacer certaines parties � en dur � par des variables PHP. Le code final ressemblera � ceci :

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

Observez les lignes o� se trouvent les champs : vous pouvez voir qu'on indique les variables entre accolades � { � et � } � et qu'on peut appeler deux propri�t�s. Ces variables sont en effet des objets et comportent donc des propri�t� : id et label que vous aurez d�finis lors de la cr�ation des champs. La variable mise seule entre accolade fera appel � la m�thode __toString de l'instance qui retournera le code HTML de la balise.

Cette fa�on de proc�der vous laisse toute latitude pour structurer vos formulaires de la mani�re qui vous convient le mieux, rien n'est impos� � ce niveau et le package jemdev\form ne traite pas du tout l'affichage.
###Ajouter le contenu et r�cup�rer le code complet du formulaire.
Une fois votre gabari d�fini et vos variables mise en place, et une fois que tous vos champs ont �t� cr��s, il ne reste qu'� r�cup�rer le tout pr�t � afficher dans votre page.

        /* On d�finit le chemin vers le gabarit */
        $form_tpl       = "chemin/vers/le/fichier/form.phtml";


Notez que le fichier a ici une extension � .phtml � qui est relativement classique, mais rien ne vous emp�che d'utiliser une autre extension, du genre � .tpl.php � par exemple. N�anmoins, je recommande � .phtml �.

        /* On inclut le fichier gabarit */
        include($form_tpl);
        /**
         * On indique � l'instance de formulaire que son contenu est la
         * variable d�finie dans le gabarit, ici $sForm
         */
        $oForm->contenu = $sForm;
        /* On r�cup�re le code complet du formulaire */
        $sFormulaire = sprintf('%s', $oForm);

Important : vous n'avez pas vu l'int�gration des champs cach� : normal, c'est ajout� automatiquement lors de la derni�re op�ration.
## Conclusion
J'ai cr�� ce package aux environ de 2007 ou 2008 et je m'en sers quotidiennement. Cette version est une refonte int�grant les espaces de nom et composer pour simplifier son utilisation. Si besoin est, faites-moi part de vos observations et/ou questions, je m'efforcerai de trouver le temps n�cessaire pour y donner suite.

[la documentation]: http://php.net/date "Documentation PHP pour la fonction date"
