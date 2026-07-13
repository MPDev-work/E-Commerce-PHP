<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/output.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <title>Document</title>
</head>
<body>
    <nav
      id="nav-shadow"
      class="fixed z-[999] inset-x-0 top-0 h-[100px] flex flex-col justify-between bg-white/80 backdrop-blur-[50px] px-[20px]"
      >
      <div class="flex flex-row justify-between items-center pt-[6px]">
        <a href="index.php" class="poppins uppercase font-semibold text-4xl">
          wexh
          <span class="text-gray-400 pl-2">Store</span>
        </a>
        <form class="relative flex w-[400px] h-[50px] justify-center items-center">
          <button
            type="submit"
            class="bi bi-search text-white absolute right-1.5 cursor-pointer bg-[#dc3545] rounded-full h-[80%] px-5"
          ></button>
          <input
            class="w-full h-full text-[16px] pl-4 pr-17 text-black border-1 border-gray-200 outline-0 rounded-full transition duration-300 hover:border-[#dc3545] focus:border-[#dc3545] "
            type="text"
            placeholder="Search"
            name="search"
          />
        </form>
        <div class="relative h-[40px] flex justify-center items-center">
          <div class="flex flex-row justify-center items-center gap-5 mr-5">
            <a
              href="../auth/login.php"
              class="h-10 px-5 border-1 border-black text-base flex justify-center items-center transition duration-200 hover:bg-black hover:text-white hover:border-transparent"
            >
              Login
            </a>
            <a
              href="../auth/register.php"
              class="h-10 px-4 border-1 border-black text-base flex justify-center items-center transition duration-200 hover:bg-black hover:text-white hover:border-transparent"
            >
              Register
            </a>
          </div>
          <a
            href="/notification"
            class="bi bi-bell h-full w-auto text-[32px] pl-6 border-l-2 border-gray-300 flex justify-center items-center"
          ></a>
          <a
            href="/bag"
            class="bi bi-bag h-full w-auto text-[32px] pl-6 flex justify-center items-center"
          ></a>
        </div>
      </div>
      <ul class="flex flex-row items-center gap-6 pb-[5px]">
        <a href="index.php">
          Home
        </a>
        <a href="shopAll.php">
          Shop All
        </a>
        <a href="category.php">
          Category
        </a>
        <a href="brand.php">
          Brand
        </a>
        <a href="freeDelivery.php">
          Free delivery
        </a>
        <a href="../src/pages/explore.php">
          Explore
        </a>
      </ul>
    </nav>
    <section class="h-max w-screen flex flex-col gap-4 mt-[120px]">
        <h1 class="uppercase font-semibold text-4xl pl-[20px]">
          all new product now available
        </h1>
        <p class="text-sm text-gray-500 pl-[20px]">
          Ads - Swap left to explore & Click to add to bag
        </p>
        <div class="flex flex-row justify-start items-center gap-[20px] overflow-x-auto overflow-y-hidden px-[20px] mt-[8px] no-scrollbar">
          <!-- {posters.map((poster) => ( -->
            <img
              class="h-[400px] w-auto rounded-2xl"
              key={poster.id}
              src={poster.src}
              alt="Poster"
            />
          <!-- ))} -->
        </div>
      </section>
      <section
        class="h-max w-screen flex flex-col gap-[10px] mt-[50px]"
        id="bestSeller"
      >
        <h1 class="uppercase font-semibold text-4xl pl-[20px]">
          best store of the year
        </h1>
        <div class="flex flex-row justify-between items-center px-[20px] mt-[40px]">
          <!-- {vendors.map((vendor) => ( -->
            <div
              key={vendor.id}
              class="w-[400px] h-max flex flex-col gap-5 pb-[20px]"
            >
              <img
                class="h-auto w-full aspect-square object-cover rounded-t-3xl"
                src={vendor.src}
                alt={vendor.alt}
              />
              <div class="flex flex-col justify-start items-start px-[20px] gap-3">
                <div class="flex flex-row gap-3 justify-start items-center">
                  <img
                    class="h-[60px] aspect-square rounded-full"
                    src={vendor.src}
                    alt={vendor.alt}
                  />
                  <h1 class="text-3xl font-semibold">{vendor.name}</h1>
                </div>
                <p class="text-xl leading-6.5">
                  Sold up to 25K+ sold of their Product and got best seller
                  award of th year.
                </p>
                <div class="w-full flex flex-row justify-center items-center gap-6 mt-[6px]">
                  <button class="px-[20px] py-[15px] rounded-full text-white bg-[#dc3545] cursor-pointer">
                    Visit {vendor.btnName}
                  </button>
                  <button class="px-[20px] py-[15px] rounded-full text-[#dc3545] border-1 border-[#dc3545] cursor-pointer ">
                    Explore product
                  </button>
                </div>
              </div>
            </div>
          <!-- ))} -->
        </div>
      </section>
      <!-- {/* Product Ads section */} -->
      <section class="w-screen flex flex-col justify-center items-center mt-[60px]">
        <h1 class="uppercase text-[58px] font-semibold mb-[60px] bg-gradient-to-r from-orange-200 to-stone-500 bg-clip-text text-transparent">
          take a look with our new product
        </h1>
        <div class="h-max w-[90%] flex flex-row justify-between items-center">
          <img
            class="h-[450px] w-auto aspect-square object-cover rounded-[40px]"
            src={Ads1}
            alt="ordairy"
          />
          <div class="flex flex-col gap-3 justify-center items-center">
            <h1 class=" uppercase text-balance font-semibold text-[42px] text-center leading-14">
              get a new experience with <span>ORDINARY</span>
            </h1>
            <p class="text-[28px] font-medium leading-10">
              Available in stock now
            </p>
            <p class=" text-[20px] text-amber-600">Pre order start 12.03</p>
            <div class="flex flex-row justify-center items-center gap-5 mt-3">
              <button class="px-[24px] py-[14px] rounded-full border-1 border-[#dc3545] bg-[#dc3545] text-white">
                Learn more
              </button>
              <button class="px-[24px] py-[14px] rounded-full border-1 border-[#dc3545] text-[#dc3545]">
                Add to bag
              </button>
            </div>
          </div>
        </div>
        <div class="h-max w-[90%] flex flex-row justify-between items-center mt-[50px] gap-12">
          <div class="flex flex-col gap-3 justify-center items-center">
            <h1 class="uppercase text-balance font-semibold text-[42px] text-center leading-14">
              small thing still important just like <span>NUEBIOME</span>.
            </h1>
            <p class="text-[28px] font-medium leading-10">
              All product is now available
            </p>
            <p class="text-[20px] text-amber-600">Pre order start 12.03</p>
            <div class="flex flex-row justify-center items-center gap-5 mt-3">
              <button class="px-[24px] py-[14px] rounded-full border-1 border-[#dc3545] bg-[#dc3545] text-white">
                Learn more
              </button>
              <button class="px-[24px] py-[14px] rounded-full border-1 border-[#dc3545] text-[#dc3545]">
                Add to bag
              </button>
            </div>
          </div>
          <img
            class="h-[450px] w-auto aspect-square object-cover rounded-[40px]"
            src={Ads2}
            alt="ordairy"
          />
        </div>
      </section>
      <!-- {/* top collection */} -->
      <section class="h-max w-screen flex flex-col mt-[80px] justify-center items-center px-5">
        <h1 class="uppercase text-[58px] font-semibold mb-[60px] bg-gradient-to-r from-teal-800/60 to-gray-300 bg-clip-text text-transparent">
          explore our collection line up
        </h1>
        <img
          class="w-full h-[700px] rounded-[50px] object-cover"
          src={Collection1}
          alt="product collection"
        />
      </section>
      <!-- {/* local collection */} -->
      <section class="h-max w-screen flex flex-col justify-center items-center px-5 rounded-[50px]">
        <h1 class="uppercase text-[58px] font-semibold my-[50px] bg-gradient-to-r from-[#ECCCCD] to-[#70B0C2] bg-clip-text text-transparent">
          best local collection of the year
        </h1>
        <div class="w-full h-max flex flex-row justify-between items-center px-[20px] gap-[20px] bg-gray-100 rounded-[70px] pt-5">
          <!-- {/* Loop all vendor */} -->
          <!-- {Collections.map((collection) => ( -->
            <div
              key={collection.id}
              class="h-max w-[calc(100%/3-40px/3)] flex flex-col gap-5 pb-[20px] justify-center items-center"
            >
              <img
                class="h-auto w-full aspect-square object-cover rounded-[50px]"
                src={collection.src}
                alt={collection.alt}
              />
              <div class="flex flex-col justify-center items-center px-[20px] gap-3">
                <p class="text-4xl font-semibold">{collection.brand}</p>
                <p class="text-xl leading-6.5 text-center font-medium capitalize text-balance">
                  {collection.title}
                </p>
                <p class="text-[16px] text-amber-600">Available in stock</p>
                <div class="flex flex-row justify-start items-center gap-6 mt-[6px]">
                  <button class="px-[20px] py-[14px] rounded-full text-white bg-[#dc3545] cursor-pointer">
                    Learn more
                  </button>
                  <button class="px-[20px] py-[14px] rounded-full text-[#dc3545] border-1 border-[#dc3545] cursor-pointer ">
                    Add to bag
                  </button>
                </div>
              </div>
            </div>
          <!-- ))} -->
        </div>
      </section>

</body>
</html>