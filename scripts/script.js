
function login() {
    let username = document.getElementById("username").value;
    let password = document.getElementById("password").value;

    if (username == "" || password == "") {
        alert("Please fill in all fields.");
    } else {
        alert("Login successful (demo only). Welcome to StreetCred!");
    }
}

function logout() {
    // 1. Optional: Clear any frontend session storage data if you use it
    localStorage.clear();
    sessionStorage.clear();

    // 2. Redirect the browser to your PHP logout processing file
    // This breaks the server session immediately
    window.location.href = "../login-page/logout.php";
}

document.addEventListener("DOMContentLoaded", () => {
    const sections = document.querySelectorAll("section, header, .hero");
    const navLinks = document.querySelectorAll(".btn-nav");

    const options = {
        root: null,
        threshold: 0.3, // Highlights the nav button when 30% of the section is visible
        rootMargin: "-80px 0px 0px 0px" // Offsets your sticky navbar height
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                // Get the target identifier from the section's class or ID
                const currentSection = entry.target.getAttribute("id") || entry.target.className.split(" ")[0];
                
                navLinks.forEach((link) => {
                    link.classList.remove("active");
                    // Check if href matches or data-section matches
                    if (link.getAttribute("href") === `#${currentSection}` || 
                       (currentSection === "hero" && link.getAttribute("href") === "#")) {
                        link.classList.add("active");
                    }
                });
            }
        });
    }, options);

    sections.forEach((section) => {
        observer.observe(section);
    });
});

