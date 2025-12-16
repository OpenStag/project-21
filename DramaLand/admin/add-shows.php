<?php
// Enable mysqli error reporting for easier debugging
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

require_once __DIR__ . '/../includes/db_connect.php';

$message = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Basic input collection
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $release_date = trim($_POST['release_date'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $runtime = trim($_POST['running_time'] ?? '');
        $episodes_count = (int)($_POST['episodes_count'] ?? 0);

        $episode_names = $_POST['episode_name'] ?? [];
        $episode_links = $_POST['episode_link'] ?? [];
        $actor_names   = $_POST['actor_name'] ?? [];

        // Minimal validation
        if ($title === '' || $release_date === '' || $category === '') {
            throw new Exception('Title, Release Date, and Category are required.');
        }

        // Handle cover upload (optional)
        $coverPath = null;
        if (!empty($_FILES['cover_image']['name'])) {
            $uploadsDir = __DIR__ . '/../assets/images/covers';
            if (!is_dir($uploadsDir)) {
                // Try to create directory if missing
                @mkdir($uploadsDir, 0775, true);
            }
            $ext = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);
            $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', strtolower($title));
            $fileName = $safeName . '_' . time() . '.' . $ext;
            $target = $uploadsDir . '/' . $fileName;
            if (!move_uploaded_file($_FILES['cover_image']['tmp_name'], $target)) {
                throw new Exception('Failed to upload cover image.');
            }
            // Path relative for web usage
            $coverPath = 'assets/images/covers/' . $fileName;
        }

        // Start transaction
        $conn->begin_transaction();

        // Insert show/item
        // Align with DB schema: uses `Release_date` instead of `release_year`
        $stmt = $conn->prepare(
            "INSERT INTO shows (title, description, Release_date, category, run_time, episode, image) VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        // Bind Release_date as string to accommodate DATE/VARCHAR
        $stmt->bind_param(
            'sssssis',
            $title,
            $description,
            $release_date,
            $category,
            $runtime,
            $episodes_count,
            $coverPath
        );
        $stmt->execute();
        $showId = $stmt->insert_id;
        $stmt->close();


        // Commit
        $conn->commit();

        $message = "Item '$title' published successfully!";
    } catch (Throwable $e) {
        // Rollback on error and expose a concise error for debugging
        if (isset($conn) && $conn instanceof mysqli) {
            $conn->rollback();
        }
        $error = 'Failed to publish: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Item - DramaLand</title>
    
    <style>
    
        body {
            background-color: #000;
            color: #fff;
            font-family: sans-serif;
            margin: 0;
            display: flex;
            justify-content: center;
        }

        .container {
            width: 100%;
            max-width: 400px; 
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            margin-bottom: 20px;
        }
        .brand { font-weight: bold; font-size: 14px; }
        .nav-links span { margin-left: 10px; cursor: pointer; color: #aaa; }
        .nav-links .active { color: #ff4d4d; }

        h2 { margin-bottom: 20px; }

        /* Form Inputs */
        .form-group { margin-bottom: 12px; }
        
        input, select, textarea {
            width: 100%;
            background-color: #000;
            border: 1px solid #333;
            border-radius: 8px;
            padding: 12px;
            color: #fff;
            box-sizing: border-box;
            font-size: 14px;
        }
        
        input:focus, textarea:focus { outline: 1px solid #555; }

        /* Upload Cover Box */
        .upload-box {
            border: 1px solid #333;
            border-radius: 12px;
            height: 200px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
            cursor: pointer;
            background-color: #050505;
        }
        .upload-box strong { font-size: 16px; }
        .upload-box small { color: #888; margin-top: 5px; }

        .section-box {
            border: 1px solid #333;
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 15px;
        }
        .section-header {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .row-inputs {
            display: flex;
            gap: 10px;
            margin-bottom: 8px;
        }
        .row-inputs input { text-align: center; }

        /* Buttons */
        .add-btn {
            background-color: #1a1a9f;
            color: white;
            border: none;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            margin: 10px auto;
            display: block;
            cursor: pointer;
            font-size: 18px;
            line-height: 25px;
        }

        .publish-btn {
            background-color: #1a1a9f;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 6px;
            width: 150px;
            font-weight: bold;
            display: block;
            margin: 30px auto;
            cursor: pointer;
        }
        .publish-btn:hover { background-color: #2a2abf; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <div class="brand">DramaLand</div>
        <div class="nav-links">
            <span class="active">Dashboard</span>
            <span>Categories</span>
            <span>Search</span>
            <span>Apps</span>
        </div>
    </div>

    <h2>Add new item</h2>

    <?php if(isset($message)) echo "<p style='color:green;'>$message</p>"; ?>
    <?php if(isset($error)) echo "<p style='color:#ff4d4d;'>$error</p>"; ?>

    <form method="POST" enctype="multipart/form-data">
        
        <div class="upload-box" onclick="document.getElementById('cover').click()">
            <strong>Upload cover</strong>
            <small>(190 * 270)</small>
            <input type="file" name="cover_image" id="cover" style="display:none;">
        </div>

        <div class="form-group"><input type="text" name="title" placeholder="Title"></div>
        <div class="form-group"><textarea name="description" placeholder="Description" rows="3"></textarea></div>
        <div class="form-group"><input type="text" name="release_date" placeholder="Release Date (e.g., 2024-03-01)"></div>
        
        <div class="form-group">
            <select name="category">
                <option value="" disabled selected>Category</option>
                <option value="Action">Action</option>
                <option value="Romance">Romance</option>
                <option value="Thriller">Thriller</option>
            </select>
        </div>
        
        <div class="form-group"><input type="text" name="running_time" placeholder="Running timed in min"></div>
        <div class="form-group"><input type="number" name="episodes_count" placeholder="Episodes"></div>

        <div class="section-box">
            <div class="section-header">
                <span>Upload video</span>
                <span>&#8964;</span>
            </div>
            <div id="video-rows">
                <div class="row-inputs">
                    <input type="text" name="episode_name[]" placeholder="episode 1">
                    <input type="text" name="episode_link[]" placeholder="link 1">
                </div>
                <div class="row-inputs">
                    <input type="text" name="episode_name[]" placeholder="episode 2">
                    <input type="text" name="episode_link[]" placeholder="link 2">
                </div>
            </div>
            <button type="button" class="add-btn" onclick="addVideoRow()">+</button>
        </div>

        <div class="section-box">
            <div class="section-header">
                <span>Cast</span>
                <span>&#8964;</span>
            </div>
            <div id="cast-rows">
                <div class="row-inputs">
                    <input type="text" name="actor_name[]" placeholder="actor name 1">
                    <input type="button" value="image" onclick="alert('File upload logic here')">
                </div>
                <div class="row-inputs">
                    <input type="text" name="actor_name[]" placeholder="actor name 2">
                    <input type="button" value="image" onclick="alert('File upload logic here')">
                </div>
            </div>
        </div>

        <button type="submit" class="publish-btn">Publish</button>
    </form>
</div>

<script>
    function addVideoRow() {
        const container = document.getElementById('video-rows');
        const count = container.children.length + 1;
        const div = document.createElement('div');
        div.className = 'row-inputs';
        div.innerHTML = `
            <input type="text" name="episode_name[]" placeholder="episode ${count}">
            <input type="text" name="episode_link[]" placeholder="link ${count}">
        `;
        container.appendChild(div);
    }
</script>

</body>
</html>