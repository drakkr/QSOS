<?php
/*
ODT Export
Atos template
English settings and translation
*/

$tpl_images = array(
  "1000020100000E7F00001FF761256DB8.png",
  "1000020100000280000000D8F71E59FF.png",
  "100000000000023200000194FE524BFD.jpg",
  "1000020100000330000001B06D405BDD.png",
  "2000000800011A5C0000CAFC03E9D74A.wmf",
  "10000201000001E4000000BC98D160DE.png",
  "100000000000000800000008DD0ADA29.png",
  "processus-en.png"
);

$tpl_subject = "QSOS Report";
$tpl_creator = "Atos";
$tpl_client1 = "Customer";
$tpl_client2 = "";
$tpl_projet = "Project";

$tpl_msg_content['Introduction'] = array(
  array("firsttitle1", "Introduction"),
  array("todo", "TODO: introduction (subject of this document, customer context, references, etc.)."),
  array("simplep", "This technical study uses the QSOS method (Qualification and Selection of Opensource Software) to analyze, evaluate and compare open source solutions."),
  array("simplep", "This method is distributed by the QSOS project, sponsored by Atos, to enable free and community-based technical intelligence. The project also manages the evaluations contributed by the community."),
  array("p", array(
      array("t","The appendix of this document introduces briefly the method. For further details please consult the QSOS website: "),
      array("a", "http://www.qsos.org"),
      array("t",".")
  )),
  array("title1", "Context"),
  array("title2", "Current situation and expectations"),
  array("todo", "TODO: Describe context of customer"),
  array("title2", "Requirement formalisation"),
  array("simplep", "The QSOS method allows to describe how an open source solution is to be used in a specific context. This is done by weighting the criteria used to evaluate the solution."),
  array("simplep", "This weighting process is independent from the criteria evaluation itself. This allow the customization of the results to any specific context."),
  array("simplep", "This report uses raw QSOS evaluations (all criteria have the same weight of 1) so each unit of organization is free to modify this weighting to obtain a result more accurate and useful regarding its own specific context.")
);

$tpl_msg_content['Template'] = array(
  array("title1","Functional description"),
  array("title2","Positioning in the IS"),
  array("todo", "TODO: introduction of the study's subject (functional domain, major concepts, applicable standards...)."),
  array("title2","Template used for analysis"),
  array("simplep","The criteria used to evaluate the solutions described in this report have been split on the following axes:")
);

$tpl_msg_content['Solutions_header'] = array(
  array("title1","Identified solutions"),
  array("title2","Perimeter"),
  array("simplep","The solutions later analyzed in this chapter are:")
);

$tpl_msg_content['Solutions_header_todo'] = array(
  array("todo","TODO: List potential alternative solutions not selected for the analysis and motivate their exclusion.")
);

$tpl_msg_content['Solutions_header_project'] = array(
  array("title3","Project analysis")
);

$tpl_msg_content['Solutions_footer_project'] = array(
  array("todo","TODO: Complete analysis (important facts, strengths, weaknesses...)."),
  array("title3","Operating principle"),
  array("todo","TODO: Describe the solution from a technical perspective (architecture, coding languages...). This is often information not part of the QSOS evaluation.")
);

$tpl_msg_content['Solutions_header_coverage'] = array(
  array("title3","Functional coverage")
);

$tpl_msg_content['Solutions_footer_coverage'] = array(
  array("todo","TODO: Complete analysis (important facts, strengths, weaknesses...).")
);

$tpl_msg_content['Analysis_header_comparison'] = array(
  array("title1","Synthesis"),
  array("title2","Comparison of solutions")
);

$tpl_msg_content['Analysis_footer_comparison'] = array(
  array("todo","TODO: Complete the comparative analysis.")
);

$tpl_msg_content['Analysis_header_conclusion'] = array(
  array("title2","Conclusion")
);

$tpl_msg_content['Analysis_footer_conclusion'] = array(
  array("todo","TODO: Complete the report's conclusions. Solution(s) to be preferred, solution(s) not to be considered...."),
  array("title2","Recommendations"),
  array("todo","TODO: Complete the recommendations. What is the best choice in the ccustomer's context? What to do next? Etc.")
);

$tpl_msg_content['Appendixes_header'] = array(
  array("title1","Appendixes"),
  array("title2","QSOS Method"),
  array("image","10000201000001E4000000BC98D160DE.png","2.2539in","0.876in"),
  array("simplep","QSOS (Qualification and Selection of Opensource Software) is a method designed by Atos to qualify, select and compare free and open source solutions. It allows a rational and structured analysis of open source alternatives and is based on objective and motivated data."),
  array("p", array(
      array("t","QSOS and associated evaluations are distributed under the « GNU Free Documentation License » ("),
      array("a", "http://www.gnu.org/copyleft/fdl.html"),
      array("t",").")
  )),
  array("simplep","QSOS has the following goals:"),
  array("list", array(
      "Objectively analyze several open source candidates:",
      array( "Which solution best fits current and future functional needs?", "Which solution integrates best in IS technical infrastructure and applicative architecture?" ),
      "Take into account constraints, quantify risks related to open source software:",
      array( "What is the maturity of the solution? What are the size and dynamism of the community (developers and users)?", "What are the risks of the solution to be « forked »? How to anticipate and manage them?", "What are the required and available levels of support services for the solution?", "Which free or open source licenses apply on the solution? What are the consequences on it's use (additional developments, share of derivative works, contribution processes, protection against « proprietarization », etc.)?", "Is it possible to influence the project (roadmap, addition of new features)?"),
      "Integrate the specific context of each enterprise or administration (existing technologies, functional perimeter, required skills and usages, criticality in the Information System...).",
      "Produce objectives, motivated and personalized results."
  )),
  array("simplep","The process proposed by QSOS is structured in four main steps, illustrated in the following figure."),
  array("image","processus-en.png","6.4965in","3.439in"),
  array("p", array(
      array("t","For further details on QSOS project and community, please refer to this website: "),
      array("a", "http://www.qsos.org")
  )),
  array("title2","Dynamic comparison table"),
  array("p", array(
      array("t", "This report is distributed along with an external appendix containing details of related QSOS evaluation. Its reference is « " ),
      array("i", "QSOS_".$docs[0]->getkey("qsosappfamily").".ods" ),
      array("t", " »." )
  )),
  array("simplep","It is a dynamic document allowing users to modify the weight of each evaluation criteria and thus to personalize the results to their own specific contexts.")
);

$tpl_msg_content['Sources'] = array(
  array("title2","QSOS evaluations"),
  array("simplep","The QSOS project use a XML format to store and manipulate its evaluations. The following documents, provided as external appendixes, were produced during the study:"),
);

?>