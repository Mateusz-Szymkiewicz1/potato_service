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
document.querySelector("#plus").addEventListener("click", function () {
    if (document.querySelector(".new_user").style.display == "none") {
        document.querySelector(".new_user").style.animation = "slideInDown 0.4s ease";
        document.querySelector(".new_user").style.display = "block";
    } else {
        document.querySelector(".new_user").style.animation = "slideOutUp 0.4s ease";
        setTimeout(function () {
            document.querySelector(".new_user").style.display = "none";
        }, 350)
    }
})
document.querySelector(".new_user button").addEventListener("click", function (e) {
    e.preventDefault();
    if (document.querySelector(".decision")) {
        document.querySelector(".decision").remove();
    }
    document.querySelector(".new_user").style.animation = "slideOutUp 0.4s ease";
    setTimeout(function () {
        document.querySelector(".new_user").style.display = "none";
    }, 350)
})
document.querySelector("#new_user span").addEventListener("click", function (e) {
    if (e.target.className == "span_wygeneruj") {
        document.querySelector("#new_user > span").innerHTML = "Wygeneruj hasło";
        let password = Math.random().toString(36).substring(2, 15) + Math.random().toString(23).substring(2, 5);
        document.querySelector("#new_user_pass").value = password;
        document.querySelector("#new_user_pass2").value = password;
        e.target.innerHTML = e.target.innerHTML + "<br/><br/><span>" + password + "</span><br/>";
    }
})
document.querySelector(".new_user input[type=submit]").addEventListener("click", async function (e) {
    e.preventDefault();
    if (document.querySelector("#new_user_name").value && document.querySelector("#new_user_host").value) {
        if (document.querySelector("#new_user_pass").value == document.querySelector("#new_user_pass2").value) {
            if (!document.querySelector(".decision")) {
                decision().then(function () {
                    document.querySelector("#new_user").submit();
                }, function () {
                    document.querySelector(".decision").style.animation = "slideOutUp 0.5s ease";
                    setTimeout(function () {
                        document.querySelector(".decision").remove();
                    }, 500)
                });
            }
        } else {
            document.querySelector(".new_user").scrollTo(0, 0);
            document.querySelector(".new_user_error").innerText = "Hasła nie są zgodne!";
        }
    } else {
        document.querySelector(".new_user").scrollTo(0, 0);
        document.querySelector(".new_user_error").innerText = "Podaj nazwę użytkownika i hosta!";
    }
})
document.querySelectorAll(".section_checkbox").forEach(el => {
    el.addEventListener("click", function (e) {
        if (e.target.checked) {
            e.target.parentElement.parentElement.querySelectorAll("input").forEach(input => {
                input.checked = true;
            })
        } else {
            e.target.parentElement.parentElement.querySelectorAll("input").forEach(input => {
                input.checked = false;
            })
        }
    })
})
if (document.querySelector(".insert_response")) {
    document.querySelector(".insert_response").addEventListener("click", function () {
        if (document.querySelector(".insert_response")) {
            document.querySelector(".insert_response").style.display = "none";
        }
    })
}
document.querySelectorAll("td").forEach(td => {
    td.addEventListener("click", function () {
        if (td.parentElement.className == "tr_focused") {
            td.parentElement.removeAttribute("class");
        } else {
            td.parentElement.classList.add("tr_focused");
        }
    })
})
document.querySelector("#block").addEventListener("click", function () {
    document.querySelector(".delete_input").value = "";
    document.querySelectorAll(".tr_focused").forEach(column => {
        if (document.querySelector(".delete_input").value == "") {
            document.querySelector(".delete_input").value = document.querySelector(".delete_input").value + column.querySelector("td").innerText.trim();
        } else {
            document.querySelector(".delete_input").value = document.querySelector(".delete_input").value + "," + column.querySelector("td").innerText.trim();
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
        window.scrollTo(0, 0);
        document.querySelector("h1 span").innerHTML = `Zaznacz kolumny do usunięcia!`;
    }
})
document.querySelector("#pencil").addEventListener("click", function () {
    let focused = document.querySelectorAll(".tr_focused").length;
    if (document.querySelector(".edit_user").style.display == "none") {
        if(focused == 1){
            document.querySelector(".edit_user h2").innerText = '';
            document.querySelector(".edit_user").style.animation = "slideInDown 0.4s ease";
            document.querySelector(".edit_user").style.display = "block";
            document.querySelector(".edit_user").scrollTo(0, 0);
            document.querySelector(".edit_user").querySelectorAll("input[type=checkbox]").forEach(el => {
                    el.checked = false;
            })
            let tr = document.querySelector(".tr_focused");
            let nazwa = tr.querySelector("td").innerText;
            document.querySelector(".edit_user h2").innerText = `Edytuj użytkownika - ${nazwa}`;
            document.querySelector("#user_name").value = nazwa;
            let privs = tr.querySelectorAll("td")[2].innerText;
            if(privs == "Wszystkie"){
                document.querySelector(".edit_user").querySelectorAll("input[type=checkbox]").forEach(el => {
                    el.checked = true;
                })
            }else if(privs){
                let privs_tab = privs.split(",");
                privs_tab.forEach(priv => {
                    priv = priv.toLowerCase();
                    if(document.querySelector(`#edit_user_${priv}`)){
                        document.querySelector(`#edit_user_${priv}`).checked = true;
                    }
                })
            }
            let max_queries = parseInt(tr.querySelectorAll("td")[4].innerText);
            let max_updates = parseInt(tr.querySelectorAll("td")[5].innerText);
            let max_conns = parseInt(tr.querySelectorAll("td")[6].innerText);
            let max_user_conns = parseInt(tr.querySelectorAll("td")[7].innerText);
            document.querySelector("#edit_user_max_queries").value = max_queries;
            document.querySelector("#edit_user_max_updates").value = max_updates;
            document.querySelector("#edit_user_max_conns").value = max_conns;
            document.querySelector("#edit_user_max_user_conns").value = max_user_conns;
        }else if(focused == 0){
            window.scrollTo(0, 0);
            document.querySelector("h1 span").innerHTML = `Zaznacz użytkownika do edycji!`;
        }else{
            window.scrollTo(0, 0);
            document.querySelector("h1 span").innerHTML = `Zaznacz jednego użytkownika do edycji!`;
        }
    } else {
        document.querySelector(".edit_user").style.animation = "slideOutUp 0.4s ease";
        setTimeout(function () {
            document.querySelector(".edit_user").style.display = "none";
        }, 350)
    }
})
document.querySelector(".edit_user button").addEventListener("click", function (e) {
    e.preventDefault();
    if (document.querySelector(".decision")) {
        document.querySelector(".decision").remove();
    }
    document.querySelector(".edit_user").style.animation = "slideOutUp 0.4s ease";
    setTimeout(function () {
        document.querySelector(".edit_user").style.display = "none";
    }, 350)
})
document.querySelector(".edit_user input[type=submit]").addEventListener("click", async function (e) {
    e.preventDefault();
    if (!document.querySelector(".decision")) {
        decision().then(function () {
            document.querySelector("#edit_user").submit();
        }, function () {
            document.querySelector(".decision").style.animation = "slideOutUp 0.5s ease";
            setTimeout(function () {
                document.querySelector(".decision").remove();
            }, 500)
        });
    }
})
