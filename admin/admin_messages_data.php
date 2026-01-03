<?php
header('Content-Type: application/json; charset=utf-8');
include("../connect.php");

$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 10;
$search = trim($_GET['search'] ?? '');

$where = "1";
if ($search !== '') {
    $s = mysqli_real_escape_string($conn, $search);
    $where .= " AND (name LIKE '%$s%' OR email LIKE '%$s%' OR message LIKE '%$s%')";
}

$start = ($page - 1) * $perPage;

$totalRes = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM messages WHERE $where");
$totalRows = intval(mysqli_fetch_assoc($totalRes)['cnt']);
$totalPages = max(1, ceil($totalRows / $perPage));

$q = mysqli_query($conn, "SELECT m.*, u.name AS username FROM messages m LEFT JOIN users u ON u.id = m.user_id WHERE $where ORDER BY created_at DESC LIMIT $start, $perPage");

ob_start();
while ($row = mysqli_fetch_assoc($q)) {
    $id = intval($row['id']);
    $user = $row['username'] ? htmlspecialchars($row['username']) : "Guest";
    $name = htmlspecialchars($row['name']);
    $email = htmlspecialchars($row['email']);
    $message = htmlspecialchars($row['message']);
    $created = htmlspecialchars($row['created_at']);
    ?>
    <tr>
        <td><?= $id ?></td>
        <td><?= $user ?></td>
        <td><?= $name ?></td>
        <td><?= $email ?></td>
        <td><?= substr($message,0,50) ?>...</td>
        <td><?= $created ?></td>
        <td class="text-center">
            <button class="btn btn-info btn-sm btn-view" data-message="<?= $message ?>" data-name="<?= $name ?>">View</button>
            <button class="btn btn-danger btn-sm btn-delete my-1" data-id="<?= $id ?>">Delete</button>
        </td>
    </tr>
    <?php
}
$rows = ob_get_clean();

$pag = '';
for ($p=1;$p<=$totalPages;$p++){
    $active = $p==$page?'active':'';
    $pag .= "<a href='#' class='pagination-btn $active' data-page='$p'>$p</a> ";
}

echo json_encode(['rows'=>$rows,'pagination'=>$pag,'total'=>$totalRows], JSON_UNESCAPED_UNICODE);
exit;
?>
