<!DOCTYPE html>
<html lang="en-us">
<head>
	<title>UCF Restarting Research Amid COVID-19</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<link rel='shortcut icon' href='<?= get_template_directory_uri(); ?>/favicon.ico'/>
	<link rel='apple-touch-icon' href='<?= get_template_directory_uri(); ?>/apple-touch-icon.png'>
	<link rel='apple-touch-icon-precomposed apple-touch-icon' href='<?= get_template_directory_uri(); ?>/apple-touch-icon-precomposed.png'>
	<?php
        require "functions/logic.php";
        require "functions/view.php";
		wp_head();

	?>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,600,800|Arvo:400,700|Crimson+Text:600,600italic|Oswald" rel="stylesheet" type="text/css">

    <script type="text/javascript" id="ucfhb-script" src="//universityheader.ucf.edu/bar/js/university-header.js?use-1200-breakpoint=1"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha384-xBuQ/xzmlsLoJpyjoggmTEz8OWUFM0/RC5BsqQBDX2v5cMvDHcMakNTNrHIW2I5f" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.1.1/js/tether.min.js"></script>
	<script src="https://cdn.ucf.edu/athena-framework/v1.0.0/js/framework.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.15.2/xlsx.full.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
</head>
<body>
	<nav class="navbar sticky-top navbar-toggleable-md navbar-light bg-primary">
        <div class="container">
            <?php if( function_exists( 'ubermenu' ) ): ?>
                <?php ubermenu( 'main' , array( 'theme_location' => 'primary' ) ); ?>
            <?php else: ?>
                <a class="navbar-brand" href="/">College of Graduate Studies</a>
                <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#main-nav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <?php wp_nav_menu(array(
                    'menu' => 'Main Menu',
                    'container_id' => 'main-nav',
                    'container_class' => 'collapse navbar-collapse',
                    'menu_class' => 'navbar-nav mr-auto'
                )); ?>
            <?php endif; ?>
        </div>
	</nav>
    <?php if( ! empty( trim( get_option('SiteWideAlert') ) ) ): ?>
    <section class="section-notice alert alert-info bg-info-t-3 mb-0" role="alert">
        <div class="container">
            <?= nl2br(get_option('SiteWideAlert')) ?>
        </div>
    </section>
    <?php endif; ?>
    <div class="page-content-wrapper">
