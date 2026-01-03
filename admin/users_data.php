<?php
header('Content-Type: application/json; charset=utf-8');
include("../connect.php");

$page = max(1, intval($_GET['page'] ?? 1)); 
$perPage = 10; 

$search = trim($_GET['search'] ?? ''); 

$where = "1"; 

if ($search !== '') {
    $s = mysqli_real_escape_string($conn, $search);
    $where .= " AND (name LIKE '%$s%' OR email LIKE '%$s%')";
}

$start = ($page - 1) * $perPage;

$totalRes = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM users WHERE $where");
$totalRows = intval(mysqli_fetch_assoc($totalRes)['cnt']);
$totalPages = max(1, ceil($totalRows / $perPage));

$q = mysqli_query($conn, "SELECT * FROM users WHERE $where ORDER BY created_at DESC LIMIT $start, $perPage");

ob_start();
while ($row = mysqli_fetch_assoc($q)) {
    $id = intval($row['id']);
    $name = htmlspecialchars($row['name']);
    $email = htmlspecialchars($row['email']);
    $phone = htmlspecialchars($row['phone']);
    $created = htmlspecialchars($row['created_at']);
    ?>
    <tr>
        <td><?= $id ?></td>
        <td><?= $name ?></td>
        <td><?= $email ?></td>
        <td><?= $phone ?></td>
        <td><?= $created ?></td>
        <td class="text-center">
            <button class="btn btn-sm btn-danger btn-delete" data-id="<?= $id ?>">Delete</button>
        </td>
    </tr>
    <?php
}
$rows = ob_get_clean();

$pag = '';
for ($p = 1; $p <= $totalPages; $p++) {
    $active = $p == $page ? 'active' : '';
    $pag .= "<a href='#' class='pagination-btn $active' data-page='$p'>$p</a> ";
}

echo json_encode(['rows' => $rows, 'pagination' => $pag, 'total' => $totalRows], JSON_UNESCAPED_UNICODE);
exit;
?>
