import Sortable from "sortablejs";

/*
---------------------------------------
GLOBAL
---------------------------------------
*/

const placementsInput = document.getElementById("placementsInput");


/*
---------------------------------------
COLLECT CURRENT PLACEMENTS
---------------------------------------
*/

function getPlacements() {

    const placements = [];

    document.querySelectorAll(".semester-drop").forEach(zone => {

        const year = zone.dataset.year;
        const sem = zone.dataset.semester;

        zone.querySelectorAll("[data-id]").forEach(item => {

            placements.push({
                year: year,
                semester: sem,
                type: item.dataset.type,
                id: item.dataset.id
            });

        });

    });

    return placements;

}


/*
---------------------------------------
SAVE TO LOCAL STORAGE
---------------------------------------
*/

function saveLayout() {

    const placements = getPlacements();

    localStorage.setItem(
        "semesterLayout",
        JSON.stringify(placements)
    );

}


/*
---------------------------------------
LOAD LAYOUT
---------------------------------------
*/

function loadLayout(data) {

    data.forEach(p => {

        const zone = document.querySelector(
            `.semester-drop[data-year="${p.year}"][data-semester="${p.semester}"]`
        );

        const item = document.querySelector(
            `[data-type="${p.type}"][data-id="${p.id}"]`
        );

        if (zone && item) {
            zone.appendChild(item);
        }

    });

}


/*
---------------------------------------
RESTORE LAYOUT
---------------------------------------
*/

function restoreLayout() {

    const saved = localStorage.getItem("semesterLayout");

    if (saved) {

        loadLayout(JSON.parse(saved));
        return;

    }

    if (window.dbPlacements) {

        loadLayout(window.dbPlacements);

        // save DB layout into local storage
        localStorage.setItem(
            "semesterLayout",
            JSON.stringify(window.dbPlacements)
        );

    }

}


/*
---------------------------------------
INITIALIZE SORTABLE
---------------------------------------
*/

function initSortable() {

    new Sortable(
        document.getElementById("availableCourses"),
        {
            group: {
                name: "placements",
                pull: true,
                put: false
            },
            sort: false,
            animation: 150
        }
    );

    new Sortable(
        document.getElementById("availableElectives"),
        {
            group: {
                name: "placements",
                pull: true,
                put: false
            },
            sort: false,
            animation: 150
        }
    );

    document.querySelectorAll(".semester-drop").forEach(zone => {

        new Sortable(zone, {

            group: {
                name: "placements",
                pull: true,
                put: true
            },

            animation: 150,

            onAdd: saveLayout,
            onUpdate: saveLayout,
            onRemove: saveLayout

        });

    });

}


/*
---------------------------------------
CLEAR BUTTON
---------------------------------------
*/

function initClearButton() {

    const clearBtn = document.getElementById("clearPlacements");

    if (!clearBtn) return;

    clearBtn.addEventListener("click", () => {

        if (!confirm("Clear all semester placements?")) return;

        const coursesBox = document.getElementById("availableCourses");
        const electivesBox = document.getElementById("availableElectives");

        document.querySelectorAll(".semester-drop [data-id]").forEach(item => {

            if (item.dataset.type === "course") {
                coursesBox.appendChild(item);
            } else {
                electivesBox.appendChild(item);
            }

        });

        localStorage.removeItem("semesterLayout");

    });

}


/*
---------------------------------------
FORM SUBMIT
---------------------------------------
*/

function initSubmit() {

    document.querySelector("form").addEventListener("submit", function (event) {

        const placements = getPlacements();

        if (placements.length === 0) {

            alert("Please assign courses to semesters first.");
            event.preventDefault();
            return;

        }

        const unplacedCourses =
            document.querySelectorAll("#availableCourses [data-id]").length;

        if (unplacedCourses > 0) {

            alert("All courses must be placed before saving.");
            event.preventDefault();
            return;

        }

        console.log(placements);


        console.log(JSON.stringify(placements));

        const hidden = document.getElementById("placementsInput");

        hidden.value = JSON.stringify(placements);

    });
}


/*
---------------------------------------
PAGE LOAD
---------------------------------------
*/

document.addEventListener("DOMContentLoaded", () => {

    restoreLayout();
    initSortable();
    initClearButton();
    initSubmit();

});