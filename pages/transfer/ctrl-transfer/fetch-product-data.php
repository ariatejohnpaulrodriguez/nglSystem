<?php
include '../../includes/conn.php';
$sql = "SELECT s.product_id AS product_id, 
            p.code AS code, 
            p.brand AS brand, 
            p.description AS description, 
            s.current_quantity AS cq
        FROM stocks s
        JOIN products p ON s.product_id = p.product_id
        WHERE s.current_quantity > 0";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($product = $result->fetch_assoc()): ?>
        <option value="<?php echo $product['product_id']; ?>" data-brand="<?php echo htmlspecialchars($product['brand']); ?>"
            data-description="<?php echo htmlspecialchars($product['description']); ?>"
            data-stock="<?php echo $product['cq']; ?>">
            <?php echo $product['code']; ?>
        </option>
    <?php endwhile;
}
$conn->close();
?>