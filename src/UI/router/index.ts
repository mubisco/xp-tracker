// Composables
import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  {
    path: '/data-manage',
    component: () => import('@/UI/layouts/default/Default.vue'),
    children: [
      {
        path: '',
        name: 'ImportExport',
        // route level code-splitting
        // this generates a separate chunk (about.[hash].js) for this route
        // which is lazy-loaded when the route is visited.
        component: () => import(/* webpackChunkName: "home" */ '@/UI/views/ExportImport.vue')
      }
    ]
  },
  {
    path: '/encounter',
    component: () => import('@/UI/layouts/default/Default.vue'),
    children: [
      {
        path: '/add',
        name: 'AddEncounter',
        // route level code-splitting
        // this generates a separate chunk (about.[hash].js) for this route
        // which is lazy-loaded when the route is visited.
        component: () => import(/* webpackChunkName: "home" */ '@/UI/views/AddEncounter.vue')
      },
      {
        path: '',
        name: 'Encounter',
        // route level code-splitting
        // this generates a separate chunk (about.[hash].js) for this route
        // which is lazy-loaded when the route is visited.
        component: () => import(/* webpackChunkName: "home" */ '@/UI/views/EncounterPage.vue')
      }
    ]
  },
  {
    path: '/',
    component: () => import('@/UI/layouts/default/Default.vue'),
    children: [
      {
        path: '/new-character',
        name: 'AddCharacter',
        // route level code-splitting
        // this generates a separate chunk (about.[hash].js) for this route
        // which is lazy-loaded when the route is visited.
        component: () => import(/* webpackChunkName: "home" */ '@/UI/views/AddCharacter.vue')
      },
      {
        path: '',
        name: 'Home',
        // route level code-splitting
        // this generates a separate chunk (about.[hash].js) for this route
        // which is lazy-loaded when the route is visited.
        component: () => import(/* webpackChunkName: "home" */ '@/UI/views/HomePage.vue')
      }
    ]
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router
