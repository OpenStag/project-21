// Wait for DOM to load
document.addEventListener('DOMContentLoaded', function() {
    
    // Mobile Menu Toggle
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            mobileMenu.classList.toggle('show');
            this.classList.toggle('active');
        });
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!mobileMenuBtn.contains(e.target) && !mobileMenu.contains(e.target)) {
                mobileMenu.classList.remove('show');
                mobileMenuBtn.classList.remove('active');
            }
        });
    }
    
    // User Dropdown Menu
    const userMenuBtn = document.getElementById('userMenuBtn');
    const userDropdown = document.getElementById('userDropdown');
    
    if (userMenuBtn && userDropdown) {
        userMenuBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown.classList.toggle('show');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!userMenuBtn.contains(e.target) && !userDropdown.contains(e.target)) {
                userDropdown.classList.remove('show');
            }
        });
    }
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href !== '#' && href !== '#!') {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });
    
    // Add loading animation to images
    const images = document.querySelectorAll('.show-image img');
    images.forEach(img => {
        img.addEventListener('load', function() {
            this.style.opacity = '1';
        });
        
        // Set initial opacity
        img.style.opacity = '0';
        img.style.transition = 'opacity 0.3s ease';
    });
    
    // Lazy loading for images (if needed for performance)
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        observer.unobserve(img);
                    }
                }
            });
        });
        
        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
    
    // Add scroll effect to navbar
    let lastScroll = 0;
    const navbar = document.querySelector('.navbar');
    
    window.addEventListener('scroll', function() {
        const currentScroll = window.pageYOffset;
        
        if (currentScroll <= 0) {
            navbar.style.boxShadow = 'none';
        } else {
            navbar.style.boxShadow = '0 2px 10px rgba(0,0,0,0.3)';
        }
        
        lastScroll = currentScroll;
    });
    
    // Add hover effect to show cards
    const showCards = document.querySelectorAll('.show-card');
    showCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.zIndex = '10';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.zIndex = '1';
        });
    });
    
    // Search functionality (if search input exists)
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            
            if (query.length >= 2) {
                searchTimeout = setTimeout(() => {
                    performSearch(query);
                }, 500);
            }
        });
    }
    
    // Handle form submissions with AJAX (example)
    const forms = document.querySelectorAll('form[data-ajax="true"]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const action = this.getAttribute('action');
            
            fetch(action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Handle success
                    console.log('Form submitted successfully');
                } else {
                    // Handle error
                    console.error('Form submission failed');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });
});

// Search function
function performSearch(query) {
    // This would typically make an AJAX call to search.php
    console.log('Searching for:', query);
    
    fetch(`api/search.php?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            // Handle search results
            displaySearchResults(data);
        })
        .catch(error => {
            console.error('Search error:', error);
        });
}

// Display search results
function displaySearchResults(results) {
    const searchResults = document.getElementById('searchResults');
    if (!searchResults) return;
    
    if (results.length === 0) {
        searchResults.innerHTML = '<p>No results found</p>';
        return;
    }
    
    let html = '';
    results.forEach(show => {
        html += `
            <div class="search-result-item" onclick="window.location.href='pages/details.php?id=${show.S_ID}'">
                <img src="${show.Image}" alt="${show.Title}">
                <div class="result-info">
                    <h4>${show.Title}</h4>
                    <p>${show.Category}</p>
                </div>
            </div>
        `;
    });
    
    searchResults.innerHTML = html;
}

// Utility function to show notifications
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '1';
        notification.style.transform = 'translateY(0)';
    }, 10);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateY(-20px)';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

// Add CSS for notifications dynamically
const notificationStyle = document.createElement('style');
notificationStyle.textContent = `
    .notification {
        position: fixed;
        top: 80px;
        right: 20px;
        padding: 15px 25px;
        border-radius: 8px;
        color: white;
        font-size: 14px;
        font-weight: 500;
        z-index: 10000;
        opacity: 0;
        transform: translateY(-20px);
        transition: all 0.3s ease;
        max-width: 300px;
    }
    
    .notification-info {
        background: #3498db;
    }
    
    .notification-success {
        background: #2ecc71;
    }
    
    .notification-error {
        background: #e74c3c;
    }
    
    .notification-warning {
        background: #f39c12;
    }
`;
document.head.appendChild(notificationStyle);