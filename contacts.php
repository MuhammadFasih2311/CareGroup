<?php
session_start();
include('connect.php');

$response = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) {

    $user_id = $_SESSION['user_id'] ?? 0;
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $message = trim($_POST['message']);

    if (!$user_id) {
        echo "NOT_LOGGED_IN";
        exit;
    }

    $check = $conn->prepare("
        SELECT COUNT(*) AS total 
        FROM messages 
        WHERE user_id = ? 
        AND created_at > (NOW() - INTERVAL 30 MINUTE)
    ");
    $check->bind_param("i", $user_id);
    $check->execute();
    $count = $check->get_result()->fetch_assoc()['total'];

    if ($count >= 4) {
        echo "LIMIT_REACHED";
        exit;
    }

    $stmt = $conn->prepare("
        INSERT INTO messages (user_id, name, email, message, created_at) 
        VALUES (?, ?, ?, ?, NOW())
    ");
    $stmt->bind_param("isss", $user_id, $name, $email, $message);

    if ($stmt->execute()) {
        echo "SUCCESS";
    } else {
        echo "ERROR";
    }
    exit;
}
?>

<?php include('header.php'); ?>

<!-- HERO SECTION -->
<section class="contact-hero">
    <div class="overlay"></div>
    <div class="container text-center content" data-aos="fade-up">
        <h1>Get in Touch</h1>
        <p>We’re here to answer your questions and assist you anytime 💙</p>
    </div>
</section>

<!-- CONTACT SECTION -->
<section class="py-5">
    <div class="container">
        <div class="row g-4 align-items-stretch">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="contact-card shadow-sm p-4 rounded-4 bg-white h-100">
                    <div class="text-center">
                        <h3 class="fw-bold mb-4 section-title">Send us <span>a Message</span></h3>
                    </div>

                    <div id="alertBox"></div>

                    <form id="contactForm" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Your Name</label>
                            <input type="text" name="name" class="form-control"
                                   value="<?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?>"
                                   placeholder="Enter your name" required maxlength="35" minlength="3" oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '');"
>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Your Email</label>
                            <input type="email" name="email" class="form-control"
                                  value="<?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?>"
                                   placeholder="name@example.com" required maxlength="35" minlength="4">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Message</label>
                            <textarea name="message" class="form-control" rows="5"
                                      placeholder="Write your message here..."
                                      required maxlength="250" minlength="4"></textarea>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-outline-her px-4 rounded-pill">
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-6" data-aos="fade-left">
                <div class="map-container rounded-4 overflow-hidden shadow-sm h-100">
                    <iframe src="https://www.google.com/maps?q=Karachi,+Pakistan&output=embed"
                        width="100%" height="100%" style="border:0;" allowfullscreen loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQs -->
<section class="about-section py-5">
    <div class="container">

        <div class="text-center mb-5">
            <h2 class="fw-bold section-title" data-aos="fade-up">
                Frequently <span>Asked Questions</span>
            </h2>
            <p class="text-muted" data-aos="fade-up" data-aos-delay="100">
                Here are some common questions our patients ask us.
            </p>
        </div>

        <div class="accordion shadow-sm rounded-4" id="faqAccordion" data-aos="flip-down">
            <div class="accordion-item">
                <h2 class="accordion-header" id="faq1">
                    <button class="accordion-button" data-bs-toggle="collapse" data-bs-target="#answer1">
                        <i class="bi bi-question-circle me-2"></i> How can I book an appointment?
                    </button>
                </h2>
                <div id="answer1" class="accordion-collapse collapse show">
                    <div class="accordion-body">
                        You can easily book an appointment through our website’s “Book Appointment”
                        section or call our support team for assistance.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="faq2">
                    <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#answer2">
                        <i class="bi bi-geo-alt me-2"></i> Where are your clinics located?
                    </button>
                </h2>
                <div id="answer2" class="accordion-collapse collapse">
                    <div class="accordion-body">
                        Our clinics are located across major cities in Pakistan,
                        including Karachi, Lahore, and Islamabad.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="faq3">
                    <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#answer3">
                        <i class="bi bi-camera-video me-2"></i> Can I consult a doctor online?
                    </button>
                </h2>
                <div id="answer3" class="accordion-collapse collapse">
                    <div class="accordion-body">
                        Yes! CARE Group offers online consultations through video calls.
                        Book your slot online and connect with certified specialists.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="faq4">
                    <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#answer4">
                        <i class="bi bi-envelope me-2"></i> How can I contact customer support?
                    </button>
                </h2>
                <div id="answer4" class="accordion-collapse collapse">
                    <div class="accordion-body">
                        Contact our support team via the form above or email at
                        <a href="mailto:support@caregroup.pk">support@caregroup.pk</a>.
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- CTA -->
<section class="about-cta text-white text-center">
    <div class="cta-overlay"></div>
    <div class="container position-relative py-5" data-aos="fade-up">
        <h2 class="fw-bold mb-3 text-warning">Find Trusted Hospitals Near You</h2>
        <p class="mb-4">Explore top-rated hospitals and book appointments with ease.</p>
        <a href="hospitals.php" class="btn btn-cta px-5 py-3 rounded-pill shadow">
            Visit Hospitals <i class="bi bi-arrow-right-circle ms-2"></i>
        </a>
    </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const forms = document.querySelectorAll("form.require-login");
    const isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;

    forms.forEach(form => {
        form.addEventListener("submit", function (e) {
            if (!isLoggedIn) {
                e.preventDefault();
                const modal = new bootstrap.Modal(document.getElementById('loginPromptModal'));
                modal.show();
            }
        });
    });
});
document.getElementById("contactForm").addEventListener("submit", function(e) {
    e.preventDefault();

    let formData = new FormData(this);
    formData.append("ajax", "1");

    fetch("", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(data => {
        let alertBox = document.getElementById("alertBox");

if (data === "SUCCESS") {
    alertBox.innerHTML = `
        <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
            Message sent successfully! 💙
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>`;
    document.getElementById("contactForm").reset();
}

else if (data === "NOT_LOGGED_IN") {
    alertBox.innerHTML = `
        <div class="alert alert-warning alert-dismissible fade show text-center" role="alert">
            Please login first.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>`;
}

else if (data === "LIMIT_REACHED") {
    alertBox.innerHTML = `
        <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
            You can only send 4 messages every 30 minutes.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>`;
}

else {
    alertBox.innerHTML = `
        <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
            Error sending message.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>`;
}

setTimeout(() => {
    let alertElement = document.querySelector(".alert");
    if (alertElement) {
        let alert = new bootstrap.Alert(alertElement);
        alert.close();
    }
}, 3000);

    });
});

</script>

<div class="modal fade" id="loginPromptModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Login Required</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <p class="text-muted mb-3">You need to log in or register to submit this form.</p>
                <a href="login.php" class="btn btn-login px-4 me-2">
                    <i class="fa-solid fa-right-to-bracket me-1"></i> Login
                </a>
                <a href="signup.php" class="btn btn-register px-4">
                    <i class="fa-solid fa-user-plus me-1"></i> Register
                </a>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>
