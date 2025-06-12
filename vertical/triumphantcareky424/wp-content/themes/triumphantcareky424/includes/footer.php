<!-- Start Footer -->

  <footer>



      <div class="ftr_map">

      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3184785.78620613!2d-79.48027847408439!3d38.7833704767971!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89b64debe9f190df%3A0xf2af37657655f6b1!2sMaryland%2C%20USA!5e0!3m2!1sen!2sph!4v1666691641235!5m2!1sen!2sph" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

      </div>



    <div class="footer_top">

      <div class="wrapper animatedParent animateOnce">

        <div class="footer_top_main">

        <div class="contact_info">

          <?php dynamic_sidebar('contact_info');?>

          </div>

          

          <div class="footer_logo">

            <a href="<?php echo get_home_url(); ?>"><figure><img src="<?php bloginfo('template_url');?>/images/footer-logo.png" alt="<?php echo get_bloginfo('name');?>"/></figure></a>

          <?php dynamic_sidebar('footer_logo');?>

          </div>



          <div class="footer_nav">

            <?php wp_nav_menu( array( 'theme_location' => 'secondary') ); ?>

          </div>



          <div class="copyright">

            &copy; Copyright

            <?php

            $start_year = '2022';

            $current_year = date('Y');

            $copyright = ($current_year == $start_year) ? $start_year : $start_year.' - '.$current_year;

            echo $copyright;

            ?>

             <span>&bull;</span><q></q>

             Designed by Proweaver

          </div>



        </div>

        	<div class="clearfix"></div>

      </div>

    </div>

  </footer>



<span class="back_top"></span>



</div> <!-- End Clearfix -->

</div> <!-- End Protect Me -->



<?php get_includes('ie');?>

<script>(function(d){var s = d.createElement("script");s.setAttribute("data-account", "m1Y1yHo0MY");s.setAttribute("src", "https://cdn.userway.org/widget.js");(d.body || d.head).appendChild(s);})(document)</script><noscript>Please ensure Javascript is enabled for purposes of <a href="https://userway.org">website accessibility</a></noscript>

<!--

Solved HTML5 & CSS IE Issues

-->

<script src="<?php bloginfo('template_url');?>/js/modernizr-custom-v2.7.1.min.js"></script>

<script src="<?php bloginfo('template_url');?>/js/jquery-2.1.1.min.js"></script>





<script src="<?php bloginfo('template_url');?>/js/calcheight.min.js"></script>

<script src="<?php bloginfo('template_url');?>/js/jquery.easing.1.3.js"></script>

<script src="<?php bloginfo('template_url');?>/js/jquery.skitter.min.js"></script>

<script src="<?php bloginfo('template_url');?>/js/css3-animate-it.min.js"></script>

<script src="<?php bloginfo('template_url');?>/js/responsiveslides.min.js"></script>

<script src="<?php bloginfo('template_url');?>/js/plugins.min.js"></script>


<script src='https://www.google.com/recaptcha/api.js'></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/Draggable.min.js"></script>
<script src="<?php bloginfo('template_url');?>/js/sprite-animation.js"></script>

<?php wp_footer(); ?>

</body>

</html>

<!-- End Footer -->

