<?xml version="1.0"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
        <xsl:output method="xml" indent="yes" encoding="UTF-8"/>
        <xsl:template match="document">
                <xsl:element name="map">
                        <xsl:attribute name="version">0.9.0</xsl:attribute>
                        <xsl:element name="node">
                                <xsl:attribute name="ID"><xsl:value-of select="header/qsosappfamily"/></xsl:attribute>
                                <xsl:attribute name="TEXT"><xsl:value-of select="header/qsosappfamily"/></xsl:attribute>
                                <font NAME="SansSerif" BOLD="true" SIZE="12"/>
				<node ID="metadata" TEXT="Metadata">
					<node ID="version" TEXT="Version">
						<node ID="version_entry">
							<xsl:attribute name="TEXT">
								<xsl:choose>
									<xsl:when test="header/qsosspecificformat=''">1.0</xsl:when>
									<xsl:otherwise>
										<xsl:value-of select="header/qsosspecificformat"/>
									</xsl:otherwise>
								</xsl:choose>
							</xsl:attribute>
							<xsl:call-template name="blue-bubble"/>
						</node>
					</node>
					<node ID="language" TEXT="Language">
						<node ID="language_entry">
							<xsl:attribute name="TEXT"><xsl:value-of select="header/language"/></xsl:attribute>
						</node>
					</node>
					<node ID="authors" TEXT="authors">
						<node BACKGROUND_COLOR="#ffcccc" ID="authors_tooltip" STYLE="bubble" TEXT="Ajouter ici autant de noeuds &quot;author&quot; que n&#xe9;cessaire">
							<font ITALIC="true" NAME="SansSerif" SIZE="12"/>
							<icon BUILTIN="messagebox_warning"/>
						</node>
						<node ID="{generate-id()}" TEXT="author">
							<node ID="{generate-id()}" TEXT="name">
								<node ID="{generate-id()}" TEXT="Author NAME">
									<xsl:call-template name="blue-bubble"/>
								</node>
							</node>
							<node ID="{generate-id()}" TEXT="email">
								<node ID="{generate-id()}" TEXT="au.thor@domain.com">
									<xsl:call-template name="blue-bubble"/>
								</node>
							</node>
						</node>
					</node>
					<node ID="dates" TEXT="dates">
						<node ID="creation" TEXT="creation">
							<node ID="creation_entry" TEXT="YYYY-MM-DD">
								<xsl:call-template name="blue-bubble"/>
							</node>
						</node>
						<node ID="update" TEXT="update">
							<node ID="update_entry" TEXT="YYYY-MM-DD">
								<xsl:call-template name="blue-bubble"/>
							</node>
						</node>
					</node>
				</node>
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
                <node ID="{@name}" TEXT="{@title}" FOLDED="true">
                        <xsl:if test="desc != ''">
                                <xsl:element name="node">
                                        <xsl:attribute name="TEXT"><xsl:value-of select="desc"/></xsl:attribute>
                                        <xsl:attribute name="STYLE">bubble</xsl:attribute>
                                        <font NAME="SansSerif" ITALIC="true" SIZE="10"/>
                                </xsl:element>
                        </xsl:if>
                        <xsl:if test="not(child::element)">
                                <xsl:if test="desc0">
                                        <xsl:element name="node">
                                                <xsl:attribute name="TEXT"><xsl:value-of select="desc0"/></xsl:attribute>
                                                <icon BUILTIN="full-0"/>
                                        </xsl:element>
                                </xsl:if>
                                <xsl:if test="desc1">
                                        <xsl:element name="node">
                                                <xsl:attribute name="TEXT"><xsl:value-of select="desc1"/></xsl:attribute>
                                                <icon BUILTIN="full-1"/>
                                        </xsl:element>
                                </xsl:if>
                                <xsl:if test="desc2">
                                        <xsl:element name="node">
                                                <xsl:attribute name="TEXT"><xsl:value-of select="desc2"/></xsl:attribute>
                                                <icon BUILTIN="full-2"/>
                                        </xsl:element>
                                </xsl:if>
                        </xsl:if>
                        <xsl:apply-templates select="element"/>
                </node>
        </xsl:template>
	<xsl:template name="blue-bubble">
		<xsl:attribute name="STYLE">bubble</xsl:attribute>
		<xsl:attribute name="COLOR">#0033ff</xsl:attribute>
		<font ITALIC="true" NAME="SansSerif" SIZE="12"/>
	</xsl:template>
</xsl:stylesheet>
