<header class="header">
    <?php echo Menu('Productos') ?>
</header>

<main>
    <div class="main_header">
        <h1>Productos</h1>
    </div>
    <div class="main_content">
        <div class="container-card">
            <?php
            $db = new SQLite3('../../server/database.db');
            $sql = "SELECT * FROM products ORDER BY category";
            $result = $db->query($sql);
            while ($row = $result->fetchArray()) {
                $id = $row['id'];
                $category = $row['category'];
                $description = $row['description'];
                $price = $row['price'];
                $image = $row['image'];
                echo "<div class='card'>";
                echo "<div class='card-circle'></div>";
                echo "<img src='../../static/images/productos/$image' alt='$description'>";
                echo "<div class='card-body'>";
                echo "<h3><i class='bi bi-bookmark-fill'></i><span>$category</span></h3>";
                echo "<p>$description</p>";
                //formatear el precio en moneda Colombiana con el formato $ 1,000.00
                echo "<p class='card-price'>$ " . number_format($price, 2) . "</p>";
                echo "</div>";
                echo "</div>";
            }
            ?>
        </div>

    </div>
</main>