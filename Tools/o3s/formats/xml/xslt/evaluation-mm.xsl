<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="xml" indent="yes" encoding="UTF-8"/>

  <xsl:template match="document">
      <xsl:element name="map">
        <xsl:attribute name="version">0.7.1</xsl:attribute>
	      <xsl:element name="node">
	        <xsl:attribute name="ID"><xsl:value-of select="header/appname"/></xsl:attribute>
		<richcontent TYPE="NODE"><html>
		  <head></head>
		  <body><p style="text-align: center">
		      <xsl:value-of select="header/appname"/><br/>
		      <xsl:value-of select="header/release"/>
		    </p></body>
		</html></richcontent>
	        <font NAME="SansSerif" BOLD="true" SIZE="12"/>
	        <xsl:apply-templates select="section"/>
	      </xsl:element>
      </xsl:element>
  </xsl:template>

  <xsl:template match="section">
    <node ID="{@name}" TEXT="{@title}">
      <xsl:if test="position() mod 2 = 0">
	<xsl:attribute name="POSITION">left</xsl:attribute>
      </xsl:if>
      <xsl:if test="position() mod 2 = 1">
	<xsl:attribute name="POSITION">right</xsl:attribute>
      </xsl:if>
      <font NAME="SansSerif" BOLD="true" SIZE="12"/>
      <xsl:if test="desc != ''"> 
	<xsl:element name="node">
	  <xsl:attribute name="TEXT"><xsl:value-of select="desc"/></xsl:attribute>
	  <xsl:attribute name="STYLE">bubble</xsl:attribute>
	  <font NAME="SansSerif" ITALIC="true" SIZE="10"/>
	</xsl:element>
      </xsl:if>
      <xsl:apply-templates select="element"/>
    </node>
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
	  <xsl:if test="desc != ''"> 
	    <xsl:element name="node">
	      <xsl:attribute name="TEXT"><xsl:value-of select="desc"/></xsl:attribute>
	      <xsl:attribute name="STYLE">bubble</xsl:attribute>
	      <font NAME="SansSerif" ITALIC="true" SIZE="10"/>
	    </xsl:element>
	  </xsl:if>
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
