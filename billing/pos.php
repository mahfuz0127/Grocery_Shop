<?php
require_once __DIR__ . '/../includes/auth.php';
$title = 'New Sale';
$extra_js = '/assets/js/pos.js';
include __DIR__ . '/../includes/header.php';
?>
<div id="pos" data-base="<?= BASE_URL ?>">
<h4 class="mb-3">New Sale</h4>
<div class="row g-3">
  <div class="col-lg-5">
    <div class="card"><div class="card-body">
      <input id="search" class="form-control mb-2" placeholder="Search name or scan barcode, press Enter" autocomplete="off" autofocus>
      <div id="results" class="list-group"></div>
    </div></div>
  </div>
  <div class="col-lg-7">
    <form method="post" action="save_sale.php" class="card"><div class="card-body">
      <table class="table table-sm align-middle">
        <thead><tr><th>Item</th><th>Qty</th><th class="text-end">Total</th><th></th></tr></thead>
        <tbody id="cart-body"></tbody>
      </table>
      <div class="row g-2">
        <div class="col-6"><label class="form-label mb-0">Customer (optional)</label>
          <input class="form-control form-control-sm" name="customer"></div>
        <div class="col-6"><label class="form-label mb-0">Payment</label>
          <select class="form-select form-select-sm" name="method"><option>Cash</option><option>Card</option><option>Mobile</option></select></div>
        <div class="col-6"><label class="form-label mb-0">Discount</label>
          <input id="discount" name="discount" type="number" min="0" step="0.01" value="0" class="form-control form-control-sm"></div>
        <div class="col-6"><label class="form-label mb-0">Amount paid</label>
          <input id="paid" name="paid" type="number" min="0" step="0.01" class="form-control form-control-sm" placeholder="blank = exact"></div>
      </div>
      <hr>
      <div class="d-flex justify-content-between"><span>Subtotal</span><span id="subtotal">0.00</span></div>
      <div class="d-flex justify-content-between fs-5 fw-bold"><span>Total</span><span id="total">0.00</span></div>
      <div class="d-flex justify-content-between"><span>Change</span><span id="change">0.00</span></div>
      <div class="mt-3 d-flex gap-2">
        <button id="save" class="btn btn-dark flex-fill" disabled>Save &amp; Print Bill</button>
        <button type="button" id="clear" class="btn btn-outline-danger">Clear</button>
      </div>
    </div></form>
  </div>
</div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
