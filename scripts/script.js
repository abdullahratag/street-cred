
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

function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const button = input.parentElement.querySelector('.toggle-password i');
            
            if (input.type === 'password') {
                input.type = 'text';
                button.classList.remove('fa-eye');
                button.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                button.classList.remove('fa-eye-slash');
                button.classList.add('fa-eye');
            }
        }

        document.getElementById('edit-profile-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const successMsg = document.getElementById('profile-success');
            successMsg.style.display = 'block';
            setTimeout(() => { successMsg.style.display = 'none'; }, 3000);
        });

        document.getElementById('change-password-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const errorMsg = document.getElementById('password-error');
            const errorText = document.getElementById('password-error-message');
            const successMsg = document.getElementById('password-success');
            
            errorMsg.style.display = 'none';
            successMsg.style.display = 'none';
            
            if (newPassword !== confirmPassword) {
                errorText.textContent = 'New passwords do not match!';
                errorMsg.style.display = 'block';
                return;
            }
            
            successMsg.style.display = 'block';
            this.reset();
            setTimeout(() => { successMsg.style.display = 'none'; }, 3000);
        });

        function confirmDelete() {
            if (confirm('Are you sure you want to delete your account? This action cannot be undone.')) {
                alert('Account deletion feature will be implemented with database logic.');
            }
        }

