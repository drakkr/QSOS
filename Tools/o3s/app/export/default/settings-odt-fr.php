<?php
/*
ODT Export
Default template
French settings and translation
*/

$tpl_images = array(
  "100000000000000800000008DD0ADA29.png",
  "10000000000000910000008C868D6347.png",
  "10000201000000720000002C399507E4.png",
  "10000201000001E4000000BC98D160DE.png",
  "10000201000001F8000000C4A8755632.png",
  "1000020100000330000001B06D405BDD.png",
  "2000000800011A5C0000CAFC03E9D74A.wmf",
  "processus-en.png"
);

$tpl_subject = "Rapport d'étude QSOS";
$tpl_creator = "Projet QSOS";
$tpl_client1 = "";
$tpl_client2 = "";
$tpl_projet = "Rapport d'étude QSOS";

$tpl_msg_content['Introduction'] = array(
  array("firsttitle1", "Introduction"),
  array("todo", "TODO : phrase d'introduction (sujet traité, références, remerciements, etc.)."),
  array("simplep", "Cette étude technique se base sur l'utilisation de la méthode QSOS (Qualification et Sélection de logiciels Open Source) pour réaliser l'analyse, l'évaluation et la comparaison des solutions libres retenues."),
  array("simplep", "Cette méthode est mise à disposition par le projet QSOS, projet libre de veille technologique communautaire sponsorisé par Atos. Ce projet est également en charge de la gestion des évaluations réalisées selon le formalisme proposé par la méthode et qui ont été reversées à la communauté."),
  array("p", array(
      array("t","L'annexe du présent document propose une brève introduction à cette méthode, pour plus de détails sur la méthode et le projet QSOS, on peut se reporter au site Web du projet : "),
      array("a", "http://www.qsos.org"),
      array("t",".")
  )),
  array("simplep", "La méthode QSOS permet de formaliser le contexte d'utilisation d'un logiciel libre. Ceci est réalisé via la pondération de critères utilisés pour évaluer le logiciel libre."),
  array("simplep", "Le processus d'affectation des poids aux différents critères d'une analyse QSOS est libre et indépendant de l'évaluation de ces critères. Ceci permet la mutualisation et la réutilisation des travaux réalisés entre les différents membres de la communauté QSOS."),
  array("simplep", "Dans cet objectif, les évaluations QSOS brutes (sans pondération spécifique liée à un contexte particulier) ont été utilisées dans le présent document. Les analyses QSOS (présentées au chapitre suivant) sont donc réalisées avec des pondérations fixées à 1 (tous les critères ont le même poids). Libre à chaque utilisateur de modifier ces pondérations par défaut pour réaliser une grille d'analyse plus pertinente dans son contexte.")
);

$tpl_msg_content['Template'] = array(
  array("title1","Template d'analyse utilisé"),
  array("simplep","Les critères utilisés pour analyser les solutions étudiées dans la suite du présent document ont été regroupés selon les axes suivants :")
);

$tpl_msg_content['Solutions_header'] = array(
  array("title1","Solutions identifiées"),
  array("title2","Périmètre"),
  array("simplep","Les solutions analysées dans la suite de ce chapitre sont les suivantes :")
);

$tpl_msg_content['Solutions_header_todo'] = array(
  array("todo","TODO : lister les éventuelles autres solutions libres non retenues en justifiant brièvement pourquoi.")
);

$tpl_msg_content['Solutions_header_project'] = array(
  array("title3","Présentation du projet")
);

$tpl_msg_content['Solutions_footer_project'] = array(
  array("todo","TODO : Compléter l'analyse (points marquants, forces, faiblesses...)."),
  array("title3","Principe de fonctionnement"),
  array("todo","TODO : Présenter la solution sous l'aspect technique (architecture, langages utilisés...). Il s'agit d'informations que ne sont pas intégrées en tant que telles dans l'analyse QSOS.")
);

$tpl_msg_content['Solutions_header_coverage'] = array(
  array("title3","Couverture fonctionnelle")
);

$tpl_msg_content['Solutions_footer_coverage'] = array(
  array("todo","TODO : Compléter l'analyse (points marquants, forces, faiblesses...).")
);

$tpl_msg_content['Analysis_header_comparison'] = array(
  array("title1","Synthèse"),
  array("title2","Comparaison des solutions")
);

$tpl_msg_content['Analysis_footer_comparison'] = array(
  array("todo","TODO : Compléter l'analyse comparative des solutions.")
);

$tpl_msg_content['Analysis_header_conclusion'] = array(
  array("title2","Conclusion")
);

$tpl_msg_content['Analysis_footer_conclusion'] = array(
  array("todo","TODO : Compléter les conclusions de l'étude. Solution(s) à préférer, solution(s) à abandonner...")
);

$tpl_msg_content['Appendixes_header'] = array(
  array("title1","Annexes"),
  array("title2","Méthode QSOS"),
  array("image","10000201000001E4000000BC98D160DE.png","2.2539in","0.876in"),
  array("simplep","QSOS (Qualification et Sélection de logiciels Open Source) est une méthode conçue par Atos pour qualifier, sélectionner et comparer les logiciels libres et open source. L’utilisation de cette méthode permet une analyse structurée et rationnelle des alternatives open source, qui conduit à une évaluation objective et argumentée des logiciels libres dans le contexte du système d’information étudié."),
  array("p", array(
      array("t","Elle est, tout comme les évaluations qu'elle produit, mise à disposition de la communauté sous licence libre « GNU Free Documentation License » ("),
      array("a", "http://www.gnu.org/copyleft/fdl.html"),
      array("t",").")
  )),
  array("simplep","Les objectifs de QSOS sont les suivants :"),
  array("list", array(
      "Analyser objectivement les différents logiciels libres candidats :",
      array( "Quel logiciel répond le mieux aux besoins fonctionnels actuels et prévus ?", "Quel logiciel s'intègre le mieux à l’infrastructure technique et à l’architecture applicative du SI ?" ),
      "Intégrer les contraintes et quantifier les risques spécifiques à l’open source :",
      array( "Quelle est la pérennité du logiciel ? L'importance et le dynamisme de la communauté des développeurs et des utilisateurs ?", "Quels sont les risques de « forks » ? Comment les anticiper et les gérer ?", "Quel est le niveau de support requis et disponible sur le logiciel ?", "Quelle est la licence libre qui régit l'usage du logiciel ? Quelles conséquences sur les usages (développements complémentaires, partage des produits dérivés, reversements, protection contre la « propriétarisation », etc.) ?", "Est-il possible d’influer sur le logiciel (ajout de nouvelles fonctionnalités) ?"),
      "Tenir compte, dans l’évaluation, du contexte de l’entreprise ou l’administration (existant technique, cible fonctionnelle, compétences des équipes internes, usages du logiciel, criticité dans le système d’information...).",
      "Produire des résultats objectifs, étayés et personnalisés."
  )),
  array("simplep","La démarche structurée proposée par QSOS est décomposée en quatre étapes principales, détaillées dans le schéma suivant."),
  array("image","1000020100000330000001B06D405BDD.png","6.4965in","3.439in"),
  array("p", array(
      array("t","On pourra se reporter au site "),
      array("a", "http://www.qsos.org"),
      array("t"," pour de plus amples détails sur la méthode et le projet libre et communautaire en charge de sa gestion.")
  )),
  array("title2","Grille de comparaison dynamique"),
  array("p", array(
      array("t", "Le présent document est accompagné d'une annexe externe, contenant les résultats de l'application de la méthode QSOS, de référence « " ),
      array("i", "QSOS_".$docs[0]->getkey("qsosappfamily").".ods" ),
      array("t", " »." )
  )),
  array("simplep","Il s'agit d'un document dynamique qui permet aux lecteurs de modifier les pondérations appliquées par défaut aux différents critères présentés plus haut, afin de disposer ainsi d'une analyse comparative plus pertinente dans leurs contextes respectifs.")
);

$tpl_msg_content['Credits'] = array(
  array("title1","Crédits"),
  array("p", array(
      array("t","Une partie de ce document a été générée depuis l'application O3S (Open Source Selection Software), accessible à l'adresse "),
      array("a", "http://www.qsos.org/o3s/"),
      array("t"," développée et mise à disposition par le projet QSOS.")
  )),
  array("simplep","Le contenu généré par O3S provient d'évaluations QSOS réalisées par la communauté :"),
);

?>