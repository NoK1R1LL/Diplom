<?php
include('db.php');

if (isset($_GET['id'])) {
    $productId = intval($_GET['id']);
    $sql = "SELECT p.name, p.description, c.name AS category, p.quantity, s.shelf_number AS shelf, p.photo 
            FROM products p
            JOIN categories c ON p.category_id = c.id
            JOIN shelves s ON p.shelf_id = s.id
            WHERE p.id = $productId";

    $result = $mysqli->query($sql);
    
    if ($row = $result->fetch_assoc()) {
        $photoPath = 'uploads/' . basename($row["photo"]);
        if (!empty($row["photo"]) && file_exists($photoPath)) {
            $photo = $photoPath;
        } else {
            $photo = "img/no-image.png";
        }

        echo json_encode([
            "success" => true,
            "name" => $row["name"],
            "description" => $row["description"],
            "category" => $row["category"],
            "quantity" => $row["quantity"],
            "shelf" => $row["shelf"],
            "photo" => $photo
        ]);
    } else {
        echo json_encode(["success" => false]);
    }
} else {
    echo json_encode(["success" => false]);
}
?>
