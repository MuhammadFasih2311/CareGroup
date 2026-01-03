<?php
header("Content-Type: application/json");
include("../connect.php");

$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 10;

$search = trim($_GET['search'] ?? '');
$name = trim($_GET['name'] ?? '');
$city = trim($_GET['city'] ?? '');

$where = "1";

if ($search !== '') {
    $s = mysqli_real_escape_string($conn, $search);
    $where .= " AND (name LIKE '%$s%' OR address LIKE '%$s%' OR city LIKE '%$s%')";
}

if ($name !== '') {
    $n = mysqli_real_escape_string($conn, $name);
    $where .= " AND name='$n'";
}

if ($city !== '') {
    $c = mysqli_real_escape_string($conn, $city);
    $where .= " AND city='$c'";
}

$start = ($page - 1) * $perPage;

$totalRes = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM hospitals WHERE $where");
$totalRows = mysqli_fetch_assoc($totalRes)['cnt'];
$totalPages = ceil($totalRows / $perPage);

$q = mysqli_query($conn, "SELECT * FROM hospitals WHERE $where ORDER BY id DESC LIMIT $start, $perPage");

ob_start();
while ($row = mysqli_fetch_assoc($q)) {
?>
<tr>
    <td><img src="../<?= htmlspecialchars($row['image']); ?>" class="doc-thumb"></td>
    <td><?= htmlspecialchars($row['name']); ?></td>
    <td><?= htmlspecialchars($row['address']); ?></td>
    <td><?= htmlspecialchars($row['city']); ?></td>
    <td><?= htmlspecialchars($row['created_at']); ?></td>
    <td class="text-center">
        <a href="hospital_edit.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
        <button class="btn btn-info btn-sm btn-copy my-1" data-id="<?= $row['id']; ?>">Copy</button>
        <button class="btn btn-danger btn-sm btn-delete" data-id="<?= $row['id']; ?>">Delete</button>
    </td>
</tr>
<?php
}
$rows = ob_get_clean();

$pagination = "";
for ($p = 1; $p <= $totalPages; $p++) {
    $active = ($p == $page) ? "active" : "";
    $pagination .= "<a href='#' class='pagination-btn $active' data-page='$p'>$p</a> ";
}

echo json_encode([
    "rows" => $rows,
    "pagination" => $pagination,
    "total" => $totalRows
]);
exit;
?>
