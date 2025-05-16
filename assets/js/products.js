document.addEventListener("DOMContentLoaded", async () => {
    const buttons = document.querySelectorAll(".filter-btn");
    const boxHolder = document.querySelector(".box-holder");
    const seeMoreContainer = document.querySelector(".buttons");
    const seeMore = document.querySelector(".buttons button");
    const cartCounts = document.querySelectorAll(".cart-count");
    const stickyCart = document.querySelector(".sticky-cart");
    const cartIcons = document.querySelectorAll(".cart-icon");
    let visibleProducts = 8;
    let isAllShown = false;
    let cartItems = JSON.parse(localStorage.getItem("cartItems")) || [];
    let productsList = [];

    try {
        const response = await fetch("../api/get_products.php");
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        productsList = await response.json();
        if (!productsList.length) {
            boxHolder.innerHTML = "<p>No products available.</p>";
            console.error("No products found.");
            return;
        }
        console.log("Products fetched:", productsList);
    } catch (error) {
        boxHolder.innerHTML = "<p>Error loading products. Check console for details.</p>";
        console.error("Error fetching products:", error);
        return;
    }

    cartItems = cartItems.filter(item => 
        item && typeof item.price === 'number' && !isNaN(item.price) && 
        typeof item.quantity === 'number' && !isNaN(item.quantity)
    );
    localStorage.setItem("cartItems", JSON.stringify(cartItems));

    if (!buttons.length || !boxHolder || !seeMore || !seeMoreContainer || !cartCounts.length || !stickyCart || !cartIcons.length) {
        boxHolder.innerHTML = "<p>Error: Required elements not found in DOM.</p>";
        console.error("Required elements not found:", { buttons: buttons.length, boxHolder, seeMore, seeMoreContainer, cartCounts: cartCounts.length, stickyCart, cartIcons: cartIcons.length });
        return;
    }

    cartCounts.forEach((count) => {
        const totalQuantity = cartItems.reduce((total, item) => total + (Number(item.quantity) || 0), 0);
        count.textContent = totalQuantity;
    });

    function createProductBox(product, displayIndex) {
        const box = document.createElement("div");
        box.classList.add("box");
        box.setAttribute("data-category", product.category);
        box.setAttribute("data-id", product.id);
        box.style.display = "block";
        box.style.setProperty('--index', displayIndex);
        box.innerHTML = `
            <div class="image">
                <img src="${product.image}" alt="${product.name}" />
            </div>
            <div class="info">
                <h3>${product.name}</h3>
            </div>
            <div class="rate">
                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i>
                <span>4.0</span>
            </div>
            <div class="buy">
                <p>${product.price} EGP</p>
                <a class="add-to-cart" href="#products"><i class="fa-solid fa-cart-shopping"></i></a>
            </div>
        `;
        boxHolder.appendChild(box);
    }

    function updateProducts(category) {
        boxHolder.innerHTML = "";
        let shownCount = 0;
        let displayIndex = 0;

        productsList.forEach((product, index) => {
            if (category === "all") {
                if (index < visibleProducts) {
                    createProductBox(product, displayIndex);
                    shownCount++;
                    displayIndex++;
                }
            } else if (product.category === category) {
                createProductBox(product, displayIndex);
                shownCount++;
                displayIndex++;
            }
        });

        if (shownCount === 0) {
            boxHolder.innerHTML = "<p>No products found in this category.</p>";
        }

        console.log(`Category: ${category}, Products shown: ${shownCount}`);

        if (category === "all") {
            seeMoreContainer.classList.add("active");
            if (shownCount >= productsList.length) {
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

    seeMore.addEventListener("click", () => {
        if (isAllShown) {
            visibleProducts = 8;
        } else {
            visibleProducts += 8;
        }
        updateProducts("all");
    });

    boxHolder.addEventListener("click", async (e) => {
        if (e.target.closest(".add-to-cart")) {
            try {
                const response = await fetch("../api/check_session.php");
                if (!response.ok) throw new Error("Failed to check session");
                const data = await response.json();
                console.log("Session check response:", data);

                if (!data.logged_in) {
                    alert("Please log in to add items to the cart.");
                    window.location.href = "../pages/form.php";
                    return;
                }

                const box = e.target.closest(".box");
                const productId = box.getAttribute("data-id");
                const product = productsList.find(p => p.id == productId);
                if (product) {
                    const existingItem = cartItems.find(item => item.id == productId);
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
                    showNotification(`Added To Cart <span class="product-name">${product.name}</span> Successfully`);
                    cartCounts.forEach((count) => {
                        count.classList.add("active");
                        setTimeout(() => count.classList.remove("active"), 200);
                    });
                }
            } catch (error) {
                console.error("Error checking session:", error);
                alert("Error checking login status. Please try again.");
            }
        }
    });

    function showNotification(message) {
        const notification = document.createElement("div");
        notification.className = "cart-notification";
        notification.innerHTML = message;
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }

    cartIcons.forEach((icon) => {
        icon.addEventListener("click", () => {
            window.location.href = "../pages/cart.php";
        });
    });

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

    console.log("Initializing page with All category");
    updateProducts("all");
});