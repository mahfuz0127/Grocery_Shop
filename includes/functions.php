<?php
function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function money($n) { return number_format((float)$n, 2); }
function qty($n) { return rtrim(rtrim(number_format((float)$n, 2, '.', ''), '0'), '.'); }
function redirect($url) { header('Location: ' . $url); exit; }

function flash($msg = null, $type = 'success') {
    if ($msg !== null) { $_SESSION['flash'] = [$msg, $type]; return ''; }
    if (!empty($_SESSION['flash'])) {
        [$m, $t] = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return "<div class='alert alert-$t py-2'>" . e($m) . "</div>";
    }
    return '';
}

/* ---- tiny prepared-statement helpers ---- */
function db($conn, $sql, $params = []) {
    $st = mysqli_prepare($conn, $sql);
    if ($params) {
        $types = '';
        foreach ($params as $p) $types .= is_int($p) ? 'i' : (is_float($p) ? 'd' : 's');
        mysqli_stmt_bind_param($st, $types, ...$params);
    }
    mysqli_stmt_execute($st);
    $res = mysqli_stmt_get_result($st);
    return $res ?: null;
}
function rows($conn, $sql, $params = []) {
    $r = db($conn, $sql, $params);
    return $r ? mysqli_fetch_all($r, MYSQLI_ASSOC) : [];
}
function row($conn, $sql, $params = []) { $r = rows($conn, $sql, $params); return $r[0] ?? null; }
function run($conn, $sql, $params = []) { db($conn, $sql, $params); return mysqli_affected_rows($conn); }

/* ---- invoice number: INV-YYYYMMDD-0001 ---- */
function invoice_no($conn) {
    $prefix = 'INV-' . date('Ymd') . '-';
    $r = row($conn, "SELECT COUNT(*) c FROM sales WHERE invoice_no LIKE ?", [$prefix . '%']);
    return $prefix . str_pad($r['c'] + 1, 4, '0', STR_PAD_LEFT);
}
