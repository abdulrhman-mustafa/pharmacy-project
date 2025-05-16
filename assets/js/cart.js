document.addEventListener("DOMContentLoaded", () => {
    const cartItemsContainer = document.querySelector(".cart-items");
    const totalPriceElement = document.querySelector("#total-price");
    const buyButton = document.querySelector(".buy-btn");
    const cartCounts = document.querySelectorAll(".cart-count");
    const checkoutPopup = document.querySelector("#checkout-popup");
    const paymentForm = document.querySelector("#payment-form");
    const cancelCheckout = document.querySelector("#cancel-checkout");
    let cartItems = JSON.parse(localStorage.getItem("cartItems")) || [];

    /* فحص البيانات في cartItems عشان نتجنب NaN */
    cartItems = cartItems.filter(item => 
        item && 
        typeof item.price === 'number' && !isNaN(item.price) && 
        typeof item.quantity === 'number' && !isNaN(item.quantity)
    );
    localStorage.setItem("cartItems", JSON.stringify(cartItems));

    /* دالة لعرض المنتجات في العربة */
    function displayCartItems() {
        cartItemsContainer.innerHTML = "";
        if (cartItems.length === 0) {
            cartItemsContainer.innerHTML = "<p>Your cart is empty.</p>";
        } else {
            cartItems.forEach((item, index) => {
                const itemElement = document.createElement("div");
                itemElement.classList.add("cart-item");
                itemElement.innerHTML = `
                    <img src="${item.image}" alt="${item.name}" style="width: 100px; height: 100px;">
                    <div class="item-details">
                        <h4>${item.name}</h4>
                        <p>Price = ${item.price.toFixed(2)} EGP</p>
                        <div class="quantity-controls">
                            <button class="decrease-btn" data-index="${index}">-</button>
                            <span>${item.quantity}</span>
                            <button class="increase-btn" data-index="${index}">+</button>
                        </div>
                        <p>Total = ${(item.price * item.quantity).toFixed(2)} EGP </p>
                        <button class="remove-btn" data-index="${index}">Remove</button>
                    </div>
                `;
                cartItemsContainer.appendChild(itemElement);
            });
        }
        updateTotalPrice();
        updateCartCount();
    }

    /* دالة لتحديث إجمالي السعر */
    function updateTotalPrice() {
        const total = cartItems.reduce((sum, item) => {
            const price = Number(item.price) || 0;
            const quantity = Number(item.quantity) || 0;
            return sum + price * quantity;
        }, 0);
        totalPriceElement.textContent = total.toFixed(2);
    }

    /* دالة لتحديث عدد المنتجات في العدادات */
    function updateCartCount() {
        const totalQuantity = cartItems.reduce((total, item) => total + (Number(item.quantity) || 0), 0);
        cartCounts.forEach((count) => {
            count.textContent = totalQuantity;
        });
    }

    /* إضافة زر Clear Cart */
    const clearButton = document.createElement("button");
    clearButton.textContent = "Clear Cart";
    clearButton.classList.add("clear-btn");
    document.querySelector(".cart-total").appendChild(clearButton);
    clearButton.addEventListener("click", () => {
        cartItems = [];
        localStorage.setItem("cartItems", JSON.stringify(cartItems));
        displayCartItems();
    });

    /* معالج النقر على أزرار + و- وRemove */
    cartItemsContainer.addEventListener("click", (e) => {
        if (e.target.classList.contains("increase-btn")) {
            const index = e.target.dataset.index;
            cartItems[index].quantity += 1;
            localStorage.setItem("cartItems", JSON.stringify(cartItems));
            displayCartItems();
        } else if (e.target.classList.contains("decrease-btn")) {
            const index = e.target.dataset.index;
            cartItems[index].quantity -= 1;
            if (cartItems[index].quantity <= 0) {
                cartItems.splice(index, 1);
            }
            localStorage.setItem("cartItems", JSON.stringify(cartItems));
            displayCartItems();
        } else if (e.target.classList.contains("remove-btn")) {
            const index = e.target.dataset.index;
            cartItems.splice(index, 1);
            localStorage.setItem("cartItems", JSON.stringify(cartItems));
            displayCartItems();
        }
    });

    /* معالج النقر على زر Buy */
    buyButton.addEventListener("click", () => {
        if (cartItems.length === 0) {
            alert("Your cart is empty!");
        } else {
            checkoutPopup.style.display = "flex"; // إظهار النافذة المنبثقة
        }
    });

    /* معالج إرسال نموذج الدفع */
    paymentForm.addEventListener("submit", async (e) => {
        e.preventDefault();

        const name = document.querySelector("#name").value.trim();
        const address = document.querySelector("#address").value.trim();
        const email = document.querySelector("#email").value.trim();
        const card = document.querySelector("#card").value.trim();
        const cvv = document.querySelector("#cvv").value.trim();

        // التحقق من البيانات
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!name) {
            alert("Please enter your name.");
            return;
        }
        if (!address) {
            alert("Please enter your address.");
            return;
        }
        if (!email || !emailRegex.test(email)) {
            alert("Please enter a valid email.");
            return;
        }
        if (!card || card.length < 16) {
            alert("Please enter a valid card number (at least 16 digits).");
            return;
        }
        if (!cvv || cvv.length < 3) {
            alert("Please enter a valid CVV (at least 3 digits).");
            return;
        }

        // إرسال بيانات الطلب للخادم
        const totalPrice = totalPriceElement.textContent;
        const orderData = {
            user_id: JSON.parse(sessionStorage.getItem("user"))?.id || 1, // افتراضي لو مفيش مستخدم
            total_price: totalPrice,
            name: name,
            address: address,
            email: email,
            cart_items: cartItems
        };

        try {
            const response = await fetch("../api/place_order.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(orderData)
            });

            const result = await response.json();
            if (result.success) {
                // إظهار نافذة الدفع الناجح
                document.querySelector("#success-popup").style.display = "flex";

                // إفراغ العربة بعد الدفع
                cartItems = [];
                localStorage.setItem("cartItems", JSON.stringify(cartItems));
                displayCartItems();

                // إغلاق نافذة الدفع
                checkoutPopup.style.display = "none";
                paymentForm.reset();
            } else {
                alert("Error placing order: " + result.message);
            }
        } catch (error) {
            console.error("Error:", error);
            alert("An error occurred while placing the order.");
        }
    });

    /* معالج النقر على زر Cancel */
    cancelCheckout.addEventListener("click", () => {
        checkoutPopup.style.display = "none";
        paymentForm.reset();
    });

    /* معالج النقر على زر "موافق" في نافذة الدفع الناجح */
    document.querySelector("#success-ok-btn").addEventListener("click", () => {
        document.querySelector("#success-popup").style.display = "none";
    });

    /* عرض المنتجات عند تحميل الصفحة */
    displayCartItems();
});