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
document.querySelector("#new_user span").addEventListener("click", function(e){
    if(e.target.className == "span_wygeneruj"){
        document.querySelector("#new_user > span").innerHTML = "Wygeneruj hasło";
        let password = Math.random().toString(36).substring(2, 15) + Math.random().toString(23).substring(2, 5);
        document.querySelector("#new_user_pass").value = password;
        document.querySelector("#new_user_pass2").value = password;
        e.target.innerHTML = e.target.innerHTML+"<br/><br/><span>"+password+"</span><br/>";
    }
})
document.querySelector(".new_user input[type=submit]").addEventListener("click", async function (e) {
    e.preventDefault();
    if(document.querySelector("#new_user_name").value && document.querySelector("#new_user_host").value){
        if(document.querySelector("#new_user_pass").value == document.querySelector("#new_user_pass2").value){
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
        }else{
            document.querySelector(".new_user").scrollTo(0,0);
            document.querySelector(".new_user_error").innerText = "Hasła nie są zgodne!";
        }
    }else{
        document.querySelector(".new_user").scrollTo(0,0);
        document.querySelector(".new_user_error").innerText = "Podaj nazwę użytkownika i hosta!";
    }
})
document.querySelectorAll(".section_checkbox").forEach(el => {
    el.addEventListener("click", function(e){
        if(e.target.checked){
            e.target.parentElement.parentElement.querySelectorAll("input").forEach(input => {
                input.checked = true;
            })
        }else{
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