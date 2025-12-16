

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DramaLand Advanced Search</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/search.css">
</head>
<body>


    <div class="main-content">
 
        <section class="advanced-search-container vw-594">
            
            <div class="topic-header">
                <h1>Advanced Search</h1>
            </div>
  
            <div class="search-options">
                <button class="search-btn">Search</button>
                <button class="keywords-btn">Keywords</button>
            </div>

            <form class="search-form" action="" method="GET">
                
                <div class="genre-section">
                    <h3>Genres</h3>
                    <p class="genre-text">Click to include a genre, or double click to exclude a genre</p>
                </div>
                

                <div class="form-row vh-92">
                    <label for="release-date">Release Date</label>
                    <input type="text" id="release-date" name="release-date">
                </div>
                
                <div class="form-row vh-92">
                    <label for="rating">Rating</label>
                    <input type="text" id="rating" name="rating">
                </div>
                
                <div class="form-row vh-92">
                    <label for="episodes">Episodes</label>
                    <input type="text" id="episodes" name="episodes">
                </div>
                
                <div class="form-row vh-92">
                    <label for="people">People</label>
                    <input type="text" id="people" name="people">
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="submit-search-btn vw-142 vh-53">Search</button>
                    <button type="reset" class="reset-btn vw-142 vh-53">Reset</button>
                </div>
            </form>
        </section>


        <section class="results-container vw-617">
            <h2>Results</h2>
            
            <div class="results-box vh-269">
                <?php
                // Check if search was submitted
                if (!empty($_GET)) {
                    // Include database connection
                    require_once __DIR__ . '/../includes/db_connect.php';
                    
                    try {
                        // Get search parameters with proper validation
                        $releaseDate = isset($_GET['release-date']) ? trim($_GET['release-date']) : '';
                        $rating = isset($_GET['rating']) ? trim($_GET['rating']) : '';
                        $episodes = isset($_GET['episodes']) ? trim($_GET['episodes']) : '';
                        $people = isset($_GET['people']) ? trim($_GET['people']) : '';
                        
                        // Build dynamic SQL query based on shows table schema
                        $sql = "SELECT DISTINCT s.*, 
                                       (SELECT GROUP_CONCAT(actor_name SEPARATOR ', ') 
                                        FROM shows_cast WHERE actor_id = s.s_id) as cast_list
                                FROM shows s
                                LEFT JOIN shows_cast sc ON s.s_id = sc.s_id
                                WHERE 1=1";
                        
                        $conditions = [];
                        $types = '';
                        $params = [];
                        
                        // Filter by release date (partial match)
                        if (!empty($releaseDate)) {
                            $sql .= " AND s.Release_date LIKE ?";
                            $types .= 's';
                            $params[] = "%$releaseDate%";
                        }
                        
                        // Filter by category (matches rating field intent)
                        if (!empty($rating)) {
                            $sql .= " AND s.category LIKE ?";
                            $types .= 's';
                            $params[] = "%$rating%";
                        }
                        
                        // Filter by episode count
                        if (!empty($episodes) && is_numeric($episodes)) {
                            $sql .= " AND s.episodes_count = ?";
                            $types .= 'i';
                            $params[] = intval($episodes);
                        }
                        
                        // Filter by people (actor names)
                        if (!empty($people)) {
                            $sql .= " AND sc.actor_name LIKE ?";
                            $types .= 's';
                            $params[] = "%$people%";
                        }
                        
                        // Prepare and execute
                        $stmt = $conn->prepare($sql);
                        if (!empty($params)) {
                            $stmt->bind_param($types, ...$params);
                        }
                        $stmt->execute();
                        $result = $stmt->get_result();
                        
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $imagePath = !empty($row['cover_image']) 
                                    ? '../' . htmlspecialchars($row['cover_image']) 
                                    : '../assets/images/covers/placeholder.jpg';
                                $title = htmlspecialchars($row['title']);
                                $description = htmlspecialchars(substr($row['description'], 0, 100));
                                
                                echo '<div class="result-card">';
                                echo '<div class="results-image-placeholder">';
                                echo '<img src="' . $imagePath . '" alt="' . $title . '" class="results-image" onerror="this.src=\'../assets/images/covers/placeholder.jpg\'">';
                                echo '</div>';
                                echo '<div class="results-details">';
                                echo '<p class="watch-free">Watch Free</p>';
                                echo '<p class="drama-title">' . $title . '</p>';
                                if (!empty($description)) {
                                    echo '<p class="description">' . $description . '...</p>';
                                }
                                echo '<p><strong>Release Date:</strong> ' . htmlspecialchars($row['Release_date']) . '</p>';
                                echo '<p><strong>Category:</strong> ' . htmlspecialchars($row['category']) . '</p>';
                                echo '<p><strong>Episodes:</strong> ' . htmlspecialchars($row['episodes_count']) . '</p>';
                                echo '<p><strong>Running Time:</strong> ' . htmlspecialchars($row['running_time']) . ' min</p>';
                                if (!empty($row['cast_list'])) {
                                    echo '<p><strong>Cast:</strong> ' . htmlspecialchars($row['cast_list']) . '</p>';
                                }
                                echo '</div>';
                                echo '</div>';
                            }
                        } else {
                            echo '<p style="color: #ccc; padding: 20px;">No results found matching your criteria. Try adjusting your search filters.</p>';
                        }
                        
                        $stmt->close();
                        
                    } catch (Exception $e) {
                        echo '<p style="color: #ff4d4d; padding: 20px;">Search error: ' . htmlspecialchars($e->getMessage()) . '</p>';
                    }
                } else {
                    echo '<p style="color: #ccc; padding: 20px; text-align: center;">Enter search criteria above to find shows.</p>';
                }
                ?>
            </div>
        </section>
    </div>

</body>
</html>
