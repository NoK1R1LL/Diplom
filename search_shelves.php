// Фильтрация по поиску
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Получаем уникальные наименования складов и количество занятых полок
$sql = "SELECT 
            s.id,
            s.name AS shelf_name, 
            COUNT(p.shelf_id) AS occupied_shelves, 
            COALESCE(s.total_shelves, 0) AS total_shelves
        FROM shelves s
        LEFT JOIN products p ON s.id = p.shelf_id
        WHERE s.name LIKE ?
        GROUP BY s.name
        ORDER BY FIELD(s.name, 'Малый склад', 'Большой склад')";

$stmt = $mysqli->prepare($sql);
$searchParam = "%$search%";
$stmt->bind_param("s", $searchParam);
$stmt->execute();
$result = $stmt->get_result();
