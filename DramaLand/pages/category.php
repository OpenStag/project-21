<?php
include '../includes/db_connect.php';
include '../includes/header.php';
include '../includes/navbar.php';
?>

<link rel="stylesheet" href="../assets/css/category.css">

<div class="container category-section">
    <h2 class="category-title">Browse Categories</h2>

    <div class="row">
        <?php
        $query = "SELECT * FROM categories";
        $result = mysqli_query($conn, $query);

        while ($row = mysqli_fetch_assoc($result)) {
        ?>
            <div class="col-md-3 mb-4">
                <div class="category-card">
                    <h5><?php echo htmlspecialchars($row['category_name']); ?></h5>
                    <a href="#">View Shows</a>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
