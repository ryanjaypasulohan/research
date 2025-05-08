<!-- Header -->
  <header>
    <div class="wrapper animatedParent animateOnce">
      <div class="header_main">
      <div class="main_logo">
        <a href="<?php echo get_home_url(); ?>"><figure><img src="<?php bloginfo('template_url');?>/images/main-logo.png" alt="<?php echo get_bloginfo('name');?>"/></figure></a>
      </div>

      <div class="header_info">
      
      <div class="hdr_num">
        <?php dynamic_sidebar('hdr_num');?>
      </div>

     <div class="social_media">
      <h2>Like, Share, <span>or Comment</span></h2>
        <ul>
          <li><a href="https://www.facebook.com" target="_blank"><figure><img src="<?php bloginfo('template_url');?>/images/facebook.png" alt="facebook"/></figure></a></li>
          <li><a href="https://www.twitter.com" target="_blank"><figure><img src="<?php bloginfo('template_url');?>/images/twitter.png" alt="twitter"/></figure></a></li>
        </ul>
     </div>

     <a href="/healthcare-training-and-staffing-apply-now" class="hdr_a">Apply Now!</a>
   
      </div>

      </div>
      	<div class="clearfix"></div>
    </div>
  </header>
<!-- End Header -->
