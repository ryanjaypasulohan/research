<?php @session_start(); ?>
<?php get_includes('head'); 
 get_includes('nav');
?>
<div class="scroll-container">
    <!-- Optional character sprite -->
    <div class="character-wrapper">
        <div id="sprite" class="sprite"></div>
    </div>

    <div class="horizontal-wrapper">

        <!-- Section 1: Top Includes -->
        <div class="section">
            <?php 
                // get_includes('head');
                get_includes('header');
               
                get_includes('banner');
            ?>
        </div>

        <!-- Section 2: Middle Section for Front Page -->
        <div class="section">
            <?php 
                // if ( is_front_page() ) {
                    get_includes('middle'); 
                // } 
            ?>
        </div>

        <!-- Section 3: Main Content -->
        <div class="section">
            <!-- Main -->
            <div id="main_area">
                <div class="wrapper animatedParent animateOnce">
                    <div class="main_holder">
                        <main>
                            <?php 
                                if ( !is_front_page() ) {
                                    if ( function_exists('yoast_breadcrumb') ) {
                                        yoast_breadcrumb('<p id="breadcrumbs">', '</p>');
                                    }
                                }

                                get_template_part('loop', 'page'); 
                            ?>
                        </main>
                    </div>

                    <?php 
                        if ( is_front_page() ) {
                            get_includes('sidebar'); 
                        } 
                    ?>

                    <div class="clearfix"></div>
                </div>
            </div>
            <!-- End Main -->
        </div>

        <!-- Section 4: Bottom and Footer -->
        <!-- <div class="section"> -->
            <?php 
                if ( is_front_page() ) {
                    get_includes('bottom'); 
                }

                get_includes('footer'); 
            ?>
        <!-- </div> -->
    </div>
</div>
