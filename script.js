/* =========================================================
   Discover Saudi Arabia — Client-side script
   - Night mode toggle (saved in localStorage)
   - Gallery search + category filter
   - Form validation (admin login + add/edit)
   ========================================================= */

(function () {
    "use strict";

    /* ---------- Night mode ---------- */
    const NIGHT_KEY = "dsa_night_mode";

    function applySavedTheme() {
        if (localStorage.getItem(NIGHT_KEY) === "1") {
            document.body.classList.add("dark");
            updateToggleLabel(true);
        }
    }

    function updateToggleLabel(isDark) {
        const btn = document.getElementById("nightToggle");
        if (btn) btn.textContent = isDark ? "الوضع النهاري" : "الوضع الليلي";
    }

    function setupNightToggle() {
        const btn = document.getElementById("nightToggle");
        if (!btn) return;
        btn.addEventListener("click", function () {
            const isDark = document.body.classList.toggle("dark");
            localStorage.setItem(NIGHT_KEY, isDark ? "1" : "0");
            updateToggleLabel(isDark);
        });
    }

    /* ---------- Gallery filter ---------- */
    function setupGalleryFilter() {
        const search   = document.getElementById("searchInput");
        const category = document.getElementById("categoryFilter");
        const counter  = document.getElementById("resultsCount");
        const cards    = document.querySelectorAll(".gallery-card");
        if (!search || !category || cards.length === 0) return;

        function applyFilter() {
            const q   = search.value.trim().toLowerCase();
            const cat = category.value;
            let shown = 0;
            cards.forEach(function (card) {
                const name = (card.dataset.name || "").toLowerCase();
                const desc = (card.dataset.desc || "").toLowerCase();
                const c    = card.dataset.category || "";
                const matchesText = !q || name.indexOf(q) !== -1 || desc.indexOf(q) !== -1;
                const matchesCat  = !cat || cat === "all" || c === cat;
                const visible     = matchesText && matchesCat;
                card.style.display = visible ? "" : "none";
                if (visible) shown++;
            });
            if (counter) counter.textContent = "عدد النتائج: " + shown;
        }

        search.addEventListener("input", applyFilter);
        category.addEventListener("change", applyFilter);
        applyFilter();
    }

    /* ---------- Generic form validation ---------- */
    function setError(field, msg) {
        const errEl = field.parentElement.querySelector(".form-error");
        if (errEl) errEl.textContent = msg || "";
    }

    function validateRequired(form) {
        let ok = true;
        form.querySelectorAll("[data-required]").forEach(function (field) {
            const val = (field.value || "").trim();
            if (!val) {
                setError(field, "هذا الحقل مطلوب.");
                ok = false;
            } else {
                setError(field, "");
            }
        });
        return ok;
    }

    function setupLoginValidation() {
        const form = document.getElementById("loginForm");
        if (!form) return;
        form.addEventListener("submit", function (ev) {
            let ok = validateRequired(form);
            const pw = form.querySelector("[name='password']");
            if (pw && pw.value && pw.value.length < 4) {
                setError(pw, "كلمة المرور قصيرة جدًا.");
                ok = false;
            }
            if (!ok) ev.preventDefault();
        });
    }

    function setupContentFormValidation() {
        const form = document.getElementById("contentForm");
        if (!form) return;
        form.addEventListener("submit", function (ev) {
            if (!validateRequired(form)) ev.preventDefault();
        });
    }

    /* ---------- Init ---------- */
    document.addEventListener("DOMContentLoaded", function () {
        applySavedTheme();
        setupNightToggle();
        setupGalleryFilter();
        setupLoginValidation();
        setupContentFormValidation();
    });
})();
