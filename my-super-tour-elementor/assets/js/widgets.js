/* My Super Tour Elementor Widgets JavaScript */

(function($) {
  'use strict';

  // Floating Orbs Parallax Effect - optimized with requestAnimationFrame
  function initFloatingOrbs() {
    const orbsContainers = document.querySelectorAll('.mst-floating-orbs-container[data-parallax="true"]');
    
    if (orbsContainers.length === 0) return;

    let ticking = false;
    let lastScrollY = window.pageYOffset;

    function updateOrbs() {
      orbsContainers.forEach(container => {
        const orbs = container.querySelectorAll('.mst-glass-orb');
        
        orbs.forEach((orb, index) => {
          const speed = 0.1 + (index * 0.05);
          const yPos = -(lastScrollY * speed);
          orb.style.transform = `translateY(${yPos}px)`;
        });
      });
      ticking = false;
    }

    window.addEventListener('scroll', function() {
      lastScrollY = window.pageYOffset;
      if (!ticking) {
        window.requestAnimationFrame(updateOrbs);
        ticking = true;
      }
    }, { passive: true });
  }

  // Universal Carousel Functionality - scrolls 1 item at a time
  function initUniversalCarousel() {
    document.querySelectorAll('.mst-carousel-universal').forEach(container => {
      const wrapper = container.querySelector('[class*="-wrapper"]');
      const track = container.querySelector('[class*="-track"]');
      const items = track ? track.children : [];
      const prevBtn = container.querySelector('.mst-arrow-prev');
      const nextBtn = container.querySelector('.mst-arrow-next');
      
      if (!track || items.length === 0) return;

      let currentIndex = 0;
      
      function getItemsPerView() {
        const width = window.innerWidth;
        const dataItems = parseInt(container.dataset.items) || 4;
        if (width <= 480) return 1;
        if (width <= 768) return Math.min(2, dataItems);
        if (width <= 1024) return Math.min(3, dataItems);
        return dataItems;
      }
      
      function getItemWidth() {
        if (items[0]) {
          const style = window.getComputedStyle(track);
          const gap = parseFloat(style.gap) || 16;
          return items[0].offsetWidth + gap;
        }
        return 300;
      }
      
      function getMaxIndex() {
        return Math.max(0, items.length - getItemsPerView());
      }

      function updateCarousel() {
        const itemWidth = getItemWidth();
        const offset = -currentIndex * itemWidth;
        track.style.transform = `translateX(${offset}px)`;
        track.style.transition = 'transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
        
        if (prevBtn) {
          prevBtn.disabled = currentIndex === 0;
          prevBtn.style.opacity = currentIndex === 0 ? '0.4' : '1';
        }
        if (nextBtn) {
          nextBtn.disabled = currentIndex >= getMaxIndex();
          nextBtn.style.opacity = currentIndex >= getMaxIndex() ? '0.4' : '1';
        }
      }

      if (prevBtn) {
        prevBtn.addEventListener('click', function(e) {
          e.preventDefault();
          if (currentIndex > 0) {
            currentIndex--; // Scroll 1 item
            updateCarousel();
          }
        });
      }

      if (nextBtn) {
        nextBtn.addEventListener('click', function(e) {
          e.preventDefault();
          if (currentIndex < getMaxIndex()) {
            currentIndex++; // Scroll 1 item
            updateCarousel();
          }
        });
      }

      // Arrow hover effects
      container.querySelectorAll('.mst-carousel-arrow-universal').forEach(arrow => {
        const hoverBg = arrow.dataset.hoverBg;
        const hoverColor = arrow.dataset.hoverColor;
        const originalBg = arrow.style.background || arrow.style.backgroundColor;
        const originalColor = arrow.style.color;
        
        arrow.addEventListener('mouseenter', function() {
          if (hoverBg) this.style.background = hoverBg;
          if (hoverColor) this.style.color = hoverColor;
        });
        
        arrow.addEventListener('mouseleave', function() {
          this.style.background = originalBg;
          this.style.color = originalColor;
        });
      });

      // Set initial flex-basis for items
      function setItemWidth() {
        const itemsPerView = getItemsPerView();
        const containerWidth = wrapper ? wrapper.offsetWidth : track.offsetWidth;
        const style = window.getComputedStyle(track);
        const gap = parseFloat(style.gap) || 16;
        const totalGaps = (itemsPerView - 1) * gap;
        const itemWidth = (containerWidth - totalGaps) / itemsPerView;
        
        Array.from(items).forEach(item => {
          item.style.flex = `0 0 ${itemWidth}px`;
          item.style.minWidth = `${itemWidth}px`;
          item.style.maxWidth = `${itemWidth}px`;
        });
      }
      
      // Responsive handling
      let resizeTimeout;
      window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
          setItemWidth();
          const maxIndex = getMaxIndex();
          if (currentIndex > maxIndex) {
            currentIndex = maxIndex;
          }
          updateCarousel();
        }, 100);
      });

      setItemWidth();
      updateCarousel();
    });
  }

  // Legacy Carousel Functionality - scroll 1 item at a time
  function initCarousel() {
    $('.mst-carousel-container:not(.mst-carousel-universal)').each(function() {
      const $container = $(this);
      const $wrapper = $container.find('.mst-carousel-wrapper');
      const $track = $container.find('.mst-carousel-track');
      const $items = $track.find('.mst-carousel-item');
      const $prevBtn = $container.find('.mst-carousel-prev');
      const $nextBtn = $container.find('.mst-carousel-next');
      
      if ($items.length === 0) return;

      let currentIndex = 0;
      
      function getItemsToShow() {
        const width = window.innerWidth;
        const dataItems = parseInt($container.data('items')) || 4;
        if (width <= 480) return 1;
        if (width <= 768) return Math.min(2, dataItems);
        if (width <= 1024) return Math.min(3, dataItems);
        return dataItems;
      }
      
      function getItemWidth() {
        return $items.first().outerWidth(true);
      }

      function getMaxIndex() {
        return Math.max(0, $items.length - getItemsToShow());
      }

      function setItemWidth() {
        const itemsToShow = getItemsToShow();
        const containerWidth = $wrapper.length ? $wrapper.width() : $container.width();
        const gap = parseFloat($track.css('gap')) || 16;
        const totalGaps = (itemsToShow - 1) * gap;
        const itemWidth = (containerWidth - totalGaps) / itemsToShow;
        
        $items.css({
          'flex': `0 0 ${itemWidth}px`,
          'min-width': `${itemWidth}px`,
          'max-width': `${itemWidth}px`
        });
      }

      function updateCarousel() {
        const itemWidth = getItemWidth();
        const offset = -currentIndex * itemWidth;
        $track.css({
          'transform': `translateX(${offset}px)`,
          'transition': 'transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94)'
        });
        
        $prevBtn.prop('disabled', currentIndex === 0).css('opacity', currentIndex === 0 ? 0.4 : 1);
        $nextBtn.prop('disabled', currentIndex >= getMaxIndex()).css('opacity', currentIndex >= getMaxIndex() ? 0.4 : 1);
      }

      $prevBtn.on('click', function(e) {
        e.preventDefault();
        if (currentIndex > 0) {
          currentIndex--; // Scroll 1 item
          updateCarousel();
        }
      });

      $nextBtn.on('click', function(e) {
        e.preventDefault();
        if (currentIndex < getMaxIndex()) {
          currentIndex++; // Scroll 1 item
          updateCarousel();
        }
      });

      // Responsive handling
      $(window).on('resize', function() {
        setItemWidth();
        const maxIndex = getMaxIndex();
        if (currentIndex > maxIndex) {
          currentIndex = maxIndex;
        }
        updateCarousel();
      });

      setItemWidth();
      updateCarousel();
    });
  }

  // WooCommerce Tour Carousel - scroll 1 item, no flicker
  function initWooCarousel() {
    document.querySelectorAll('.mst-woo-carousel-container').forEach(container => {
      const wrapper = container.querySelector('.mst-woo-carousel-wrapper');
      const track = container.querySelector('.mst-woo-carousel-track');
      const items = track ? track.children : [];
      const prevBtn = container.querySelector('.mst-arrow-prev');
      const nextBtn = container.querySelector('.mst-arrow-next');
      
      if (!track || items.length === 0) return;

      let currentIndex = 0;
      
      function getItemsPerView() {
        const width = window.innerWidth;
        const dataItems = parseInt(container.dataset.items) || 3;
        if (width <= 480) return 1;
        if (width <= 768) return Math.min(2, dataItems);
        if (width <= 1024) return Math.min(3, dataItems);
        return dataItems;
      }
      
      function getItemWidth() {
        if (items[0]) {
          const style = window.getComputedStyle(track);
          const gap = parseFloat(style.gap) || 16;
          return items[0].offsetWidth + gap;
        }
        return 300;
      }
      
      function getMaxIndex() {
        return Math.max(0, items.length - getItemsPerView());
      }

      function setItemWidths() {
        const itemsPerView = getItemsPerView();
        const wrapperWidth = wrapper.offsetWidth;
        const style = window.getComputedStyle(track);
        const gap = parseFloat(style.gap) || 16;
        const totalGaps = (itemsPerView - 1) * gap;
        const itemWidth = (wrapperWidth - totalGaps) / itemsPerView;
        
        Array.from(items).forEach(item => {
          item.style.flex = `0 0 ${itemWidth}px`;
          item.style.minWidth = `${itemWidth}px`;
          item.style.maxWidth = `${itemWidth}px`;
        });
        
        // Show track after widths are set (prevents 4->3 flicker)
        track.classList.add('mst-initialized');
      }

      function updateCarousel() {
        const itemWidth = getItemWidth();
        const offset = -currentIndex * itemWidth;
        track.style.transform = `translateX(${offset}px)`;
        track.style.transition = 'transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
        
        if (prevBtn) {
          prevBtn.disabled = currentIndex === 0;
          prevBtn.style.opacity = currentIndex === 0 ? '0.4' : '1';
        }
        if (nextBtn) {
          nextBtn.disabled = currentIndex >= getMaxIndex();
          nextBtn.style.opacity = currentIndex >= getMaxIndex() ? '0.4' : '1';
        }
      }

      if (prevBtn) {
        prevBtn.addEventListener('click', function(e) {
          e.preventDefault();
          if (currentIndex > 0) {
            currentIndex--;
            updateCarousel();
          }
        });
      }

      if (nextBtn) {
        nextBtn.addEventListener('click', function(e) {
          e.preventDefault();
          if (currentIndex < getMaxIndex()) {
            currentIndex++;
            updateCarousel();
          }
        });
      }

      // Arrow hover effects
      [prevBtn, nextBtn].forEach(arrow => {
        if (!arrow) return;
        const hoverBg = arrow.dataset.hoverBg;
        const hoverColor = arrow.dataset.hoverColor;
        const originalBg = arrow.style.background;
        const originalColor = arrow.style.color;
        
        arrow.addEventListener('mouseenter', function() {
          if (hoverBg) this.style.background = hoverBg;
          if (hoverColor) this.style.color = hoverColor;
        });
        
        arrow.addEventListener('mouseleave', function() {
          this.style.background = originalBg;
          this.style.color = originalColor;
        });
      });

      // Resize handler
      let resizeTimeout;
      window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
          setItemWidths();
          const maxIndex = getMaxIndex();
          if (currentIndex > maxIndex) currentIndex = maxIndex;
          updateCarousel();
        }, 100);
      });

      setItemWidths();
      updateCarousel();
    });
  }

  // Tour Carousel - scroll 1 item
  function initTourCarousel() {
    document.querySelectorAll('.mst-tour-carousel-container').forEach(container => {
      const wrapper = container.querySelector('.mst-tour-carousel-wrapper');
      const track = container.querySelector('.mst-tour-carousel-track');
      const items = track ? track.children : [];
      const prevBtn = container.querySelector('.mst-tour-carousel-arrow.mst-arrow-prev');
      const nextBtn = container.querySelector('.mst-tour-carousel-arrow.mst-arrow-next');
      
      if (!track || items.length === 0) return;

      let currentIndex = 0;
      
      function getItemsPerView() {
        const width = window.innerWidth;
        const dataItems = parseInt(container.dataset.items) || 4;
        if (width <= 480) return 1;
        if (width <= 768) return 2;
        if (width <= 1024) return 3;
        return dataItems;
      }
      
      function getItemWidth() {
        if (items[0]) {
          const style = window.getComputedStyle(track);
          const gap = parseFloat(style.gap) || 16;
          return items[0].offsetWidth + gap;
        }
        return 300;
      }
      
      function getMaxIndex() {
        return Math.max(0, items.length - getItemsPerView());
      }

      function setItemWidths() {
        const itemsPerView = getItemsPerView();
        const wrapperWidth = wrapper ? wrapper.offsetWidth : container.offsetWidth;
        const style = window.getComputedStyle(track);
        const gap = parseFloat(style.gap) || 16;
        const totalGaps = (itemsPerView - 1) * gap;
        const itemWidth = (wrapperWidth - totalGaps) / itemsPerView;
        
        Array.from(items).forEach(item => {
          item.style.flex = `0 0 ${itemWidth}px`;
          item.style.minWidth = `${itemWidth}px`;
          item.style.maxWidth = `${itemWidth}px`;
        });
      }

      function updateCarousel() {
        const itemWidth = getItemWidth();
        const offset = -currentIndex * itemWidth;
        track.style.transform = `translateX(${offset}px)`;
        track.style.transition = 'transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
        
        if (prevBtn) {
          prevBtn.disabled = currentIndex === 0;
          prevBtn.style.opacity = currentIndex === 0 ? '0.4' : '1';
        }
        if (nextBtn) {
          nextBtn.disabled = currentIndex >= getMaxIndex();
          nextBtn.style.opacity = currentIndex >= getMaxIndex() ? '0.4' : '1';
        }
      }

      if (prevBtn) {
        prevBtn.addEventListener('click', function(e) {
          e.preventDefault();
          if (currentIndex > 0) {
            currentIndex--;
            updateCarousel();
          }
        });
      }

      if (nextBtn) {
        nextBtn.addEventListener('click', function(e) {
          e.preventDefault();
          if (currentIndex < getMaxIndex()) {
            currentIndex++;
            updateCarousel();
          }
        });
      }

      let resizeTimeout;
      window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
          setItemWidths();
          const maxIndex = getMaxIndex();
          if (currentIndex > maxIndex) currentIndex = maxIndex;
          updateCarousel();
        }, 100);
      });

      setItemWidths();
      updateCarousel();
    });
  }

  // Guide Carousel - scroll 1 item
  function initGuideCarousel() {
    document.querySelectorAll('.mst-guide-carousel-container').forEach(container => {
      const wrapper = container.querySelector('.mst-guide-carousel-wrapper');
      const track = container.querySelector('.mst-guide-carousel-track');
      const items = track ? track.children : [];
      const prevBtn = container.querySelector('.mst-guide-carousel-arrow.mst-arrow-prev');
      const nextBtn = container.querySelector('.mst-guide-carousel-arrow.mst-arrow-next');
      
      if (!track || items.length === 0) return;

      let currentIndex = 0;
      
      function getItemsPerView() {
        const width = window.innerWidth;
        const dataItems = parseInt(container.dataset.items) || 3;
        if (width <= 480) return 1;
        if (width <= 768) return 2;
        if (width <= 1024) return Math.min(3, dataItems);
        return dataItems;
      }
      
      function getItemWidth() {
        if (items[0]) {
          const style = window.getComputedStyle(track);
          const gap = parseFloat(style.gap) || 24;
          return items[0].offsetWidth + gap;
        }
        return 350;
      }
      
      function getMaxIndex() {
        return Math.max(0, items.length - getItemsPerView());
      }

      function updateCarousel() {
        const itemWidth = getItemWidth();
        const offset = -currentIndex * itemWidth;
        track.style.transform = `translateX(${offset}px)`;
        track.style.transition = 'transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
        
        if (prevBtn) {
          prevBtn.disabled = currentIndex === 0;
          prevBtn.style.opacity = currentIndex === 0 ? '0.4' : '1';
        }
        if (nextBtn) {
          nextBtn.disabled = currentIndex >= getMaxIndex();
          nextBtn.style.opacity = currentIndex >= getMaxIndex() ? '0.4' : '1';
        }
      }

      if (prevBtn) {
        prevBtn.addEventListener('click', function(e) {
          e.preventDefault();
          if (currentIndex > 0) {
            currentIndex--;
            updateCarousel();
          }
        });
      }

      if (nextBtn) {
        nextBtn.addEventListener('click', function(e) {
          e.preventDefault();
          if (currentIndex < getMaxIndex()) {
            currentIndex++;
            updateCarousel();
          }
        });
      }

      // Set equal heights for cards
      function setEqualHeights() {
        let maxHeight = 0;
        Array.from(items).forEach(item => {
          item.style.height = 'auto';
          item.style.minHeight = 'auto';
        });
        Array.from(items).forEach(item => {
          const height = item.offsetHeight;
          if (height > maxHeight) maxHeight = height;
        });
        Array.from(items).forEach(item => {
          item.style.minHeight = maxHeight + 'px';
        });
      }

      function setItemWidths() {
        const itemsPerView = getItemsPerView();
        const wrapperWidth = wrapper.offsetWidth;
        const style = window.getComputedStyle(track);
        const gap = parseFloat(style.gap) || 24;
        const totalGaps = (itemsPerView - 1) * gap;
        const itemWidth = (wrapperWidth - totalGaps) / itemsPerView;
        
        Array.from(items).forEach(item => {
          item.style.flex = `0 0 ${itemWidth}px`;
          item.style.minWidth = `${itemWidth}px`;
          item.style.maxWidth = `${itemWidth}px`;
        });
      }
      
      // Wait for images to load
      let imagesLoaded = 0;
      const images = track.querySelectorAll('img');
      const totalImages = images.length;
      
      function onReady() {
        setItemWidths();
        setTimeout(setEqualHeights, 100);
        updateCarousel();
      }
      
      if (totalImages === 0) {
        onReady();
      } else {
        images.forEach(img => {
          if (img.complete) {
            imagesLoaded++;
            if (imagesLoaded === totalImages) onReady();
          } else {
            img.addEventListener('load', () => {
              imagesLoaded++;
              if (imagesLoaded === totalImages) onReady();
            });
          }
        });
      }

      let resizeTimeout;
      window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
          setItemWidths();
          setEqualHeights();
          const maxIndex = getMaxIndex();
          if (currentIndex > maxIndex) currentIndex = maxIndex;
          updateCarousel();
        }, 150);
      });
    });
  }

  // Review Carousel - scroll 1 item
  function initReviewCarousel() {
    document.querySelectorAll('.mst-review-carousel-container').forEach(container => {
      const wrapper = container.querySelector('.mst-review-carousel-wrapper');
      const track = container.querySelector('.mst-review-carousel-track');
      const items = track ? track.children : [];
      const prevBtn = container.querySelector('.mst-arrow-prev');
      const nextBtn = container.querySelector('.mst-arrow-next');
      
      if (!track || items.length === 0) return;

      let currentIndex = 0;
      
      function getItemsPerView() {
        const width = window.innerWidth;
        const dataItems = parseInt(container.dataset.items) || 3;
        if (width <= 480) return 1;
        if (width <= 768) return 2;
        return Math.min(3, dataItems);
      }
      
      function getItemWidth() {
        if (items[0]) {
          const style = window.getComputedStyle(track);
          const gap = parseFloat(style.gap) || 24;
          return items[0].offsetWidth + gap;
        }
        return 350;
      }
      
      function getMaxIndex() {
        return Math.max(0, items.length - getItemsPerView());
      }

      function setItemWidths() {
        const itemsPerView = getItemsPerView();
        const wrapperWidth = wrapper ? wrapper.offsetWidth : container.offsetWidth;
        const style = window.getComputedStyle(track);
        const gap = parseFloat(style.gap) || 24;
        const totalGaps = (itemsPerView - 1) * gap;
        const itemWidth = (wrapperWidth - totalGaps) / itemsPerView;
        
        Array.from(items).forEach(item => {
          item.style.flex = `0 0 ${itemWidth}px`;
          item.style.minWidth = `${itemWidth}px`;
          item.style.maxWidth = `${itemWidth}px`;
        });
      }

      function updateCarousel() {
        const itemWidth = getItemWidth();
        const offset = -currentIndex * itemWidth;
        track.style.transform = `translateX(${offset}px)`;
        track.style.transition = 'transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
        
        if (prevBtn) {
          prevBtn.disabled = currentIndex === 0;
          prevBtn.style.opacity = currentIndex === 0 ? '0.4' : '1';
        }
        if (nextBtn) {
          nextBtn.disabled = currentIndex >= getMaxIndex();
          nextBtn.style.opacity = currentIndex >= getMaxIndex() ? '0.4' : '1';
        }
      }

      if (prevBtn) {
        prevBtn.addEventListener('click', function(e) {
          e.preventDefault();
          if (currentIndex > 0) {
            currentIndex--;
            updateCarousel();
          }
        });
      }

      if (nextBtn) {
        nextBtn.addEventListener('click', function(e) {
          e.preventDefault();
          if (currentIndex < getMaxIndex()) {
            currentIndex++;
            updateCarousel();
          }
        });
      }

      let resizeTimeout;
      window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
          setItemWidths();
          const maxIndex = getMaxIndex();
          if (currentIndex > maxIndex) currentIndex = maxIndex;
          updateCarousel();
        }, 100);
      });

      setItemWidths();
      updateCarousel();
    });
  }

  // Guest Carousel - scroll 1 item
  function initGuestCarousel() {
    document.querySelectorAll('.mst-guest-carousel-container').forEach(container => {
      const wrapper = container.querySelector('.mst-guest-carousel-wrapper');
      const track = container.querySelector('.mst-guest-carousel-track');
      const items = track ? track.children : [];
      const prevBtn = container.querySelector('.mst-arrow-prev');
      const nextBtn = container.querySelector('.mst-arrow-next');
      
      if (!track || items.length === 0) return;

      let currentIndex = 0;
      
      function getItemsPerView() {
        const width = window.innerWidth;
        const dataItems = parseInt(container.dataset.items) || 4;
        if (width <= 480) return 1;
        if (width <= 768) return 2;
        if (width <= 1024) return 3;
        return dataItems;
      }
      
      function getItemWidth() {
        if (items[0]) {
          const style = window.getComputedStyle(track);
          const gap = parseFloat(style.gap) || 16;
          return items[0].offsetWidth + gap;
        }
        return 280;
      }
      
      function getMaxIndex() {
        return Math.max(0, items.length - getItemsPerView());
      }

      function setItemWidths() {
        const itemsPerView = getItemsPerView();
        const wrapperWidth = wrapper ? wrapper.offsetWidth : container.offsetWidth;
        const style = window.getComputedStyle(track);
        const gap = parseFloat(style.gap) || 16;
        const totalGaps = (itemsPerView - 1) * gap;
        const itemWidth = (wrapperWidth - totalGaps) / itemsPerView;
        
        Array.from(items).forEach(item => {
          item.style.flex = `0 0 ${itemWidth}px`;
          item.style.minWidth = `${itemWidth}px`;
          item.style.maxWidth = `${itemWidth}px`;
        });
      }

      function updateCarousel() {
        const itemWidth = getItemWidth();
        const offset = -currentIndex * itemWidth;
        track.style.transform = `translateX(${offset}px)`;
        track.style.transition = 'transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
        
        if (prevBtn) {
          prevBtn.disabled = currentIndex === 0;
          prevBtn.style.opacity = currentIndex === 0 ? '0.4' : '1';
        }
        if (nextBtn) {
          nextBtn.disabled = currentIndex >= getMaxIndex();
          nextBtn.style.opacity = currentIndex >= getMaxIndex() ? '0.4' : '1';
        }
      }

      if (prevBtn) {
        prevBtn.addEventListener('click', function(e) {
          e.preventDefault();
          if (currentIndex > 0) {
            currentIndex--;
            updateCarousel();
          }
        });
      }

      if (nextBtn) {
        nextBtn.addEventListener('click', function(e) {
          e.preventDefault();
          if (currentIndex < getMaxIndex()) {
            currentIndex++;
            updateCarousel();
          }
        });
      }

      let resizeTimeout;
      window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
          setItemWidths();
          const maxIndex = getMaxIndex();
          if (currentIndex > maxIndex) currentIndex = maxIndex;
          updateCarousel();
        }, 100);
      });

      setItemWidths();
      updateCarousel();
    });
  }

  // Shop Grid Guide Hover
  function initShopGridGuides() {
    document.querySelectorAll('.mst-shop-grid-guide').forEach(guide => {
      const hoverBorder = guide.dataset.hoverBorder;
      const originalBorder = guide.style.borderColor;
      
      guide.addEventListener('mouseenter', function() {
        if (hoverBorder) this.style.borderColor = hoverBorder;
        this.style.transform = 'scale(1.1)';
      });
      
      guide.addEventListener('mouseleave', function() {
        this.style.borderColor = originalBorder;
        this.style.transform = 'scale(1)';
      });
    });
  }

  // Guide Photo Hover Effect
  function initGuideHover() {
    document.querySelectorAll('.mst-woo-carousel-guide').forEach(guide => {
      const hoverBorder = guide.dataset.hoverBorder;
      const originalBorder = guide.style.borderColor;
      
      guide.addEventListener('mouseenter', function() {
        if (hoverBorder) this.style.borderColor = hoverBorder;
        this.style.transform = 'scale(1.1)';
      });
      
      guide.addEventListener('mouseleave', function() {
        this.style.borderColor = originalBorder;
        this.style.transform = 'scale(1)';
      });
    });
  }

  // Smooth scroll reveal animations
  function initScrollAnimations() {
    const observerOptions = {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = '1';
          entry.target.style.transform = 'translateY(0)';
        }
      });
    }, observerOptions);

    const animatedElements = document.querySelectorAll(
      '.mst-liquid-glass-card, .mst-tour-card, .mst-review-card, .mst-team-member, .mst-category-card'
    );

    animatedElements.forEach(el => {
      el.style.opacity = '0';
      el.style.transform = 'translateY(20px)';
      el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
      observer.observe(el);
    });
  }

  // Booking Modal Trigger
  function initBookingButtons() {
    $(document).on('click', '.mst-tour-book-button', function(e) {
      e.preventDefault();
      alert('Booking functionality requires backend integration. Connect to your booking system.');
    });
  }

  // Contact Button Handler
  function initContactButtons() {
    $(document).on('click', '.mst-contact-button', function(e) {
      e.preventDefault();
      const email = $(this).closest('.mst-contact-block').find('.mst-contact-item a[href^="mailto:"]').attr('href');
      if (email) {
        window.location.href = email;
      }
    });
  }

  // Instagram Button Handler
  function initInstagramButtons() {
    $(document).on('click', '.mst-instagram-button', function(e) {
      e.preventDefault();
      const handle = $(this).closest('.mst-instagram-block').find('.mst-instagram-handle').text().replace('@', '');
      if (handle) {
        window.open(`https://instagram.com/${handle}`, '_blank');
      }
    });
  }

  // Lightbox for Review Photos
  function initLightbox() {
    // Create lightbox overlay if not exists
    if (!document.getElementById('mst-lightbox')) {
      const lightbox = document.createElement('div');
      lightbox.id = 'mst-lightbox';
      lightbox.innerHTML = `
        <div class="mst-lightbox-overlay" style="position: fixed; inset: 0; background: rgba(0,0,0,0.9); z-index: 99999; display: none; align-items: center; justify-content: center; cursor: zoom-out; padding: 20px;">
          <button class="mst-lightbox-close" style="position: absolute; top: 20px; right: 20px; background: rgba(255,255,255,0.2); border: none; width: 48px; height: 48px; border-radius: 50%; color: #fff; font-size: 24px; cursor: pointer; backdrop-filter: blur(10px); transition: all 0.3s ease;">×</button>
          <img class="mst-lightbox-img" src="" alt="" style="max-width: 90%; max-height: 90%; object-fit: contain; border-radius: 8px; box-shadow: 0 20px 60px rgba(0,0,0,0.5);">
        </div>
      `;
      document.body.appendChild(lightbox);
      
      const overlay = lightbox.querySelector('.mst-lightbox-overlay');
      const img = lightbox.querySelector('.mst-lightbox-img');
      const closeBtn = lightbox.querySelector('.mst-lightbox-close');
      
      function closeLightbox() {
        overlay.style.display = 'none';
        document.body.style.overflow = '';
      }
      
      overlay.addEventListener('click', function(e) {
        if (e.target === overlay || e.target === closeBtn) {
          closeLightbox();
        }
      });
      
      closeBtn.addEventListener('click', closeLightbox);
      
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && overlay.style.display === 'flex') {
          closeLightbox();
        }
      });
    }
    
    // Attach click handlers to lightbox triggers (simple version)
    document.querySelectorAll('a.mst-lightbox-trigger').forEach(trigger => {
      trigger.addEventListener('click', function(e) {
        e.preventDefault();
        const imgSrc = this.getAttribute('href');
        const overlay = document.querySelector('#mst-lightbox .mst-lightbox-overlay');
        const img = overlay.querySelector('.mst-lightbox-img');
        
        img.src = imgSrc;
        overlay.style.display = 'flex';
        document.body.style.overflow = 'hidden';
      });
    });
  }

  // Reviews Section Gallery Lightbox
  function initReviewsGalleryLightbox() {
    const lightboxEl = document.querySelector('.mst-reviews-lightbox');
    if (!lightboxEl) return;

    let currentGallery = [];
    let currentIndex = 0;

    const lightboxImage = lightboxEl.querySelector('.mst-lightbox-image');
    const lightboxCounter = lightboxEl.querySelector('.mst-lightbox-current');
    const lightboxTotal = lightboxEl.querySelector('.mst-lightbox-total');
    const prevBtn = lightboxEl.querySelector('.mst-lightbox-prev');
    const nextBtn = lightboxEl.querySelector('.mst-lightbox-next');
    const closeBtn = lightboxEl.querySelector('.mst-lightbox-close');
    const overlay = lightboxEl.querySelector('.mst-lightbox-overlay');

    function openLightbox(galleryId, photoIndex) {
      const card = document.querySelector(`[data-review-index][data-gallery-id="${galleryId}"]`)?.closest('.mst-review-card') 
                   || document.querySelector(`.mst-lightbox-trigger[data-gallery-id="${galleryId}"]`)?.closest('.mst-review-card');
      
      if (!card) return;
      
      const galleryData = card.querySelector('.mst-review-gallery-data');
      if (!galleryData) return;
      
      currentGallery = Array.from(galleryData.querySelectorAll('img')).map(img => img.src);
      currentIndex = parseInt(photoIndex) || 0;

      if (currentGallery.length === 0) return;

      updateLightboxImage();
      lightboxEl.style.display = 'block';
      document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
      lightboxEl.style.display = 'none';
      document.body.style.overflow = '';
    }

    function updateLightboxImage() {
      if (currentGallery[currentIndex]) {
        lightboxImage.src = currentGallery[currentIndex];
        lightboxCounter.textContent = currentIndex + 1;
        lightboxTotal.textContent = currentGallery.length;
      }
    }

    function prevImage() {
      currentIndex = (currentIndex - 1 + currentGallery.length) % currentGallery.length;
      updateLightboxImage();
    }

    function nextImage() {
      currentIndex = (currentIndex + 1) % currentGallery.length;
      updateLightboxImage();
    }

    // Event listeners
    document.querySelectorAll('.mst-reviews-section .mst-lightbox-trigger').forEach(trigger => {
      trigger.addEventListener('click', function(e) {
        e.preventDefault();
        const galleryId = this.dataset.galleryId;
        const photoIndex = this.dataset.photoIndex;
        openLightbox(galleryId, photoIndex);
      });
    });

    if (closeBtn) closeBtn.addEventListener('click', closeLightbox);
    if (overlay) overlay.addEventListener('click', closeLightbox);
    if (prevBtn) prevBtn.addEventListener('click', prevImage);
    if (nextBtn) nextBtn.addEventListener('click', nextImage);
    
    // Close on click outside image (on container background)
    const lightboxContainer = lightboxEl.querySelector('.mst-lightbox-container');
    if (lightboxContainer) {
      lightboxContainer.addEventListener('click', function(e) {
        // Close only if clicked on the container itself, not on buttons or image
        if (e.target === lightboxContainer || e.target.classList.contains('mst-lightbox-content')) {
          closeLightbox();
        }
      });
    }

    document.addEventListener('keydown', function(e) {
      if (lightboxEl.style.display !== 'block') return;
      if (e.key === 'Escape') closeLightbox();
      if (e.key === 'ArrowLeft') prevImage();
      if (e.key === 'ArrowRight') nextImage();
    });
  }

  // Reviews Load More Button
  function initReviewsLoadMore() {
    document.querySelectorAll('.mst-reviews-load-more-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        const section = this.closest('.mst-reviews-section');
        if (!section) return;
        
        const hiddenReviews = section.querySelectorAll('.mst-review-hidden');
        hiddenReviews.forEach(review => {
          review.classList.remove('mst-review-hidden');
        });
        
        // Hide the Load More button after revealing all
        this.closest('.mst-reviews-load-more').style.display = 'none';
      });
    });
  }

  // Cursor Glow Effect for cards
  // REMOVED .mst-shop-grid-card - user wants NO cursor glow on Shop Grid
  function initCursorGlow() {
    const cardSelectors = [
      '.mst-tour-card',
      '.mst-woo-carousel-card',
      '.mst-woo-tour-card',
      '.mst-carousel-card',
      '.mst-review-card-v2',
      '.mst-category-card',
      '.mst-liquid-glass-card'
    ];
    
    cardSelectors.forEach(selector => {
      document.querySelectorAll(selector).forEach(card => {
        // Skip if already initialized
        if (card.dataset.glowInit) return;
        card.dataset.glowInit = 'true';
        
        // Check if cursor glow is enabled (via parent container or default)
        const container = card.closest('[data-cursor-glow]');
        const cursorGlowEnabled = !container || container.dataset.cursorGlow !== 'false';
        
        if (!cursorGlowEnabled) return;
        
        // Create glow element
        const glow = document.createElement('div');
        glow.className = 'mst-cursor-glow';
        card.appendChild(glow);
        
        // Track mouse movement
        card.addEventListener('mousemove', function(e) {
          const rect = card.getBoundingClientRect();
          const x = e.clientX - rect.left;
          const y = e.clientY - rect.top;
          glow.style.left = x + 'px';
          glow.style.top = y + 'px';
        });
        
        card.addEventListener('mouseenter', function() {
          glow.style.opacity = '1';
          
          // Apply icon glow based on container settings
          const iconGlowIntensity = container?.dataset.iconGlow || '4';
          const iconGlowColor = container?.dataset.iconGlowColor || 'rgba(255, 255, 255, 0.5)';
          
          if (parseInt(iconGlowIntensity) > 0) {
            card.querySelectorAll('.fa, .fas, .far, svg, .mst-woo-star').forEach(icon => {
              // Skip button icons
              if (icon.closest('.mst-woo-carousel-button, .mst-shop-grid-button, .mst-tour-book-button')) return;
              icon.style.filter = `drop-shadow(0 0 ${iconGlowIntensity}px ${iconGlowColor})`;
              icon.style.transition = 'filter 0.3s ease';
            });
          }
        });
        
        card.addEventListener('mouseleave', function() {
          glow.style.opacity = '0';
          
          // Remove icon glow
          card.querySelectorAll('.fa, .fas, .far, svg, .mst-woo-star').forEach(icon => {
            icon.style.filter = '';
          });
        });
      });
    });
  }

  // NEW: Cursor-follow glow for badges, icons, buttons
  function initFollowGlow() {
    document.querySelectorAll('.mst-follow-glow').forEach(el => {
      el.addEventListener('mousemove', function(e) {
        const rect = this.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        this.style.setProperty('--mx', x + 'px');
        this.style.setProperty('--my', y + 'px');
      });
    });
  }

  // Wishlist button hover effect
  function initWishlistHover() {
    document.querySelectorAll('.mst-wishlist-btn[data-hover-bg]').forEach(btn => {
      const hoverBg = btn.dataset.hoverBg;
      const originalBg = btn.style.background || btn.style.backgroundColor;
      
      btn.addEventListener('mouseenter', function() {
        if (hoverBg) this.style.background = hoverBg;
      });
      
      btn.addEventListener('mouseleave', function() {
        this.style.background = originalBg;
      });
    });
  }

  // Wishlist flying heart animation
  function animateHeartFly(fromElement, toElement, isAdding) {
    const heart = document.createElement('div');
    heart.className = 'mst-flying-heart';
    heart.innerHTML = '❤️';
    heart.style.cssText = `
      position: fixed;
      z-index: 999999;
      font-size: 24px;
      pointer-events: none;
      transition: all 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    `;
    
    const fromRect = fromElement.getBoundingClientRect();
    const toRect = toElement.getBoundingClientRect();
    
    // Starting position
    heart.style.left = fromRect.left + fromRect.width / 2 + 'px';
    heart.style.top = fromRect.top + fromRect.height / 2 + 'px';
    heart.style.color = isAdding ? '#ffffff' : '#ff0000';
    heart.style.opacity = '1';
    heart.style.transform = 'scale(1) translate(-50%, -50%)';
    
    document.body.appendChild(heart);
    
    // Animate to target
    requestAnimationFrame(() => {
      requestAnimationFrame(() => {
        heart.style.left = toRect.left + toRect.width / 2 + 'px';
        heart.style.top = toRect.top + toRect.height / 2 + 'px';
        heart.style.transform = 'scale(0.3) translate(-50%, -50%)';
        heart.style.opacity = '0';
      });
    });
    
    setTimeout(() => heart.remove(), 600);
  }

  // Initialize wishlist animation handlers
  function initWishlistAnimation() {
    // Find wishlist icon in header
    const wishlistIcon = document.querySelector('.header-wishlist, .etheme-wishlist-widget, [class*="wishlist"]');
    
    if (!wishlistIcon) {
      console.warn('Wishlist icon not found in header');
      return;
    }
    
    // Handle wishlist button clicks
    document.addEventListener('click', function(e) {
      const wishlistBtn = e.target.closest('.mst-wishlist-btn, .mst-shop-grid-wishlist');
      
      if (wishlistBtn) {
        // Check if adding or removing
        const isInWishlist = wishlistBtn.classList.contains('in-wishlist') || 
                            wishlistBtn.classList.contains('added');
        const isAdding = !isInWishlist;
        
        // Animate heart flying
        animateHeartFly(wishlistBtn, wishlistIcon, isAdding);
        
        // Toggle state class
        if (isAdding) {
          wishlistBtn.classList.add('in-wishlist');
        } else {
          wishlistBtn.classList.remove('in-wishlist');
        }
      }
    });
  }

  // Initialize all functionality
  // Auto-fit badges so they never touch wishlist (mobile/tablet)
  function initBadgeAutoPosition() {
    if (window.innerWidth > 1024) return;

    const overlaps = (a, b) => {
      if (!a || !b) return false;
      return !(
        a.right <= b.left ||
        a.left >= b.right ||
        a.bottom <= b.top ||
        a.top >= b.bottom
      );
    };

    document.querySelectorAll('.mst-woo-carousel-card, .mst-shop-grid-card').forEach(card => {
      const badges = card.querySelector('.mst-badges-auto-position');
      const wishlist = card.querySelector('.mst-wishlist-btn, .mst-woo-carousel-wishlist');
      if (!badges || !wishlist) return;

      // Reset previous state
      badges.classList.remove('mst-badges-bottom');
      badges.classList.remove('mst-badges-shifted');
      badges.style.removeProperty('--badges-top');

      const cardRect = card.getBoundingClientRect();
      const wishRect = wishlist.getBoundingClientRect();

      // Limit badges width so they never reach the wishlist button
      const computed = window.getComputedStyle(badges);
      const left = parseFloat(computed.left) || 12;
      const safeGap = 10;
      const maxWidth = Math.max(140, Math.floor(wishRect.left - cardRect.left - left - safeGap));
      badges.style.setProperty('--badges-max-width', `${maxWidth}px`);

      // If still overlaps (e.g., very large badge sizes), move badges below wishlist
      const badgesRect = badges.getBoundingClientRect();
      if (overlaps(badgesRect, wishRect)) {
        const nextTop = Math.floor(wishRect.bottom - cardRect.top + 10);
        badges.style.setProperty('--badges-top', `${nextTop}px`);
        badges.classList.add('mst-badges-shifted');
      }
    });
  }

  function initAll() {
    initFloatingOrbs();
    initCarousel();
    initUniversalCarousel();
    initWooCarousel();
    initTourCarousel();
    initGuideCarousel();
    initReviewCarousel();
    initGuestCarousel();
    initGuideHover();
    initShopGridGuides();
    initScrollAnimations();
    initBookingButtons();
    initContactButtons();
    initInstagramButtons();
    initLightbox();
    initReviewsGalleryLightbox();
    initReviewsLoadMore();
    initCursorGlow();
    initFollowGlow();
    initWishlistHover();
    initWishlistAnimation();
    initBadgeAutoPosition();
  }
  
  // Handle badge repositioning on resize
  let badgeResizeTimeout;
  window.addEventListener('resize', function() {
    clearTimeout(badgeResizeTimeout);
    badgeResizeTimeout = setTimeout(initBadgeAutoPosition, 150);
  });

  $(window).on('elementor/frontend/init', function() {
    initAll();
    
    // Re-initialize on Elementor preview changes
    if (typeof elementorFrontend !== 'undefined') {
      elementorFrontend.hooks.addAction('frontend/element_ready/widget', function() {
        setTimeout(initAll, 100);
      });
    }
  });

  // ========================================
  // WOO CAROUSEL - DYNAMIC GUIDE INJECTION
  // ========================================
  function initWooCarouselGuides() {
    const $carouselCards = $('.mst-woo-carousel-card[data-product-id]');
    if ($carouselCards.length === 0) return;

    // Collect all product IDs that have guide placeholders
    const productIds = [];
    $carouselCards.each(function() {
      const $card = $(this);
      const $placeholder = $card.find('.mst-woo-carousel-guide-placeholder');
      if ($placeholder.length > 0) {
        const productId = $card.data('product-id');
        if (productId) {
          productIds.push(productId);
        }
      }
    });

    if (productIds.length === 0) return;

    // Fetch guide data from REST API (same endpoint used by guide-system.php)
    const restUrl = window.mstData?.restUrl || '/wp-json/';
    $.ajax({
      url: restUrl + 'mst/v1/guides/' + productIds.join(','),
      type: 'GET',
      success: function(guides) {
        // Inject guide photos for each product
        $carouselCards.each(function() {
          const $card = $(this);
          const productId = $card.data('product-id');
          const $placeholder = $card.find('.mst-woo-carousel-guide-placeholder');
          
          if ($placeholder.length > 0 && guides[productId]) {
            const guide = guides[productId];
            
            // Create guide photo element
            const guideHTML = `
              <a href="${guide.url}" 
                 class="mst-woo-carousel-guide-inside" 
                 onclick="event.stopPropagation();">
                <img src="${guide.avatar}" 
                     alt="${guide.name}" 
                     style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
              </a>
            `;
            
            $placeholder.html(guideHTML);
          }
        });
      },
      error: function(xhr, status, error) {
        console.log('Guide fetch error:', error);
      }
    });
  }

  // Initialize guide injection
  initWooCarouselGuides();
  
  // Re-initialize on Elementor preview updates
  if (typeof elementorFrontend !== 'undefined') {
    elementorFrontend.hooks.addAction('frontend/element_ready/widget', function() {
      setTimeout(initWooCarouselGuides, 100);
    });
  }

  // Fallback for non-Elementor pages
  $(document).ready(function() {
    if (typeof elementorFrontend === 'undefined') {
      initAll();
    }
  });

})(jQuery);
