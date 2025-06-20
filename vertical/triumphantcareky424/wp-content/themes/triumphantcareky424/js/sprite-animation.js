//Smooth Scrolling

let scrollY = window.pageYOffset || document.documentElement.scrollTop;
    let velocity = 0;
    let isScrolling = false;
    const friction = 0.88; // Higher friction = quicker stop
    const multiplier = 0.25; // Controls sensitivity
    const minVelocity = 0.5; // Minimum velocity to keep animation alive
    let rafId; // To track the animation frame

    // Polyfill for requestAnimationFrame and cancelAnimationFrame
    const requestAnimFrame = window.requestAnimationFrame || window.mozRequestAnimationFrame || 
                            window.webkitRequestAnimationFrame || window.msRequestAnimationFrame;
    const cancelAnimFrame = window.cancelAnimationFrame || window.mozCancelAnimationFrame;

    function updateScroll() {
        // Clamp scroll position to valid boundaries
        const maxScroll = document.documentElement.scrollHeight - window.innerHeight;
        scrollY = Math.max(0, Math.min(scrollY, maxScroll));
        
        window.scrollTo(0, scrollY);
        
        // Apply friction and check if we need to stop
        velocity *= friction;
        if (Math.abs(velocity) > minVelocity) {
            scrollY += velocity;
            rafId = requestAnimFrame(updateScroll);
        } else {
            isScrolling = false;
            velocity = 0;
        }
    }

    function handleWheel(event) {
        if (event.cancelable) event.preventDefault(); // Only prevent default if possible
        
        // Update velocity
        velocity += event.deltaY * multiplier;
        
        // Start animation loop if not already running
        if (!isScrolling) {
            isScrolling = true;
            if (rafId) cancelAnimFrame(rafId); // Cancel any pending frame
            rafId = requestAnimFrame(updateScroll);
        }
    }

    // Use passive event listener for smoother performance
    window.addEventListener("wheel", handleWheel, { passive: false });
    window.addEventListener("touchmove", handleWheel, { passive: false }); // For touch

    // Reset velocity on user interaction (e.g., manual scroll)
    window.addEventListener("scroll", () => {
        if (!isScrolling) {
            scrollY = window.pageYOffset || document.documentElement.scrollTop;
        }
    });

    
// Sprite Scroll Animation

document.addEventListener("DOMContentLoaded", () => {
    gsap.registerPlugin(ScrollTrigger);
  
    const sprite = document.getElementById("sprite");
    const container = document.getElementById("sprite-container");
  
    const frameWidth = 121.5;
    const frameHeight = 138.2;
    const totalFrames = 10;
    const columns = 5;
  
    let currentFrame = 0;
    let frameInterval = 0;
    let lastX = null;
  
    function updateSpriteFrame() {
      const col = currentFrame % columns;
      const row = Math.floor(currentFrame / columns);
      const bgX = -col * frameWidth;
      const bgY = -row * frameHeight;
      sprite.style.backgroundPosition = `${bgX}px ${bgY}px`;
    }
  
    function animateButterfly(scrollProgress) {
      // Animate sprite frame
      const spriteProgress = Math.floor(scrollProgress * 1000) % totalFrames;
      if (spriteProgress !== frameInterval) {
        currentFrame = (currentFrame + 1) % totalFrames;
        updateSpriteFrame();
        frameInterval = spriteProgress;
      }
  
      // Restore original sine pattern
      const x = (Math.sin(scrollProgress * Math.PI * 2) * 0.4 + scrollProgress) * (window.innerWidth - frameWidth * 2);
      const y = scrollProgress * (window.innerHeight - frameHeight * 2);
      
      
  
      // Flip container instead of sprite to avoid flicker
      if (lastX !== null) {
        if (x > lastX) {
          container.style.transform = `translate(${x}px, ${y}px) scaleX(-1)`; // Flying right
        } else if (x < lastX) {
          container.style.transform = `translate(${x}px, ${y}px) scaleX(1)`; // Flying left
        } else {
          container.style.transform = `translate(${x}px, ${y}px)`; // No change
        }
      } else {
        container.style.transform = `translate(${x}px, ${y}px)`; // Initial position
      }
  
      lastX = x;
    }
  
    // Set initial position to prevent jump
    container.style.transform = `translate(0px, 0px)`;
  
    ScrollTrigger.create({
      trigger: document.body,
      start: "top top",
      end: "bottom bottom",
      scrub: 0.5,
      onUpdate: (self) => {
        animateButterfly(self.progress);
      }
    });
  });
  

  //mouse butterfly animation

  document.addEventListener("DOMContentLoaded", () => {
  const mouseContainer = document.getElementById("mouse-butterfly-container");
  const mouseSprite = document.getElementById("mouse-butterfly");

  const frameWidth = 121.5;
  const frameHeight = 138.2;
  const totalFrames = 10;
  const columns = 5;

  let currentFrame = 0;
  let lastMouseX = null;

  // Animate wings independently every 100ms
  setInterval(() => {
    currentFrame = (currentFrame + 1) % totalFrames;
    const col = currentFrame % columns;
    const row = Math.floor(currentFrame / columns);
    const bgX = -col * frameWidth;
    const bgY = -row * frameHeight;
    mouseSprite.style.backgroundPosition = `${bgX}px ${bgY}px`;
  }, 100);

  // Smooth follow with direction flip
    let direction = 1; // 1 = right, -1 = left

  document.addEventListener("mousemove", (e) => {
    const x = e.clientX - frameWidth / 2;
    const y = e.clientY - frameHeight / 2;

    // Determine direction (flip only the sprite, not the whole container)
    if (lastMouseX !== null) {
      direction = x > lastMouseX ? -1 : 1; // Flip if going right
      mouseSprite.style.transform = `scaleX(${direction})`;
    }
    lastMouseX = x;

    // Move container
    gsap.to(mouseContainer, {
      x: x,
      y: y,
      duration: 0.5,
      ease: "power2.out"
    });
  });


  // Optional: Idle floating effect when mouse is not moving
  let idleX = window.innerWidth / 2;
  let idleY = window.innerHeight / 2;
  let idleAngle = 0;

  setInterval(() => {
    if (!lastMouseX) {
      idleAngle += 0.1;
      const x = idleX + Math.cos(idleAngle) * 30;
      const y = idleY + Math.sin(idleAngle * 1.5) * 20;

      mouseContainer.style.transform = `translate(${x}px, ${y}px) scaleX(1)`;
    }
  }, 30);
});


//scroll-stopping zoomable image section
document.addEventListener("DOMContentLoaded", () => {
  gsap.registerPlugin(ScrollTrigger);

  document.querySelectorAll("#image-gallery-section .zoom-container").forEach(container => {
    const image = container.querySelector(".zoom-image");

    let scrollLocked = false;

    const zoomTimeline = gsap.timeline({
      scrollTrigger: {
        trigger: container,
        start: "top top",
        end: "+=200%",
        scrub: 1.5,
        pin: true,
        anticipatePin: 1,
        // markers: true,

        // Lock scroll when entering
        onEnter: () => {
          if (!scrollLocked) {
            document.body.style.overflow = "hidden";
            scrollLocked = true;
          }
        },

        // Unlock scroll when leaving
        onLeave: () => {
          document.body.style.overflow = "";
          scrollLocked = false;
        },

        // In case the user scrolls back up
        onLeaveBack: () => {
          document.body.style.overflow = "";
          scrollLocked = false;
        }
      }
    });

    // Zoom animation
    zoomTimeline.fromTo(image, { scale: 1 }, { scale: 2.5, ease: "power2.out" });
  });
});
