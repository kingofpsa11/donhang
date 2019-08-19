
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
    outputOrder: 'App\\Notifications\\OutputOrder',
    contract: 'App\\Notifications\\Contract',
    outputOrderApproved: 'App\\Notifications\\OutputOrderApproved',
};

$(document).ready(function () {
    window.onscroll = function() {myFunction()};
    
    let navbar = $(".navbar.navbar-static-top");
    
    
    let sticky = navbar.offset().top;
    console.log(sticky);
    
    function myFunction() {
        if (window.pageYOffset >= sticky) {
            navbar.addClass("sticky")
        } else {
            navbar.removeClass("sticky");
        }
    }
    
    if (typeof Laravel !== 'undefined' && Laravel.userId) {
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
        dropdownMenu.find('.header').html(`<span>Bạn không có thông báo mới</span>`);
        dropdownMenu.find('.menu').html('');
        dropdownMenu.find('.markAllRead').html('');
    }
}

function makeNotification(notification) {
    let to = routeNotification(notification);
    let notificationText = makeNotificationText(notification);
    return '<li><a href="' + to + '"><i class="fa fa-users text-aqua"></i><span>' + notificationText + '</span></a></li>';
}

function routeNotification(notification) {
    let to = '?read=' + notification.id;
    if (notification.type === NOTIFICATION_TYPES.manufacturer) {
        to = 'manufacturer-orders/' + notification.data.manufacturer_order_id + to;
    } else if (notification.type === NOTIFICATION_TYPES.outputOrder) {
        to = 'good-deliveries/' + notification.data.good_delivery_id + '/edit' + to;
    } else if (notification.type === NOTIFICATION_TYPES.contract) {
        to = 'contracts/' + notification.data.contract_id + to;
    } else if (notification.type === NOTIFICATION_TYPES.outputOrderApproved) {
        to = 'output-orders/' + notification.data.output_order_id + to;
    }

    return '/' + to;
}

function makeNotificationText(notification) {
    let text = '';
    if (notification.type === NOTIFICATION_TYPES.manufacturer) {
        // const name = notification.data.manufacturer_id;
        text = '<strong>Phòng Kế hoạch</strong> gửi LSX số ' + notification.data.number;
    } else if (notification.type === NOTIFICATION_TYPES.outputOrder) {
        text = 'Phòng KHKD đã gửi LXH số ' + notification.data.output_order_number;
    } else if (notification.type === NOTIFICATION_TYPES.contract) {
        if (notification.data.status === 5) {
            text = 'Lãnh đạo đã phê duyệt đơn hàng số ' + notification.data.number;
        } else {
            text = 'Phòng KHKD trình đơn hàng số ' + notification.data.number;
        }
    } else if (notification.type === NOTIFICATION_TYPES.outputOrderApproved) {
        text = 'Lệnh xuất hàng số ' + notification.data.output_order_number;
    }


    return text;
}
