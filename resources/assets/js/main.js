//browserify entrypoint.. 

var Vue = require('vue');

var VueRouter = require('vue-router');

Vue.use(VueRouter);

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