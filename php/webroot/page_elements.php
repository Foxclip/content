<?php

function echoHead(string $title, array $styles): void {
    $css_links = "";
    foreach ($styles as $style) {
        $css_links .= "    <link rel=\"stylesheet\" href=\"{$style}\" />\n";
    }
    echo <<<EOD
<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{$title}</title>
    {$css_links}
</head>
EOD;
}

function echoFooter(): void
{
    echo <<<EOD
<footer id="footer">
    <div id="footerLeftSection">
        <span>&copy; 2025</span>
    </div>
    <div id="footerCenterSection"></div>
    <div id="footerRightSection"></div>
</footer>
EOD;
}
