// JavaScript-Code für das Backend
// ----------------------------------------------

// Funktion zum Überprüfen der Eingabeformulare im Backend
function validateForm() {
    var email = document.getElementById("email").value;
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;

    if (email === "" || username === "" || password === "") {
        alert("Bitte füllen Sie alle Felder aus.");
        return false;
    }
}

// JavaScript-Code für das Frontend
// ----------------------------------------------

$(".form")
    .find("input, textarea")
    .on("keyup blur focus", function (e) {
        var $this = $(this),
            label = $this.prev("label");

        if (e.type === "keyup") {
            if ($this.val() === "") {
                label.removeClass("active highlight");
            } else {
                label.addClass("active highlight");
            }
        } else if (e.type === "blur") {
            if ($this.val() === "") {
                label.removeClass("active highlight");
            } else {
                label.removeClass("highlight");
            }
        } else if (e.type === "focus") {
            if ($this.val() === "") {
                label.removeClass("highlight");
            } else if ($this.val() !== "") {
                label.addClass("highlight");
            }
        }
    });

$(".tab a").on("click", function (e) {
    e.preventDefault();

    $(this).parent().addClass("active");
    $(this).parent().siblings().removeClass("active");

    target = $(this).attr("href");

    $(".tab-content > div").not(target).hide();

    $(target).fadeIn(600);
});

window.addEventListener("DOMContentLoaded", (event) => {
    const inputFields = document.querySelectorAll(
        ".field-wrap input, .field-wrap textarea"
    );

    inputFields.forEach((input) => {
        input.addEventListener("input", (event) => {
            const inputField = event.target;
            const label = inputField.nextElementSibling;

            if (inputField.value !== "") {
                label.classList.add("filled");
            } else {
                label.classList.remove("filled");
            }
        });

        if (input.value !== "") {
            input.nextElementSibling.classList.add("filled");
        }
    });
});

// JavaScript-Code für das Frontend des Background
// ----------------------------------------------


// JavaScript-Code für das Modal
// ----------------------------------------------

document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("searchModal");
    const closeModal = document.getElementById("closeModal");
    const searchInput = document.querySelector("input[name='search']");

    searchInput.addEventListener("click", function () {
        modal.style.display = "block";
    });

    closeModal.addEventListener("click", function () {
        modal.style.display = "none";
    });

    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });
});

$(document).ready(function () {
    // Suche nach dem Suchfeld und füge einen Klick-Event-Handler hinzu
    $("#searchInput").click(function () {
        // Zeige das Modalfenster an
        $("#searchModal").modal("show");
    });
});

// JavaScript-Code für das Echtzeitanzeige
// ----------------------------------------------

function updateRealTime() {
    const realTimeElement = document.getElementById('realTime');
    const currentTime = new Date().toLocaleTimeString();
    realTimeElement.textContent = `Current time: ${currentTime} Uhr`;
}

setInterval(updateRealTime, 1000); // Update every second

// JavaScript-Code für das Repeat-Button
// ----------------------------------------------

document.getElementById('deleteButton').addEventListener('click', function () {
    var confirmed = confirm('Sind Sie sicher, dass Sie diesen Artikel löschen möchten?');
    if (confirmed) {
        window.location.href = '/article.php?id=<?php echo $articleId; ?>';
    }
});

// JavaScript-Code für das Email-Formular
// ----------------------------------------------

/* paste this line in verbatim */
window.formbutton=window.formbutton||function(){(formbutton.q=formbutton.q||[]).push(arguments)};
/* customize formbutton below*/
formbutton("create", {
    action: "https://formspree.io/f/mnqkdzbb",
    title: "Wie kann ich Ihnen helfen?",
    fields: [
        {
            type: "email",
            label: "Email:",
            name: "email",
            required: true,
            placeholder: "your@email.com"
        },
        {
            type: "textarea",
            label: "Message:",
            name: "message",
            placeholder: "Was ist Ihr Anliegen?..",
        },
        { type: "submit" }
    ],
    styles: {
        title: {
            backgroundColor: "gray"
        },
        button: {
            backgroundColor: "gray"
        }
    }
});

function showConfirmation() {
    return confirm("Möchten Sie das Formular wirklich absenden?");
}

document.addEventListener("DOMContentLoaded", function () {
    const cards = document.querySelectorAll(".card");
    cards.forEach(card => card.classList.add("show"));
});
