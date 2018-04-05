<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="xml" indent="yes" encoding="UTF-8"/>

  <xsl:template match="document">
    <xsl:element name="document">
      <xsl:apply-templates select="header"/>
      <xsl:apply-templates select="section"/>
    </xsl:element>
  </xsl:template>

  <xsl:template match="header">
    <xsl:element name="header">
      <xsl:apply-templates select="authors"/>
      <xsl:apply-templates select="dates"/>
      <xsl:apply-templates select="appname"/>
      <xsl:apply-templates select="desc"/>
      <xsl:apply-templates select="release"/>
      <xsl:apply-templates select="licenseid"/>
      <xsl:apply-templates select="licensedesc"/>
      <xsl:apply-templates select="url"/>
      <xsl:apply-templates select="demourl"/>
      <xsl:apply-templates select="language"/>
      <qsosappname><xsl:value-of select="/document/header/appname"/></qsosappname>
      <qsosformat>2.0</qsosformat>
      <xsl:apply-templates select="qsosspecificformat"/>
      <xsl:apply-templates select="qsosappfamily"/>
    </xsl:element>
  </xsl:template>

  <xsl:template match="authors">
    <authors>
      <xsl:apply-templates select="author"/>
    </authors>
  </xsl:template>

  <xsl:template match="author">
    <author>
      <xsl:apply-templates select="name"/>
      <xsl:apply-templates select="email"/>
    </author>
  </xsl:template>

  <xsl:template match="dates">
    <dates>
      <xsl:apply-templates select="creation"/>
      <xsl:apply-templates select="validation"/>
    </dates>
  </xsl:template>

  <xsl:template match="@*|node()">
    <xsl:copy><xsl:apply-templates select="@*|node()"/></xsl:copy>
  </xsl:template>

</xsl:stylesheet>
