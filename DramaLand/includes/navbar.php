<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
$is_logged_in = isset($_SESSION['user_id']);
$username = $is_logged_in ? $_SESSION['username'] : '';
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <!-- Brand -->
        <a class="navbar-brand" href="index.php">
            <i class="fas fa-play-circle me-2"></i>
            <span class="brand-text"><?php echo Dramaland; ?></span>
        </a>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Search Bar (Mobile) -->
        <div class="d-lg-none w-100 my-2">
            <form class="d-flex" action="pages/search.php" method="GET">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search dramas..." name="q">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <!-- Main Navigation -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" 
                       href="index.php">
                        <i class="fas fa-home me-1"></i> Home
                    </a>
                </li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-list me-1"></i> Categories
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="pages/category.php?genre=Romance">Romance</a></li>
                        <li><a class="dropdown-item" href="pages/category.php?genre=Comedy">Comedy</a></li>
                        <li><a class="dropdown-item" href="pages/category.php?genre=Action">Action</a></li>
                        <li><a class="dropdown-item" href="pages/category.php?genre=Drama">Drama</a></li>
                        <li><a class="dropdown-item" href="pages/category.php?genre=Thriller">Thriller</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="pages/category.php">All Categories</a></li>
                    </ul>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="pages/apps.php">
                        <i class="fas fa-mobile-alt me-1"></i> Apps
                    </a>
                </li>
            </ul>

            <!-- Search Bar (Desktop) -->
            <form class="d-none d-lg-flex me-3" action="pages/search.php" method="GET" style="min-width: 300px;">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search dramas, actors..." name="q">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>

            <!-- User Menu -->
            <ul class="navbar-nav">
                <?php if ($is_logged_in): ?>
                    <!-- Notifications -->
                    <li class="nav-item dropdown">
                        <a class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-bell"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                3
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><h6 class="dropdown-header">Notifications</h6></li>
                            <li><a class="dropdown-item" href="#">New episode available</a></li>
                            <li><a class="dropdown-item" href="#">Show update</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-center" href="#">View All</a></li>
                        </ul>
                    </li>

                    <!-- User Profile -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>
                            <?php echo htmlspecialchars($username); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><h6 class="dropdown-header">My Account</h6></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-heart me-2"></i>Favorites</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-history me-2"></i>Watch History</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                                <li><a class="dropdown-item text-warning" href="admin/dashboard.php">
                                    <i class="fas fa-cog me-2"></i>Admin Panel
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item text-danger" href="auth/logout.php">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <!-- Auth Links -->
                    <li class="nav-item">
                        <a class="nav-link" href="auth/login.php">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary btn-sm ms-2" href="auth/signup.php">
                            Sign Up
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Spacer for fixed navbar -->
<div style="height: 76px;"></div>