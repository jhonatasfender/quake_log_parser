var home = require('../app/controllers/home');

//you can include all your controllers

module.exports = function (app, passport) {

    app.get('/', home.home);//home

    /*app.post('/signup', passport.authenticate('local-signup', {
        successRedirect: '/home', // redirect to the secure profile section
        failureRedirect: '/signup', // redirect back to the signup page if there is an error
        failureFlash: true // allow flash messages
    }));*/

}
