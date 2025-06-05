<!DOCTYPE html>

<!--[if lt IE 10]>      <html class="no-js lt-ie11 lt-ie10 lt-ie9 lt-ie8 lt-ie7"> <![endif]-->

<!--[if IE 10]>         <html class="no-js lt-ie11 lt-ie10 lt-ie9 lt-ie8"> <![endif]-->

<!--[if IE 11]>         <html class="no-js lt-ie11"> <![endif]-->

<!--[if gt IE 11]><!-->
<html class="no-js" lang="en-US">
<!--<![endif]-->

<head>

  <meta charset="utf-8">

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->



  <title><?php echo get_bloginfo('name');?></title>

  <link rel="icon" href="<?php bloginfo('template_url');?>/images/favicon.png" />

  <link rel="stylesheet" href="<?php bloginfo('template_url');?>/style.min.css">

  <link rel="stylesheet" href="<?php bloginfo('template_url');?>/css/media.min.css">

  <link rel="stylesheet" href="<?php bloginfo('template_url');?>/css/hamburgers.min.css">

  <link rel="stylesheet" href="<?php bloginfo('template_url');?>/css/rslides.min.css">

  <link rel="stylesheet" href="<?php bloginfo('template_url');?>/css/font-awesome.min.css">

  <link rel="stylesheet" href="<?php bloginfo('template_url');?>/css/animations.min.css">

  <link rel="stylesheet" href="<?php bloginfo('template_url');?>/css/skitter.styles.min.css">
  <link rel="stylesheet" href="<?php bloginfo('template_url');?>/css/scrollanimation.min.css">



  <!--Admin Responsive-->



  <?php if ( is_user_logged_in() ) { ?>

  <style>
  @media only screen and (max-width : 800px) {

    nav.toggle_right_style {
      top: 32px;
    }

  }

  @media only screen and (max-width : 782px) {

    nav.toggle_right_style {
      top: 46px;
    }

  }
  </style>

  <?php }?>

  <?php if(is_page('364')) { ?>
  <link rel="stylesheet" href="<?php bloginfo("template_url") ?>/css/testimonial_css/testimonial.css">
  <?php }?>

  <?php if(!is_front_page()) { ?>

  <style>
  /* Other */

  .animated {
    animation-name: none !important;
    opacity: 1 !important;
    -webkit-transform: none !important;
    transform: none !important;
  }

  #main_area {
    margin: 0 auto;
    padding: 40px 25px;
    min-height: 0;
    position: relative;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    width: 100%;
    font-size: 18px;
    font-weight: 400;
    line-height: 35px;
  }

  .main_holder {
    float: none;
    width: 100%;
    top: 0;
    right: 0;
    left: 0;
    bottom: 0;
    position: relative;
    min-height: 0;
    margin: 0 auto;
    padding: 0;
    height: auto;
  }

  .entry-content {
    float: none;
    width: 100%;
    max-width: 100%;
    height: auto;
    min-height: 0;
    left: 0;
    right: 0;
    bottom: 0;
    top: 0;
    padding: 0;
    margin: 0 auto;
  }

  main {
    float: none;
    width: 100%;
    margin: 0 auto;
    padding: 0;
    height: auto;
    position: relative;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    min-height: 300px;
  }

  main p {
    margin-bottom: 30px;
    padding: 0;
    width: 100%;
  }
  </style>

  <?php }?>



  <?php wp_head(); ?>

</head>

<body>
  <div class="protect-me">
    <div class="clearfix">
    <div id="sprite-container">
  <div id="sprite"></div>
</div>

<div id="mouse-butterfly-container" style="position: fixed; top: 0; left: 0; pointer-events: none; z-index: 9999;">
  <div id="mouse-butterfly" style="
    width: 121.5px;
    height: 138.2px;
    background-image: url('/research/vertical/triumphantcareky424/wp-content/themes/triumphantcareky424/images/sprite/butterfly-flying.png');
    background-repeat: no-repeat;
  "></div>
</div>
