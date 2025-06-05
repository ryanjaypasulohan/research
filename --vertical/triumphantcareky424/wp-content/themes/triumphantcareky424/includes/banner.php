<!-- Banner -->
<div id="banner">
<div class="wrapper animatedParent animateOnce">
	<?php if (is_front_page() ) { ?>
	<div class="bnr_main">
	<div class="slider">
<div class="box_skitter box_skitter_large">
		<ul>
			<li><figure><img src="<?php bloginfo('template_url');?>/images/slider/1.jpg" class="random"  alt="nurses using laptop and group of people doing health care training"/></figure></li>
			<li><figure><img src="<?php bloginfo('template_url');?>/images/slider/2.jpg" class="random"  alt="group of health staff and man and woman doing health care training"/></figure></li>
			<li><figure><img src="<?php bloginfo('template_url');?>/images/slider/3.jpg" class="random"  alt="nurse and woman doing cpr"/></figure></li>
			<li><figure><img src="<?php bloginfo('template_url');?>/images/slider/4.jpg" class="random"  alt="group of nurses and woman teaching people cpr"/></figure></li>
		</ul>
	</div>
	<ul class="rslides">
		<li><figure><img src="<?php bloginfo('template_url');?>/images/rslider/1.jpg" alt="nurses using laptop"/></figure></li>
		<li><figure><img src="<?php bloginfo('template_url');?>/images/rslider/2.jpg" alt="group of people doing health care training"/></figure></li>
		<li><figure><img src="<?php bloginfo('template_url');?>/images/rslider/3.jpg" alt="group of health staff"/></figure></li>
		<li><figure><img src="<?php bloginfo('template_url');?>/images/rslider/4.jpg" alt="man and woman doing health care training"/></figure></li>
		<li><figure><img src="<?php bloginfo('template_url');?>/images/rslider/5.jpg" alt="nurse"/></figure></li>
		<li><figure><img src="<?php bloginfo('template_url');?>/images/rslider/6.jpg" alt="woman doing cpr"/></figure></li>
		<li><figure><img src="<?php bloginfo('template_url');?>/images/rslider/7.jpg" alt="group of nurses"/></figure></li>
		<li><figure><img src="<?php bloginfo('template_url');?>/images/rslider/8.jpg" alt="woman teaching people cpr"/></figure></li>
	</ul>
  </div>

  <div class="bnr_info1 animated fadeInLeft">
	<?php dynamic_sidebar('bnr_info1');?>

  </div>
  <div class="bnr_info2 animated fadeInRight">
	<?php dynamic_sidebar('bnr_info2');?>
  </div>
	</div>
	 <?php } else {?>
		<div class="non_ban">
			<figure>
			<?php if(is_home() && is_author() && is_category() && is_tag() && is_single()) { ?>
			<?php if (has_post_thumbnail() ) {?>
			<?php the_post_thumbnail('full');?>
			<?php }else{ ?>
			<img src="<?php bloginfo('template_url');?>/images/slider/nonhome.jpg" alt="group of nurses" />
			<?php } ?>
			<?php } elseif (has_post_thumbnail() ) { ?>
			<?php the_post_thumbnail('full');?>
			<?php } else { ?>
			<img src="<?php bloginfo('template_url'); ?>/images/slider/nonhome.jpg" alt="group of nurses">
			<?php } ?>
			</figure>
			<div class="page_title">
				<?php if(!is_home() && !is_author() && !is_category() && !is_tag() && !is_single()) { ?>
					<h1 class="h1_title"><?php the_title(); ?></h1>
					<?php echo do_shortcode("[short_title id='" . get_the_ID() . "']"); ?>
				<?php } else { ?>
					<h1 class="headings_title">Blog</h1>
				<?php } ?>
			</div>
		</div>

		<?php }?>
	<div class="clearfix"></div>
</div>
</div>
<!-- End Banner -->
