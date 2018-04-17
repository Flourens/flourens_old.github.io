<?php include_once("arbo.php"); ?>
<!doctype html>
<html lang="fr-CH">
    <head>
        <meta charset="utf-8">
        <title><?php if (isset($page['title'])) { echo $page['title']; } ?></title>
        <meta name="description" content="<?php if (isset($page['description'])) { echo  $page['description']; } ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php
        if (isset($page['noindex']) && ($page['noindex'] == "noindex")) {
        ?><meta name="robots" content="noindex">
        <?php } ?>
        <?php
        if (isset($page['id']['canonical'])) {
        ?><link rel="canonical" href="https://caseo.ch/<?php echo $pages[$page['id']]['canonical']; ?>" />
        <?php } ?><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.0/normalize.min.css">
        <link rel="stylesheet" href="css/style.css">
    </head>

    <body>
       
        <header id="topbar">
            <div class="row">
                <a href="./" class="logo">LOGO</a>
                <a href="" class="cta_btn">Demande d'offre</a>
            </div>
        </header>
        
