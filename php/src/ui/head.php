<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="<?php echo $_SESSION['csrf_token']; ?>" />
    <title><?php echo $title; ?></title>
    <?php
    foreach ($styles as $style) {
        echo "    <link rel=\"stylesheet\" href=\"css/{$style}\" />\n";
    }
    ?>
</head>
