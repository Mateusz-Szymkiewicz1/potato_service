document.querySelector("#new_default").addEventListener("change", function (e) {
    if (e.target.value == "defined") {
        document.querySelector("#new_defined_default").removeAttribute("hidden");
    } else {
        document.querySelector("#new_defined_default").hidden = "true";
    }
})
document.querySelector("#updated_default").addEventListener("change", function (e) {
    if (e.target.value == "defined") {
        document.querySelector("#updated_defined_default").removeAttribute("hidden");
    } else {
        document.querySelector("#updated_defined_default").hidden = "true";
    }
})
document.querySelector("#pencil").addEventListener("click", function () {
    if (document.querySelector(".update_form").style.display == "none") {
        let focused = document.querySelectorAll(".tr_focused").length;
        if (focused == 1) {
            let column = document.querySelector(".tr_focused");
            document.querySelector(".update_form").style.animation = "slideInDown 0.4s ease";
            document.querySelector(".update_form").style.display = "block";
            document.querySelector("h1 span").innerHTML = ``;
            document.querySelector("#updated_nazwa").value = column.querySelector("td").innerText.trim();
            document.querySelector("#old_name").value = column.querySelector("td").innerText.trim();
            document.querySelector("#updated_typ").value = column.querySelector("td:nth-child(2)").innerText.trim().split("(")[0];
            document.querySelector("#updated_dlugosc").value = column.querySelector("td:nth-child(2)").innerText.trim().split("(")[1].split(")")[0];
            if (column.querySelector("td:nth-child(3)").innerText == "YES") {
                document.querySelector("#updated_null").checked = true;
            } else {
                document.querySelector("#updated_null").checked = false;
            }
            if (column.querySelector("td:nth-child(4)").innerText == "null") {
                document.querySelector("#updated_default").value = "null";
            } else if (column.querySelector("td:nth-child(4)").innerText == "current_timestamp") {
                document.querySelector("#updated_default").value = "timestamp";
            } else {
                document.querySelector("#updated_defined_default").removeAttribute("hidden");
                document.querySelector("#updated_defined_default").value = column.querySelector("td:nth-child(4)").innerText;
            }
            if (column.querySelector("td:nth-child(5").innerText.includes("auto_increment")) {
                document.querySelector("#updated_ai").checked = true;
            } else {
                document.querySelector("#updated_ai").checked = false;
            }
            if (column.querySelector("td .primary_key")) {
                document.querySelector("#updated_index").value = "PRI";
            } else if (column.querySelector("td .foreign_key")) {
                document.querySelector("#updated_index").value = "UNI";
            } else {
                document.querySelector("#updated_index").value = "";
            }
        } else if (focused == 0) {
            window.scrollTo(0,0);
            document.querySelector("h1 span").innerHTML = `Zaznacz pole do edycji!`;
        } else {
            window.scrollTo(0,0);
            document.querySelector("h1 span").innerHTML = `Zaznacz tylko jedno pole!`;
        }
    } else {
        document.querySelector(".update_form").style.animation = "slideOutUp 0.4s ease";
        setTimeout(function () {
            document.querySelector(".update_form").style.display = "none";
        }, 350)
    }
})
document.querySelector(".insert_form input[type=submit]").addEventListener("click", async function (e) {
    e.preventDefault();
    if (!document.querySelector(".decision")) {
        decision().then(function () {
            document.querySelector("#insert_form").submit();
        }, function () {
            document.querySelector(".decision").style.animation = "slideOutUp 0.5s ease";
            setTimeout(function () {
                document.querySelector(".decision").remove();
            }, 500)
        });
    }
})
document.querySelector("#block").addEventListener("click", function () {
    document.querySelector(".delete_input").value = "";
    document.querySelectorAll(".tr_focused").forEach(column => {
        if (document.querySelector(".delete_input").value == "") {
            document.querySelector(".delete_input").value = document.querySelector(".delete_input").value + "DROP COLUMN " + column.querySelector("td").innerText.trim();
        } else {
            document.querySelector(".delete_input").value = document.querySelector(".delete_input").value + ", DROP COLUMN " + column.querySelector("td").innerText.trim();
        }
    })
    if (document.querySelector(".delete_input").value) {
        if (!document.querySelector(".decision")) {
            decision().then(function () {
                document.querySelector("#delete_form").submit();
            }, function () {
                document.querySelector(".decision").style.animation = "slideOutUp 0.5s ease";
                setTimeout(function () {
                    document.querySelector(".decision").remove();
                }, 500)
            });
        }
    } else {
        window.scrollTo(0,0);
        document.querySelector("h1 span").innerHTML = `Zaznacz kolumny do usunięcia!`;
    }
})