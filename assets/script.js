window.onload = function(){
    
    let button = document.getElementById(showHistory)
    let history = document.getElementsByClassName(history)
    
    button.addEventListener("click", (e) => {
        history.style.display = block;
        console.log("cc");
    })
}