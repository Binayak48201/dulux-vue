import Vue from 'vue'
import VueRouter from 'vue-router'

// Routes
import { canNavigate } from '@/libs/acl/routeProtection'
import { isUserLoggedIn, getUserData, getHomeRouteForLoggedInUser } from '@/auth/utils'
// import apps from './routes/apps'
// import dashboard from './routes/dashboard'
// import uiElements from './routes/ui-elements/index'
import pages from './routes/pages'
// import chartsMaps from './routes/charts-maps'
// import formsTable from './routes/forms-tables'
// import others from './routes/others'

Vue.use(VueRouter)

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  scrollBehavior() {
    return { x: 0, y: 0 }
  },
  routes: [
    {
      path: '/',
      name: 'auth-login',
      component: () => import('@/views/pages/authentication/Login.vue'),
      meta: {
        layout: 'full',
        resource: 'Auth',
        guest: true,
      },
    },
    {
      path: '/dashboard',
      name: 'dashboard-ecommerce',
      component: () => import('@/views/dashboard/ecommerce/Ecommerce.vue'),
      meta: {
        auth: true,
      },
    },

    // { path: '/', redirect: { name: 'dashboard-ecommerce' } },
    // ...apps,
    // ...dashboard,
    // ...pages,
    // ...chartsMaps,
    // ...formsTable,
    // ...uiElements,
    // ...others,
    {
      path: '*',
      redirect: 'error-404',
    },
  ],
})

router.beforeEach((to, _, next) => {
  const isLoggedIn = isUserLoggedIn()
  if (to.matched.some(record => record.meta.auth)) {
    if (!isLoggedIn) {
      next({
        path: '/',
        params: { nextUrl: to.fullPath },
      })
    } else {
      next()
    }
  } else if (to.matched.some(record => record.meta.guest)) {
    if (isLoggedIn == null) {
      next()
    } else {
      next({ name: 'dashboard-ecommerce' })
    }
  } else {
    next()
  }
  // const isLoggedIn = isUserLoggedIn()
  //
  // if (!canNavigate(to)) {
  //   // Redirect to login if not logged in
  //   if (!isLoggedIn) return next({ name: 'auth-login' })
  //
  //   // If logged in => not authorized
  //   return next({ name: 'misc-not-authorized' })
  // }
  // // Redirect if logged in
  // // if (to.meta.auth && isLoggedIn) {
  // //   // const userData = getUserData()
  // //   // next(getHomeRouteForLoggedInUser(userData ? userData : null))
  // //   return next({ name: 'dashboard-ecommerce' })
  // // }
  // if (to.matched.some(record => record.meta.auth)) {
  //   if (isLoggedIn) {
  //     return next()
  //   }
  // }
  // return next()
})

// ? For splash screen
// Remove afterEach hook if you are not using splash screen
router.afterEach(() => {
  // Remove initial loading
  const appLoading = document.getElementById('loading-bg')
  if (appLoading) {
    appLoading.style.display = 'none'
  }
})

export default router
