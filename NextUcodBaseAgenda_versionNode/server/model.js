const mongoose = require('mongoose');

const Schema = mongoose.Schema

let UserSchema = new Schema({
  id:     { type: Number, required: true, unique: true},
  name:   { type: String, required: true },
  email:  { type: String, required: true},
  pass :  { type: String, required: true},
  birthdate: { type: Date, required: true},
  events : [{
      id        : { type: Number, required: true, unique: true},
      title     : { type: String, required: true },
      allDay    : { type : Boolean, require: true },
      startDate : { type: String, required: true },
      startTime : { type: String },
      endDate   : { type: String },
      endTime   : { type: String }
  }]
})

let UserModel = mongoose.model('users', UserSchema)

module.exports = UserModel
