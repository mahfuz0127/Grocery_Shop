(() => {
  const root = document.getElementById('pos');
  const url = root.dataset.base + '/billing/cart_actions.php';
  const $ = id => document.getElementById(id);
  const fmt = n => (+n).toFixed(2);
  const esc = s => String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
  let subtotal = 0, timer = null, found = [];

  const api = async data => (await fetch(url, { method: 'POST', body: new URLSearchParams(data) })).json();

  function calc() {
    const d = Math.min(Math.max(parseFloat($('discount').value) || 0, 0), subtotal);
    const total = subtotal - d;
    const paid = parseFloat($('paid').value);
    $('total').textContent = fmt(total);
    $('change').textContent = isNaN(paid) ? '0.00' : fmt(Math.max(paid - total, 0));
    $('save').disabled = subtotal <= 0;
  }

  function render(c) {
    if (c.error) alert(c.error);
    subtotal = c.subtotal;
    $('subtotal').textContent = fmt(subtotal);
    $('cart-body').innerHTML = c.items.length ? c.items.map(i => `
      <tr>
        <td>${esc(i.name)}<div class="small text-muted">${fmt(i.price)}</div></td>
        <td style="width:100px"><input type="number" min="0" step="0.01" class="form-control form-control-sm qty" data-id="${i.id}" value="${i.qty}"></td>
        <td class="text-end">${fmt(i.total)}</td>
        <td><button type="button" class="btn btn-sm btn-outline-danger rm" data-id="${i.id}">&times;</button></td>
      </tr>`).join('') : '<tr><td colspan="4" class="text-center text-muted">Cart is empty</td></tr>';
    calc();
  }

  async function search() {
    const r = await api({ action: 'search', q: $('search').value.trim() });
    found = r.products;
    $('results').innerHTML = found.map(p => `
      <button type="button" class="list-group-item list-group-item-action d-flex justify-content-between add" data-id="${p.id}">
        <span>${esc(p.name)}<small class="text-muted d-block">${esc(p.barcode || '')} · stock ${+p.stock} ${esc(p.unit)}</small></span>
        <strong>${fmt(p.sell_price)}</strong>
      </button>`).join('') || '<div class="text-muted small p-2">No products found</div>';
  }

  async function add(id) {
    render(await api({ action: 'add', id }));
    $('search').focus();
  }

  $('search').addEventListener('input', () => { clearTimeout(timer); timer = setTimeout(search, 250); });
  $('search').addEventListener('keydown', async e => {
    if (e.key !== 'Enter') return;
    e.preventDefault();
    clearTimeout(timer);
    await search();
    if (found.length) { await add(found[0].id); $('search').value = ''; search(); }
  });
  $('results').addEventListener('click', e => { const b = e.target.closest('.add'); if (b) add(b.dataset.id); });
  $('cart-body').addEventListener('change', async e => {
    if (e.target.classList.contains('qty')) render(await api({ action: 'update', id: e.target.dataset.id, qty: e.target.value }));
  });
  $('cart-body').addEventListener('click', async e => {
    const b = e.target.closest('.rm'); if (b) render(await api({ action: 'remove', id: b.dataset.id }));
  });
  $('clear').addEventListener('click', async () => { if (confirm('Clear cart?')) render(await api({ action: 'clear' })); });
  $('discount').addEventListener('input', calc);
  $('paid').addEventListener('input', calc);
  // Prevent Enter in number fields from submitting the form by accident
  root.addEventListener('keydown', e => { if (e.key === 'Enter' && e.target.tagName === 'INPUT') e.preventDefault(); });

  api({ action: 'cart' }).then(render);
  search();
})();
