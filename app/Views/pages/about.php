<section class="bg-indigo-700 text-white py-20">
    <div class="container mx-auto px-6 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">About Our Company</h1>
        <p class="text-lg md:text-xl max-w-3xl mx-auto">
            We believe in innovation, quality, and customer care. Our mission is to make technology accessible and
            useful.
        </p>
    </div>
</section>

<section class=" py-16 bg-white">
    <div class="container mx-auto px-6">
        <div class="flex flex-col md:flex-row items-center gap-12">
            <div class="w-full md:w-1/2">
                <div class="placeholder h-64 md:h-80 flex items-center justify-center">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-16 w-16 text-gray-500 opacity-70"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                        />
                    </svg>
                </div>
            </div>
            <div class="md:w-1/2 space-y-4">
                <h2 class="text-3xl font-bold text-gray-900">Who We Are</h2>
                <p class="text-lg text-gray-600">
                    Our company was founded in 2015 with the goal of creating high-quality digital solutions for
                    businesses.
                    Over the years, we have helped more than 500 clients improve their processes and reach a new
                    level.
                </p>
                <p class="text-gray-600">
                    We specialize in web development, interface design, and digital marketing. Our team is
                    comprised of
                    professionals who love their work and strive for excellence.
                </p>
                <div class="mt-6">
                    <a
                        href="contact.php"
                        class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-indigo-700 transition"
                    >
                        Contact Us
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-16 bg-gray-100">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl font-bold text-center mb-12 text-gray-900">Our Mission and Values</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-lg shadow-md text-center">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-8 w-8 text-indigo-600"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"
                        />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3">Innovation</h3>
                <p class="text-gray-600">We are always looking for new solutions to stay one step ahead.</p>
            </div>
            <div class="bg-white p-8 rounded-lg shadow-md text-center">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-8 w-8 text-indigo-600"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3">Quality</h3>
                <p class="text-gray-600">Every project undergoes strict quality control and testing.</p>
            </div>
            <div class="bg-white p-8 rounded-lg shadow-md text-center">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-8 w-8 text-indigo-600"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z"
                        />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3">Trust</h3>
                <p class="text-gray-600">
                    We build long-term relationships with clients based on honesty and transparency.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="py-16 bg-white">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl font-bold text-center mb-12 text-gray-900">Our Team</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <?php foreach ($team as $member): ?>
                <div class="text-center">
                    <div
                        class="w-32 h-32 bg-gray-300 rounded-full mx-auto mb-4 flex items-center justify-center overflow-hidden">
                        <span class="text-gray-600 font-medium"><?= substr($member['name'], 0, 2) ?></span>
                    </div>
                    <h3 class="text-xl font-semibold"><?= $member['name'] ?></h3>
                    <p class="text-indigo-600"><?= $member['role'] ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>