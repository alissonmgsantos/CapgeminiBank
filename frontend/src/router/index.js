import Vue from 'vue';
import Router from 'vue-router';
import signin from '@/pages/signin';

Vue.use(Router);

export default new Router({
  routes: [
    {
      path: '/',
      name: '',
      redirect: '/',
    },
    {
      path: '/signin',
      name: 'signin',
      component: signin,
    },
  ],
});
