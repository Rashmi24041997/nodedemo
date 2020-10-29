const mongoose = require("../database/database");

let emailSchema = new mongoose.Schema({
  Name: String,

  Address: String,

  Age: String,

  Gender: String,

  Phone: String,

  File: String,
});

module.exports = mongoose.model("user", emailSchema);
