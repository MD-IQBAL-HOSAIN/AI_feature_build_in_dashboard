/* (document.querySelectorAll("[toast-list]") || document.querySelectorAll("[data-choices]") || document.querySelectorAll("[data-provider]")) && (document.writeln("<script type='text/javascript' src='https://cdn.jsdelivr.net/npm/toastify-js'><\/script>")); */
// Check if any of the elements exist

if (document.querySelectorAll("[toast-list]") || document.querySelectorAll("[data-choices]") || document.querySelectorAll("[data-provider]")) {
    // Create script element
    var script = document.createElement("script");
    script.src = "https://cdn.jsdelivr.net/npm/toastify-js";
    script.type = "text/javascript";
    script.async = true;  // Load the script asynchronously
    document.head.appendChild(script);  // Append the script to the document head
}

