@extends('layouts.user_type.guest')

@section('content')

    <main class="main-content">
        <section class="min-vh-100 position-relative">
            <!-- Navbar/Header Section -->
            <div class="container">
                <nav class="navbar py-3">
                    <div class="container-fluid">
                        <div class="nav-wrapper">
                            <!-- Logo and Brand -->
                            <div class="brand-section">
                                <a class="navbar-brand d-flex align-items-center" href="#">
                                    <img src="{{ asset('assets/img/univaultlogo.png') }}" alt="UniVault Logo" style="height: 40px;">
                                    <span class="ms-2 h4 mb-0" style="color: #333;">UniVault</span>
                                </a>
                            </div>
                            
                            <!-- Navigation Links -->
                            <div class="nav-links">
                                <a class="nav-link px-3" href="#about-us">About Us</a>
                            </div>
                            
                            <!-- Auth Buttons -->
                            <div class="nav-buttons">
                                @auth
                                    <a href="{{ route('dashboard') }}" class="btn nav-btn">Go to Dashboard</a>
                                @else
                                    <a href="{{ route('session.register') }}" class="btn nav-btn me-2">SIGN UP</a>
                                    <a href="{{ route('session.login') }}" class="btn nav-btn">LOG IN</a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </nav>
            </div>

            <!-- Hero Section -->
            <div class="container2 mt-0">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h1 class="mb-3" style="font-size: 2rem; color: #821131; font-weight: 600;">UNLOCK EFFICIENCY!</h1>
                        <h2 class="mb-3" style="font-size: 1.2rem; color: #821131; font-weight: 600;">THE FUTURE OF INVENTORY MANAGEMENT STARTS WITH US</h2>
                        <p class="mb-4" style="font-size: 1.1rem; color: #4B5563; line-height: 1.6;">Take the First Step Towards Efficiency and Control - Revolutionize Your Workflow with Smart Inventory Solutions, Seamless Management, and Limitless Potential.</p> 
                    </div>
                    <div class="col-lg-6">
                        <div class="position-relative" style="width: 93%; height: auto;">
                            <img src="{{ asset('assets/img/univaultlogo.png') }}" alt="UniVault Badge" class="img-fluid" style="width: 100%; height: auto; opacity: 0.4;">
                            <img src="{{ asset('assets/img/TUP.logo.png') }}" alt="TUP Logo" class="img-fluid position-absolute" 
                                style="width: 19%; height: auto; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- After the hero section -->
        <section class="about-us" id="about-us">
            <h1>About Us</h1>
            <p>
                Meet the passionate team behind our journey of excellence and
                innovation.
            </p>
            <div class="team">
                <!-- Team Member 1 -->
                <div class="member">
                <img src="{{ asset('assets/img/cervera-prof.jpg') }}" alt="Member 1" />
                    <h3>Arsenio A. Cervera</h3>
                    <p>Back-End Developer</p>
                    <div class="social-links">
                        <a
                    href="https://www.linkedin.com/in/arsenio-cervera-1501sn"
                    target="_blank"
                    >
                    <img src="{{ asset('assets/img/linkedin (1).png') }}" alt="LinkedIn" />
                    </a>
                    <a href="https://github.com/Cervera-jpg" target="_blank">
                    <img src="{{ asset('assets/img/github.png') }}" alt="GitHub" />
                    </a>
                </div>
                </div>
                <!-- Team Member 2 -->
                <div class="member">
                <img src="{{ asset('assets/img/codillo-prof.jpg') }}" alt="Member 2" />
                <h3>John Christian S. Codillo</h3>
                <p>Full-Stack Developer</p>
                <div class="social-links">
                    <a href="https://www.linkedin.com/in/jc-codillo" target="_blank">
                    <img src="{{ asset('assets/img/linkedin (1).png') }}" alt="LinkedIn" />
                    </a>
                    <a href="https://github.com/jccodillio" target="_blank">
                    <img src="{{ asset('assets/img/github.png') }}" alt="GitHub" />
                    </a>
                </div>      
                </div>
                <!-- Team Member 3 -->
                <div class="member">
                <img src="{{ asset('assets/img/ejusa-prof.jpg') }}" alt="Member 3" />
                <h3>Patricia Mae N. Ejusa</h3>
                <p>Front-End Developer</p>
                <div class="social-links">
                    <a
                    href="https://www.linkedin.com/in/patricia-mae-ejusa-2992442a4/"
                    target="_blank"
                    >
                    <img src="{{ asset('assets/img/linkedin (1).png') }}" alt="LinkedIn" />
                    </a>
                    <a href="https://github.com/patriciamaeejusa" target="_blank">
                    <img src="{{ asset('assets/img/github.png') }}" alt="GitHub" />
                    </a>
                </div>
                </div>
                <!-- Team Member 4 -->
                <div class="member">
                <img src="{{ asset('assets/img/diego-prof.jpg') }}" alt="Member 4" />
                <h3>Carl Joseph B. Diego</h3>
                <p>UI/UX Designer</p>
                <div class="social-links">
                    <a
                    href="https://www.linkedin.com/in/carl-joseph-diego-6422202a4"
                    target="_blank"
                    >
                    <img src="{{ asset('assets/img/linkedin (1).png') }}" alt="LinkedIn" />
                    </a>
                    <a href="https://github.com/vurtunegodz" target="_blank">
                    <img src="{{ asset('assets/img/github.png') }}" alt="GitHub" />
                    </a>
                </div>
                </div>
                <!-- Team Member 5 -->
                <div class="member">
                <img src="{{ asset('assets/img/morada-prof.jpg') }}" alt="Member 5" />
                <h3>Joanna Marie L. Morada</h3>
                <p>Quality Assurance </p>
                <div class="social-links">
                    <a
                    href="https://www.linkedin.com/in/joanna-marie-morada-6ab1902a3"
                    target="_blank"
                    >
                    <img src="{{ asset('assets/img/linkedin (1).png') }}" alt="LinkedIn" />
                    </a>
                    <a href="https://github.com/Namichwan" target="_blank">
                    <img src="{{ asset('assets/img/github.png') }}" alt="GitHub" />
                    </a>
                    </div>
                </div>
            </div>
        </section>


    </main>
    <footer class="footer">
            <p>&copy; 2024 UniVault | All Rights Reserved</p>
        </footer>
<style>
/* Update button styling with media queries */
.nav-btn {
    background-color: #821131;
    color: white;
    padding: 8px 24px;
    border-radius: 50px;
    font-weight: 500;
    text-transform: uppercase;
    transition: all 0.3s ease;
    border: none;
}

/* Media queries for smaller screens */
@media (max-width: 768px) {
    .nav-btn {
        padding: 6px 16px;
        font-size: 0.8rem;
    }

    .navbar-brand img {
        height: 30px !important;
        min-width: 30px;
        object-fit: contain;
    }

    .navbar-brand span {
        font-size: 1.2rem !important;
    }

    .nav-link {
        font-size: 0.9rem;
        padding: 0.5rem !important;
    }
}

/* For even smaller screens */
@media (max-width: 576px) {
    .nav-btn {
        padding: 4px 12px;
        font-size: 0.75rem;
    }

    .navbar-brand img {
        height: 25px !important;
    }

    .navbar-brand span {
        font-size: 1rem !important;
    }
}
/* Add these media queries to your existing styles */
@media (max-width: 400px) {
    .navbar-collapse {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        padding: 1rem;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        z-index: 1000;
    }

    .navbar-nav {
        margin: 0 !important;
        padding: 0.5rem 0;
    }

    .nav-buttons {
        display: flex;
        justify-content: flex-end;
        padding-top: 0.5rem;
        border-top: 1px solid #eee;
    }

    .nav-btn {
        padding: 4px 8px;
        font-size: 0.7rem;
    }

    .navbar-brand span {
        font-size: 0.9rem !important;
    }

    .navbar-brand img {
        height: 20px !important;
    }
}

/* Update existing navbar styles */
.navbar-toggler {
    padding: 4px 8px;
    font-size: 1rem;
}

.navbar-toggler:focus {
    box-shadow: none;
    outline: none;
}

/* Update navbar styling */
.navbar {
    background: white;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
}

.nav-link {
    color: #4B5563;
    font-weight: 500;
    transition: color 0.3s ease;
}

.nav-link:hover {
    color: #821131;
}

.social-links a {
    color: #DC2626;
    transition: color 0.3s ease;
}

.social-links a:hover {
    color: #B91C1C;
}

.text-primary {
    color: #DC2626 !important;
}

/* About Us */
.about-us {
  text-align: center;
  padding: 50px 20px;
  background-color: #fff;
}

.about-us h1 {
  font-size: 2.5em;
  margin-bottom: 10px;
  color: #821131;
}

.about-us p {
  font-size: 1.2em;
  margin-bottom: 30px;
  color: #555;
}

.team {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 20px;
}
.member {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background-color: #fff;
  border: 1px solid #ddd;
  border-radius: 10px;
  padding: 20px;
  text-align: center;
  width: 200px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.member img {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  margin-bottom: 15px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.member:hover {
  transform: translateY(-10px);
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.member img {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  margin-bottom: 15px;
}

.member h3 {
  font-size: 1.2em;
  margin-bottom: 5px;
  color: #821131;
}

.member p {
  font-size: 1em;
  color: #666;
}

.member {
  text-align: center;
  position: relative;
}

.social-links {
  display: flex;
  justify-content: center;
  gap: 10px;
  margin-top: 20px;
}

.social-links img {
  width: 24px;
  height: 24px;
  cursor: pointer;
}

.footer {
    background-color: #821131;
    color: white;
    text-align: center;
    padding: 5px 0;
    width: 100vw;
    margin: 50px 0 0 0;
    position: relative;
    left: 50%;
    right: 50%;
    margin-left: -50vw;
    margin-right: -50vw;
    z-index: 10;
}

.footer p {
    margin: 0;
    font-size: 0.9rem;
}

.main-content {
    overflow-x: hidden;
    width: 100%;
    position: relative;
}

/* Add these media queries to your existing styles */
@media (max-width: 400px) {
    .navbar-collapse {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        padding: 1rem;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        z-index: 1000;
    }

    .navbar-nav {
        margin: 0 !important;
        padding: 0.5rem 0;
    }

    .nav-buttons {
        display: flex;
        justify-content: flex-end;
        padding-top: 0.5rem;
        border-top: 1px solid #eee;
    }

    .nav-btn {
        padding: 4px 8px;
        font-size: 0.7rem;
    }

    .navbar-brand span {
        font-size: 0.9rem !important;
    }

    .navbar-brand img {
        height: 20px !important;
    }
    .team {
        gap: 10px;
        padding: 0 5px;
    }
    
    .member {
        width: calc(50% - 10px); /* Makes 2 members per row with smaller gap */
        padding: 10px;
        min-width: 120px;
    }

    .member img {
        width: 60px;
        height: 60px;
    }

    .member h3 {
        font-size: 0.8em;
        margin-bottom: 2px;
    }

    .member p {
        font-size: 0.7em;
        margin-bottom: 5px;
    }

    .member .social-links {
        gap: 5px;
        margin-top: 10px;
    }

    .member .social-links img {
        width: 16px;
        height: 16px;
    }

    /* Adjust About Us section for smaller screens */
    .about-us h1 {
        font-size: 1.8em;
    }

    .about-us p {
        font-size: 1em;
        margin-bottom: 20px;
    }
    
}

/* Update existing navbar styles */
.navbar-toggler {
    padding: 4px 8px;
    font-size: 1rem;
}

.navbar-toggler:focus {
    box-shadow: none;
    outline: none;
}

/* Update the navbar styles */
.nav-wrapper {
    display: flex;
    width: 100%;
    align-items: center; /* Align items vertically */
    justify-content: space-between; /* Space between logo and buttons */
}

.brand-section {
    display: flex;
    align-items: center;
}

.navbar-brand {
    display: flex;
    align-items: center;
}

.nav-links {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
}

.nav-buttons {
    display: flex;
    margin-left: auto; /* Push buttons to the right */
}

@media (max-width: 400px) {
    .nav-btn {
        padding: 4px 8px;
        font-size: 0.7rem;
    }
    
    .navbar-brand img {
        height: 25px !important;
    }
    
    .navbar-brand span {
        font-size: 0.9rem !important;
    }
    
    .nav-link {
        font-size: 0.9rem;
    }
}

/* Update the team and member styles for very small screens */
@media (max-width: 378px) {
    .team {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        padding: 0 10px;
    }
    
    /* Center the last item if it's alone in its row */
    .member:last-child:nth-child(odd) {
        grid-column: 1 / -1;
        justify-self: center;
        width: calc(50% - 10px); /* Match the width of other members */
    }

    .member {
        width: 100%;
        padding: 10px;
        min-width: unset;
    }

    .member img {
        width: 70px;
        height: 70px;
    }

    .member h3 {
        font-size: 0.75em;
        margin-bottom: 2px;
        line-height: 1.2;
    }

    .member p {
        font-size: 0.65em;
        margin-bottom: 5px;
        line-height: 1.2;
    }

    .member .social-links {
        gap: 8px;
        margin-top: 8px;
    }

    .member .social-links img {
        width: 20px;
        height: 20px;
    }

    /* Adjust About Us section */
    .about-us {
        padding: 30px 15px;
    }

    .about-us h1 {
        font-size: 1.5em;
    }

    .about-us p {
        font-size: 0.9em;
        margin-bottom: 15px;
    }

}
@media (max-width: 640px) { /* sm breakpoint in Tailwind is 640px */
    .container2 {
        margin-top: 3rem !important; /* mt-5 in Tailwind equals 1.25rem, but using 3rem for better spacing */
    }
}
</style>
@endsection

