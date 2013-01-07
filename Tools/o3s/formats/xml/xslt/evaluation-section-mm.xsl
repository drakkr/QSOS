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
    <xsl:element name="node">
      <xsl:attribute name="ID"><xsl:value-of select="@name"/></xsl:attribute>
      <xsl:attribute name="TEXT"><xsl:value-of select="@title"/></xsl:attribute>

      <xsl:if test="score = '0'">
	<xsl:attribute name="FOLDED">true</xsl:attribute>
	<icon BUILTIN="button_cancel"/>
      </xsl:if>
      <xsl:if test="score = '1'">
	<xsl:attribute name="FOLDED">true</xsl:attribute>
	<icon BUILTIN="yes"/>
      </xsl:if>
      <xsl:if test="score = '2'">
	<xsl:attribute name="FOLDED">true</xsl:attribute>
	<icon BUILTIN="button_ok"/>
      </xsl:if>

      <xsl:choose>
	<xsl:when test="child::element">
	</xsl:when>

	<xsl:otherwise>
	  <xsl:element name="node">
	    <xsl:if test="score = '0'">
	      <xsl:attribute name="TEXT"><xsl:value-of select="desc0"/></xsl:attribute>
	    </xsl:if>
	    <xsl:if test="score = '1'">
	      <xsl:attribute name="TEXT"><xsl:value-of select="desc1"/></xsl:attribute>
	    </xsl:if>
	    <xsl:if test="score = '2'">
	      <xsl:attribute name="TEXT"><xsl:value-of select="desc2"/></xsl:attribute>
	    </xsl:if>
	    <xsl:attribute name="STYLE">bubble</xsl:attribute>
	    <font NAME="SansSerif" ITALIC="true" SIZE="10"/>
	  </xsl:element>

	  <xsl:if test="comment != ''">     
	    <xsl:element name="node">
	      <xsl:attribute name="TEXT"><xsl:value-of select="comment"/></xsl:attribute>
	      <font NAME="SansSerif" ITALIC="true" SIZE="10"/>
	    </xsl:element>
	  </xsl:if>
	</xsl:otherwise>
      </xsl:choose>

      <xsl:apply-templates select="element"/>

    </xsl:element>
  </xsl:template>

</xsl:stylesheet>
