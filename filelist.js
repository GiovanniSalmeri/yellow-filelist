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
        if (this.getAttribute("aria-expanded")=="true") {
            this.setAttribute("aria-expanded", "false");
            ul.setAttribute("aria-hidden", "true"); 
            ul.style.maxHeight = 0;
        } else {
            this.setAttribute("aria-expanded", "true");
            ul.setAttribute("aria-hidden", "false"); 
            var ulHeight = ul.scrollHeight;
            do {
                ul.style.maxHeight = (parseInt(ul.style.maxHeight)+ulHeight)+"px";
                ul = ul.parentElement.parentElement;
            } while (!ul.classList.contains("filelist-collapsible"));
        }
    }
});
