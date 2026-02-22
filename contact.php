<?php
require_once 'includes/config.php';
require_once 'includes/models/ContactInquiry.php';

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    if (empty($name) || empty($email) || empty($message)) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        try {
            ContactInquiry::create([
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'subject' => $subject,
                'message' => $message
            ]);
            $success = true;
        } catch (Exception $e) {
            $error = 'Failed to submit your inquiry. Please try again.';
        }
    }
}

$pageTitle = 'Contact Us';
require_once 'includes/header.php';
?>

<section class="simple-page-header">
  <h1>Contact Us</h1>

  <div class="breadcrumb">
    <a href="index.php">
      <i class="fas fa-home"></i> Home
    </a>
    <span>/</span>
    <span>Contact Us</span>
  </div>
</section>
<style>
        .simple-page-header {
  padding: 60px 20px 40px;
  text-align: center;
  background: #f7f5ee;
}

.simple-page-header h1 {
  font-size: 42px;
  font-weight: 800;
  margin-bottom: 12px;
  color: #1e1e1e;
}

.breadcrumb {
  display: flex;
  justify-content: center;
  gap: 8px;
  font-size: 14px;
}

.breadcrumb a {
  color: #6a8f2d;
  text-decoration: none;
  font-weight: 500;
}

.breadcrumb span {
  color: #555;
}

/* Responsive */
@media (max-width: 768px) {
  .simple-page-header h1 {
    font-size: 32px;
  }
}
</style>
<!-- Contact Section -->
<section class="contact-section">
    <div class="container">
        <div class="contact-grid">
            <!-- Contact Form -->
            <div class="contact-form-wrapper reveal-left">
                <h3>Send Us a Message</h3>
                <p style="color: var(--text-light); margin-bottom: 25px;">Have a question or feedback? We'd love to hear from you. Fill out the form below and we'll get back to you as soon as possible.</p>
                
                <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span>Thank you for your message! We'll get back to you soon.</span>
                </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
                <?php endif; ?>
                
                <form action="contact.php" method="post">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Full Name *</label>
                            <input type="text" id="name" name="name" placeholder="Enter your name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" placeholder="Enter your email" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" placeholder="Enter your phone">
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <select id="subject" name="subject">
                                <option value="">Select a subject</option>
                                <option value="General Inquiry">General Inquiry</option>
                                <option value="Order Related">Order Related</option>
                                <option value="Product Information">Product Information</option>
                                <option value="Feedback">Feedback</option>
                                <option value="Complaint">Complaint</option>
                                <option value="Business Partnership">Business Partnership</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="message">Your Message *</label>
                        <textarea id="message" name="message" placeholder="Write your message here..." required></textarea>
                    </div>
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>
            
            <!-- Contact Info -->
            <div class="contact-info-wrapper reveal-right">
                <h3>Get In Touch</h3>
                <p style="color: var(--text-light); margin-bottom: 25px;">We're here to help and answer any question you might have. We look forward to hearing from you!</p>
                
                <div class="contact-info-cards">
                    <div class="contact-info-card">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-text">
                            <h4>Our Address</h4>
                            <p><?php echo SITE_ADDRESS; ?></p>
                        </div>
                    </div>
                    
                    <div class="contact-info-card">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-text">
                            <h4>Email Us</h4>
                            <a href="mailto:<?php echo SITE_EMAIL; ?>"><?php echo SITE_EMAIL; ?></a>
                            <p>We reply within 24 hours</p>
                        </div>
                    </div>
                    
                    <div class="contact-info-card">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="contact-text">
                            <h4>Call Us</h4>
                            <a href="tel:<?php echo SITE_PHONE; ?>"><?php echo SITE_PHONE; ?></a>
                            <p>Mon - Sat: 9:00 AM - 7:00 PM</p>
                        </div>
                    </div>
                    
                    <div class="contact-info-card">
                        <div class="contact-icon">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <div class="contact-text">
                            <h4>WhatsApp</h4>
                            <a href="https://wa.me/918958489684" target="_blank">+91 8958489684</a>
                            <p>Quick responses on WhatsApp</p>
                        </div>
                    </div>
                </div>
                
                
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="section section-light">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-badge"><i class="fas fa-question-circle"></i> FAQ</span>
            <h2 class="section-title">Frequently Asked <span>Questions</span></h2>
        </div>
        <div style="max-width: 800px; margin: 0 auto;">
            <div class="why-choose-card reveal" style="text-align: left; margin-bottom: 20px;">
                <h4 style="margin-bottom: 10px; color: var(--dark-bg);">What is your return policy?</h4>
                <p style="color: var(--text-light);">We offer a 07-day hassle-free return policy on all products. If you're not satisfied, contact us for a full refund.</p>
            </div>
            <div class="why-choose-card reveal delay-100" style="text-align: left; margin-bottom: 20px;">
                <h4 style="margin-bottom: 10px; color: var(--dark-bg);">How long does shipping take?</h4>
                <p style="color: var(--text-light);">Standard delivery takes 3-5 business days. Express delivery is available for metro cities with 1-2 day delivery.</p>
            </div>
            <div class="why-choose-card reveal delay-200" style="text-align: left; margin-bottom: 20px;">
                <h4 style="margin-bottom: 10px; color: var(--dark-bg);">Are your products safe?</h4>
                <p style="color: var(--text-light);">Yes, all our products are 100% natural, lab-tested, and manufactured in GMP-certified facilities. They are completely safe for long-term use.</p>
            </div>
            <div class="why-choose-card reveal delay-300" style="text-align: left;">
                <h4 style="margin-bottom: 10px; color: var(--dark-bg);">Do you offer COD?</h4>
                <p style="color: var(--text-light);">Yes, we offer Cash on Delivery across India.</p>
            </div>
        </div>
    </div>
</section>


<style>
    /* ==============================
   CONTACT PAGE BASE
================================ */
.contact-section {
  padding: 80px 0;
  background: #f7f5ee;
}

.contact-grid {
  display: grid;
  grid-template-columns: 1.1fr 0.9fr;
  gap: 60px;
  align-items: flex-start;
}

/* ==============================
   CONTACT FORM
================================ */
.contact-form-wrapper {
  background: #fff;
  padding: 45px;
  border-radius: 18px;
  box-shadow: 0 20px 50px rgba(0,0,0,0.08);
}

.contact-form-wrapper h3 {
  font-size: 30px;
  margin-bottom: 10px;
  color: #1e1e1e;
}

.contact-form-wrapper p {
  font-size: 15px;
  line-height: 1.7;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
}

.form-group {
  display: flex;
  flex-direction: column;
  margin-bottom: 22px;
}

.form-group label {
  font-size: 14px;
  margin-bottom: 8px;
  font-weight: 500;
  color: #333;
}

.form-group input,
.form-group select,
.form-group textarea {
  padding: 14px 16px;
  border-radius: 10px;
  border: 1px solid #cfd6a3;
  font-size: 15px;
  outline: none;
  transition: all 0.3s ease;
  background: #fff;
}

.form-group textarea {
  resize: none;
  min-height: 130px;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
  border-color: #9bb83d;
  box-shadow: 0 0 0 3px rgba(155,184,61,0.2);
}

/* ==============================
   SUBMIT BUTTON
================================ */
.submit-btn {
  margin-top: 10px;
  background: #9bb83d;
  color: #fff;
  border: none;
  padding: 14px 28px;
  border-radius: 10px;
  font-size: 15px;
  font-weight: 600;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 10px;
  transition: all 0.3s ease;
}

.submit-btn:hover {
  background: #869f33;
  transform: translateY(-2px);
}

/* ==============================
   ALERTS
================================ */
.alert {
  padding: 14px 18px;
  border-radius: 10px;
  font-size: 14px;
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 10px;
}

.alert-success {
  background: #eaf5d4;
  color: #557a18;
}

.alert-error {
  background: #ffe3e3;
  color: #b71c1c;
}

/* ==============================
   CONTACT INFO
================================ */
.contact-info-wrapper {
  background: #fff;
  padding: 45px;
  border-radius: 18px;
  box-shadow: 0 20px 50px rgba(0,0,0,0.08);
}

.contact-info-wrapper h3 {
  font-size: 28px;
  margin-bottom: 10px;
}

.contact-info-cards {
  display: grid;
  gap: 22px;
  margin-top: 30px;
}

.contact-info-card {
  display: flex;
  gap: 18px;
  align-items: flex-start;
}

.contact-icon {
  width: 46px;
  height: 46px;
  background: #9bb83d;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-size: 18px;
  flex-shrink: 0;
}

.contact-text h4 {
  font-size: 16px;
  margin-bottom: 5px;
  color: #1e1e1e;
}

.contact-text p,
.contact-text a {
  font-size: 14px;
  color: #555;
  line-height: 1.6;
  text-decoration: none;
}

.contact-text a:hover {
  color: #9bb83d;
}

/* ==============================
   FAQ SECTION CLEANUP
================================ */
.section-light {
  background: #fff;
  padding: 80px 0;
}

.section-header {
  text-align: center;
  margin-bottom: 50px;
}

.section-title {
  font-size: 36px;
}

.section-title span {
  color: #9bb83d;
}

.why-choose-card {
  padding: 22px;
  border-radius: 14px;
  background: #f7f5ee;
}

/* ==============================
   RESPONSIVE
================================ */
@media (max-width: 992px) {
  .contact-grid {
    grid-template-columns: 1fr;
    gap: 40px;
  }
}

@media (max-width: 576px) {
  .contact-form-wrapper,
  .contact-info-wrapper {
    padding: 30px;
  }

  .form-row {
    grid-template-columns: 1fr;
  }

  .section-title {
    font-size: 30px;
  }
}
</style>
<?php require_once 'includes/footer.php'; ?>
