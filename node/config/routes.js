var home = require('../app/controllers/home');

//you can include all your controllers

module.exports = function (app, passport) {

    app.get('/', home.home);
    app.get('/list', home.list);

    app.post('/search', home.search);

}
