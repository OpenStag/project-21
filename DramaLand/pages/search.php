

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

                <div class="result-card">
                    <div class="results-image-placeholder">
                        <img src="../assets/images/cast/lastempress1.jpg" alt="" class="results-image">
                    </div>

                    <div class="reults-details"><br>
                        <p class="watch-free">Watch Free</p>
                        <p class="drama-title">The Last Empress</p>
                    </div>
                </div>


            </div>
        </section>
    </div>

</body>
</html>
