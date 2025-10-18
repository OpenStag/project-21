// Dramaland - Main JavaScript File



$(document).ready(function() {
    // Initialize all components when document is ready
    initNavbar();
    initHeroSection();
    initDramaCards();
    initScrollEffects();
    initSearchFunctionality();
    initResponsiveBehavior();
});

// Navbar Functionality
function initNavbar() {
    console.log('🎬 Dramaland navbar initialized');
    
    // Navbar scroll effect
    var lastScrollTop = 0;
    var navbar = $('.navbar');
    var navbarHeight = navbar.outerHeight();

    $(window).on('scroll', function() {
        var scrollTop = $(this).scrollTop();
        
        if (scrollTop > lastScrollTop && scrollTop > navbarHeight) {
            // Scroll down - hide navbar
            navbar.addClass('navbar-hidden');
        } else {
            // Scroll up - show navbar
            navbar.removeClass('navbar-hidden');
        }
        
        // Add background when scrolled
        if (scrollTop > 100) {
            navbar.addClass('navbar-scrolled');
        } else {
            navbar.removeClass('navbar-scrolled');
        }
        
        lastScrollTop = scrollTop;
    });

    // Mobile menu close on click
    $('.navbar-nav .nav-link').on('click', function() {
        $('.navbar-collapse').collapse('hide');
    });

    // Navbar search functionality
    $('#navbar-search-form').on('submit', function(e) {
        var query = $('#navbar-search-input').val().trim();
        if (query.length < 2) {
            e.preventDefault();
            showNotification('Please enter at least 2 characters to search.', 'warning');
        }
    });

    // User dropdown hover effect
    $('.nav-item.dropdown').hover(
        function() {
            $(this).addClass('show');
            $(this).find('.dropdown-menu').addClass('show');
        },
        function() {
            $(this).removeClass('show');
            $(this).find('.dropdown-menu').removeClass('show');
        }
    );
}

// Hero Section Functionality
function initHeroSection() {
    console.log('🎬 Dramaland hero section initialized');
    
    // Hero section parallax effect
    $(window).on('scroll', function() {
        var scrolled = $(window).scrollTop();
        $('.hero-section').css('transform', 'translateY(' + (scrolled * 0.5) + 'px)');
    });

    // Hero buttons animation
    $('.hero-buttons .btn').hover(
        function() {
            var $btn = $(this);
            $btn.addClass('btn-pulse');
            setTimeout(function() {
                $btn.removeClass('btn-pulse');
            }, 600);
        },
        function() {
            $(this).removeClass('btn-pulse');
        }
    );
}

// Drama Cards Functionality
function initDramaCards() {
    console.log('🎬 Dramaland drama cards initialized');
    
    // Card hover effects
    $('.drama-card').hover(
        function() {
            // Mouse enter
            var $card = $(this);
            $card.addClass('card-hovered');
            
            // Add shimmer effect
            $card.find('.drama-image').append('<div class="card-shimmer"></div>');
            
            // Play subtle animation
            $card.find('.drama-badge, .drama-rank, .upcoming-badge').addClass('animate-badge');
        },
        function() {
            // Mouse leave
            var $card = $(this);
            $card.removeClass('card-hovered');
            $card.find('.card-shimmer').remove();
            $card.find('.drama-badge, .drama-rank, .upcoming-badge').removeClass('animate-badge');
        }
    );

    // Click handler for drama cards
    $('.drama-card').on('click', function(e) {
        if (!$(e.target).is('a, .btn')) {
            var dramaTitle = $(this).find('.drama-title').text();
            var $card = $(this);
            
            showNotification('Loading ' + dramaTitle + '...', 'info');
            
            // Simulate loading and redirect to details page
            setTimeout(function() {
                console.log('Redirecting to ' + dramaTitle + ' details page');
            }, 1000);
        }
    });

    // Favorite button functionality
    $('.favorite-btn').on('click', function(e) {
        e.stopPropagation();
        var $btn = $(this);
        var dramaId = $btn.data('drama-id');
        var isFavorite = $btn.hasClass('active');
        
        toggleFavorite(dramaId, !isFavorite, $btn);
    });
}

// Scroll Effects
function initScrollEffects() {
    console.log('🎬 Dramaland scroll effects initialized');
    
    // Smooth scrolling for anchor links
    $('a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        var target = $(this).attr('href');
        if ($(target).length) {
            $('html, body').stop().animate({
                scrollTop: $(target).offset().top - 80
            }, 1000);
        }
    });

    // Section reveal animation
    var sections = $('section');
    
    function checkScroll() {
        var windowHeight = $(window).height();
        var windowTop = $(window).scrollTop();
        var windowBottom = windowTop + windowHeight;
        
        sections.each(function() {
            var $section = $(this);
            var sectionTop = $section.offset().top;
            var sectionBottom = sectionTop + $section.outerHeight();
            
            if ((sectionBottom >= windowTop) && (sectionTop <= windowBottom)) {
                $section.addClass('section-visible');
            }
        });
    }
    
    // Initial check and scroll event
    checkScroll();
    $(window).on('scroll', checkScroll);
}

// Search Functionality
function initSearchFunctionality() {
    console.log('🎬 Dramaland search functionality initialized');
    
    // Search input auto-complete (simplified)
    $('#navbar-search-input').on('input', debounce(function() {
        var query = $(this).val().trim();
        if (query.length >= 2) {
            showSearchSuggestions(query);
        }
    }, 300));

    // Quick search categories
    $('.search-category').on('click', function() {
        var category = $(this).data('category');
        $('#navbar-search-input').val(category).focus();
    });
}

// Responsive Behavior
function initResponsiveBehavior() {
    console.log('🎬 Dramaland responsive behavior initialized');
    
    // Handle window resize
    var resizeTimer;
    $(window).on('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            updateResponsiveElements();
        }, 250);
    });

    // Mobile-specific behaviors
    if ($(window).width() < 768) {
        enableMobileFeatures();
    }
}

// Utility Functions
function showNotification(message, type) {
    if (type === undefined) {
        type = 'info';
    }
    
    var icons = {
        success: 'fa-check-circle',
        error: 'fa-exclamation-circle',
        warning: 'fa-exclamation-triangle',
        info: 'fa-info-circle'
    };
    
    var notification = $(
        '<div class="notification notification-' + type + '">' +
        '<i class="fas ' + icons[type] + '"></i>' +
        '<span>' + message + '</span>' +
        '<button class="notification-close">&times;</button>' +
        '</div>'
    );
    
    $('body').append(notification);
    
    // Animate in
    notification.slideDown(300);
    
    // Auto remove after 5 seconds
    setTimeout(function() {
        notification.slideUp(300, function() {
            $(this).remove();
        });
    }, 5000);
    
    // Close button
    notification.find('.notification-close').on('click', function() {
        notification.slideUp(300, function() {
            $(this).remove();
        });
    });
}

function toggleFavorite(dramaId, isFavorite, $button) {
    // Simulate API call
    showNotification(isFavorite ? 'Added to favorites' : 'Removed from favorites', 'success');
    
    // Update button state
    $button.toggleClass('active', isFavorite);
    $button.find('i')
        .toggleClass('fas', isFavorite)
        .toggleClass('far', !isFavorite);
}

function showSearchSuggestions(query) {
    console.log('Fetching suggestions for: ' + query);
}

function updateResponsiveElements() {
    var isMobile = $(window).width() < 768;
    
    if (isMobile) {
        $('body').addClass('mobile-view');
    } else {
        $('body').removeClass('mobile-view');
    }
}

function enableMobileFeatures() {
    // Add touch effects for mobile
    $('.drama-card').on('touchstart', function() {
        $(this).addClass('touch-active');
    }).on('touchend', function() {
        $(this).removeClass('touch-active');
    });
}

// Performance optimization: Debounce function
function debounce(func, wait) {
    var timeout;
    return function executedFunction() {
        var context = this;
        var args = arguments;
        var later = function() {
            clearTimeout(timeout);
            func.apply(context, args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Performance optimization: Throttle function
function throttle(func, limit) {
    var inThrottle;
    return function() {
        var context = this;
        var args = arguments;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(function() {
                inThrottle = false;
            }, limit);
        }
    };
}

// Export functions for global access
window.Dramaland = {
    showNotification: showNotification,
    toggleFavorite: toggleFavorite,
    debounce: debounce,
    throttle: throttle
};

console.log('🚀 Dramaland main.js loaded successfully!');