// Globally register all base components for convenience, because they
// will be used very frequently. Components are registered using the
// PascalCased version of their file name.
import Vue from 'vue';
import axios from 'axios'
import VueAxios from 'vue-axios'

Vue.use(VueAxios, axios);

//Vue Component(s)
Vue.component('file-upload', require('./components/FileUpload').default);

//Initialise Vue
new Vue({
    el: '#app',
    data: {
        message: 'Hello Vue!'
    }
});