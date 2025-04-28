<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trackify: About us</title>
    <link rel="stylesheet" href="aboutUS.css">
    <script src="https://kit.fontawesome.com/6c820a33bb.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="shortcut icon" href="Trackify logo No background.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&family=Satoshi:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="shortcut icon" href="../MainPage/static/TrackifylogoNobackground.png" type="image/x-icon">
    <link rel=stylesheet href="Style.css">
</head>
<body class="bg-gray-600">
    <div id="about" class="bg-gray-900 animate__animated animate__fadeIn">
        <div class="container flex flex-wrap">
            <div class="about-col-1 w-full md:w-1/2">
                <img src="Aboutus.png" alt="" class="w-full float">
                <div class="flex justify-center mt-4">
                    <button class="px-4 py-2 text-green-500 border border-orange-400 rounded-full hover:bg-white hover:text-black transition duration-1000 transform hover:scale-105 pulse-hover border-glow">
                        <a href="../MainPage/mainpage.php" class="flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i> Back to home
                        </a>
                    </button>
                </div>
            </div>
            <div class="about-col-2 w-full md:w-1/2">
                <h1 class="sub-title glow-text animate__animated animate__bounceIn">About Us</h1>
                <p class="animate__animated animate__fadeIn animate__delay-1s">A gamified habit tracker turns daily routines into an engaging experience by incorporating rewards, challenges, and progress tracking. Users earn points, badges, or levels as they complete habits, making consistency more fun and motivating. Features like streaks, leaderboards, and virtual rewards keep users accountable and encourage long-term habit formation. Personalization options, such as avatars or themes, enhance user engagement. By making self-improvement feel like a game, a gamified habit tracker helps build positive routines effortlessly.</p>

                <div class="tab-titles animate__animated animate__fadeIn animate__delay-1s">
                    <p class="tab-links active-link pulse-hover" onclick="opentab('team')">
                        <i class="fas fa-users mr-2"></i>Team
                    </p>
                    <p class="tab-links pulse-hover" onclick="opentab('Skillset')">
                        <i class="fas fa-code mr-2"></i>Skillset
                    </p>
                    <p class="tab-links pulse-hover" onclick="opentab('vision')">
                        <i class="fas fa-eye mr-2"></i>Vision
                    </p>
                    <p class="tab-links pulse-hover" onclick="opentab('Mission')">
                        <i class="fas fa-bullseye mr-2"></i>Mission
                    </p>
                    <p class="tab-links pulse-hover" onclick="opentab('why')">
                        <i class="fas fa-question-circle mr-2"></i>Why Trackify
                    </p>
                </div>
                <div class="tab-contents active-tab tab-content-transition animate__animated animate__fadeIn" id="team">
                    <ol type="1" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <li class="team-member bg-gray-800 p-4 rounded-lg transition duration-300 hover:bg-gray-700">
                            <span class="text-orange-400 font-bold">1. Hardik Kumar</span> - 
                            <span class="text-blue-400">12301328</span>
                        </li>
                        <li class="team-member bg-gray-800 p-4 rounded-lg transition duration-300 hover:bg-gray-700">
                            <span class="text-orange-400 font-bold">2. Raman Negi</span> - 
                            <span class="text-blue-400">12315380</span>
                        </li>
                        <li class="team-member bg-gray-800 p-4 rounded-lg transition duration-300 hover:bg-gray-700">
                            <span class="text-orange-400 font-bold">3. Tushar Upadhyay</span> - 
                            <span class="text-blue-400">12307313</span>
                        </li>
                        <li class="team-member bg-gray-800 p-4 rounded-lg transition duration-300 hover:bg-gray-700">
                            <span class="text-orange-400 font-bold">4. Diya Maji</span> - 
                            <span class="text-blue-400">12319218</span>
                        </li>
                    </ol>                  
                </div>
                <div class="tab-contents tab-content-transition" id="Skillset">
                    <ul class="space-y-3">
                        <li class="flex items-center">
                            <i class="fas fa-laptop-code text-orange-400 mr-3"></i>
                            <span>Frontend - HTML, CSS, JavaScript, Tailwind CSS, JQuery, Bootstrap</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-server text-orange-400 mr-3"></i>
                            <span>Backend - PHP</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-database text-orange-400 mr-3"></i>
                            <span>Database - MySQL</span>
                        </li>
                    </ul>                  
                </div>
                <div class="tab-contents tab-content-transition" id="vision">
                    <div class="bg-gray-800 p-6 rounded-lg border-l-4 border-green-400">
                        <i class="fas fa-quote-left text-orange-400 text-2xl"></i>
                        <p class="italic text-lg">A fun, interactive platform that helps users build and maintain positive habits through gamification, rewarding progress with points, challenges, and personalized avatars.</p>
                        <i class="fas fa-quote-right text-orange-400 text-2xl float-right"></i>
                    </div>                
                </div>
                <div class="tab-contents tab-content-transition" id="Mission">
                    <div class="bg-gray-800 p-6 rounded-lg border-l-4 border-blue-400">
                        <i class="fas fa-quote-left text-orange-400 text-2xl"></i>
                        <p class="italic text-lg">To empower users to build lasting positive habits by providing an engaging, rewarding, and motivating gamified experience.</p>
                        <i class="fas fa-quote-right text-orange-400 text-2xl float-right"></i>
                    </div>                
                </div>
                <div class="tab-contents tab-content-transition" id="why">
                    <div class="bg-gray-800 p-6 rounded-lg border-l-4 border-purple-400">
                        <i class="fas fa-lightbulb text-orange-400 text-2xl"></i>
                        <p class="text-lg mt-2">The habit tracker website gamifies personal development by turning consistent habit building into a fun, rewarding process.</p>
                        <div class="mt-4 flex space-x-2">
                            <span class="px-3 py-1 bg-green-500 rounded-full text-xs">Motivation</span>
                            <span class="px-3 py-1 bg-blue-500 rounded-full text-xs">Engagement</span>
                            <span class="px-3 py-1 bg-purple-500 rounded-full text-xs">Rewards</span>
                        </div>
                    </div>                
                </div>
             </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        function opentab(tabname) {
            $('.tab-contents').removeClass('active-tab animate__fadeIn').hide();
            $('.tab-links').removeClass('active-link');
            $(`.tab-links:contains(${tabname})`).addClass('active-link animate__animated animate__pulse');
            $(`#${tabname}`).addClass('active-tab animate__animated animate__fadeIn').show();
            setTimeout(() => {
                $(`.tab-links:contains(${tabname})`).removeClass('animate__animated animate__pulse');
            }, 1000);
        }
        
        $(document).ready(function() {
            $('.team-member').hover(
                function() {
                    $(this).addClass('transform -translate-y-1 shadow-lg');
                },
                function() {
                    $(this).removeClass('transform -translate-y-1 shadow-lg');
                }
            );
            
            $('button').on('click', function(e) {
                let x = e.pageX - $(this).offset().left;
                let y = e.pageY - $(this).offset().top;
                
                let ripple = $('<span class="ripple-effect"></span>');
                ripple.css({
                    left: x,
                    top: y
                });
                
                $(this).append(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 1000);
            });
        });
    </script>
</body>
</html>
