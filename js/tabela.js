window.limit = 50;
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
            document.querySelector(".delete_input").value = column.querySelector("td").innerText.trim();
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
        window.scrollTo(0,0);
        document.querySelector("h1 span").innerHTML = `Zaznacz kolumny do usunięcia!`;
    }
})
document.querySelector("#pencil").addEventListener("click", function () {
    if(document.querySelector(".update_form").style.display == "none"){
        let focused = document.querySelectorAll(".tr_focused").length;
        if (focused == 1) {
            let column = document.querySelector(".tr_focused");
            document.querySelector(".update_form").style.animation = "slideInDown 0.4s ease";
            document.querySelector(".update_form").style.display = "block";
            document.querySelector("h1 span").innerHTML = ``;
            for(let i = 2;i<=column.querySelectorAll("td").length;i++){
                let wartosc = column.querySelector(`td:nth-child(${i})`).innerText;
                document.querySelectorAll(`.update_input`)[i-1].value = wartosc;
            }
            document.querySelector(".update_id").value = column.querySelector(`td`).innerText;
        }else if (focused == 0){
            window.scrollTo(0,0);
            document.querySelector("h1 span").innerHTML = `Zaznacz pole do edycji!`;
        }else{
            window.scrollTo(0,0);
            document.querySelector("h1 span").innerHTML = `Zaznacz tylko jedno pole!`;
        }
    }else{
        if (document.querySelector(".decision")) {
            document.querySelector(".decision").remove();
        }
        document.querySelector(".update_form").style.animation = "slideOutUp 0.4s ease";
        setTimeout(function () {
            document.querySelector(".update_form").style.display = "none";
        }, 350)
    }
})