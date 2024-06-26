// Composables
import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  {
    path: '/encounter',
    component: () => import('@/UI/layouts/default/Default.vue'),
    children: [
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
        path: 'create-party',
        name: 'CreateParty',
        component: () => import(/* webpackChunkName: "home" */ '@/UI/views/CreateParty.vue')
      },
      {
        path: 'new-character/:partyUlid',
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
