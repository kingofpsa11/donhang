
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('admin-lte');

// import 'jquery-validation/dist/jquery.validate.min.js';

// window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// const app = new Vue({
//     el: '#app'
// });

window.Pusher = require('pusher-js');
import Echo from "laravel-echo";

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'fcd7e1e10df31aac8de8',
    cluster: 'ap1',
    encrypted: true
});

var notifications = [];

const NOTIFICATION_TYPES = {
    manufacturer: 'App\\Notifications\\ManufacturerOrder',
    goodTransfer: 'App\\Notifications\\GoodTransfer',
    outputOrder: 'App\\Notifications\\OutputOrder',
    contract: 'App\\Notifications\\Contract',
};

$(document).ready(function () {
    if (Laravel.userId) {
        $.get('/notifications', function (data) {
            addNotifications(data, '.notifications-menu');
        });

        window.Echo.private(`App.User.${Laravel.userId}`)
            .notification((notification) => {
                addNotifications([notification], '.notifications-menu');
            });
    }
});

function addNotifications(newNotifications, target) {
    notifications = _.concat(notifications, newNotifications);
    showNotifications(notifications, target);
}

function showNotifications(notifications, target) {
    let dropdownMenu = $(target).find('.dropdown-menu');

    if (notifications.length) {
        let htmlElements = notifications.map(function (notification) {
            return makeNotification(notification);
        });
        $(target).find('span.label').removeClass('hidden');
        $(target).find('span.label').html(notifications.length);
        dropdownMenu.find('.header').html(`Bạn có ${notifications.length} thông báo mới`);
        dropdownMenu.find('.menu').html(htmlElements.join(''));
    } else {
        dropdownMenu.find('span.label').addClass('hidden');
        dropdownMenu.find('.header').html(`Bạn không có thông báo mới`);
        dropdownMenu.find('.menu').html('');
    }
}

function makeNotification(notification) {
    let to = routeNotification(notification);
    let notificationText = makeNotificationText(notification);
    return '<li><a href="' + to + '"><i class="fa fa-users text-aqua"></i>' + notificationText + '</a></li>';
}

function routeNotification(notification) {
    let to = '?read=' + notification.id;
    if (notification.type === NOTIFICATION_TYPES.manufacturer) {
        to = 'manufacturer-order' + to;
    } else if (notification.type === NOTIFICATION_TYPES.goodTransfer) {
        to = 'good-transfer' + to;
    } else if (notification.type === NOTIFICATION_TYPES.outputOrder) {
        to = 'good-delivery/' + notification.data.good_delivery_id + '/edit' + to;
    } else if (notification.type === NOTIFICATION_TYPES.contract) {
        to = 'good-delivery/' + notification.data.contract_id + '/edit' + to;
    }

    return '/' + to;
}

function makeNotificationText(notification) {
    let text = '';
    if (notification.type === NOTIFICATION_TYPES.manufacturer) {
        // const name = notification.data.manufacturer_id;
        text = '<strong>Phòng Kế hoạch</strong> gửi LSX số ' + notification.data.manufacturer_id;
    } else if (notification.type === NOTIFICATION_TYPES.goodTransfer) {
        text = 'Giám đốc đã phê duyệt phiếu xuất số ' + notification.data.good_transfer_id;
    } else if (notification.type === NOTIFICATION_TYPES.outputOrder) {
        text = 'Phòng KHKD đã gửi LXH số ' + notification.data.output_order_number;
    } else if (notification.type === NOTIFICATION_TYPES.contract) {
        if (notification.data.status === 5) {
            text = 'Lãnh đạo đã phê duyệt đơn hàng số ' + notification.data.contract_id;
        } else {
            text = 'Phòng KHKD đã trình đơn hàng số ' + notification.data.contract_id;
        }
    }

    return text;
}
