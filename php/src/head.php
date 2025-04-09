<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php echo $title; ?></title>
    <?php
    foreach ($styles as $style) {
        echo "    <link rel=\"stylesheet\" href=\"css/{$style}\" />\n";
    }
    ?>
</head>
