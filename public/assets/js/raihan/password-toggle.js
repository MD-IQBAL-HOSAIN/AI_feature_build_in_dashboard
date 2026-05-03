Array.from(document.querySelectorAll("form .auth-pass-inputgroup")).forEach(function(s){Array.from(s.querySelectorAll(".password-addon")).forEach(function(t){t.addEventListener("click",function(t)
    {   
        let e=s.querySelector(".password-input");"password"===e.type?e.type="text":e.type="password"
    })})});
        