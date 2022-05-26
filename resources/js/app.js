/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;
import VueChatScroll from 'vue-chat-scroll';
Vue.use(VueChatScroll);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('chats', require('./components/ChatsComponent.vue').default);
Vue.component('reports', require('./components/ReportsComponent.vue').default);
Vue.component('VisitorsToday', require('./components/Visuals/VisitorsToday.vue').default);
Vue.component('AnsweredChat', require('./components/Visuals/AnsweredChat.vue').default);
Vue.component('MissedChat', require('./components/Visuals/MissedChat.vue').default);
Vue.component('HourlyVisitor', require('./components/Visuals/HourlyVisitor.vue').default);
Vue.component('profile-edit-form', require('./components/ProfileUpdateComponent.vue').default);
Vue.component('vue-multiselect', window.VueMultiselect.default)
Vue.component('widget-scheduler-picker', require('./components/WidgetSchedulePicker.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});

     
/* Code used to copy widget code to the clipboard */
function copyContentsToClipboard(event) {
  // Source: https://www.w3schools.com/howto/howto_js_copy_clipboard.asp
  const copyText = event.target;

  copyText.select();
  copyText.setSelectionRange(0, 99999);

  if (navigator.clipboard) {
    navigator.clipboard.writeText(copyText.textContent);
    alert("Copied text!");
  }
  else {
    console.error("Clipboard unavailable due to insecure context, or browser limitations.");

    const unavailableClipboardAlert = document.querySelector("#unavailable-clipboard");

    unavailableClipboardAlert.classList.remove("d-none");
    unavailableClipboardAlert.classList.add("d-block");
  }
}

const widgetCode = document.querySelector(".widget-code");

if (widgetCode) {
  widgetCode.addEventListener("click", copyContentsToClipboard);
  
  if (bootstrap.Tooltip) {
    new bootstrap.Tooltip(widgetCode);
  }
}


function toggleheaderleft() {
      var x = document.getElementById("message_main");
      var y = document.getElementById("whisper");
      if (x.style.display === "none") {
        x.style.display = "flex";
        y.style.display ="none";
      } else {
        x.style.display = "none";
        y.style.display ="flex";
      }
    }
