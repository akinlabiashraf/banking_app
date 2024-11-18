<?php
include 'db_connection.php';

session_start();

?>
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

  <title>BloomSpring - Sign In</title>
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
          src="images/illustrations/dashboard-check.svg"
          alt="image" />
        <img
          class="w-full"
          x-show="$store.global.isDarkModeEnabled"
          src="images/illustrations/dashboard-check-dark.svg"
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
              Welcome Back
            </h2>
            <p class="text-slate-400 dark:text-navy-300">
              Please sign in to continue
            </p>
          </div>
        </div>
        <div class="mt-16">
          <form action="login_controller.php" method="POST">
            <?php
            // Check if there's an error message in the session and display it
            if (isset($_SESSION['invalid_data'])) {
              echo $_SESSION['invalid_data'];
              // Remove the error message from the session to prevent it from displaying again on subsequent page loads
              unset($_SESSION['invalid_data']);
            }
            ?>
            <label class="relative flex">
              <input
                class="form-input peer w-full rounded-lg bg-slate-150 px-3 py-2 pl-9 ring-primary/50 placeholder:text-slate-400 hover:bg-slate-200 focus:ring dark:bg-navy-900/90 dark:ring-accent/50 dark:placeholder:text-navy-300 dark:hover:bg-navy-900 dark:focus:bg-navy-900"
                placeholder="email"
                type="text"
                name="email"
                required />
              <!-- Username icon -->
            </label>
            <label class="relative mt-4 flex">
              <input
                class="form-input peer w-full rounded-lg bg-slate-150 px-3 py-2 pl-9 ring-primary/50 placeholder:text-slate-400 hover:bg-slate-200 focus:ring dark:bg-navy-900/90 dark:ring-accent/50 dark:placeholder:text-navy-300 dark:hover:bg-navy-900 dark:focus:bg-navy-900"
                placeholder="Password"
                type="password"
                name="password"
                required />
              <!-- Password icon -->
            </label>
            <button
              class="btn mt-10 h-10 w-full bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90"
              type="submit">
              Sign In
            </button>
          </form>

          <div class="mt-4 text-center text-xs+">
            <p class="line-clamp-1">
              <span>Dont have Account?</span>

              <a
                class="text-primary transition-colors hover:text-primary-focus dark:text-accent-light dark:hover:text-accent"
                href="signup.php">Create account</a>
            </p>
          </div>
          <div class="my-7 flex items-center space-x-3">
            <div class="h-px flex-1 bg-slate-200 dark:bg-navy-500"></div>
            <p>OR</p>
            <div class="h-px flex-1 bg-slate-200 dark:bg-navy-500"></div>
          </div>
          <div class="flex space-x-4">
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
        </div>
      </div>
      <div
        class="my-5 flex justify-center text-xs text-slate-400 dark:text-navy-300">
        <a href="#">Privacy Notice</a>
        <div class="mx-3 my-1 w-px bg-slate-200 dark:bg-navy-500"></div>
        <a href="#">Term of service</a>
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

</html>