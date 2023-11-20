document.addEventListener('DOMContentLoaded', function () {
    const groupLabel = document.getElementById('group_label');
    const slugInput = document.getElementById('group_slug');
    const helper = document.getElementById('group_slug_help');
    
    const time = slugInput.dataset.time;
    const appUrl = slugInput.dataset.appurl;

    function updateSlugValue(value) {
        const sluggedLabel = slugify(value);
        slugInput.value = sluggedLabel;

        helper.textContent = `Le groupe sera accessible à l'adresse ${appUrl}/group/${time}-${sluggedLabel}`;
    }

    function updateHelperValue(value) {
        helper.textContent = `Le groupe sera accessible à l'adresse ${appUrl}/group/${time}-${value}`;
    }

    groupLabel.addEventListener('input', function (e) {
        const value = e.currentTarget.value;

        updateSlugValue(value);
    })

    slugInput.addEventListener('input', function (e) {
        const value = e.currentTarget.value;

        updateHelperValue(value);
    })

    function slugify(label) {
        return label
            .toLowerCase()
            .trim()
            .replace(/[^\w\s-]/g, '')
            .replace(/[\s_-]+/g, '-')
            .replace(/^-+|-+$/g, '');
    }

    updateSlugValue(groupLabel.value);
    updateHelperValue(slugInput.value);
});