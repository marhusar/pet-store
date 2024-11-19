import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import router from './router'
import 'vue-multiselect/dist/vue-multiselect.css';


const app = createApp(App);
app.use(router); // Use the router
app.mount('#app');
