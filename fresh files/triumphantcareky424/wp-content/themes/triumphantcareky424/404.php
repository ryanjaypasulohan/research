<!DOCTYPE html>

<!--[if lt IE 8]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->

<!--[if IE 8]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->

<!--[if IE 9]>         <html class="no-js lt-ie9"> <![endif]-->

<!--[if gt IE 9]><!--> <html class="no-js" lang="en"> <!--<![endif]-->

<head>

		<meta charset="utf-8">

		<meta name="viewport" content="width=device-width, initial-scale=1">



		<title>Page not Found</title>

		<link rel="shortcut icon" href="<?php echo get_site_icon_url();?>" type="image/x-icon">

		<link rel="stylesheet" href="<?php bloginfo('template_url');?>/css/404.min.css">
		 <link rel="icon" href="http://www.triumphantcaresolutions.com/wp-content/themes/triumphantcareky424/images/favicon.png" />

	</head>

<body>

	<div class="protect-me">

	<div class="clearfix">

		<div class = "for-searching">

			<div class="fourofour-logo">

				<a href="<?php echo get_home_url(); ?>"><img src="<?php bloginfo('template_url'); ?>/images/main-logo.png" alt="<?php echo get_bloginfo('name');?>" /></a>

			</div>

			<div class="wrapper">

				<div id="post-0" class="post error404 not-found">

					<p class = "fourOfour">404<p>

					<h1 class="entry-title"><?php _e( 'Oops! This is Awkward', 'twentyten' ); ?></h1>

						<p class = "wrongtext"><?php _e( 'Something has gone wrong. The page you were looking for doesn\'t exist.', 'twentyten' ); ?></p>

						<?php get_search_form(); ?>

				</div><!-- #post-0 -->

				<script type="text/javascript">

					// focus on search field after it has loaded

					document.getElementById('s') && document.getElementById('s').focus();

				</script>

			</div>

		</div>

		</div>

	</div>

	<?php get_includes('ie');?>

<script src="<?php bloginfo('template_url');?>/js/vendor/jquery-1.9.0.min.js"></script>

</body>

</html>

