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
                    <img src="<?= get_template_directory_uri(); ?>/images/logos/UILexternal_WG7406_Office of Research.svg" style="width: 100%;">
                    <?php if( ! empty( get_theme_mod('CollegeGraduateOfficeLocation') ) ): ?>
                        <p>
                            <b class="text-primary">Office Location:</b><br>
                            <?php echo nl2br(get_theme_mod('CollegeGraduateOfficeLocation')); ?>
                        </p>
                    <?php endif; ?>
                    <p class="mt-4 mb-0">
                        <?php echo nl2br(get_theme_mod('CollegeGraduateMailingAddress')); ?>
                    </p>
                </div>
                <div class="pt-3 pb-3 col-12 col-sm-6 offset-md-1 col-md-3">
                    <h2 class="h4 mt-0">Resources</h2>
                    <?php if( function_exists( 'ubermenu' ) ): ?>
                        <?php ubermenu( 'verticalplain' , array( 'theme_location' => 'research-footer-resources' ) ); ?>
                    <?php else: ?>
                        <?php wp_nav_menu(array(
                            'menu' => 'research-footer-resources',
                            'container_id' => 'footer-resources',
                            'container_class' => '',
                            'menu_class' => ''
                        )); ?>
                    <?php endif; ?>
                </div>
                <div class="pt-3 pb-3 col-12 col-sm-6 offset-md-1 col-md-3">
                    <h2 class="h4 mt-0">Contact</h2>
                    <p>For questions regarding export control compliance, please contact: <a href="mailto:Ashley.Guritza@ucf.edu" target="_blank" rel="noreferrer noopener">Ashley.Guritza@ucf.edu</a></p>
                </div>
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