<?xml version="1.0"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="xml" indent="yes" encoding="UTF-8"/>

  <xsl:template match="map">
    <xsl:element name="document">
      <xsl:apply-templates select="node"/>
    </xsl:element>
  </xsl:template>

  <xsl:template match="node">
    <xsl:choose>
      <xsl:when test="parent::map">
	<header>
	    <xsl:apply-templates select="//node[@ID='authors']"/>
	    <dates>
	      <creation><xsl:value-of select="//node[@ID='creation_entry']/@TEXT"/></creation>
	      <validation><xsl:value-of select="//node[@ID='update_entry']/@TEXT"/></validation>
	    </dates>
	    <appname></appname>
	    <desc></desc>
	    <release></release>
	    <licenseid></licenseid>
	    <licensedesc></licensedesc>
	    <url></url>
	    <demourl></demourl>
	    <language><xsl:value-of select="//node[@ID='language_entry']/@TEXT"/></language>
	    <qsosappname></qsosappname>
	    <qsosformat>1.0</qsosformat>
	    <qsosspecificformat><xsl:value-of select="//node[@ID='version_entry']/@TEXT"/></qsosspecificformat>
	    <qsosappfamily><xsl:value-of select="@TEXT"/></qsosappfamily>
	</header>
	<xsl:apply-templates select="node"/>
      </xsl:when>
      <xsl:when test="./@ID='maturity'">
	<section name="maturity" title="Maturité">
	  <desc>Maturité du projet en charge du développement et de la maintenance du logiciel</desc>
	  <element name="legacy" title="Patrimoine">
	    <desc>Historique et patrimoine du projet</desc>
	    <element name="age" title="Age du projet">
	      <desc0>Inférieur à trois mois</desc0>
	      <desc1>Entre trois mois et trois ans</desc1>
	      <desc2>Supérieur à trois ans</desc2>
	      <comment/>
	      <score/>
	    </element>
	    <element name="historyknowproblems" title="Historique">
	      <desc0>Le logiciel connaît de nombreux problèmes qui peuvent être rédhibitoires</desc0>
	      <desc1>Pas de problèmes majeurs, ni de crise ou historique inconnu</desc1>
	      <desc2>Bon historique de gestion de projet et de crise</desc2>
	      <comment/>
	      <score/>
	    </element>
	    <element name="developersidentificationturnover" title="Equipe de développement">
	      <desc0>Très peu de développeurs identifiés ou développeur unique</desc0>
	      <desc1>Quelques développeurs actifs</desc1>
	      <desc2>Equipe de développement importante et identifiée</desc2>
	      <comment/>
	      <score/>
	    </element>
	    <element name="popularity" title="Popularité">
	      <desc0>Très peu d'utilisateurs identifiés</desc0>
	      <desc1>Usage décelable</desc1>
	      <desc2>Nombreux utilisateurs et références</desc2>
	      <comment/>
	      <score/>
	    </element>
	  </element>
	  <element name="activity" title="Activité">
	    <desc>Activité du et autour du projet</desc>
	    <element name="contributingcommunity" title="Communauté des contributeurs">
	      <desc0>Pas de communauté ou de réelle activité (forum, liste de diffusion...)</desc0>
	      <desc1>Communauté existante avec une activité notable</desc1>
	      <desc2>Communauté forte : grosse activité sur les forums, de nombreux contributeurs et défenseurs</desc2>
	      <comment/>
	      <score/>
	    </element>
	    <element name="activityonbugs" title="Activité autour des bugs">
	      <desc0>Réactivité faible sur le forum ou sur la liste de diffusion, ou rien au sujet des corrections de bugs dans les notes de versions</desc0>
	      <desc1>Activité détectable mais sans processus clairement exposé, temps de résolution long</desc1>
	      <desc2>Forte réactivité, basée sur des rôles et des assignations de tâches</desc2>
	      <comment/>
	      <score/>
	    </element>
	    <element name="activityonfunctionalities" title="Activité autour des fonctionnalités">
	      <desc0>Pas ou peu de nouvelles fonctionnalités</desc0>
	      <desc1>Évolution du produit conduite par une équipe dédiée ou par des utilisateurs, mais sans processus clairement exposé</desc1>
	      <desc2>Les requêtes pour les nouvelles fonctionnalités sont clairement outillées, feuille de route disponible</desc2>
	      <comment/>
	      <score/>
	    </element>
	    <element name="activityonreleases" title="Activité sur les releases/versions">
	      <desc0>Très faible activité que ce soit sur les versions de production ou de développement (alpha, beta)</desc0>
	      <desc1>Activité que ce soit sur les versions de production ou de développement (alpha, beta), avec des versions correctives mineures fréquentes</desc1>
	      <desc2>Activité importante avec des versions correctives fréquentes et des versions majeures planifiées liées aux prévisions de la feuille de route</desc2>
	      <comment/>
	      <score/>
	    </element>
	  </element>
	  <element name="strategy" title="Gouvernance">
	    <desc>Stratégie du projet</desc>
	    <element name="copyrightowners" title="Détenteur des droits">
	      <desc0>Les droits sont détenus par quelques individus ou entités commerciales</desc0>
	      <desc1>Les droits sont détenus par de nombreux individus de façon homogène</desc1>
	      <desc2>Les droits sont détenus par une entité légale, une fondation dans laquelle la communauté a confiance (ex: FSF, Apache, ObjectWeb)</desc2>
	      <comment/>
	      <score/>
	    </element>
	    <element name="roadmap" title="Feuille de route">
	      <desc0>Pas de feuille de route publiée</desc0>
	      <desc1>Feuille de route sans planning</desc1>
	      <desc2>Feuille de route versionnée, avec planning et mesures de retard</desc2>
	      <comment/>
	      <score/>
	    </element>
	    <element name="ID_740641571" title="Pilotage du projet">
	      <desc0>Pas de pilotage clair du projet</desc0>
	      <desc1>Pilotage dicté par un seul individu ou une entité commerciale</desc1>
	      <desc2>Indépendance forte de l'équipe de développement, droits détenus par une entité reconnue</desc2>
	      <comment/>
	      <score/>
	    </element>
	    <element name="ID_548761152" title="Mode de distribution">
	      <desc0>Existence d'une distribution commerciale ou propriétaire ou distribution libre limitée fonctionnellement</desc0>
	      <desc1>Sous-partie du logiciel disponible sous licence propriétaire (Coeur / Greffons...)</desc1>
	      <desc2>Distribution totalement ouverte et libre</desc2>
	      <comment/>
	      <score/>
	    </element>
	  </element>
	  <element name="industrializedsolution" title="Industrialisation">
	    <desc>Niveau d'industrialisation du projet</desc>
	    <element name="services" title="Services">
	      <desc>Offres de services (Support, Formation, Audit...)</desc>
	      <desc0>Pas d'offre de service identifiée</desc0>
	      <desc1>Offre existante mais restreinte géographiquement ou en une seule langue ou fournie par un seul fournisseur ou sans garantie</desc1>
	      <desc2>Offre riche, plusieurs fournisseurs, avec des garanties de résultats</desc2>
	      <comment/>
	      <score/>
	    </element>
	    <element name="documentation" title="Documentation">
	      <desc0>Pas de documentation utilisateur</desc0>
	      <desc1>La documentation existe mais est en partie obsolète ou restreinte à une seule langue ou peu détaillée</desc1>
	      <desc2>Documentation à jour, traduite et éventuellement adaptée à différentes cibles de lecteurs (enduser, sysadmin, manager...)</desc2>
	      <comment/>
	      <score/>
	    </element>
	    <element name="qualityassurance" title="Méthode qualité">
	      <desc>Processus et méthode qualité</desc>
	      <desc0>Pas de processus qualité identifié</desc0>
	      <desc1>Processus qualité existant, mais non formalisé ou non outillé</desc1>
	      <desc2>Processus qualité basé sur l'utilisation d'outils et de méthodologies standards</desc2>
	      <comment/>
	      <score/>
	    </element>
	    <element name="modificationofsourcecode" title="Modification du code">
	      <desc0>Pas de moyen pratique de proposer des modifications de code</desc0>
	      <desc1>Des outils sont fournis pour accéder et modifier le code (ex : CVS, SVN) mais ne sont pas vraiment utilisés pour développer le produit</desc1>
	      <desc2>Le processus de modification de code est bien défini, exposé et respecté, basé sur des rôles bien définis</desc2>
	      <comment/>
	      <score/>
	    </element>
	  </element>
	</section>
      </xsl:when>
      <xsl:when test="./@ID='metadata'"></xsl:when>
      <xsl:when test="@ID = 'authors'">
	<authors>
	  <xsl:apply-templates select="node"/>
	</authors>
      </xsl:when>
      <xsl:when test="@TEXT = 'author' and ancestor::node/@ID = 'authors'">
	<author>
	  <xsl:apply-templates select="node"/>
	</author>
      </xsl:when>
      <xsl:when test="@TEXT = 'name' and ancestor::node/ancestor::node/@ID = 'authors'">
	  <name><xsl:value-of select="child::node/@TEXT"/></name>
      </xsl:when>
      <xsl:when test="@TEXT = 'email' and ancestor::node/ancestor::node/@ID = 'authors'">
	  <email><xsl:value-of select="child::node/@TEXT"/></email>
      </xsl:when>
      <xsl:when test="./@BACKGROUND_COLOR='#ffcccc'"></xsl:when>
      <xsl:when test="./@STYLE='bubble'">
	<desc><xsl:value-of select="@TEXT"/></desc>
      </xsl:when>
      <xsl:when test="child::icon">
      	<xsl:if test="icon/@BUILTIN = 'full-0'"><desc0><xsl:value-of select="@TEXT"/></desc0></xsl:if>
      	<xsl:if test="icon/@BUILTIN = 'full-1'"><desc1><xsl:value-of select="@TEXT"/></desc1></xsl:if>
      	<xsl:if test="icon/@BUILTIN = 'full-2'"><desc2><xsl:value-of select="@TEXT"/></desc2></xsl:if>
      </xsl:when>
      <xsl:when test="count(ancestor::node()) = 3">
        <section name="{@ID}" title="{@TEXT}">
          <xsl:apply-templates select="attribute"/>
	  <xsl:apply-templates select="node"/>
         </section>
      </xsl:when>
      <xsl:otherwise>
        <element name="{@ID}" title="{@TEXT}">
	  <xsl:apply-templates select="attribute"/>
	  <xsl:apply-templates select="node"/>
	  <xsl:if test="child::node/icon">
	  	<comment></comment>
	  	<score></score>
	  </xsl:if>
        </element>
      </xsl:otherwise>
    </xsl:choose>
  </xsl:template>
  
  <xsl:template match="attribute">
    <xsl:element name="{@NAME}">
      <xsl:value-of select="@VALUE"/>
    </xsl:element>
  </xsl:template>
  
</xsl:stylesheet>