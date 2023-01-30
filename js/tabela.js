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
window.limit = 50;
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
document.querySelector(".insert_form button").addEventListener("click", function (e) {
    e.preventDefault();
    document.querySelector(".insert_form").style.animation = "slideOutUp 0.4s ease";
    setTimeout(function () {
        document.querySelector(".insert_form").style.display = "none";
    }, 350)
})
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
if (document.querySelector(".insert_response")) {
    document.querySelector(".insert_response").addEventListener("click", function () {
        if (document.querySelector(".insert_response")) {
            document.querySelector(".insert_response").style.display = "none";
        }
    })
}
document.addEventListener("click", function (e) {
    if (e.target.className == "more_btn") {
        let show_type;
        if (e.target.id == "more_btn") {
            window.limit += 50;
            show_type = "more";
        } else {
            window.limit = window.rows_num;
            show_type = "all";
        }
        var xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function () {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                let response_array = JSON.parse(this.response);
                response_array.forEach(row => {
                    let tr = document.createElement("tr");
                    for (i in row) {
                        let td = document.createElement("td");
                        td.innerText = row[i];
                        tr.appendChild(td);
                    }
                    document.querySelector("tbody").appendChild(tr);
                    var left = document.querySelector("table").offsetLeft - 50;
                    document.querySelector("#plus").style.marginLeft = left + "px";
                    document.querySelector("#plus").style.display = "block";
                })
            }
        }
        let last_id = document.querySelector("table tr:last-of-type td:first-of-type").innerText;
        xmlHttp.open("GET", `get_more_rows.php?tabela=${window.tabela}&last_id=${last_id}&type=${show_type}`, true);
        xmlHttp.send(null);
        if (window.limit >= window.rows_num) {
            document.querySelector("#more_btn").remove();
            document.querySelector("#all_btn").remove();
        }
    }
})
document.querySelectorAll("td").forEach(td => {
    td.addEventListener("click", function () {
        if (td.parentElement.className == "tr_focused") {
            td.parentElement.removeAttribute("class");
        } else {
            td.parentElement.classList.add("tr_focused");
        }
    })
})
document.querySelector(".insert_form input[type=submit]").addEventListener("click", async function (e) {
    e.preventDefault();
    decision().then(function () {
        document.querySelector("#insert_form").submit();
    }, function () {
        document.querySelector(".decision").style.animation = "slideOutUp 0.5s ease";
        setTimeout(function () {
            document.querySelector(".decision").remove();
        }, 500)
    });
})