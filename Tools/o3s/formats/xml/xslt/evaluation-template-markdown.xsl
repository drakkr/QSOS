<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

<xsl:template match="section">
  <xsl:apply-templates select="element"/>
</xsl:template>

<xsl:template match="element">
  <xsl:choose>
  <xsl:when test="parent::section">
* <xsl:value-of select="@title"/> : <xsl:value-of select="desc"/>
    <xsl:if test="desc0 != ''">

        .0 : <xsl:value-of select="desc0"/>

        .1 : <xsl:value-of select="desc1"/>

        .2 : <xsl:value-of select="desc2"/>

    </xsl:if>
    <xsl:apply-templates select="element"/>
  </xsl:when>
  <xsl:when test="parent::element">
    + <xsl:value-of select="@title"/> : <xsl:value-of select="desc"/>
    <xsl:if test="desc0 != ''">

        .0 : <xsl:value-of select="desc0"/>

        .1 : <xsl:value-of select="desc1"/>

        .2 : <xsl:value-of select="desc2"/>

    </xsl:if>
 
    <xsl:apply-templates select="element"/>
  </xsl:when>
</xsl:choose>
</xsl:template>

</xsl:stylesheet>