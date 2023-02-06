// Decyzja
async function decision() {
    return new Promise(function (resolve, reject) {
        let decision = document.createElement("div");
        decision.classList.add("decision");
        decision.innerHTML = `<span>Na pewno?</span><br /><button id="button_tak">TAK</button><button id="button_nie">NIE</button>`;
        document.body.appendChild(decision);
        decision.style.animation = "slideInDown 0.5s ease";
        document.querySelector("#button_tak").addEventListener("click", function () {
            resolve();
        })
        document.querySelector("#button_nie").addEventListener("click", function () {
            reject();
        })
    })
}
// Zaznacz/Odznacz Wszystko
let zaznacz_counter = 1;
document.querySelector(".zaznacz").addEventListener("click", function (e) {
    if (zaznacz_counter % 2) {
        document.querySelectorAll("tr").forEach(tr => {
            tr.className = "tr_focused";
            e.target.innerText = "Odznacz wszystko";
        })
        document.querySelector("tr").removeAttribute("class");
    } else {
        if (document.querySelector(".tr_focused")) {
            document.querySelectorAll("tr").forEach(tr => {
                tr.removeAttribute("class");
            })
        }
        e.target.innerText = "Zaznacz wszystko";
    }
    zaznacz_counter++;
})
// Responsive ;/
document.querySelector("#plus").style.display = "none";
setTimeout(function () {
    var left = document.querySelector("table").offsetLeft - 50;
    document.querySelector("#plus").style.marginLeft = left + "px";
    document.querySelector("#plus").style.display = "block";
}, 100)
window.addEventListener("resize", function () {
    var left = document.querySelector("table").offsetLeft - 50;
    document.querySelector("#plus").style.marginLeft = left + "px";
    document.querySelector("#plus").style.display = "block";
})
// Plus onclick
document.querySelector("#plus").addEventListener("click", function () {
    if (document.querySelector(".insert_form").style.display == "none") {
        document.querySelector(".insert_form").style.animation = "slideInDown 0.4s ease";
        document.querySelector(".insert_form").style.display = "block";
    } else {
        document.querySelector(".insert_form").style.animation = "slideOutUp 0.4s ease";
        setTimeout(function () {
            document.querySelector(".insert_form").style.display = "none";
        }, 350)
    }
})
// Zamknięcie formularza insert
document.querySelector(".insert_form button").addEventListener("click", function (e) {
    e.preventDefault();
    if (document.querySelector(".decision")) {
        document.querySelector(".decision").remove();
    }
    document.querySelector(".insert_form").style.animation = "slideOutUp 0.4s ease";
    setTimeout(function () {
        document.querySelector(".insert_form").style.display = "none";
    }, 350)
})
// Zamykanie popupów
if (document.querySelector(".insert_response")) {
    document.querySelector(".insert_response").addEventListener("click", function () {
        if (document.querySelector(".insert_response")) {
            document.querySelector(".insert_response").style.display = "none";
        }
    })
}
// Zaznaczanie wierszy
document.querySelectorAll("td").forEach(td => {
    td.addEventListener("click", function () {
        if (td.parentElement.className == "tr_focused") {
            td.parentElement.removeAttribute("class");
        } else {
            td.parentElement.classList.add("tr_focused");
        }
    })
})
// Zamknięcia formularza update
document.querySelector(".update_form button").addEventListener("click", function (e) {
    e.preventDefault();
    if (document.querySelector(".decision")) {
        document.querySelector(".decision").remove();
    }
    document.querySelector(".update_form").style.animation = "slideOutUp 0.4s ease";
    setTimeout(function () {
        document.querySelector(".update_form").style.display = "none";
    }, 350)
})
// Przesłanie formularza update
document.querySelector(".update_form input[type=submit]").addEventListener("click", async function (e) {
    e.preventDefault();
    if (!document.querySelector(".decision")) {
        decision().then(function () {
            document.querySelector("#update_form").submit();
        }, function () {
            document.querySelector(".decision").style.animation = "slideOutUp 0.5s ease";
            setTimeout(function () {
                document.querySelector(".decision").remove();
            }, 500)
        });
    }
})