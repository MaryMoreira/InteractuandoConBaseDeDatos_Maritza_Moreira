const Router = require('express').Router();
const Users  = require('./model.js');
const Crypt  = require('./crypt.js');


// atiende al login
Router.post('/login', function(req, res) {
    let name = req.body.user,
        pass = req.body.pass;

    // busca los usuarios correspondientes
    Users.findOne({email: name}).exec(function(err, doc){
        if (err || !doc) { // si existe error o no existe el registro
            res.send("Usuario inexistente");
            return;
        }
        // verifica que la contraseña sea la correcta
        if(pass == Crypt.decrypt(doc.pass)){
            res.send('Validado');
        }else{
            res.send('Contraseña invalida');
        }
    })
})

// Elimina un evento
Router.post('/event/delete', function(req, res) {
    let uid = req.params.id
    Users.remove({userId: uid}, function(error) {
        if(error) {
            res.status(500)
            res.json(error)
        }
        res.send("Registro eliminado")
    })
})

// Inserta un evento
Router.post('/event/insert', function(req, res) {
    let uid = req.params.id
    Users.remove({userId: uid}, function(error) {
        if(error) {
            res.status(500)
            res.json(error)
        }
        res.send("Registro eliminado")
    })
})

// Actualiza un evento
Router.post('/event/update', function(req, res) {
    let uid = req.params.id
    Users.remove({userId: uid}, function(error) {
        if(error) {
            res.status(500)
            res.json(error)
        }
        res.send("Registro eliminado")
    })
})

module.exports = Router
