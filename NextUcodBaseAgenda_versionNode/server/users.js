const Users  = require('./model.js');
const Crypt  = require('./crypt.js');
const moment = require('moment');
const fs     = require('fs');


// funcion que crea los usuarios por omision del archivo user.json
function createUsers(){
    let rawData = fs.readFileSync('server/users.json');
    let users   = JSON.parse(rawData);

    // crea los usuarios en la base de datos
    users.forEach( u => {
        // busca si previamente si ya existen
        Users.findOne({email: u.email}).exec(function(err, doc){
            if(err){ // si error ingresa el usuario
                console.log("Mongo DB error(user create):", err);
                return;
            }
            if(!doc) {// si no existe el registro del usuario lo inserta
                let dataUser = {...u};
                dataUser.pass = Crypt.encrypt(u.pass); // encripta el password
                dataUser.birthdate = moment(u.birthdate).format("YYYY-MM-DD"); // convierte en date
                let dbUser         = new Users(dataUser); // instancia del schema
                dbUser.save( (error) => { // guarda al usuario en mongo
                    if (error) {
                        console.log("User not create:", u.name, error);
                    }else{
                        console.log("User created: ", u.name);
                    }
                })
                return;
            }
            console.log("User exist : ", u.name);
        });
    });
}

module.exports = createUsers;