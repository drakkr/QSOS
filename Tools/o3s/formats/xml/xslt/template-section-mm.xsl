<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="xml" indent="yes" encoding="UTF-8"/>

  <xsl:template match="section">
      <xsl:element name="map">
        <xsl:attribute name="version">0.9</xsl:attribute>
	  <node ID="{@name}" TEXT="{@title}">
	    <font NAME="SansSerif" BOLD="true" SIZE="12"/>
	    <xsl:apply-templates select="element"/>
	  </node>
      </xsl:element>
  </xsl:template>

  <xsl:template match="element">
    <node ID="{@name}" TEXT="{@title}">
    <xsl:if test="score != ''">
	    <xsl:attribute name="FOLDED">true</xsl:attribute>
	    <xsl:element name="node">
	      <xsl:attribute name="TEXT"><xsl:value-of select="desc"/></xsl:attribute>
	      <xsl:attribute name="STYLE">bubble</xsl:attribute>
	      <font NAME="SansSerif" ITALIC="true" SIZE="10"/>
	    </xsl:element>
    </xsl:if>

    <xsl:apply-templates select="element"/>
    </node>
  </xsl:template>
</xsl:stylesheet>
