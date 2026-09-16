<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:s="http://www.sitemaps.org/schemas/sitemap/0.9">
    <xsl:output method="html" encoding="UTF-8" indent="yes"/>

    <xsl:template match="/">
        <html>
            <head>
                <meta charset="UTF-8"/>
                <title>Sitemap</title>
                <style>
                    body { font-family: system-ui, sans-serif; margin: 24px; color: #1e293b; }
                    h1 { font-size: 1.25rem; margin-bottom: 16px; }
                    table { border-collapse: collapse; width: 100%; }
                    th, td { border: 1px solid #e2e8f0; padding: 8px 12px; text-align: left; font-size: 14px; }
                    th { background: #f8fafc; }
                    a { color: #2563eb; word-break: break-all; }
                </style>
            </head>
            <body>
                <xsl:apply-templates/>
            </body>
        </html>
    </xsl:template>

    <xsl:template match="s:sitemapindex">
        <h1>Sitemap Index</h1>
        <table>
            <tr><th>Location</th><th>Last Modified</th></tr>
            <xsl:for-each select="s:sitemap">
                <tr>
                    <td><a href="{s:loc}"><xsl:value-of select="s:loc"/></a></td>
                    <td><xsl:value-of select="s:lastmod"/></td>
                </tr>
            </xsl:for-each>
        </table>
    </xsl:template>

    <xsl:template match="s:urlset">
        <h1>URL Set</h1>
        <table>
            <tr><th>URL</th><th>Last Modified</th><th>Change Freq</th><th>Priority</th></tr>
            <xsl:for-each select="s:url">
                <tr>
                    <td><a href="{s:loc}"><xsl:value-of select="s:loc"/></a></td>
                    <td><xsl:value-of select="s:lastmod"/></td>
                    <td><xsl:value-of select="s:changefreq"/></td>
                    <td><xsl:value-of select="s:priority"/></td>
                </tr>
            </xsl:for-each>
        </table>
    </xsl:template>
</xsl:stylesheet>
