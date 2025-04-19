<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Trackify</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&family=Satoshi:wght@400;600;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="mainpage.css">
    <link rel="shortcut icon" href="static/TrackifylogoNobackground.png" type="image/x-icon">
</head>
<body class="bg-gray-900" id="home">
    <header id="header" class=" sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <img alt="Trackify logo" class="w-10 h-15 border-blue-600" src="static/TrackifylogoNobackground.png"10"/>
                <span id=tt class="text-2xl font-extrabold text-blue-600 tracking-wide font-abril-fatface">Trackify</span>
            </div>
            <nav class="hidden md:flex space-x-2">
                <button class="px-4 py-2 text-white border border-white rounded-full hover:bg-white hover:text-black transition duration-2000 transform hover:scale-105"><a href="#home">Home</a></button>
                <button class="px-4 py-2 text-white border border-white rounded-full hover:bg-white hover:text-black transition duration-2000 transform hover:scale-105"><a href="#features">Features</a></button>
                <button class="px-4 py-2 text-white border border-white rounded-full hover:bg-white hover:text-black transition duration-2000 transform hover:scale-105"><a href="#topics">topics</a></button>
                <button class="px-4 py-2 text-white border border-white rounded-full hover:bg-white hover:text-black transition duration-2000 transform hover:scale-105"><a href="../Aboutus/AboutUS.php">About us</a></button>
                <button class="px-4 py-2 text-white border border-white rounded-full hover:bg-white hover:text-black transition duration-2000 transform hover:scale-105"><a href="../Contactus/Contactus.html">Contact us</a></button>
            </nav>
            <div class="space-x-4 flex items-center">
                <button class="px-4 py-2 text-blue-600 border border-blue-600 rounded-full hover:bg-blue-600 hover:text-white transition duration-2000 transform hover:scale-105"><a href="https://trackify-assistant.onrender.com/">TrackifyAI</a></button>
                <button class="px-4 py-2 text-white bg-blue-600 rounded-full hover:bg-gray-700 transition duration-2000 transform hover:scale-105"><a href="../index.php">Logout</a></button>
                <button id="mobile-menu-button" class="md:hidden px-4 py-2 text-gray-700 hover:text-blue-600 transition duration-2000">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </header>

    <div id="mobile-menu" class="fixed inset-0 bg-black bg-opacity-95 z-50 hidden fade-in">
        <div class="flex justify-end p-4 fade-in">
            <button id="close-mobile-menu" class="text-white">
                <i class="fas fa-times fa-2x"></i>
            </button>
        </div>
        <nav class="flex flex-col items-center fade-in">
            <a class="text-white text-lg py-2" href="#home">Home</a>
            <a class="text-white text-lg py-2" href="#features">Features</a>
            <a class="text-white text-lg py-2" href="#topics">Topics</a>
            <a class="text-white text-lg py-2" href="../Aboutus/AboutUS.php">About Us</a>
            <a class="text-white text-lg py-2" href="../Contactus/Contactus.html">Contact us</a>
        </nav>
    </div>

    <main class="container mx-auto px-4 md:px-10 py-6 fade-in">
        <section class="flex flex-col md:flex-row items-center text-center md:text-left fade-in fade-in">
            <div class="md:w-1/2">
                <div class="flex justify-center md:justify-start items-center space-x-2 mb-6 fade-in">
                    <span class="bg-gray-900 fade-in text-blue-700 px-3 py-1 rounded-full font-abril-fatface border-2 border-amber-300">Habits Matter</span>
                </div>
                <h1 id="bbh" class="text-4xl md:text-6xl font-bold text-orange-500 mb-4 font-abril-fatface">Build Better Habits, </h1>
                <h1 id="bbl" class="text-3xl md:text-5xl font-bold text-green-600 mb-4 font-abril-fatface">Build a Better Life, </h1>
                <p class="text-gray-400 mb-6">Good habits are the foundation of success—small daily actions that lead to extraordinary results.</p>
                <div class="flex justify-center md:justify-start items-center space-x-2">
                    <form action="https://www.google.com/search" method="GET">
                        <input class="px-4 py-2 border border-gray-300 rounded-l-full focus:outline-none cursor-text bg-gray-900 text-white hover:bg-gray-900 hover:transition-transform hover:duration-500 hover:transform hover:scale-105" name="q" placeholder="Search your Habit" type="text" required/>
                        <button class="px-4 py-2 border border-gray-300 bg-orange-500 text-white rounded-r-full hover:bg-blue-700 transition duration-2000 transform hover:scale-105">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="md:w-1/2 mt-8 md:mt-0 flex justify-center fade-in">
                <img alt="Illustration of a person meditating with a clock and a book" id="habitimgg" class="w-3/4 md:w-full scale-up" src="static/Habitimages.png" width="300"/>
            </div>
        </section>
        <section class="mt-16 fade-in" id="features">
            <h2 class="text-2xl font-bold text-blue-600 mb-6 font-abril-fatface">Features We Provide</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 fade-in">
                <div class="h-300 bg-gray-600 p-6 transition-colors fade-in duration-400 ease-in-out rounded-[50px] border-2 border-white cursor-pointer hover:bg-gray-900 hover:rounded-[30px] hover:transition-transform hover:duration-500 hover:transform hover:scale-105">
                <a href="../ToDoList/todolist.php">
                    <img alt="BMI icon" class="mx-auto mb-4 transition-transform transform hover:scale-110" src="static/Habitlist.png" width="60"/>
                    <h3 class="text-xl font-semibold text-orange-500 mb-2">Customizable Habit Lists</h3>
                    <p class="text-green-400">Users can create and manage their own habits.</p>
                </a>
                </div>
                <div class="bg-gray-600 p-6 transition-colors fade-in duration-400 ease-in-out rounded-[50px] border-2 border-white cursor-pointer hover:bg-gray-900 hover:rounded-[30px] hover:transition-transform hover:duration-500 hover:transform hover:scale-105">
                <a href="../DailyStreak/routine.php">
                    <img alt="Food Recommendation icon" class="mx-auto mb-4 transition-transform transform hover:scale-110" src="static/Streak.png" width="60"/>
                    <h3 class="text-xl font-semibold text-orange-500 mb-2">Daily/Weekly Streaks</h3>
                    <p class="text-green-400">Encourages consistency with streak-based rewards.</p>
                </a>
                </div>
                <!-- <div class="bg-gray-600 p-6 transition-colors fade-in duration-400 ease-in-out rounded-[50px] border-2 border-white cursor-pointer hover:bg-gray-900 hover:rounded-[30px] hover:transition-transform hover:duration-500 hover:transform hover:scale-105">
                    <img alt="Nutritional Value icon" class="mx-auto mb-4 transition-transform transform hover:scale-110" src="static/Progressvis.png" width="60"/>
                    <h3 class="text-xl font-semibold text-orange-500 mb-2">Progress Visualization</h3>
                    <p class="text-green-400">Charts, graphs, or a calendar to track progress.</p>
                </div> -->
                <div class="bg-gray-600 p-6 transition-colors fade-in duration-400 ease-in-out rounded-[50px] border-2 border-white cursor-pointer hover:bg-gray-900 hover:rounded-[30px] hover:transition-transform hover:duration-500 hover:transform hover:scale-105">
                <a href="../NotificationAndReminder/notificationsandremainder.php">
                    <img alt="Interactive Chatbot icon" class="mx-auto mb-4 transition-transform transform hover:scale-110" src="static/notification.png" width="60"/>
                    <h3 class="text-xl font-semibold text-orange-500 mb-2">Reminders & Notifications</h3>
                    <p class="text-green-400">Timely alerts to keep users accountable.</p>
                </a>
                </div>
                <!-- <div class="bg-black p-6 transition-colors fade-in duration-400 ease-in-out rounded-[20px] border-2 border-amber-400 cursor-pointer hover:bg-gray-900 hover:rounded-[30px] hover:transition-transform hover:duration-500 hover:transform hover:scale-105">
                    <img alt="Loot Boxes icon" class="mx-auto mb-4 transition-transform transform hover:scale-110" src="lootboxes.png" width="60"/>
                    <h3 class="text-xl font-semibold text-orange-500 mb-2">Random Rewards (Loot Boxes)</h3>
                    <p class="text-green-400">Surprise bonuses for streaks or achievements.</p>
                </div> -->
                <div class="bg-gray-600 p-6 transition-colors fade-in duration-400 ease-in-out rounded-[50px] border-2 border-white cursor-pointer hover:bg-gray-900 hover:rounded-[30px] hover:transition-transform hover:duration-500 hover:transform hover:scale-105">
                    <a href="../ChallengesAndQuotes/ChallengesAndQuotes.php">
                    <img alt="Challenges icon" class="mx-auto mb-4 transition-transform transform hover:scale-110" src="static/challenges.png" width="60"/>
                    <h3 class="text-xl font-semibold text-orange-500 mb-2">Challenges & Quests</h3>
                    <p class="text-green-400">Set personal or group challenges to increase motivation.</p>
                    </a>
                </div>
                <div class="bg-gray-600 p-6 transition-colors fade-in duration-400 ease-in-out rounded-[50px] border-2 border-white cursor-pointer hover:bg-gray-900 hover:rounded-[30px] hover:transition-transform hover:duration-500 hover:transform hover:scale-105">
                <a href="../XpandLevels/xpandlevels.html">
                    <img alt="XP icon" class="mx-auto mb-4 transition-transform transform hover:scale-110" src="static/XP.png" width="60"/>
                    <h3 class="text-xl font-semibold text-orange-500 mb-2">XP & Levels</h3>
                    <p class="text-green-400">Completing habits earns experience points (XP) and levels up the user.</p>
                </a>
                </div>
                <div class="bg-gray-600 p-6 transition-colors fade-in duration-400 ease-in-out rounded-[50px] border-2 border-white cursor-pointer hover:bg-gray-900 hover:rounded-[30px] hover:transition-transform hover:duration-500 hover:transform hover:scale-105">
                    <img alt="Badges icon" class="mx-auto mb-4 transition-transform transform hover:scale-110" src="static/Badge.png" width="60"/>
                    <h3 class="text-xl font-semibold text-orange-500 mb-2">Achievements & Badges</h3>
                    <p class="text-green-400">Unlockable rewards for milestones.</p>
                </div>
            </div>
        </section>
        <section class="mt-16 fade-in" id="topics">
            <h2 class="text-2xl font-bold text-blue-600 fade-in mb-6 font-abril-fatface">Help Topics</h2>
            <h3 class="text-xl font-semibold text-white  mb-4 font-abril-fatface">Enhance Your Lifestyle</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                <div class="cursor-pointer border-2 border-white bg-gray-600 p-5 rounded-[20px] shadow-md hover:bg-gray-900 hover:shadow-lg transition-transform transform hover:scale-105 hover:bg-blue-50 duration-2000 fade-in">
                    <img alt="Wake Up Early" class="w-full h-40 object-cover rounded-t-lg mb-4 transition-transform transform hover:scale-110" src="static/Wake.png" width="300"/>
                    <h4 class="text-lg font-semibold text-orange-500 mb-2">Wake Up Early</h4>
                    <p class="text-green-400">Start your day with a fresh mind and extra time for productivity.</p>
                </div>
                <div class="cursor-pointer border-2 fade-in border-white bg-gray-600 p-5 rounded-[20px] shadow-md hover:bg-gray-900 hover:shadow-lg transition-transform transform hover:scale-105 hover:bg-blue-50 duration-2000 fade-in">
                    <img alt="Exercise Daily" class="w-full h-40 object-cover rounded-t-lg mb-4 transition-transform transform hover:scale-110" src="static/Exercise.png" width="300"/>
                    <h4 class="text-lg font-semibold text-orange-500 mb-2">Exercise Daily</h4>
                    <p class="text-green-400">Keep your body and mind healthy with regular physical activity.</p>
                </div>
                <div class="cursor-pointer border-2 fade-in border-white bg-gray-600 p-5 rounded-[20px] shadow-md hover:bg-gray-900 hover:shadow-lg transition-transform transform hover:scale-105 hover:bg-blue-50 duration-2000 fade-in">
                    <img alt="Read Regularly" class="w-full h-40 object-cover rounded-t-lg mb-4 transition-transform transform hover:scale-110" src="static/read.png" width="300"/>
                    <h4 class="text-lg font-semibold text-orange-500 mb-2">Read Regularly</h4>
                    <p class="text-green-400">Expand your knowledge and improve focus by reading daily.</p>
                </div>
                <div class="cursor-pointer border-2 fade-in border-white bg-gray-600 p-5 rounded-[20px] shadow-md hover:bg-gray-900 hover:shadow-lg transition-transform transform hover:scale-105 hover:bg-blue-50 duration-2000 fade-in">
                    <img alt="Practice Gratitude" class="w-full h-40 object-cover rounded-t-lg mb-4 transition-transform transform hover:scale-110" src="static/practice.png" width="300"/>
                    <h4 class="text-lg font-semibold text-orange-500 mb-2">Practice Gratitude</h4>
                    <p class="text-green-400">Cultivate positivity by acknowledging what you're thankful for.</p>
                </div>
                <div class="cursor-pointer border-2 fade-in border-white bg-gray-600 p-5 rounded-[20px] shadow-md hover:bg-gray-900 hover:shadow-lg transition-transform transform hover:scale-105 hover:bg-blue-50 duration-2000 fade -in">
                    <img alt="Set Daily Goals" class="w-full h-40 object-cover rounded-t-lg mb-4 transition-transform transform hover:scale-110" src="static/dailygoals.png" width="300"/>
                    <h4 class="text-lg font-semibold text-orange-500 mb-2">Set Daily Goals</h4>
                    <p class="text-green-400">Stay organized and motivated by planning your tasks.</p>
                </div>
                <div class="cursor-pointer border-2 fade-in border-white bg-gray-600 p-5 rounded-[20px] shadow-md hover:bg-gray-900 hover:shadow-lg transition-transform transform hover:scale-105 hover:bg-blue-50 duration-2000 fade-in">
                    <img alt="Limit Screen Time" class="w-full h-40 object-cover rounded-t-lg mb-4 transition-transform transform hover:scale-110" src="static/Limitscreentime.png" width="300"/>
                    <h4 class="text-lg font-semibold text-orange-500 mb-2">Limit Screen Time</h4>
                    <p class="text-green-400">Reduce distractions and improve mental well-being.</p>
                </div>
                <div class="cursor-pointer border-2 fade-in border-white bg-gray-600 p-5 rounded-[20px] shadow-md hover:bg-gray-900 hover:shadow-lg transition-transform transform hover:scale-105 hover:bg-blue-50 duration-2000 fade-in">
                    <img alt="Eat Healthy" class="w-full h-40 object-cover rounded-t-lg mb-4 transition-transform transform hover:scale-110" src="static/Eathealthy.png" width="300"/>
                    <h4 class="text-lg font-semibold text-orange-500 mb-2">Eat Healthy</h4>
                    <p class="text-green-400">Fuel your body with nutritious food for sustained energy.</p>
                </div>
                <div class="cursor-pointer border-2 fade-in border-white bg-gray-600 p-5 rounded-[20px] shadow-md hover:bg-gray-900 hover:shadow-lg transition-transform transform hover:scale-105 hover:bg-blue-50 duration-2000 fade-in">
                    <img alt="Stay Hydrated" class="w-full h-40 object-cover rounded-t-lg mb-4 transition-transform transform hover:scale-110" src="static/stayhydrated.png" width="300"/>
                    <h4 class="text-lg font-semibold text-orange-500 mb-2">Stay Hydrated</h4>
                    <p class="text-green-400">Drink enough water to maintain optimal health.</p>
                </div>
                <div class="cursor-pointer border-2 fade-in border-white bg-gray-600 p-5 rounded-[20px] shadow-md hover:bg-gray-900 hover:shadow-lg transition-transform transform hover:scale-105 hover:bg-blue-50 duration-2000 fade-in">
                    <img alt="Sleep Well" class="w-full h-40 object-cover rounded-t-lg mb-4 transition-transform transform hover:scale-110" src="static/sleepwell.png" width="300"/>
                    <h4 class="text-lg font-semibold text-orange-500 mb-2">Sleep Well</h4>
                    <p class="text-green-400">Prioritize quality rest for better performance and mood.</p>
                </div>
                <div class="cursor-pointer border-2 fade-in border-white bg-gray-600 p-5 rounded-[20px] shadow-md hover:bg-gray-900 hover:shadow-lg transition-transform transform hover:scale-105 hover:bg-blue-50 duration-2000 fade-in">
                    <img alt="Keep Learning" class="w-full h-40 object-cover rounded-t-lg mb-4 transition-transform transform hover:scale-110" src="static/keeplearning.png" width="300"/>
                    <h4 class="text-lg font-semibold text-orange-500 mb-2">Keep Learning</h4>
                    <p class="text-green-400">Continuously acquire new skills to grow personally and professionally.</p>
                </div>
            </div>
        </section>
    </main>
    <footer class="bg-black py-8 mt-16 fade-in">
        <div class="container mx-auto px-4 fade-in">
            <div class="flex flex-col md:flex-row justify-between items-center fade-in" >
                <div class="flex items-center space-x-2 mb-4 md:mb-0">
                    <img alt="Trackify logo" class="w-10 h-10" src="static/TrackifylogoNobackground.png" width="40"/>
                    <span class="text-xl font-bold text-blue-600">Trackify</span>
                </div>
                <div class="flex space-x-8 mb-4 md:mb-0 fade-in">
                    <div>
                        <h4 class="text-lg font-semibold text-blue-600 mb-2">Product</h4>
                        <ul class="space ```html
                        <li><a class="text-white hover:text-blue-600" href="#">Overview</a></li>
                        <li><a class="text-white hover:text-blue-600" href="#">Features</a></li>
                        <li><a class="text-white hover:text-blue-600" href="#">Solutions</a></li>
                        <li><a class="text-white hover:text-blue-600" href="#">Pricing</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold text-blue-600 mb-2">Company</h4>
                        <ul class="space-y-1">
                            <li><a class="text-white hover:text-blue-600" href="#">About Us</a></li>
                            <li><a class="text-white hover:text-blue-600" href="#">Careers</a></li>
                            <li><a class="text-white hover:text-blue-600" href="#">Press</a></li>
                            <li><a class="text-white hover:text-blue-600" href="#">News</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold text-blue-600 mb-2 fade-in">Support</h4>
                        <ul class="space-y-1">
                            <li><a class="text-white hover:text-blue-600" href="#">Help Center</a></li>
                            <li><a class="text-white hover:text-blue-600" href="#">Contact Us</a></li>
                            <li><a class="text-white hover:text-blue-600" href="#">Privacy Policy</a></li>
                            <li><a class="text-white hover:text-blue-600" href="#">Terms</a></li>
                        </ul>
                    </div>
                </div>
                <div class="text-center md:text-left fade-in">
                    <h4 class="text-lg font-semibold text-blue-600 mb-2">Contact us</h4>
                    <p class="text-white">1234567890</p>
                    <p class="text-white">trackify@gmail.com</p>
                    <div class="flex space-x-4 mt-4">
                        <a class="text-orange-500 hover:text-blue-600" href="#"><i class="fab fa-facebook-f"></i></a>
                        <a class="text-orange-500 hover:text-blue-600" href="#"><i class="fab fa-twitter"></i></a>
                        <a class="text-orange-500 hover:text-blue-600" href="#"><i class="fab fa-instagram"></i></a>
                        <a class="text-orange-500 hover:text-blue-600" href="#"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <script>
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
    document.getElementById('mobile-menu').classList.remove('hidden');
});

document.getElementById('close-mobile-menu').addEventListener('click', function() {
    document.getElementById('mobile-menu').classList.add('hidden');
});
document.addEventListener('DOMContentLoaded', function() {
    const fadeElements = document.querySelectorAll('.fade-in');

    const observerOptions = {
        root: null, 
        rootMargin: '0px',
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target); 
            }
        });
    }, observerOptions);

    fadeElements.forEach(element => {
        observer.observe(element);
    });
});
    </script>
</body>
</html>
