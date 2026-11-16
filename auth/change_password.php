<?php
require_once __DIR__ . '/../includes/auth.php';
$title = 'Change Password';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $a = row($conn, "SELECT * FROM admins WHERE id=?", [(int)$_SESSION['admin_id']]);
    $new = $_POST['new'] ?? '';
    if (!password_verify($_POST['current'] ?? '', $a['password'])) flash('Current password is wrong', 'danger');
    elseif (strlen($new) < 6) flash('New password must be at least 6 characters', 'danger');
    elseif ($new !== ($_POST['confirm'] ?? '')) flash('Passwords do not match', 'danger');
    else {
        run($conn, "UPDATE admins SET password=? WHERE id=?", [password_hash($new, PASSWORD_DEFAULT), (int)$a['id']]);
        flash('Password changed');
    }
    redirect('change_password.php');
}
include __DIR__ . '/../includes/header.php';
?>
<h4 class="mb-3">Change Password</h4>
<form method="post" class="card card-body" style="max-width:400px">
  <input class="form-control mb-2" type="password" name="current" placeholder="Current password" required>
  <input class="form-control mb-2" type="password" name="new" placeholder="New password" required>
  <input class="form-control mb-3" type="password" name="confirm" placeholder="Confirm new password" required>
  <button class="btn btn-dark">Update</button>
</form>
<?php include __DIR__ . '/../includes/footer.php'; ?>
