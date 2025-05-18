<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - SmileCare Dental Clinic</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --white: #ffffff;
            --primary-color: #0077cc;
            --secondary-color: #005fa3;
            --light-color: #f0f0f0;
            --shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .about-hero {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('clinic-building.jpg');
            background-size: cover;
            background-position: center;
            color: var(--white);
            padding: 100px 0;
            text-align: center;
        }
        
        .about-section {
            padding: 80px 0;
        }
        
        .about-content {
            display: flex;
            flex-wrap: wrap;
            gap: 50px;
            margin-top: 50px;
        }
        
        .about-text {
            flex: 1;
            min-width: 300px;
        }
        
        .about-image {
            flex: 1;
            min-width: 300px;
        }
        
        .about-image img {
            width: 100%;
            border-radius: 10px;
            box-shadow: var(--shadow);
        }
        
        .mission-vision {
            background: var(--light-color);
            padding: 80px 0;
        }
        
        .mv-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }
        
        .mv-card {
            background: var(--white);
            padding: 30px;
            border-radius: 10px;
            box-shadow: var(--shadow);
            text-align: center;
        }
        
        .mv-card i {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }
        
        .mv-card h3 {
            margin-bottom: 15px;
            color: var(--secondary-color);
        }
        
        .clinic-features {
            padding: 80px 0;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }
        
        .feature-card {
            text-align: center;
            padding: 30px 20px;
        }
        
        .feature-card i {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }
        
        .feature-card h3 {
            margin-bottom: 15px;
        }
        
        @media (max-width: 768px) {
            .about-hero, .about-section, .mission-vision, .clinic-features {
                padding: 60px 0;
            }
        }
    </style>
</head>
<body>
    <!-- Header (same as homepage) -->
    <header>
        <div class="container">
            <nav class="navbar">
                <a href="index.php" class="logo">
                    <i class="fas fa-tooth"></i>SmileCare Dental
                </a>
                
                <ul class="nav-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="services.php">Services</a></li>
                    <li><a href="dentists.php">Dentists</a></li>
                    <li><a href="about.php" class="active">About Us</a></li>
                    <li><a href="#contact">Contact</a></li>
                    <li><a href="#appointment" class="btn btn-primary">Book Appointment</a></li>
                </ul>
                
                <div class="menu-toggle">
                    <i class="fas fa-bars"></i>
                </div>
            </nav>
        </div>
    </header>

    <!-- About Hero -->
    <section class="about-hero">
        <div class="container">
            <h1>About SmileCare Dental</h1>
            <p>Your trusted partner in dental health since 2010</p>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section">
        <div class="container">
            <div class="about-content">
                <div class="about-text">
                    <h2>Our Story</h2>
                    <p>Founded in 2010 by Dr. Sarah Johnson, SmileCare Dental began as a small practice with a big vision: to provide exceptional dental care in a warm, welcoming environment. What started as a single-dentist office has grown into a thriving multi-specialty practice serving thousands of patients in our community.</p>
                    <p>Over the years, we've invested in state-of-the-art technology and continued education to ensure we're always providing the most advanced treatments available. But despite our growth, we've never lost sight of our core values: compassion, excellence, and personalized care.</p>
                    <p>Today, our team of highly skilled professionals works together to provide comprehensive dental care for the whole family, from your child's first visit through your golden years.</p>
                </div>
                <div class="about-image">
                    <img src="clinic-interior.jpg" alt="SmileCare Dental Clinic Interior">
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Vision -->
    <section class="mission-vision">
        <div class="container">
            <div class="section-title">
                <h2>Our Mission & Vision</h2>
                <p>Guiding principles that shape everything we do</p>
            </div>
            
            <div class="mv-grid">
                <div class="mv-card">
                    <i class="fas fa-heart"></i>
                    <h3>Our Mission</h3>
                    <p>To provide exceptional, compassionate dental care that improves our patients' oral health and enhances their quality of life, while creating a welcoming environment where patients feel valued and cared for.</p>
                </div>
                <div class="mv-card">
                    <i class="fas fa-eye"></i>
                    <h3>Our Vision</h3>
                    <p>To be the most trusted dental practice in our community, recognized for clinical excellence, patient-centered care, and commitment to advancing oral health education and access to care.</p>
                </div>
                <div class="mv-card">
                    <i class="fas fa-star"></i>
                    <h3>Our Values</h3>
                    <p>Compassion, Integrity, Excellence, Innovation, and Community. These values guide every decision we make and every interaction we have with our patients and each other.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Clinic Features -->
    <section class="clinic-features">
        <div class="container">
            <div class="section-title">
                <h2>Why Choose SmileCare</h2>
                <p>What sets us apart from other dental practices</p>
            </div>
            
            <div class="features-grid">
                <div class="feature-card">
                    <i class="fas fa-user-md"></i>
                    <h3>Expert Team</h3>
                    <p>Our dentists and staff are highly trained and participate in ongoing education to stay at the forefront of dental care.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-teeth-open"></i>
                    <h3>Advanced Technology</h3>
                    <p>We invest in the latest dental technology for more accurate diagnoses and comfortable treatments.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-heartbeat"></i>
                    <h3>Preventive Focus</h3>
                    <p>We emphasize preventive care to help you maintain optimal oral health and avoid complex procedures.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-comments"></i>
                    <h3>Patient Education</h3>
                    <p>We take time to explain treatment options and help you make informed decisions about your care.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer (same as homepage) -->
    <footer>
        <!-- Footer content from homepage -->
    </footer>

    <script>
        // Mobile menu toggle (same as homepage)
        document.querySelector('.menu-toggle').addEventListener('click', function() {
            document.querySelector('.nav-links').classList.toggle('active');
        });
    </script>
</body>
</html>