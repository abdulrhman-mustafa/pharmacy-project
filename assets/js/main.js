// Humburger Menu
const humburger = document.querySelector(".humburger");
const navbar = document.querySelector("nav");
const bars = document.querySelector(".fa-bars");
const xmark = document.querySelector(".fa-xmark");

humburger.addEventListener("click", () => {
    navbar.classList.toggle("active");
    bars.classList.toggle("active");
    xmark.classList.toggle("active");
});



// Navgation sticky

window.addEventListener("scroll", function() {
    let header = document.getElementById("sticky");
    
    if (window.scrollY > 200) {
        header.classList.add("sticky");
    } else {
        header.classList.remove("sticky");
    }
});


// btn-scroll


let btn = document.getElementById("btn-scroll");

window.onscroll = function (){
    if(scrollY >= 500) {
        btn.style.display = 'block';
    }else 
    {
        btn.style.display = 'none';
    }
}
    btn.onclick = function() {
        scroll({
            top: 0,
        });
}

// AOS
AOS.init();