<?php @session_start();
get_includes('head');
get_includes('header');
get_includes('nav');
get_includes('banner');
?>
<!-- Main -->
<div id="main_area">
    	<div class="wrapper">
	<div class="main_holder">
    		<main>
				<?php get_template_part('loop','single');?>
		</main>
</div>
<div class="clearfix"></div>
	</div>
</div>
<!-- End Main -->
<?php get_includes('footer');?>
