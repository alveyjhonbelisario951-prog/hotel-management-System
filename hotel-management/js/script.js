document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".alert-close").forEach(function (button) {
        button.addEventListener("click", function () {
            button.closest(".alert").remove();
        });
    });

    document.querySelectorAll(".auto-dismiss").forEach(function (box) {
        setTimeout(function () {
            box.style.transition = "opacity .4s";
            box.style.opacity = "0";
            setTimeout(function () { box.remove(); }, 400);
        }, 3500);
    });

    var roomFormReset = document.getElementById("resetRoomForm");
    if (roomFormReset) {
        roomFormReset.addEventListener("click", function () {
            var form = document.getElementById("roomForm");
            form.elements.room_number.value = "";
            form.elements.room_type.value = "";
            form.elements.capacity.value = "2";
            form.elements.price_per_night.value = "";
            form.elements.description.value = "";
            form.elements.status.value = "Available";

            var errors = document.querySelector(".validation-errors");
            if (errors) errors.remove();
            form.elements.room_number.focus();
        });
    }

    var search = document.getElementById("roomSearch");
    if (!search) return;

    var statusSelect = document.getElementById("statusFilter");
    var typeSelect = document.getElementById("typeFilter");
    var resetButton = document.getElementById("resetFilters");
    var counter = document.getElementById("resultCount");
    var empty = document.getElementById("emptyState");
    var items = Array.prototype.slice.call(document.querySelectorAll("[data-item]"));

    function applyFilters() {
        var term = search.value.trim().toLowerCase();
        var wantedStatus = statusSelect.value;
        var wantedType = typeSelect.value;
        var shown = 0;

        items.forEach(function (item) {
            var matches = (!term || item.dataset.text.indexOf(term) !== -1) &&
                (!wantedStatus || item.dataset.status === wantedStatus) &&
                (!wantedType || item.dataset.type === wantedType);
            item.style.display = matches ? "" : "none";
            if (matches) shown++;
        });

        counter.textContent = "Showing " + shown + " of " + items.length + " rooms";
        if (empty) empty.style.display = items.length > 0 && shown === 0 ? "block" : "none";
    }

    search.addEventListener("input", applyFilters);
    statusSelect.addEventListener("change", applyFilters);
    typeSelect.addEventListener("change", applyFilters);
    resetButton.addEventListener("click", function () {
        search.value = "";
        statusSelect.value = "";
        typeSelect.value = "";
        applyFilters();
        search.focus();
    });

    applyFilters();
});
