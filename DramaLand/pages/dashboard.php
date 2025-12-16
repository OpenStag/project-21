<?php
require_once '../includes/config.php';
require_once '../includes/db_connect.php';

$page_title = 'K-Drama Streaming - DramaLand';

// Fetch featured show (most viewed)
$featured_query = "SELECT * FROM Shows ORDER BY Total_views DESC LIMIT 1";
$featured_result = $conn->query($featured_query);
$featured_show = $featured_result->fetch_assoc();

// Fetch latest shows (12 shows as per requirement)
$latest_query = "SELECT s.*, MAX(sd.Epi_ID) as latest_episode 
                 FROM Shows s 
                 LEFT JOIN Shows_Data sd ON s.S_ID = sd.S_ID 
                 GROUP BY s.S_ID 
                 ORDER BY s.Added_date DESC 
                 LIMIT 12";
$latest_result = $conn->query($latest_query);

// Fetch top K-Dramas by views
$top_query = "SELECT s.*, MAX(sd.Epi_ID) as latest_episode 
              FROM Shows s 
              LEFT JOIN Shows_Data sd ON s.S_ID = sd.S_ID 
              WHERE s.Category = 'K-Drama' 
              GROUP BY s.S_ID 
              ORDER BY s.Total_views DESC 
              LIMIT 3";
$top_result = $conn->query($top_query);

// Fetch popular K-Dramas
$popular_query = "SELECT s.*, MAX(sd.Epi_ID) as latest_episode 
                  FROM Shows s 
                  LEFT JOIN Shows_Data sd ON s.S_ID = sd.S_ID 
                  WHERE s.Category = 'K-Drama' 
                  GROUP BY s.S_ID 
                  ORDER BY s.Total_views DESC 
                  LIMIT 3 OFFSET 3";
$popular_result = $conn->query($popular_query);

// Fetch upcoming shows
$upcoming_query = "SELECT * FROM Shows 
                   WHERE Status = 'ongoing' OR Release_date > CURDATE() 
                   ORDER BY Release_date ASC 
                   LIMIT 3";
$upcoming_result = $conn->query($upcoming_query);
?>

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
    <section class="hero" style="background-image: url('../assets/images/covers/<?php echo $featured_show ? htmlspecialchars($featured_show['Image']) : 'The Trauma Code.jpg'; ?>');">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="hero-title">
                <?php echo $featured_show ? htmlspecialchars($featured_show['Title']) : 'The Trauma Code<br>Heroes on Call'; ?>
            </h1>
            <button class="watch-btn" onclick="window.location.href='../pages/details.php?id=<?php echo $featured_show['S_ID']; ?>'">Watch Now</button>
        </div>
    </section>

    <!-- Latest Update Section -->
    <section class="content-section">
        <div class="container">
            <h2 class="section-title">Latest Update</h2>
            <div class="drama-grid">
                <?php
                // Reset pointer to show first 3 from latest 12
                $latest_result->data_seek(0);
                $count = 0;
                while ($show = $latest_result->fetch_assoc()) {
                    if ($count >= 3) break;
                    ?>
                    <div class="drama-card" onclick="window.location.href='../pages/details.php?id=<?php echo $show['S_ID']; ?>'">
                        <div class="drama-image">
                            <img src="../assets/images/covers/<?php echo htmlspecialchars($show['Image']); ?>" 
                                 alt="<?php echo htmlspecialchars($show['Title']); ?>">
                            <div class="episode-badge">EP <?php echo $show['latest_episode'] ?? $show['Episode']; ?></div>
                        </div>
                        <div class="drama-info">
                            <h3 class="drama-title"><?php echo htmlspecialchars($show['Title']); ?></h3>
                        </div>
                    </div>
                    <?php
                    $count++;
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
                <?php while ($show = $top_result->fetch_assoc()): ?>
                    <div class="drama-card" onclick="window.location.href='../pages/details.php?id=<?php echo $show['S_ID']; ?>'">
                        <div class="drama-image">
                            <img src="../assets/images/covers/<?php echo htmlspecialchars($show['Image']); ?>" 
                                 alt="<?php echo htmlspecialchars($show['Title']); ?>">
                            <div class="episode-badge">EP <?php echo $show['latest_episode'] ?? $show['Episode']; ?></div>
                        </div>
                        <div class="drama-info">
                            <h3 class="drama-title"><?php echo htmlspecialchars($show['Title']); ?></h3>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <!-- Popular K-Dramas Section -->
    <section class="content-section">
        <div class="container">
            <h2 class="section-title">Popular K-Dramas</h2>
            <div class="drama-grid">
                <?php while ($show = $popular_result->fetch_assoc()): ?>
                    <div class="drama-card" onclick="window.location.href='../pages/details.php?id=<?php echo $show['S_ID']; ?>'">
                        <div class="drama-image">
                            <img src="../assets/images/covers/<?php echo htmlspecialchars($show['Image']); ?>" 
                                 alt="<?php echo htmlspecialchars($show['Title']); ?>">
                            <div class="episode-badge">EP <?php echo $show['latest_episode'] ?? $show['Episode']; ?></div>
                        </div>
                        <div class="drama-info">
                            <h3 class="drama-title"><?php echo htmlspecialchars($show['Title']); ?></h3>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <!-- Upcoming K-Dramas Section -->
    <section class="content-section">
        <div class="container">
            <h2 class="section-title">Upcoming K-Dramas to Watch</h2>
            <div class="drama-grid">
                <?php while ($show = $upcoming_result->fetch_assoc()): ?>
                    <div class="drama-card" onclick="window.location.href='../pages/details.php?id=<?php echo $show['S_ID']; ?>'">
                        <div class="drama-image">
                            <img src="../assets/images/covers/<?php echo htmlspecialchars($show['Image']); ?>" 
                                 alt="<?php echo htmlspecialchars($show['Title']); ?>">
                            <div class="date-badge">
                                <?php echo date('M j', strtotime($show['Release_date'])); ?>
                            </div>
                        </div>
                        <div class="drama-info">
                            <h3 class="drama-title"><?php echo htmlspecialchars($show['Title']); ?></h3>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <?php include '../includes/footer.php'; ?>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>