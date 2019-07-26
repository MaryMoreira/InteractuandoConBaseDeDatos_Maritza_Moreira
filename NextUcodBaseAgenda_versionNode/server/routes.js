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
            req.session.data = {id: doc.id}; // coloca el id del usuario
            res.send('Validado');
        }else{
            res.send('Contraseña invalida');
        }
    })
})

// obtiene todos los eventos del usuario
Router.get('/events/all', function(req, res) {
    let userId = req.session.data.id;
    // valida que la session sea valida
    if(!userId){
        res.send("Usuario no autorizado");
        return;
    }

    // busca el usuario correspondientes
    Users.findOne({id: userId}).exec(function(err, doc){
        if (err || !doc) { // si existe error o no existe el registro
            res.send("Error :", err);
            return;
        }
        res.json(doc.events); // envia los eventos del usuario
    })
})

// Inserta un evento
Router.post('/events/new', function(req, res) {
    let userId = req.session.data.id;
    // valida que la session sea valida
    if(!userId){
        res.json({error: false, msg: 'Usuario no autorizado'});
        return;
    }

    // busca el usuario correspondientes
    Users.findOne({id: userId}).exec(function(err, doc){
        if (err || !doc) { // si existe error o no existe el registro
            res.json({ error: true, msg : "Error search: " + err} );
            return;
        }
        let event = {...req.body}; // obtiene el evento enviado
        event.id = (doc.events.length == 0 ? 0 : doc.events[doc.events.length-1].id) + 1;
        // actualiza los eventos del usuario
        Users.update({id: userId}, {$push: { events: event } }, (error, result) => {
            if (error){
                res.json({ error: true, msg : "Error update: " + err} );
                return;
            }
            res.json({ error: false, msg : "Se insertó el evento satisfactoriamente"} );
        })
    })

})

// Elimina un evento
Router.post('/events/delete', function(req, res) {
    let userId = req.session.data.id;
    // valida que la session sea valida
    if(!userId){
        res.json({error: false, msg: 'Usuario no autorizado'});
        return;
    }

    // actualiza los eventos del usuario, eliminando el evento
    Users.update({id: userId}, {$pullAll  : { events: {id : req.body.id } } }, (error, result) => {
        if (error){
            res.json({ error: true, msg : "Error eliminar: " + err} );
            return;
        }
        res.json({ error: false, msg : "Se eliminó el evento satisfactoriamente"} );
    })
})

// Actualiza un evento
Router.post('/events/update', function(req, res) {
    let userId = req.session.data.id;
    // valida que la session sea valida
    if(!userId){
        res.json({error: false, msg: 'Usuario no autorizado'});
        return;
    }

    // actualiza el evento del usuario
    Users.update({ id: userId, 'events.id': req.body.id},
                 { $set  : {
                             'events.$.start' : req.body.start,
                             'events.$.end'   :  req.body.end,
                             'events.$.allDay':  req.body.allDay
                            } }, (error) => {

        if (error){
            res.json({ error: true, msg : "Error al actualizar: " + err} );
            return;
        }
        res.json({ error: false, msg : "Se actualizó el evento satisfactoriamente"} );
    })
})

module.exports = Router
