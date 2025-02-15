import './bootstrap'; // Ensure this loads Laravel Echo and Pusher
import Search from './live-search';
import Chat from './chat';
import Profile from './profile';

// Initialize Search feature if the search icon exists
if (document.querySelector(".header-search-icon")) {
    try {
        new Search();
    } catch (error) {
        console.error("Error initializing Search:", error);
    }
}

// Initialize Chat feature if the chat icon exists
if (document.querySelector(".header-chat-icon")) {
    try {
        new Chat();
    } catch (error) {
        console.error("Error initializing Chat:", error);
    }
}

// Initialize Profile feature if the profile navigation exists
if (document.querySelector(".profile-nav")) {
    try {
        new Profile();
    } catch (error) {
        console.error("Error initializing Profile:", error);
    }
}

// Listening to Chat events via Laravel Echo
Echo.channel('chatchannel')
    .listen('ChatMessage', (event) => {
        console.log(event.chat); // Log the broadcasted message to the browser console
    });

// Listening to Profile events via Laravel Echo
Echo.channel('profile-updates')
    .listen('ProfileUpdated', (event) => {
        console.log(event.data); // Log data sent by the event
        // Update the profile UI here, for example:
        document.querySelector('.profile-slot-content').innerHTML = event.data.theHTML;
        document.title = event.data.docTitle + " | OurApp"; // Update document title
    });
