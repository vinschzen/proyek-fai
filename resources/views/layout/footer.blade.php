<footer class="bg-gray-700 text-white p-8">
  <div class="container mx-auto">
      <div class="grid grid-cols-2 gap-4 m-4">
          <div>
              <h3 class="text-lg font-semibold mb-4">Contact Us</h3>
              <p>Email: info@yourwebsite.com</p>
              <p>Phone: +1 (123) 456-7890</p>
              <p>Address: 123 Main St, Cityville</p>
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
