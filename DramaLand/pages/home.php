<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>K-Drama Streaming - DramaLand</title>
    <link rel="stylesheet" href="../assets/css/home.css">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>

    <!-- Hero Section -->
    <section class="hero" style="background-image: url('../assets/images/covers/The Trauma Code.jpg');">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="hero-title">The Trauma Code<br>Heroes on Call</h1>
            <button class="watch-btn">Watch Now</button>
        </div>
    </section>

    <!-- Latest Update Section -->
    <section class="content-section">
        <div class="container">
            <h2 class="section-title">Latest Update</h2>
            <div class="drama-grid">
                <?php
                $latest_dramas = [
                    [
                        'title' => 'Bon Appetit: Your Majesty',
                        'episode' => 'EP 7',
                        'image' => '../assets/images/covers/BonAppetite.jpg'
                    ],
                    [
                        'title' => 'Confidence Queen',
                        'episode' => 'EP 3',
                        'image' => '../assets/images/covers/Confidence.jpg'
                    ],
                    [
                        'title' => 'Twelve',
                        'episode' => 'EP 7',
                        'image' => '../assets/images/covers/twelve.jpg'
                    ]
                ];

                foreach ($latest_dramas as $drama) {
                    ?>
                    <div class="drama-card">
                        <div class="drama-image-wrapper">
                            <img src="<?php echo htmlspecialchars($drama['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($drama['title']); ?>" 
                                 class="drama-image" 
                                 loading="lazy">
                            <div class="show-overlay">
                              
                            
                            </div>
                            <div class="episode-badge"><?php echo htmlspecialchars($drama['episode']); ?></div>
                        </div>
                        <div class="drama-info">
                            <h3 class="drama-title"><?php echo htmlspecialchars($drama['title']); ?></h3>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Top K-Dramas Section -->
    <section class="content-section">
        <div class="container">
            <h2 class="section-title">Top K-Dramas</h2>
            <div class="drama-grid">
                <?php
                $top_dramas = [
                    [
                        'title' => 'The Glory',
                        'episode' => 'EP 16',
                        'image' => '../assets/images/covers/Theglory.jpg'
                    ],
                    [
                        'title' => 'My Demon',
                        'episode' => 'EP 16',
                        'image' => '../assets/images/covers/Mydemon.png'
                    ],
                    [
                        'title' => 'Study Group',
                        'episode' => 'EP 19',
                        'image' => '../assets/images/covers/studygroup.jpg'
                    ]
                ];

                foreach ($top_dramas as $drama) {
                    ?>
                    <div class="drama-card">
                        <div class="drama-image-wrapper">
                            <img src="<?php echo htmlspecialchars($drama['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($drama['title']); ?>" 
                                 class="drama-image" 
                                 loading="lazy">
                            <div class="show-overlay">
                               
                            </div>
                            <div class="episode-badge"><?php echo htmlspecialchars($drama['episode']); ?></div>
                        </div>
                        <div class="drama-info">
                            <h3 class="drama-title"><?php echo htmlspecialchars($drama['title']); ?></h3>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Popular K-Dramas Section -->
    <section class="content-section">
        <div class="container">
            <h2 class="section-title">Popular K-Dramas</h2>
            <div class="drama-grid">
                <?php
                $popular_dramas = [
                    [
                        'title' => 'Alchemy of Souls',
                        'episode' => 'EP 10',
                        'image' => '../assets/images/covers/alchemyofsouls.jpg'
                    ],
                    [
                        'title' => 'Descendants of the Sun',
                        'episode' => 'EP 16',
                        'image' => '../assets/images/covers/Decendantsofthesun.jpg'
                    ],
                    [
                        'title' => 'Blind',
                        'episode' => 'EP 16',
                        'image' => '../assets/images/covers/BLIND.jpg'
                    ]
                ];

                foreach ($popular_dramas as $drama) {
                    ?>
                    <div class="drama-card">
                        <div class="drama-image-wrapper">
                            <img src="<?php echo htmlspecialchars($drama['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($drama['title']); ?>" 
                                 class="drama-image" 
                                 loading="lazy">
                            <div class="show-overlay">
                               
                            </div>
                            <div class="episode-badge"><?php echo htmlspecialchars($drama['episode']); ?></div>
                        </div>
                        <div class="drama-info">
                            <h3 class="drama-title"><?php echo htmlspecialchars($drama['title']); ?></h3>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Upcoming K-Dramas Section -->
    <section class="content-section">
        <div class="container">
            <h2 class="section-title">Upcoming K-Dramas to Watch</h2>
            <div class="drama-grid">
                <?php
                $upcoming_dramas = [
                    [
                        'title' => 'Genie, Make a Wish',
                        'date' => 'Oct 3',
                        'image' => '../assets/images/covers/makeawish.jpg'
                    ],
                    [
                        'title' => 'To the Moon',
                        'date' => 'Sep 19',
                        'image' => '../assets/images/covers/tothemoon.jpg'
                    ],
                    [
                        'title' => 'Walking on Thin Ice',
                        'date' => 'Sep 19',
                        'image' => '../assets/images/covers/walkingonthinice.jpeg'
                    ]
                ];

                foreach ($upcoming_dramas as $drama) {
                    ?>
                    <div class="drama-card">
                        <div class="drama-image-wrapper">
                            <img src="<?php echo htmlspecialchars($drama['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($drama['title']); ?>" 
                                 class="drama-image" 
                                 loading="lazy">
                            <div class="show-overlay">
                                
                            </div>
                            <div class="date-badge"><?php echo htmlspecialchars($drama['date']); ?></div>
                        </div>
                        <div class="drama-info">
                            <h3 class="drama-title"><?php echo htmlspecialchars($drama['title']); ?></h3>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </section>

    <?php include '../includes/footer.php'; ?>
    
    <script src="../assets/js/main.js"></script>
</body>
</html>