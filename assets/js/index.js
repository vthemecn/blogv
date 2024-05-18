/**
 * JavaScript 项目主文件
 */

// 幻灯片
import swiper from './src/swiper';
swiper();

import bar from './src/bar';
bar();


import { lazyLoad } from './src/lazy-load';
lazyLoad();

import contentAction from './src/content-action.js';
contentAction();

import { darkModeInit } from './src/dark-mode';
darkModeInit();

import headerMobile from "./src/header-mobile";
headerMobile();

import headerPc from "./src/header-pc";
headerPc();

import home from './src/home';
home.homeInit();

// import auth from './src/auth';
// auth.init();

import users from './src/users.js';
users();

import orders from './src/orders.js';
orders();

import comments from './src/comments.js';
comments();

// import header1 from './src/header1';
// header1();

// import modal from "../plugins/modal/modal";
// modal();

// PC 端
// import { pcNavSearch } from './src/pc';
// pcNavSearch();

// import { articleReward } from './src/article';
// articleReward();

// import { mobileTopMenu, mobileTopSearch } from './src/mobile';
// mobileTopMenu();
// mobileTopSearch();
