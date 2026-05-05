</div> <!-- End content -->
</div> <!-- End flex -->
<!-- Footer -->
<footer class="bg-warning text-dark text-center py-3">
    <p class="mb-0">
        &copy; <?= date('Y'); ?> Flour Mill Management System |
        Version 1.0 |
        Developed by <strong>M. USAID</strong> |
        <a href="mailto:muhammadusaid136@gmail.com" target="_blank" class="text-dark text-decoration-none fs-6">
            <i class="fa fa-envelope fa-md"></i> muhammadusaid136@gmail.com
        </a> |
        <a href="https://www.linkedin.com/in/m-usaid-saddiq-110500320/" target="_blank" class="text-dark text-decoration-none mt-2" style="font-size:17px; margin-top: 2px;">
            <i class="fab fa-linkedin fa-md"></i> Muhammad Usaid
        </a> |
        <a href="https://www.github.com/usaid136/" target="_blank" class="text-dark text-decoration-none mt-2" style="font-size:17px; margin-top: 2px;">
            <i class="fab fa-github fa-md"></i> Muhammad Usaid
        </a>
    </p>
</footer>


<!-- Javascript -->
<script>
    const product = document.getElementById('product');
    const rate = document.getElementById('rate');
    const quantity = document.getElementById('quantity');
    const total = document.getElementById('total');

    // Auto fill rate from selected product
    function updateRate() {

        let selectedOption = product.options[product.selectedIndex];
        let productRate = selectedOption.getAttribute('data-rate');

        rate.value = productRate ? productRate : '';

        calculateTotal();
    }

    // Calculate total
    function calculateTotal() {

        let q = parseFloat(quantity.value);
        let r = parseFloat(rate.value);

        if (!isNaN(q) && !isNaN(r)) {
            total.value = q * r;
        } else {
            total.value = '';
        }
    }

    // Events
    product.addEventListener('change', updateRate);
    quantity.addEventListener('input', calculateTotal);
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/script.js"></script>

</body>

</html>