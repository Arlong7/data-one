<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Navigation Bar</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
  <style>
    /* Custom styles for improved design */
    .nav-item {
      transition: color 0.3s ease, border-bottom 0.3s ease;
    }
    .nav-item:hover {
      color: #2563eb; /* blue-700 */
      border-bottom: 2px solid #2563eb; /* blue-700 */
    }
    .mobile-menu a:hover {
      background-color: #f3f4f6; /* gray-100 */
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="bg-white shadow-lg">
    <div class="max-w-6xl mx-auto px-4">
      <div class="flex justify-between items-center">
        <!-- Logo -->
        <a href="index.php" class="flex items-center py-4 px-2">
          <span class="font-semibold text-blue-500 text-lg">Hostit</span>
        </a>
        <!-- Primary Navbar items -->
        <div class="hidden md:flex items-center space-x-1">
          <a href="index.php" class="nav-item py-4 px-2 text-blue-500 border-blue-500 font-semibold">Home</a>
          <a href="about.php" class="nav-item py-4 px-2 text-blue-500 font-semibold">About</a>
          <a href="employee.php" class="nav-item py-4 px-2 text-blue-500 font-semibold">Employee</a>
          <a href="staymember.php" class="nav-item py-4 px-2 text-blue-500 font-semibold">StayMember</a>
          <a href="complete_member.php" class="nav-item py-4 px-2 text-blue-500 font-semibold">Complete_member</a>
          <a href="alternate_member.php" class="nav-item py-4 px-2 text-blue-500 font-semibold">Alternate_member</a>
          <a href="movesout.php" class="nav-item py-4 px-2 text-blue-500 font-semibold">Movesout</a>
          <a href="login.php" class="nav-item py-4 px-2 text-blue-500 font-semibold">Sign In</a>
          <a href="register.php" class="nav-item py-4 px-2 text-blue-500 font-semibold">Sign Up</a>
        </div>
        <!-- Mobile menu button -->
        <div class="md:hidden flex items-center">
          <button class="outline-none mobile-menu-button">
            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
            </svg>
          </button>
        </div>
      </div>
    </div>
  </nav>
  <script>
    const btn = document.querySelector("button.mobile-menu-button");
    const menu = document.querySelector(".mobile-menu");
    btn.addEventListener("click", () => {
      menu.classList.toggle("hidden");
    });
  </script>
  <!-- Main Content -->
  <div class="container mx-auto p-4">
    <!-- Add your page content here -->
  </div>
</body>
</html>
