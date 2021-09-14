import additionalRoutes from "../../user_account_app_extras/additionalRoutes";

export default [
  {
    path: "/user/account/account-details",
    component: () => import(/*webpackChunkName: "user_account_app/pages/details" */"../Pages/Details"),
    name: "details",
    meta: {
      title: "Account Details",
      icon: "badge-account-horizontal-outline",
    },
  },
  {
    path: "/user/account/addresses",
    component: () => import(/*webpackChunkName: "user_account_app/pages/addresses" */"../Pages/Addresses"),
    name: "addresses",
    meta: {
      title: "My Addresses",
      icon: "home-group",
    },
  },
  {
    path: "/user/account/order-history",
    component: () => import(/*webpackChunkName: "user_account_app/pages/order-history" */"../Pages/OrderHistory"),
    name: "history",
    meta: {
      title: "Order History",
      icon: "history",
    },
  },
  {
    path: "/user/account/product-reviews",
    component: () => import(/*webpackChunkName: "user_account_app/pages/product-reviews" */"../Pages/ProductReviews"),
    name: "reviews",
    meta: {
      title: "Product Reviews",
      icon: "star",
    },
  },
  {
    path: "/user/account",
    component: () => import(/*webpackChunkName: "user_account_app/pages/notifications" */"../Pages/Notifications"),
    name: "notifications",
    meta: {
      title: "Notifications",
      icon: "message-alert-outline",
    },
  },
  {
    path: "/user/account/returns",
    component: () => import(/*webpackChunkName: "user_account_app/pages/returns" */"../Pages/Returns"),
    name: "returns",
    meta: {
      title: "Returns",
      icon: "keyboard-return",
    },
  },
  {
    path: "/user/account/wishlist",
    component: () => import(/*webpackChunkName: "user_account_app/pages/wishlist" */"../Pages/Wishlist"),
    name: "wishlist",
    meta: {
      title: "Wishlist",
      icon: "thumb-up-outline",
    },
  },
  ...additionalRoutes,
  {
    path: "/user/account/login",
    component: () => import(/*webpackChunkName: "user_account_app/pages/account/login" */"../Pages/Account/Login"),
    name: "login",
    meta: {
      title: "Login",
      icon: "",
      auth: false,
      menu: false,
    },
  },
  {
    path: "/user/account/email-verified",
    component: () => import(/*webpackChunkName: "user_account_app/pages/account/email-verified" */"../Pages/Account/EmailVerified"),
    name: "email-verified",
    meta: {
      title: "Email Address Verified",
      icon: "",
      auth: false,
      menu: false,
    },
  },
  {
    path: "/user/account/forgot-password",
    component: () => import(/*webpackChunkName: "user_account_app/pages/account/forgot-password" */"../Pages/Account/ForgotPassword"),
    name: "forgot-password",
    meta: {
      title: "Forgot Password",
      icon: "",
      auth: false,
      menu: false,
    },
  },
  {
    path: "/user/account/reset-password/:token",
    component: () => import(/*webpackChunkName: "user_account_app/pages/account/reset-password" */"../Pages/Account/ResetPassword"),
    name: "reset-password",
    meta: {
      title: "Reset Password",
      icon: "",
      auth: false,
      menu: false,
    },
  },
  {
    path: "/user/account/logout",
    component: () => import(/*webpackChunkName: "user_account_app/pages/account/logout" */"../Pages/Account/Logout"),
    name: "logout",
    meta: {
      title: "Logging Out",
      icon: "",
      auth: true,
      menu: false,
    },
  },
  {
    path: "/user/account/register",
    component: () => import(/*webpackChunkName: "user_account_app/pages/account/register" */"../Pages/Account/Register"),
    name: "register",
    meta: {
      title: "Register for an Account",
      icon: "",
      auth: false,
      menu: false,
    },
  },
  {
    path: "/:pathMatch(.*)*",
    name: "404",
    component: () => import(/*webpackChunkName: "user_account_app/pages/404" */"../Pages/404"),
    meta: {
      menu: false,
    },
  },
];
