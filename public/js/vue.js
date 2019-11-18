let app = new Vue({
    el  : '#obj',
    data    : {
        message: '1234',
        link: 'https://www.facebook.com',
        html: '<a href="https://www.facebook.com">FB</a>',
        x: 0,
        y: 0,
        counter: 0,
    },
    methods: {
        changeValue: function (e) {
            this.message = e.target.value;
        },
        calculate: function (e) {
            this.x = e.clientX;
            this.y = e.clientY;
        }
    }
});
Vue.config.devtools = true;