function toggleChat(){
    const chatWindow=document.getElementById("chatWindow");


if(chatWindow.style.display==="block"){
    chatWindow.style.display="none";
}
else{
    chatWindow.style.display="block";
    document.getElementById("userMessage").focus();
}

}

function sendMessage(){
    const input=document.getElementById("userMessage");
    const messages=document.getElementById("chatMessages");
    const message=input.value.trim();

    if(message === ""){
        return;
    }

    const userMessage=document.createElement("p");
    userMessage.className="user-message";
    userMessage.textContent=message;
    messages.appendChild(userMessage);

    input.value="";

    const botMessage=document.createElement("p");
    botMessage.className="bot-message";
    botMessage.textContent="Thanks for your Message! Real AI Response will be connected soon";

    messages.appendChild(botMessage);

    messages.scrollTop=messages.scrollHeight;
}