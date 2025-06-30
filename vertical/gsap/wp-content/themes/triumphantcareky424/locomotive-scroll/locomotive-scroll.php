<?php
/**
 * Template Name: Chaotic Scroll Page
 */
get_includes('head');
get_includes('header');
// get_includes('nav');
?>

<!-- Locomotive Scroll CSS -->
<link rel="stylesheet" href="https://unpkg.com/locomotive-scroll@4.1.4/dist/locomotive-scroll.min.css" />

<style>


  #locomotiveScroll {
    background: #111;
    color: #fff;
  }

  [data-scroll-container] {
    position: relative;
    overflow: hidden;
    min-height: 150vh !important;
  }

  .dummy-top {
    height: 100vh;
    background: #000;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3vw;
  }

  .line-wrapper {
    position: relative;
    width: 100vw;
    overflow: visible;
  }

  .text-line {
    display: inline-block;
    white-space: nowrap;
    padding: 0vh 5vw;
    font-size: 10vw;
    font-weight: 800;
    background: white;
    line-height: 1;
    color: #111;
  }

  .l1 { transform: rotate(-15deg);top: 8vh; }
  .l2 { transform: rotate(20deg); top: 10vh;}
  .l3 { transform: rotate(-10deg); top: 13vh;}
  .l4 { transform: rotate(25deg); top: 17vh;}
  .l5 { transform: rotate(-18deg); top: 15vh;}
  .l6 { transform: rotate(12deg); top: 20vh;}
  .l7 { transform: rotate(-8deg); top: 21vh;}
  .l8 { transform: rotate(22deg); top: 24vh;}
  .l9 { transform: rotate(-5deg); top: 27vh;}
  .l10 { transform: rotate(15deg); top: 30vh;}
</style>

<div id="locomotiveScroll">
  <div data-scroll-container>

    <div class="dummy-top" data-scroll-section>
      Locomotive Scrolling Texts
    </div>

    <div data-scroll-section><div class="line-wrapper l1"><div class="text-line" data-scroll data-scroll-speed="12" data-scroll-direction="horizontal">BOSS LETS STOP THIS</div></div></div>
    <div data-scroll-section><div class="line-wrapper l2"><div class="text-line" data-scroll data-scroll-speed="-10" data-scroll-direction="horizontal">BOSS?</div></div></div>
    <div data-scroll-section><div class="line-wrapper l3"><div class="text-line" data-scroll data-scroll-speed="15" data-scroll-direction="horizontal">ASA KO NAOG BOSS</div></div></div>
    <div data-scroll-section><div class="line-wrapper l4"><div class="text-line" data-scroll data-scroll-speed="-14" data-scroll-direction="horizontal">JEEP NI BOSS?</div></div></div>
    <div data-scroll-section><div class="line-wrapper l5"><div class="text-line" data-scroll data-scroll-speed="20" data-scroll-direction="horizontal">UNAHAN LANG BOSS</div></div></div>
    <div data-scroll-section><div class="line-wrapper l6"><div class="text-line" data-scroll data-scroll-speed="-18" data-scroll-direction="horizontal">SUKLI NAKO BOSS</div></div></div>
    <div data-scroll-section><div class="line-wrapper l7"><div class="text-line" data-scroll data-scroll-speed="10" data-scroll-direction="horizontal">NALAPAS NAKO BOSS</div></div></div>
    <div data-scroll-section><div class="line-wrapper l8"><div class="text-line" data-scroll data-scroll-speed="-20" data-scroll-direction="horizontal">UNAHAN LANG</div></div></div>
    <div data-scroll-section><div class="line-wrapper l9"><div class="text-line" data-scroll data-scroll-speed="16" data-scroll-direction="horizontal">PAGDALI GAMAY</div></div></div>
    <div data-scroll-section><div class="line-wrapper l10"><div class="text-line" data-scroll data-scroll-speed="-25" data-scroll-direction="horizontal">DIRI RAKA BOSS?</div></div></div>

  </div>
</div>

<!-- Locomotive Scroll JS -->
<script src="https://unpkg.com/locomotive-scroll@4.1.4/dist/locomotive-scroll.min.js"></script>
<script>
  const scroll = new LocomotiveScroll({
    el: document.querySelector('[data-scroll-container]'),
    smooth: true,
    inertia: 0.25
  });
</script>

<?php get_footer(); ?>
