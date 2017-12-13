# SOFAD Starter

Licence : SOFAD, tous droits réservés



# Description

SOFAD Starter est un *starter theme* destiné à être utilisé pour la production
de cours dans SOFADauteur. Par conséquent, seuls les gabarits pour l'affichage
des pages sont définis. Des gabarits de prévisualisation des *custom post types*
de SOFADauteur sont aussi inclus.

SOFAD Starter inclut [Foundation 6](https://foundation.zurb.com/) et SOFAD mini.
SOFAD mini est un [framework](https://codex.wordpress.org/Theme_Frameworks) dont
l'objectif principal est de faciliter le rendu d'éléments de navigation basés
sur la parentalité des pages.

# Usage

Idéalement, se baser sur les *grids* de [Foundation 6](https://foundation.zurb.com/)
pour concevoir des mises en page réactives. Foundation 6 inclus aussi bon nombre
d'éléments UI dont on fera usage s'il y  a lieu.

Toutes les feuilles de style, images etc. nécessaires au bonne affichage du cours
doivent être placées dans le dossier `assets`.

## Navigation par parentalité

Reproduire la table des matières du cours sous la forme d'une arborescence de
pages parentes. Utiliser le champ *ordre* pour spécifier l'ordre entre les
sections et les pages sœurs. Règle du pouce : faire des bonds de dix [10, 20, 30… 10n]
lors de la spécification de l'ordre. Utiliser les plages entre les
dizaines [11 à 19, 21 à 29, 31 à 39, etc.] pour gérer les modifications dans
l'ordre et les ajouts de pages imprévus.

Utiliser SOFAD mini pour produire une navigation basée sur la parenté avec
la page affichée. Les fonctions de SOFAD mini sont définies et documentées
dans `sofadmini/functions.php`. Les fragments HTML fournis (optionnellement)
en arguments de ces fonctions utilisent la
syntaxe [printf](https://secure.php.net/manual/function.sprintf.php).
Pour des exemples d'usage, voir `page.php` et `section.php`.

## Pages parentes bidon et catégorie « groupeur »

Créer une catégorie de pages dont l'identifiant est `groupeur` si elle
n'existe pas déjà. Placer dans cette catégorie toutes les pages parentes
sans contenu dont la seule utilité est de représenter une section. La
navigation générée par SOFAD mini ne produira jamais d'hyperlien menant
à une page catégorisée comme `groupeur`. Selon le contexte, un élément
de navigation `groupeur` sera affiché sans hyperlien ou avec un hyperlien
menant au premier descandant de l'élément qui n'est pas lui-même `groupeur`.

N'hésitez pas à définir des gabarits supplémentaires, tels que `section.php`,
ou à supprimer `section.php` si vous n'en avez pas besoin.
