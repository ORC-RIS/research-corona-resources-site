<?php
?>
</div>
<div class="back-to-top-holder">
    <div class='back-to-top-button icon'><i class="fas fa-chevron-up"></i></div>
</div>
<footer>
    <section class="footer-content">
        <div class="container">
            <div class="row">
                <div class="pt-3 pb-3 col-12 col-sm-6 col-md-3">
                    <img src="<?= get_template_directory_uri(); ?>/images/logos/UILexternal_WG7406_Office of Research.svg" style="max-width:200px; width: 100%;">
                    <!---
                    <img class="pb-2 mb-3" alt="UCF College of Graduate Studies" style="max-width: 255px" src="<?php echo get_template_directory_uri(); ?>/images/logos/UILinternal_WGrgb_College of Graduate Studies.svg">
                    <ul class="pb-3 footer-social-icons">
                        <?php render_social_menu('footer-social') ?>
                    </ul>
                    ---->
                    <?php if( ! empty( get_theme_mod('CollegeGraduateOfficeLocation') ) ): ?>
                        <p>
                            <b class="text-primary">Office Location:</b><br>
                            <?php echo nl2br(get_theme_mod('CollegeGraduateOfficeLocation')); ?>
                        </p>
                    <?php endif; ?>
                    <p class="mt-4 mb-0">
                        <!---<b class="text-primary">Mailing Address:</b><br>--->
                        <?php echo nl2br(get_theme_mod('CollegeGraduateMailingAddress')); ?>
                    </p>
                </div>
                <!----
                <div class="pt-3 pb-3 col-12 col-sm-6 col-md-3">
                    <h2 class="h4 mt-0">Modules</h2>
                    <?php if( function_exists( 'ubermenu' ) ): ?>
                        <?php ubermenu( 'verticalplain' , array( 'theme_location' => 'footer-modules' ) ); ?>
                    <?php else: ?>
                        <?php wp_nav_menu(array(
                            'menu' => 'footer-modules',
                            'container_id' => 'footer-modules',
                            'container_class' => '',
                            'menu_class' => ''
                        )); ?>
                    <?php endif; ?>
                </div>
                ---->
                <div class="pt-3 pb-3 col-12 col-sm-6 offset-md-1 col-md-3">
                    <h2 class="h4 mt-0">Resources</h2>
                    <?php if( function_exists( 'ubermenu' ) ): ?>
                        <?php ubermenu( 'verticalplain' , array( 'theme_location' => 'footer-resources' ) ); ?>
                    <?php else: ?>
                        <?php wp_nav_menu(array(
                            'menu' => 'footer-resources',
                            'container_id' => 'footer-resources',
                            'container_class' => '',
                            'menu_class' => ''
                        )); ?>
                    <?php endif; ?>
                </div><!---
                <div class="pt-3 pb-3 col-12 col-sm-6 col-md-3">
                    <h2 class="h4 mt-0">Office Hours</h2>
                    <p>
							<?php echo nl2br(get_theme_mod('CollegeGraduateHoursShort')); ?>
						</p>
                    <p>
                        General inquiries will be responded to on a first-come-first service basis by
                        emailing: <a href="mailto:graduate@ucf.edu" style="text-decoration: underline">graduate@ucf.edu</a>.
                        We ask for your patience and flexibility as we navigate a change of operations as a result of the
                        Coronavirus situation. Response time may be delayed.
                    </p>
						<p>
							Phone: <?php echo get_theme_mod('CollegeGraduatePhone'); ?><br>
							Fax: <?php echo get_theme_mod('CollegeGraduateFax'); ?>
						</p>
                </div>
            ---->

            </div>
        </div>
    </section>
    <section class="footer-copyright">
        <?= date("Y") ?> &copy; <a href="/about">University of Central Florida - Office of Research</a>
        | <a href="mailto:<?= get_theme_mod('CollegeGraduateWebmasterEmail') ?>"><?= get_theme_mod('CollegeGraduateWebmasterEmail') ?></a>
    </section>
</footer>
<?php wp_footer(); ?>
<?php if( defined("GA_TRACKING_ID") ) { ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= GA_TRACKING_ID ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', '<?= GA_TRACKING_ID ?>');
    </script>
<?php } ?>
</body>
</html>