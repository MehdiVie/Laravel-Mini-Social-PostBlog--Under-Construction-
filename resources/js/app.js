import './bootstrap';
import Search from './live-search';
import Chat from './chat';

if (document.querySelector(".header-search-icon")) {
    new Search();
}

if (document.querySelector(".header-chat-icon")) {
    new Chat();
}

import './bootstrap'; // Ensure this loads Laravel Echo and Pusher

Echo.channel('chatchannel')
    .listen('ChatMessage', (event) => {
        console.log(event.chat); // Log the broadcasted message to the browser console
    });

//Echo.connector.pusher.connection.bind('connected', () => {
//        console.log('Pusher connected successfully!');
//});
