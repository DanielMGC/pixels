<?php

function redirectTohttps() {

    if(!(strpos($_SERVER['HTTP_HOST'], "localhost") !== false || strpos($_SERVER['HTTP_HOST'], "entelodonte") !== false || ((isset($_SERVER['HTTPS'])) && (strtolower($_SERVER['HTTPS']) == 'on')) || ((isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) && (strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == 'https')))) {
    
        $redirect= "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        header("Location:$redirect"); 
    }
    
}

?>

    <meta charset="utf-8"/>
    <meta property="og:title" content="Pixel Creatures by DanielMGC">
    <meta property="og:description" content="Create pixel creatures and combine them for wacky results!">
    <meta property="og:image" content="http://thebob.com.br/pixels/android-icon-144x144.png">
    <meta property="og:url" content="https://thebob.com.br/pixels">
    <meta name="twitter:card" content="summary_large_image">
</head>
<body>
    <?php redirectTohttps(); ?>
    <div class="bg">
        <div class="center">
            <header class="header-title">
                <h1>Pixel creatures</h1>
                <p>by DanielMGC_</p>
            </header>

            <?php if (isset($_SESSION["username"])) { ?>
                <div class="div-username">
                    > Welcome <?php echo $_SESSION["username"]; ?>
                    <input type="hidden" id="hidUserId" value="<?php echo $_SESSION["userid"]; ?>" />
                </div>
            <?php } ?>
