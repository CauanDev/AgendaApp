import { createWebHistory, createRouter } from 'vue-router'

import LoginView from '../views/LoginView.vue'
import RegisterView from '../views/RegisterView.vue'
import TokenView from '../views/VerificationView.vue'
import HomeView from '../views/HomeView.vue'
import serviceRoutes from './serviceRouter'
import TarefasView from '../views/TarefasView.vue'
const routes = [
  {
    path: '/login/:toast?', name: 'login', component: LoginView,
    meta: {
      auth: false
    }
  },
  {
    path: '/register', name: 'register', component: RegisterView,
    meta: {
      auth: false
    }
  },
  {
    path: '/verification', name: 'verification', component: TokenView,
    meta: {
      auth: false
    }
  },
  {
    path: '/home',
    name: 'home',
    component: HomeView,
    meta: {
      auth: true
    }
  },
  {
    path: '/tarefas',
    name:'tarefas',
    component: TarefasView,
    meta: {
      auth: true
    }
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach(serviceRoutes);

export default router;
