<script>
  // Input nominal: tampil dengan titik ribuan (1.500.000); server membuang titiknya lagi.
  document.querySelectorAll('.js-rupiah').forEach(function (el) {
    el.addEventListener('input', function () {
      var digit = el.value.replace(/\D/g, '');
      el.value = digit.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    });
  });

  // Petunjuk akun COA mengikuti pilihan jangka waktu
  document.querySelectorAll('.js-hutang-jangka').forEach(function (sel) {
    var hint = sel.parentNode.querySelector('.js-hutang-akun-hint');
    function perbarui() {
      hint.innerHTML = sel.value === 'Panjang'
        ? 'Dicatat di COA: <b>2300 &ndash; Hutang Jangka Panjang</b>'
        : 'Dicatat di COA: <b>2000 &ndash; Utang</b>';
    }
    sel.addEventListener('change', perbarui);
    perbarui();
  });
</script>
