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
        component: () => import(/* webpackChunkName: "export" */ '@/UI/views/ExportImport.vue')
      }
    ]
  },
  {
    path: '/encounter',
    component: () => import('@/UI/layouts/default/Default.vue'),
    children: [
      {
        path: 'edit/:encounterId',
        name: 'EditEncounter',
        component: () => import(/* webpackChunkName: "home" */ '@/UI/views/EditEncounter.vue')
      },
      {
        path: 'add',
        name: 'AddEncounter',
        component: () => import(/* webpackChunkName: "home" */ '@/UI/views/AddEncounter.vue')
      },
      {
        path: '',
        name: 'Encounter',
        component: () => import(/* webpackChunkName: "home" */ '@/UI/views/EncounterPage.vue')
      }
    ]
  },
  {
    path: '/',
    component: () => import('@/UI/layouts/default/Default.vue'),
    children: [
      {
        path: 'new-character',
        name: 'AddCharacter',
        component: () => import(/* webpackChunkName: "home" */ '@/UI/views/AddCharacter.vue')
      },
      {
        path: '',
        name: 'Home',
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
