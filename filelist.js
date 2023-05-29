// Filelist extension, https://github.com/GiovanniSalmeri/yellow-filelist

"use strict";
document.addEventListener("DOMContentLoaded", function() {
    var lis = document.querySelectorAll("ul.filelist-collapsible li.filelist-directory");
    lis.forEach(function(li, i) {
        var liSpan = li.firstChild;
        var ul = liSpan.nextElementSibling;
        var id = "filelist-panel-"+i;
        var button = document.createElement("button");
        button.textContent = liSpan.textContent;
        button.setAttribute("aria-expanded", "false");
        button.setAttribute("aria-controls", id);
        button.addEventListener("click", toggle);
        liSpan.replaceWith(button);
        ul.setAttribute("aria-hidden", "true");
        ul.id = id;
        ul.style.maxHeight = 0;
    });

    function toggle() {
        var ul = this.nextElementSibling;
        var ulHeight = ul.scrollHeight;
        var close = this.getAttribute("aria-expanded")=="true";
        this.setAttribute("aria-expanded", !close);
        ul.setAttribute("aria-hidden", close); 
        do {
            ul.style.maxHeight = (parseInt(ul.style.maxHeight)+(ulHeight*(close ? -1 : 1)))+"px";
            ul = ul.parentElement.parentElement;
        } while (!ul.classList.contains("filelist-collapsible"));
    }
});
