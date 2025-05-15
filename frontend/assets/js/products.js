/* قائمة ثابتة للمنتجات (مطابقة لـ index.html) */
const productsList = [
    { id: "1", name: "Mascara BioNike", price: 250, image: "../assets/images/cos-1.jpg", category: "cosmetics" },
    { id: "2", name: "Kohl BioNike", price: 200, image: "../assets/images/cos-2.jpg", category: "cosmetics" },
    { id: "3", name: "lipGloss BioNike", price: 160, image: "../assets/images/cos-3.jpg", category: "cosmetics" },
    { id: "4", name: "Maalox Plus", price: 120, image: "../assets/images/malox plus.jpg", category: "regular" },
    { id: "5", name: "Bruffen 600", price: 130, image: "../assets/images/brufen.jpg", category: "regular" },
    { id: "6", name: "Strepsils Orange", price: 145, image: "../assets/images/strepsils.jpg", category: "regular" },
    { id: "7", name: "Trittico 100 mg", price: 50, image: "../assets/images/trittico.jpg", category: "controlled" },
    { id: "8", name: "Xigduo 5 mg/1000 mg", price: 53, image: "../assets/images/xigduo.jpg", category: "controlled" },
    { id: "9", name: "Pantoloc 20 mg", price: 65, image: "../assets/images/pantoloc.jpg", category: "controlled" },
    { id: "10", name: "He Nordea Serum", price: 81, image: "../assets/images/cos-4.jpg", category: "cosmetics" },
    { id: "11", name: "Eico Joya Nourishing Hair Conditioner", price: 320, image: "../assets/images/cos-5.jpg", category: "cosmetics" },
    { id: "12", name: "Concealer Collagra", price: 290, image: "../assets/images/cos-6.jpg", category: "cosmetics" },
    { id: "13", name: "Mixtard 30", price: 120, image: "../assets/images/mixtrad.jpg", category: "regular" },
    { id: "14", name: "Panadol Extra", price: 220, image: "../assets/images/panadol.jpg", category: "regular" },
    { id: "15", name: "Panadol Cold & Flu", price: 120, image: "../assets/images/pas-c&f.jpg", category: "regular" },
    { id: "16", name: "L-Carnitine", price: 85, image: "../assets/images/L-c.jpg", category: "controlled" },
    { id: "17", name: "alzmenda", price: 95, image: "../assets/images/alzmenda.jpg", category: "controlled" },
    { id: "18", name: "Apexidonea", price: 90, image: "../assets/images/apex.jpg", category: "controlled" },
    { id: "19", name: "Dago Shampoo", price: 290, image: "../assets/images/cos-7.jpg", category: "cosmetics" },
    { id: "20", name: "Foundation Collagra", price: 290, image: "../assets/images/cos-8.jpg", category: "cosmetics" },
    { id: "21", name: "Otrivin (Adults)", price: 35, image: "../assets/images/otrivin1.jpg", category: "regular" },
    { id: "22", name: "Otrivin (children)", price: 25, image: "../assets/images/otrivin2.jpg", category: "regular" },
    { id: "23", name: "Nolvadex", price: 105, image: "../assets/images/nolvadex.jpg", category: "controlled" },
    { id: "24", name: "Asmakast", price: 80, image: "../assets/images/asmakast.jpg", category: "controlled" }
];

/* تهيئة الكود بعد تحميل الصفحة */
document.addEventListener("DOMContentLoaded", () => {
    /* اختيار العناصر من DOM */
    const buttons = document.querySelectorAll(".filter-btn");
    const boxes = document.querySelectorAll(".box");
    const seeMoreContainer = document.querySelector(".buttons");
    const seeMore = document.querySelector(".buttons button");
    const cartCounts = document.querySelectorAll(".cart-count");
    const addToCartButtons = document.querySelectorAll(".add-to-cart");
    const stickyCart = document.querySelector(".sticky-cart");
    const cartIcons = document.querySelectorAll(".cart-icon");
    let visibleProducts = 8;
    let isAllShown = false;
    let cartItems = JSON.parse(localStorage.getItem("cartItems")) || [];

    /* فحص البيانات في cartItems عشان نتجنب NaN */
    cartItems = cartItems.filter(item => 
        item && 
        typeof item.price === 'number' && !isNaN(item.price) && 
        typeof item.quantity === 'number' && !isNaN(item.quantity)
    );
    localStorage.setItem("cartItems", JSON.stringify(cartItems));

    /* التحقق من وجود العناصر */
    if (!buttons.length || !boxes.length || !seeMore || !seeMoreContainer || !cartCounts.length || !addToCartButtons.length || !stickyCart || !cartIcons.length) {
        return;
    }

    /* تحديث العدادات فورًا */
    cartCounts.forEach((count) => {
        const totalQuantity = cartItems.reduce((total, item) => total + (Number(item.quantity) || 0), 0);
        count.textContent = totalQuantity;
    });


    /* دالة لتحديث رؤية المنتجات */
    function updateProducts(category) {
        let shownCount = 0;
        let displayIndex = 0;

        boxes.forEach((box, index) => {
            if (category === "all") {
                if (index < visibleProducts) {
                    box.style.display = "block";
                    box.style.setProperty('--index', displayIndex);
                    shownCount++;
                    displayIndex++;
                } else {
                    box.style.display = "none";
                }
            } else if (box.getAttribute("data-category") === category) {
                box.style.display = "block";
                box.style.setProperty('--index', displayIndex);
                shownCount++;
                displayIndex++;
            } else {
                box.style.display = "none";
            }
        });

        console.log(`Category: ${category}, Products shown: ${shownCount}`);

        if (category === "all") {
            seeMoreContainer.classList.add("active");
            if (shownCount >= boxes.length) {
                seeMore.textContent = "Hide More";
                isAllShown = true;
            } else {
                seeMore.textContent = "See More";
                isAllShown = false;
            }
        } else {
            seeMoreContainer.classList.remove("active");
        }
    }

    /* معالج النقر على أزرار الفلترة */
    buttons.forEach((button) => {
        button.addEventListener("click", () => {
            buttons.forEach((btn) => btn.classList.remove("active"));
            button.classList.add("active");
            visibleProducts = 4;
            const category = button.getAttribute("data-category");
            console.log(`Selected category: ${category}`);
            updateProducts(category);
        });
    });

    /* معالج النقر على زر See More */
    seeMore.addEventListener("click", () => {
        if (isAllShown) {
            visibleProducts = 8;
        } else {
            visibleProducts += 8;
        }
        updateProducts("all");
    });

    /* معالج النقر على أيقونة العربة في المنتجات */
    addToCartButtons.forEach((button) => {
        button.addEventListener("click", () => {
            const productId = button.closest(".box").getAttribute("data-id");
            const product = productsList.find(p => p.id === productId);
            if (product) {
                const existingItem = cartItems.find(item => item.id === productId);
                if (existingItem) {
                    existingItem.quantity += 1;
                } else {
                    cartItems.push({ ...product, quantity: 1 });
                }
                localStorage.setItem("cartItems", JSON.stringify(cartItems));
                const totalQuantity = cartItems.reduce((total, item) => total + (Number(item.quantity) || 0), 0);
                cartCounts.forEach((count) => {
                    count.textContent = totalQuantity;
                });
                console.log(`Added product ID: ${productId}, Cart count: ${totalQuantity}`);

                // إضافة رسالة تنبيهية
                showNotification(`Added To Cart <span class="product-name">${product.name}</span> Successfully`);

                cartCounts.forEach((count) => {
                    count.classList.add("active");
                    setTimeout(() => {
                        count.classList.remove("active");
                    }, 200);
                });
            }
        });
    });

// دالة لعرض التنبيه
    function showNotification(message) {
        // إنشاء عنصر التنبيه
        const notification = document.createElement("div");
        notification.className = "cart-notification";
        notification.innerHTML = message;

        // إضافة العنصر للـ body
        document.body.appendChild(notification);

        // إزالة التنبيه بعد 3 ثواني
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
    /* ربط أيقونات العربة بصفحة cart.html */
    cartIcons.forEach((icon) => {
        icon.addEventListener("click", () => {
            window.location.href = "./pages/cart.html"; // تأكد إن المسار صحيح
        });
    });

    // cartIcons.forEach((icon) => {
    //     icon.addEventListener("click", () => {
    //         window.location.href = "home.php?page=cart"; // تحويل إلى home.php?page=cart
    //     });
    // });

    /* إخفاء sticky-cart في أول 500 بيكسل وإظهارها بعد 500 بيكسل */
    function handleStickyCart() {
        const scrollPosition = window.scrollY;
        if (scrollPosition >= 4700) {
            stickyCart.classList.add("active");
        } else {
            stickyCart.classList.remove("active");
        }
    }

    window.addEventListener("scroll", handleStickyCart);
    handleStickyCart();

    /* التهيئة مع فئة All */
    console.log("Initializing page with All category");
    updateProducts("all");
});