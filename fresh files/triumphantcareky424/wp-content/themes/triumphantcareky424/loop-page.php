<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <?php if ( is_front_page() ) { ?>
  <h1 class="h1_title">Introducing <span>Triumphant Care <small>Solutions LLC</small></span></h1>
  <!-- <?php //echo do_shortcode("[short_title id='" . get_the_ID() . "']"); ?> -->
  <?php } else { ?>
  <?php if($post->post_content=="" && !is_page('sitemap') && !is_page('364')) { ?>
  <p>We are still updating our website with contents. Please check back next time.</p>
  <?php } ?>
  <?php } ?>

  <?php echo do_shortcode("[page_intro id='" . get_the_ID() . "']"); ?>
  <div class="entry-content">


    <?php the_content(); ?>

    <!-- Testimonials -->
    <!--?php if(is_page('page_ID_or_permalink')) { comments_template( '', true ); } ?-->

    <?php if(is_page('sitemap')){ ?>
    <ol class="sitemap"><?php wp_list_pages(array('title_li' => '')); ?></ol>
    <?php } else if(is_page('364')) { ?>
    <?php comments_template( '', true ); ?>
    <?php } ?>
    <!-- <?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'twentyten' ), 'after' => '</div>' ) ); ?> -->
    <?php edit_post_link( __( 'Edit', 'twentyten' ), '<span class="edit-link">', '</span>' ); ?>
  </div><!-- .entry-content -->
</div><!-- #post-## -->
<?php endwhile; // end of the loop. ?>