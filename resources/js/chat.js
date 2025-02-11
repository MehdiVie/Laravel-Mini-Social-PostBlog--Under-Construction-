import DOMPurify from "dompurify";


export default class Chat {
  constructor() {
    this.openedYet = false;
    this.chatWrapper = document.querySelector("#chat-wrapper");
    this.avatar = document.querySelector("#chat-wrapper").dataset.avatar;
    this.openIcon = document.querySelector(".header-chat-icon");
    this.injectHTML();
    this.chatLog = document.querySelector("#chat");
    this.chatField = document.querySelector("#chatField");
    this.chatForm = document.querySelector("#chatForm");
    this.closeIcon = document.querySelector(".chat-title-bar-close");
    this.openConnection(); // Ensure connection is always opened
    this.events();
  }

  // Events
  events() {
    this.chatForm.addEventListener("submit", (e) => {
      e.preventDefault();
      this.sendMessageToServer();
    });
    this.openIcon.addEventListener("click", () => this.showChat());
    this.closeIcon.addEventListener("click", () => this.hideChat());
  }

  // Methods
  sendMessageToServer() {
    const test = document.createElement("div");
    test.innerHTML = DOMPurify.sanitize(this.chatField.value);

    axios.post("/send-chat-message", { textvalue: this.chatField.value });

    this.chatLog.insertAdjacentHTML(
      "beforeend",
      DOMPurify.sanitize(`
    <div class="chat-self">
        <div class="chat-message">
          <div class="chat-message-inner">
            ${test.textContent}
          </div>
        </div>
        <img class="chat-avatar avatar-tiny" src="${this.avatar}">
      </div>
    `)
    );
    this.chatLog.scrollTop = this.chatLog.scrollHeight;
    this.chatField.value = "";
    this.chatField.focus();
  }

  hideChat() {
    this.chatWrapper.classList.remove("chat--visible");
  }

  showChat() {
    console.log("Chat opened.");
    if (!this.openedYet) {
      console.log("Initializing connection...");
      this.openConnection();
    }
    this.openedYet = true;
    this.chatWrapper.classList.add("chat--visible");
    this.chatField.focus();
  }
  

  openConnection() {
    // Ensure this method is called only once
    if (this.connectionOpened) {
      console.log("Connection already opened.");
      return;
    }
  
    console.log("Attempting to connect to chatchannel...");
    this.connectionOpened = true; // Mark the connection as opened
  
    window.Echo.channel("chatchannel").listen("ChatMessage", (e) => {
      console.log("Event received in chat.js in openConnection function.js", e);
      this.displayMessageFromServer(e.chat);
    });
  }
  

  displayMessageFromServer(data) {
    console.log("Displaying message from server:", data);
    if (!this.chatWrapper.classList.contains("chat--visible")) {
      this.showChat(); // Open the chat window for the receiver
    }
    this.chatLog.insertAdjacentHTML(
      "beforeend",
      DOMPurify.sanitize(`
    <div class="chat-other">
        <a href="/profile/${data.username}"><img class="avatar-tiny" src="${data.avatar}"></a>
        <div class="chat-message"><div class="chat-message-inner">
          <a href="/profile/${data.username}"><strong>${data.username}:</strong></a>
          ${data.textvalue}
        </div></div>
      </div>
    `)
    );
    this.chatLog.scrollTop = this.chatLog.scrollHeight;
  }

  injectHTML() {
    this.chatWrapper.classList.add("chat-wrapper--ready");
    this.chatWrapper.innerHTML = `
    <div class="chat-title-bar">Chat <span class="chat-title-bar-close"><i class="fas fa-times-circle"></i></span></div>
    <div id="chat" class="chat-log"></div>
    
    <form id="chatForm" class="chat-form border-top">
      <input type="text" class="chat-field" id="chatField" placeholder="Type a message…" autocomplete="off">
    </form>
    `;
  }
}
