document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('.compare-form');
    const compareLink = document.getElementById('compare-link');

    // Keep track of all entered IDs
    let allIds = [];

    const input = document.getElementById('ids');

    input.addEventListener('paste', () => {
        // Allow paste to happen first
        setTimeout(() => {
            input.value = decodeURIComponent(input.value);
        }, 0);
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const raw = document.getElementById('ids').value.trim();
        if (!raw) return;

        // Split and clean new IDs
        const newIds = raw
            .split(',')
            .map(id => id.trim())
            .filter(Boolean);

        // Append new IDs, avoiding duplicates
        newIds.forEach(id => {
            if (!allIds.includes(id)) allIds.push(id);
        });

        if (allIds.length === 0) return;

        // Build the URL
        const baseUrl = 'https://emporiosurfaces.local/support/recommendations/batchid/';
        const builtUrl = baseUrl + encodeURIComponent(allIds.join(','));

        // Update link
        compareLink.href = builtUrl;
        compareLink.textContent = builtUrl;

        // Clear input
        document.getElementById('ids').value = '';
        document.getElementById('ids').focus();
    });
});