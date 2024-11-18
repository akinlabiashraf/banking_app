<!doctype html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>
  <!-- Meta tags  -->
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta
    name="viewport"
    content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />

  <title>BloomSpring - Sign Up</title>
  <link rel="icon" type="image/png" href="images/favicon.png" />

  <!-- CSS Assets -->
  <link rel="stylesheet" href="css/app.css" />

  <!-- Javascript Assets -->
  <script src="js/app.js" defer></script>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com/" />
  <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&amp;display=swap"
    rel="stylesheet" />
  <script>
    /**
     * THIS SCRIPT REQUIRED FOR PREVENT FLICKERING IN SOME BROWSERS
     */
    localStorage.getItem("_x_darkMode_on") === "true" &&
      document.documentElement.classList.add("dark");
  </script>
</head>

<body x-data class="is-header-blur" x-bind="$store.global.documentBody">
  <!-- App preloader-->
  <div
    class="app-preloader fixed z-50 grid h-full w-full place-content-center bg-slate-50 dark:bg-navy-900">
    <div class="app-preloader-inner relative inline-block size-48"></div>
  </div>

  <!-- Page Wrapper -->
  <div
    id="root"
    class="min-h-100vh flex grow bg-slate-50 dark:bg-navy-900"
    x-cloak>
    <div class="fixed top-0 hidden p-6 lg:block lg:px-12">
      <a href="#" class="flex items-center space-x-2">
        <img class="size-12" src="images/app-logo.svg" alt="logo" />
        <p
          class="text-xl font-semibold uppercase text-slate-700 dark:text-navy-100">
          BloomSpring
        </p>
      </a>
    </div>
    <div class="hidden w-full place-items-center lg:grid">
      <div class="w-full max-w-lg p-6">
        <img
          class="w-full"
          x-show="!$store.global.isDarkModeEnabled"
          src="images/illustrations/dashboard-meet.svg"
          alt="image" />
        <img
          class="w-full"
          x-show="$store.global.isDarkModeEnabled"
          src="images/illustrations/dashboard-meet-dark.svg"
          alt="image" />
      </div>
    </div>
    <main
      class="flex w-full flex-col items-center bg-white dark:bg-navy-700 lg:max-w-md">
      <div class="flex w-full max-w-sm grow flex-col justify-center p-5">
        <div class="text-center">
          <img
            class="mx-auto size-16 lg:hidden"
            src="images/app-logo.svg"
            alt="logo" />
          <div class="mt-4">
            <h2
              class="text-2xl font-semibold text-slate-600 dark:text-navy-100">
              Welcome To BloomSpring
            </h2>
            <p class="text-slate-400 dark:text-navy-300">
              Please sign up to continue
            </p>
          </div>
        </div>

        <div class="mt-10 flex space-x-4">
          <button
            class="btn w-full space-x-3 border border-slate-300 font-medium text-slate-800 hover:bg-slate-150 focus:bg-slate-150 active:bg-slate-150/80 dark:border-navy-450 dark:text-navy-50 dark:hover:bg-navy-500 dark:focus:bg-navy-500 dark:active:bg-navy-500/90">
            <img
              class="size-5.5"
              src="images/logos/google.svg"
              alt="logo" />
            <span>Google</span>
          </button>
          <button
            class="btn w-full space-x-3 border border-slate-300 font-medium text-slate-800 hover:bg-slate-150 focus:bg-slate-150 active:bg-slate-150/80 dark:border-navy-450 dark:text-navy-50 dark:hover:bg-navy-500 dark:focus:bg-navy-500 dark:active:bg-navy-500/90">
            <img
              class="size-5.5"
              src="images/logos/github.svg"
              alt="logo" />
            <span>Github</span>
          </button>
        </div>
        <div class="my-7 flex items-center space-x-3">
          <div class="h-px flex-1 bg-slate-200 dark:bg-navy-500"></div>
          <p class="text-tiny+ uppercase">or sign up with email</p>

          <div class="h-px flex-1 bg-slate-200 dark:bg-navy-500"></div>
        </div>
        <form action="signup_controller.php" method="POST">
          <div class="mt-4 space-y-4">
            <label class="relative flex">
              <input
                class="form-input peer w-full rounded-lg bg-slate-150 px-3 py-2 pl-9"
                placeholder="fullname"
                type="text"
                name="fullname"
                required />
              <!-- SVG icons as before -->
            </label>
            <label class="relative flex">
              <input
                class="form-input peer w-full rounded-lg bg-slate-150 px-3 py-2 pl-9"
                placeholder="Username"
                type="text"
                name="username"
                required />
              <!-- SVG icons as before -->
            </label>
            <label class="relative flex">
              <input
                class="form-input peer w-full rounded-lg bg-slate-150 px-3 py-2 pl-9"
                placeholder="Email"
                type="email"
                name="email"
                required />
            </label>
            <label class="relative flex">
              <input
                class="form-input peer w-full rounded-lg bg-slate-150 px-3 py-2 pl-9"
                placeholder="Password"
                type="password"
                name="password"
                required />
            </label>
            <label class="relative flex">
              <input
                class="form-input peer w-full rounded-lg bg-slate-150 px-3 py-2 pl-9"
                placeholder="Repeat Password"
                type="password"
                name="repeat_password"
                required />
            </label>
            <!-- Checkbox and Sign Up Button as before -->
            <button
              type="submit"
              class="btn mt-10 h-10 w-full bg-primary font-medium text-white">
              Sign Up
            </button>
          </div>
        </form>

        <div class="mt-4 text-center text-xs+">
          <p class="line-clamp-1">
            <span>Already have an account? </span>
            <a
              class="text-primary transition-colors hover:text-primary-focus dark:text-accent-light dark:hover:text-accent"
              href="login.php">Sign In</a>
          </p>
        </div>
      </div>
    </main>
  </div>

  <!-- 
        This is a place for Alpine.js Teleport feature 
        @see https://alpinejs.dev/directives/teleport
      -->
  <div id="x-teleport-target"></div>
  <script>
    window.addEventListener("DOMContentLoaded", () => Alpine.start());
  </script>
</body>

<!-- Mirrored from lineone.piniastudio.com/pages-singup-2.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 13 Nov 2024 03:51:35 GMT -->

</html>