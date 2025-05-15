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

    /* معالج النقر على أزرار + و- */
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
    paymentForm.addEventListener("submit", (e) => {
        e.preventDefault();
        const name = document.querySelector("#name").value;
        const address = document.querySelector("#address").value;
        const email = document.querySelector("#email").value;
        const card = document.querySelector("#card").value;
        const cvv = document.querySelector("#cvv").value;

        // هنا يمكنك إضافة منطق لمعالجة بيانات الدفع (مثل إرسالها إلى خادم)
        console.log("Payment Details:", { name, address, email, card, cvv });

        // إظهار نافذة الدفع الناجح
        document.querySelector("#success-popup").style.display = "flex";

        // إفراغ العربة بعد الدفع
        cartItems = [];
        localStorage.setItem("cartItems", JSON.stringify(cartItems));
        displayCartItems();

        // إغلاق نافذة الدفع
        checkoutPopup.style.display = "none";
        paymentForm.reset(); // إعادة تعيين النموذج
    });

    /* معالج النقر على زر Cancel */
    cancelCheckout.addEventListener("click", () => {
        checkoutPopup.style.display = "none"; // إغلاق النافذة المنبثقة
        paymentForm.reset(); // إعادة تعيين النموذج
    });

    /* معالج النقر على زر "موافق" في نافذة الدفع الناجح */
    document.querySelector("#success-ok-btn").addEventListener("click", () => {
        document.querySelector("#success-popup").style.display = "none";
    });

    /* عرض المنتجات عند تحميل الصفحة */
    displayCartItems();
});