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

             Designed by <a href="https://www.proweaver.com/" target="_blank" rel="nofollow">Proweaver</a>

          </div>



        </div>

        	<div class="clearfix"></div>

      </div>

    </div>

  </footer>

  </div>



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

<script src="<?php bloginfo('template_url');?>/js/plugins.js"></script>

<script src='https://www.google.com/recaptcha/api.js'></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
  
<script>
  gsap.registerPlugin(ScrollTrigger);

  const sections = document.querySelectorAll(".section");
  const wrapper = document.querySelector(".horizontal-wrapper");
  const character = document.querySelector(".character-wrapper");
  const sprite = document.getElementById("sprite");

  // Sprite configuration
  const frameWidth = 170.74;
  const frameHeight = 231.7;
  const columns = 5;
  const rows = 2;
  const totalFrames = columns * rows;
  let currentFrame = 0;

  // Track scroll direction
  let lastScroll = window.scrollY;

  // Total scrollable width
  const totalScrollWidth = wrapper.scrollWidth - window.innerWidth;

  // Animate horizontal scrolling
  gsap.to(wrapper, {
    x: () => -totalScrollWidth,
    ease: "none",
    scrollTrigger: {
      trigger: ".scroll-container",
      pin: true,
      scrub: 1,
      end: () => "+=" + totalScrollWidth,
      onUpdate: (self) => {
        const progress = self.progress; // 0 to 1
        const maxX = window.innerWidth - frameWidth - 50; // stay within screen
        const x = progress * maxX;
        character.style.left = `${x}px`;

        // Direction flip
        const currentScroll = window.scrollY;
        if (currentScroll > lastScroll) {
          sprite.style.transform = "scaleX(1)"; // facing right
        } else if (currentScroll < lastScroll) {
          sprite.style.transform = "scaleX(-1)"; // facing left
        }
        lastScroll = currentScroll;
      }
    }
  });

  // Sprite frame animation on scroll
  let isScrolling = false;
  let scrollInterval;

  function updateFrame() {
    currentFrame = (currentFrame + 1) % totalFrames;
    const col = currentFrame % columns;
    const row = Math.floor(currentFrame / columns);
    const x = -col * frameWidth;
    const y = -row * frameHeight;
    sprite.style.backgroundPosition = `${x}px ${y}px`;
  }

  window.addEventListener("scroll", () => {
    isScrolling = true;
    if (!scrollInterval) {
      scrollInterval = setInterval(() => {
        if (isScrolling) {
          updateFrame();
          isScrolling = false;
        } else {
          clearInterval(scrollInterval);
          scrollInterval = null;
        }
      }, 80); // adjust to make running faster/slower
    }
  });
</script>








<?php wp_footer(); ?>

</body>

</html>

<!-- End Footer -->

