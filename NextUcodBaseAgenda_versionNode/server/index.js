const http = require('http'),
      path = require('path'),
      Routing = require('./routes.js'),
      express = require('express'),
      bodyParser = require('body-parser'),
      mongoose = require('mongoose');

const PORT = 3000;      // puerto que escuchara el servidor
const app = express();  // obtiene express
// crea el servidor http
const Server = http.createServer(app)

// conecta la base de datos
mongoose.connect('mongodb://localhost/agenda', function(err){
    if(err) {
        console.log("Mongo is not connect");
    }else{
        console.log("Connect to Mongo DB");
        require('./users.js')(); // ingresa los usuarios por omision
    }
});

// configuracion de express
app.use(express.static('client'))
app.use(bodyParser.json())
app.use(bodyParser.urlencoded({ extended: true}))
app.use('/', Routing)

// escucha el servidor http
Server.listen(PORT, function() {
  console.log('Server is listeng on port: ' + PORT)
})
