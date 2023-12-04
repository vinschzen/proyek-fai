<footer class="bg-gray-700 text-white p-8">
    <div class="container mx-auto">
        <div class="grid grid-cols-2 gap-4 m-4">
            <div>
                <h3 class="text-lg font-semibold mb-4">Contact Us</h3>
                <p>Email: txt@gmail.com</p>
                <p>Phone: +62 8123456789</p>
                <p>Address: Jl. Ngagel Jaya Tengah No.73-77</p>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                <ul>
                    <li><a href="{{ route('toHome') }}">Home</a></li>
                    <li><a>About Us</a></li>
                    <li><a>Contact</a></li>
                    <li><a>Services</a></li>
                </ul>
            </div>
        </div>
        <hr class="my-6 border-white">
        <p>&copy; {{ date('Y') }} Your Website. All rights reserved.</p>
    </div>
</footer>
