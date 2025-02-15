import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

export default class Profile {
  constructor() {
    this.links = document.querySelectorAll(".profile-nav a");
    this.contentArea = document.querySelector(".profile-slot-content");
    this.isAPI = !!document.querySelector("meta[name='api-enabled']");
    this.events();

    // Initialize Pusher (Laravel Echo)
    this.echo = new Echo({
      broadcaster: 'pusher',
      key: 'your-pusher-app-key',
      cluster: 'your-pusher-app-cluster',
      forceTLS: true
    });

    // Subscribe to a channel
    this.channel = this.echo.channel('profile-updates');
    this.channel.listen('ProfileUpdated', (event) => {
      console.log('Profile updated:', event);
      this.updateContent(event.data);
    });
  }

  events() {
    if (this.isAPI) {
      addEventListener("popstate", () => {
        this.handleChange();
      });
      this.links.forEach(link => {
        link.addEventListener("click", e => this.handleLinkClick(e));
      });
    }
  }

  async handleChange() {
    this.links.forEach(link => link.classList.remove("active"));
    this.links.forEach(async link => {
      if (link.getAttribute("href") == window.location.pathname) {
        const response = await axios.get(link.href + "/raw");
        this.updateContent(response.data);
        link.classList.add("active");
      }
    });
  }

  async handleLinkClick(e) {
    e.preventDefault();
    this.links.forEach(link => link.classList.remove("active"));
    e.target.classList.add("active");
    const response = await axios.get(e.target.href + "/raw");
    this.contentArea.innerHTML = DOMPurify.sanitize(response.data.theHTML);
    document.title = response.data.docTitle + " | OurApp";
    history.pushState({}, "", e.target.href);
  }

  updateContent(data) {
    this.contentArea.innerHTML = DOMPurify.sanitize(data.theHTML);
    document.title = data.docTitle + " | OurApp";
  }
}
