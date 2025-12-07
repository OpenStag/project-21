<?php
// Get current page for active link highlighting
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/navbar.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <!-- Logo -->
            <div class="nav-logo">
                <a href="index.php" class="logo-link">
                    <span class="logo-text">DramaLand</span>
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="nav-links">
                <a href="category.php" class="nav-link <?php echo $current_page == 'category.php' ? 'active' : ''; ?>">
                    Categories
                </a>
                <a href="search.php" class="nav-link <?php echo $current_page == 'search.php' ? 'active' : ''; ?>">
                    Search
                </a>
                <a href="apps.php" class="nav-link <?php echo $current_page == 'apps.php' ? 'active' : ''; ?>">
                    Apps
                </a>
                <div class="profile-icon">
        <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png">
    </div>
                
            </div>

            <!-- Mobile Menu Toggle -->
            <button class="mobile-menu-toggle" id="mobileMenuToggle">
                <span class="toggle-bar"></span>
                <span class="toggle-bar"></span>
                <span class="toggle-bar"></span>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div class="mobile-menu" id="mobileMenu">
            <div class="mobile-menu-header">
                <span class="mobile-menu-title">Menu</span>
                <button class="mobile-menu-close" id="mobileMenuClose">
                    <span class="close-icon">×</span>
                </button>
            </div>
            <div class="mobile-menu-links">
                <a href="index.php" class="mobile-nav-link <?php echo $current_page == 'index.php' ? 'active' : ''; ?>">
                    Home
                </a>
                <a href="categories.php" class="mobile-nav-link <?php echo $current_page == 'categories.php' ? 'active' : ''; ?>">
                    Categories
                </a>
                <a href="search.php" class="mobile-nav-link <?php echo $current_page == 'search.php' ? 'active' : ''; ?>">
                    Search
                </a>
                <a href="apps.php" class="mobile-nav-link <?php echo $current_page == 'apps.php' ? 'active' : ''; ?>">
                    Apps
                </a>
            </div>
        </div>
    </nav>

    <script>
        // Mobile menu functionality
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const mobileMenuClose = document.getElementById('mobileMenuClose');
            const mobileMenu = document.getElementById('mobileMenu');
            
            // Toggle mobile menu
            if (mobileMenuToggle) {
                mobileMenuToggle.addEventListener('click', function() {
                    mobileMenu.classList.add('active');
                    document.body.style.overflow = 'hidden';
                });
            }
            
            // Close mobile menu
            if (mobileMenuClose) {
                mobileMenuClose.addEventListener('click', function() {
                    mobileMenu.classList.remove('active');
                    document.body.style.overflow = '';
                });
            }
            
            // Close mobile menu when clicking outside
            document.addEventListener('click', function(event) {
                if (!mobileMenu.contains(event.target) && !mobileMenuToggle.contains(event.target)) {
                    mobileMenu.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });
            
            // Close mobile menu on window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    mobileMenu.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });
            
            // Add click event to mobile menu links
            const mobileLinks = document.querySelectorAll('.mobile-nav-link');
            mobileLinks.forEach(link => {
                link.addEventListener('click', function() {
                    mobileMenu.classList.remove('active');
                    document.body.style.overflow = '';
                });
            });
        });
    </script>
</body>
</html>