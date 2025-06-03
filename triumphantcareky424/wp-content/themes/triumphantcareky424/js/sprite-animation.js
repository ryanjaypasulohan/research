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

// Sprite Animation


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
  