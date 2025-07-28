<x-app-layout>
    <!DOCTYPE html>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Main Container -->
    <div class="max-w-screen-2xl mx-auto mt-6 px-8">
        
        <!-- Yellow Header Section -->
    <div class="bg-[#F4C04D] h-[250px] w-full flex justify-center items-start pt-8">
        <h2 class="text-4xl font-bold text-[#1D3557] mt-2">ABOUT US</h2>
    </div>

        <!-- White Content Box -->
        <div class="bg-white shadow-lg px-10 py-8 mt-0 max-w-[100%] mx-auto">

        <!-- Image Section (Centered & Overlapping) -->
        <div class="flex justify-center -mt-40 mb-6 relative z-10">
            <img src="/img/aboutus123.png" alt="About Us Image" class="w-[80%] rounded-lg shadow-lg mx-auto">
        </div>

                
            <div class="flex justify-center items-center text-center px-10">
        <div class="max-w-3xl custom-justify">
            <p class="text-lg leading-relaxed">
            Asia Pacific College (APC) is introducing the APC Clinic which serves as the primary healthcare provider at this college, dedicated to meeting the medical needs of its students, faculty, and staff. We are committed to delivering accessible, high-quality healthcare services that support the well-being of our academic community.              </p>

            <br>
            <p class="text-lg leading-relaxed">
            Our clinic provides a range of essential medical services, including free medical checkups, blood pressure monitoring, and access to necessary medications. This ensures that quality care remains within the reach of all. With our continuous effort to enhance campus healthcare, we are pleased to announce the upcoming addition of dental checkups as part of our expanding services.            </p>

            <br>
            <p class="text-lg leading-relaxed">
            At APC Clinic, we prioritize preventive care and wellness, creating a safe and supportive environment where healthcare is readily available. We are dedicated to providing compassionate, student-centered care, whether it is for routine consultations or urgent medical assistance, in order to help our community stay healthy and focused on their academic pursuits.            </p>
        </div>
    </div>

                <!-- Black Divider Line -->
                <div class="border-t-2 border-black w-full my-6"></div>

                <!-- Mission | Vision | Values -->
                <div class="grid grid-cols-3 gap-6 text-center text-gray-700">

                    <!-- Mission -->
                    <div>
                        <h3 class="text-xl font-bold text-[#1D3557]">Mission</h3>
                        <p class="text-md mt-2 leading-relaxed">
                            Our mission is to provide high-quality, patient-centered healthcare through 
                            innovative medical solutions and compassionate care. We are committed to promoting 
                            wellness, preventing illness, and ensuring timely, accurate diagnoses. 
                            By integrating advanced technology and a dedicated healthcare team, we strive to 
                            enhance the well-being of every patient in our community.
                        </p>
                    </div>

                    <!-- Vision -->
                    <div>
                        <h3 class="text-xl font-bold text-[#1D3557]">Vision</h3>
                        <p class="text-md mt-2 leading-relaxed">
                            Our vision is to become a leading healthcare provider known for excellence 
                            in medical services, patient safety, and holistic wellness. 
                            We aspire to create a healthier community by setting new standards in clinical care, 
                            research, and medical education. Through continuous innovation and collaboration, 
                            we aim to be a trusted healthcare partner for generations to come.
                        </p>
                    </div>

                    <style>
                        .custom-justify {
                            text-align: justify;
                        }
                    </style>

                    <!-- Values -->
                    <div>
                        <h3 class="text-xl font-bold text-[#1D3557]">Values</h3>
                        <p class="text-md mt-2 leading-relaxed">
                            We uphold compassion by treating every patient with empathy, dignity, and respect. 
                            <em>We strive for excellence</em>, maintaining the highest standards of medical care 
                            and continuous improvement. Integrity guides our practice, ensuring honesty, transparency, 
                            and ethical healthcare. We embrace innovation, utilizing advanced medical solutions 
                            to enhance patient care. Above all, we value community, prioritizing the well-being of 
                            students, faculty, and staff through accessible and comprehensive healthcare services.
                        </p>
                    </div>

                </div>

            </div>

        </div>

    </body>
    </html>

    <footer class="bg-prims-yellow-1 w-full h-16 mt-6 flex items-center pl-6 relative">
        <img src="img/apc-logo.svg" class="object-scale-down h-16">
        <p class="absolute inset-0 flex items-center justify-center w-full">
        Copyright © 2025<span class="ml-1 text-blue-500">Asia Pacific College</span>. All Rights Reserved.
        </p>
        </footer>

</x-app-layout>
