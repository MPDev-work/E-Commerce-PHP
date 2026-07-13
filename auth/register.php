<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/output.css" />
    <title>Document</title>
</head>
<body class="w-screen h-full flex justify-center items-center">
    <section class="bg-[#f2f2f6] w-screen scroll-smooth pb-12">
      <nav class="fixed top-0 left-0 right-0 h-[60px] flex items-center justify-between px-5 bg-white ">
        <a href="../client/index.php" class="text-black no-underline">
          <i class="bi bi-arrow-bar-left"></i> Back to home page
        </a>
        <a href="register.php">
          <h1 class="uppercase text-[40px] font-bold tracking-[-2px]">
            solis <span class="text-[#d3d3d6] ml-2">skin</span>
          </h1>
        </a>
        <h3 class="text-[20px] font-medium">Sign in to SOLIS SKIN</h3>
      </nav>
      <section class="flex flex-col items-center justify-center mt-40 gap-5">
        <div class="flex flex-col items-center gap-2.5 p-5 bg-white rounded-[45px]">
          <h3 class="text-[24px] mb-1">Enter your information</h3>
          <div class="w-full h-[1px] mt-[5px] mb-[10px] bg-[repeating-linear-gradient(to_right,#d6d6d6_0px,#d6d6d6_4px,transparent_5px,transparent_8px)]"></div>
          <form class="flex flex-col items-center gap-2.5" action="../controller/authController.php" method="POST">
            <label class="w-full text-left">.Full name</label>
            <input
              type="text"
              name="username"
              placeholder="Enter your name"
              class="w-[600px] h-[50px] bg-[#f2f2f6] text-base rounded-full px-4 outline-transparent focus:outline-1 focus:outline-black"
            />
            <label class="w-full text-left">.Email address</label>
            <input
              type="email"
              name="email"
              placeholder="example@gmail.com"
              class="w-[600px] h-[50px] bg-[#f2f2f6] text-base rounded-full px-4 outline-transparent focus:outline-1 focus:outline-black"
            />
            <label class="w-full text-left">.Password</label>
            <input
              name="pwd"
              type="password"
              placeholder="Enter password"
              class="w-[600px] h-[50px] bg-[#f2f2f6] text-[16px] rounded-full px-4 outline-transparent focus:outline-1 focus:outline-black"
            />
            <button class="w-[600px] h-[50px] bg-black text-white text-[20px] rounded-full cursor-pointer mt-5" type="submit" name="register">
              <i class="bi bi-person-fill"></i> Register Now
            </button>
          </form>
        </div>
        <div class="relative w-[640px] h-[20px] mt-5">
          <div class="w-full h-[1px] bg-gray-300 opacity-50"></div>
          <p class="absolute left-1/2 -translate-x-1/2 bottom-1/2 px-2 bg-[#f2f2f6] text-[18px]">
            or
          </p>
        </div>
        <div class="flex flex-col items-center gap-2.5">
          <div class="relative w-[640px] h-[50px] bg-white rounded-full flex items-center justify-center text-[20px] cursor-pointer">
            <i class="bi bi-google absolute left-2.5 text-[30px] flex justify-center items-center"></i>
            <p>Login with Google</p>
          </div>

          <div class="relative w-[640px] h-[50px] bg-white rounded-full flex items-center justify-center text-[20px] cursor-pointer">
            <i class="bi bi-facebook absolute left-2.5 text-[30px] flex justify-center items-center"></i>
            <p>Login with Facebook</p>
          </div>
        </div>
        <h3 class="text-[20px]">
          Have an Account?
          <a href="login.php" class="underline font-semibold">
            Login
          </a>
        </h3>
      </section>
    </section>
</body>
</html>