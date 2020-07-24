import Vue from 'vue';
import Router from 'vue-router';
import signin from '@/pages/signin';
import signup from '@/pages/signup';
import home from '@/pages/home';

Vue.use(Router);

export default new Router({
  routes: [
    {
      path: '/',
      titulo: '',
      redirect: '/signin',
    },
    {
      path: '/signin',
      titulo: 'signin',
      component: signin,
    },
    {
      path: '/signup',
      titulo: 'signup',
      component: signup,
    },
    {
      path: '/home',
      titulo: 'home',
      component: home,
    },
  ],
});
