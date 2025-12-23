document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('#wcaf-filters-form');

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const data = new FormData(form);

        fetch('/wp-admin/admin-ajax.php?action=wcaf_filter_products', {
            method: 'POST',
            body: data,
        })
        .then(res => res.json())
        .then(response => {
            document.querySelector('#product-results').innerHTML = response.html;
        });
    });
});