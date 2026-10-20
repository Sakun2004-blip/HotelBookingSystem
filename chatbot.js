document.addEventListener("DOMContentLoaded", () => {
    const chatWindow = document.getElementById("chatWindow");
    const chatToggle = document.getElementById("chatToggle");
    const chatClose = document.getElementById("chatClose");
    const chatForm = document.getElementById("chatForm");
    const input = document.getElementById("userMessage");
    const messages = document.getElementById("chatMessages");

    if (!chatWindow || !chatToggle || !chatClose || !chatForm || !input || !messages) {
        return;
    }

    const openChat = () => {
        chatWindow.classList.add("is-open");
        chatWindow.setAttribute("aria-hidden", "false");
        chatToggle.setAttribute("aria-expanded", "true");
        input.focus();
    };

    const closeChat = () => {
        chatWindow.classList.remove("is-open");
        chatWindow.setAttribute("aria-hidden", "true");
        chatToggle.setAttribute("aria-expanded", "false");
        chatToggle.focus();
    };

    const addMessage = (text, className, action) => {
        const message = document.createElement("p");
        message.className = className;
        message.textContent = text;
        if (action) {
            const link = document.createElement("a");
            link.className = "chat-action-link";
            link.href = action.href;
            link.textContent = action.label;
            message.append(document.createElement("br"), link);
        }
        messages.appendChild(message);
        messages.scrollTop = messages.scrollHeight;
    };

    const getAction = (message) => {
        const text = message.toLowerCase();
        if (/\b(book|booking|reserve|reservation|room availability)\b/.test(text)) {
            return { href: "login.php", label: "Sign in to book a room" };
        }
        if (/\b(facilit(y|ies)|pool|wifi|wi-fi|parking|spa|gym|fitness|restaurant|room service)\b/.test(text)) {
            return { href: "Facilities.html", label: "Explore hotel facilities" };
        }
        if (/\b(location|located|address|directions|map)\b/.test(text)) {
            return { href: "#locationSection", label: "View location on this page" };
        }
        if (/\b(contact|phone|email|call|staff|help|reception)\b/.test(text)) {
            return { href: "#contactSection", label: "View contact information" };
        }
        return null;
    };

    const getReply = (message) => {
        const text = message.toLowerCase();

        if (/\b(hi|hello|hey|good morning|good evening)\b/.test(text)) {
            return "Hello! Welcome to Grand Vila Hotel. What would you like to know about your stay?";
        }
        if (/\b(book|booking|reserve|reservation|room availability)\b/.test(text)) {
            return "To book a room, sign in or create an account, then open the booking page to choose an available room and your dates.";
        }
        if (/\b(facilit(y|ies)|pool|wifi|wi-fi|parking|spa|gym|fitness|restaurant|room service)\b/.test(text)) {
            return "Hotel facilities listed on our website include free Wi-Fi, a swimming pool, restaurant and room service, spa and fitness center, and complimentary parking.";
        }
        if (/\b(room|suite|amenit(y|ies)|air conditioning|bedding)\b/.test(text)) {
            return "Our rooms and suites are described as having modern amenities, plush bedding, air conditioning, high-speed Wi-Fi, and private bathrooms.";
        }
        if (/\b(location|located|address|directions|map)\b/.test(text)) {
            return "The location section on this page includes our map and visit information. Please check it for the latest directions and address details.";
        }
        if (/\b(contact|phone|email|call|staff|help|reception)\b/.test(text)) {
            return "Our website lists the hotel's contact information in the footer. For booking help, you can also sign in and use the booking page.";
        }
        if (/\b(payment|pay|checkout|check-out|check in|check-in)\b/.test(text)) {
            return "Choose your check-in and check-out dates on the booking page. After submitting a valid booking, the site continues to payment.";
        }
        if (/\b(thank|thanks)\b/.test(text)) {
            return "You're welcome! Is there anything else I can help you with?";
        }
        if (/\b(bye|goodbye)\b/.test(text)) {
            return "Thank you for visiting Grand Vila Hotel. We hope to welcome you soon!";
        }

        return "I can help with room bookings, facilities, room amenities, location, and contact information. Try asking about one of those topics.";
    };

    chatToggle.addEventListener("click", (event) => {
        event.preventDefault();
        if (chatWindow.classList.contains("is-open")) {
            closeChat();
        } else {
            openChat();
        }
    });

    chatClose.addEventListener("click", closeChat);

    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape" && chatWindow.classList.contains("is-open")) {
            closeChat();
        }
    });

    chatForm.addEventListener("submit", (event) => {
        event.preventDefault();
        const message = input.value.trim();
        if (!message) {
            return;
        }

        addMessage(message, "user-message");
        input.value = "";
        addMessage(getReply(message), "bot-message", getAction(message));
        input.focus();
    });

    messages.addEventListener("click", (event) => {
        const button = event.target.closest("[data-message]");
        if (!button) {
            return;
        }
        input.value = button.dataset.message;
        chatForm.requestSubmit();
    });
});