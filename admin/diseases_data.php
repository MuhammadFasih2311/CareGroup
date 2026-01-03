<?php
header('Content-Type: application/json; charset=utf-8');
include("../connect.php");

$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 10;

$search = trim($_GET['search'] ?? '');
$category = trim($_GET['category'] ?? '');

$where = "1";
if ($search !== '') {
    $s = mysqli_real_escape_string($conn, $search);
    $where .= " AND (name LIKE '%$s%' OR description LIKE '%$s%')";
}

if ($category !== '') {
    $cat = mysqli_real_escape_string($conn, $category);
    $where .= " AND category='$cat'";
}

$start = ($page - 1) * $perPage;

$totalRes = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM diseases WHERE $where");
$totalRows = intval(mysqli_fetch_assoc($totalRes)['cnt']);
$totalPages = max(1, ceil($totalRows / $perPage));

$q = mysqli_query($conn, "SELECT * FROM diseases WHERE $where ORDER BY created_at DESC LIMIT $start, $perPage");

ob_start();
while ($row = mysqli_fetch_assoc($q)) {
    $img = htmlspecialchars($row['image']);
    $name = htmlspecialchars($row['name']);
    $description = htmlspecialchars($row['description']);
    $created = htmlspecialchars($row['created_at']);
    $id = intval($row['id']);
    ?>
    <tr>
        <td><img src="../<?= $img ?>" alt="" class="doc-thumb"></td>
        <td><?= $name ?></td>
        <td><?= (strlen($description) > 80 ? substr($description, 0, 80) . '...' : $description) ?></td>
        <td><?= $created ?></td>
        <td class="text-center">
            <a href="disease_edit.php?id=<?= $id ?>" class="btn btn-sm btn-warning">Edit</a>
            <button class="btn btn-sm btn-info btn-copy my-1" data-id="<?= $id ?>">Copy</button>
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
