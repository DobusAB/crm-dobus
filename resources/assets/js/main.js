//browserify entrypoint.. 

var Vue = require('vue');

var VueRouter = require('vue-router');

var VueResource = require('vue-resource');

Vue.use(VueRouter);
Vue.use(VueResource);

var App = Vue.extend({});

var router = new VueRouter();

router.map({
    '/': {
        component: require('./components/dashboard/index.vue')
    }
});

router.redirect({
  '*': '/'
});


router.start(App, 'body');