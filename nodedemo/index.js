var http = require('http');

//var  express =require("express");
//const app  = express(); 
/*app.get('/', function(req,res){

})

app.listen(8000);*/


// FIRST WE CREATE SERVER 
// http.createServer( function (req,res){
 

//     console.log("server created ");
//     res.writeHead(200, {'Content-Type': 'text/plain'});
//     res.end('Hello World!');

// }).listen(8000);
var express = require("express");
const validator = require("express-joi-validation").createValidator({});
//const Joi = require("@hapi/joi");
let user = require("./schema/schema");
let querySchema = require("./validation/validation");
//let db1 = require('./database/database')
var bodyParser = require("body-parser");
var path = require("path");
var app = express();

app.use(express.static("public"));
app.engine("html", require("ejs").renderFile);

app.use(bodyParser.json());
/**
 * parse requests of content-type - application/x-www-form-urlencoded
 */
app.use(bodyParser.urlencoded({ extended: true }));

app.get("/createdata", function (req, res) {
  res.render("create.html");
});
app.get("/updatedata", function (req, res) {
  res.render("update.html");
});

app.post("/create", validator.body(querySchema), function (req, res) {
  var obj = new user(req.body);
console.log(req.body)
  obj
    .save()
    .then((data) => {
      res.send(data);
    })
    .catch((err) => {
      res.status(500).send({
        message: err.message || "Some error occurred while creating the User.",
      });
    });
});

app.get("/find", function (req, res) {
  user.find().then((users) => {
    res.status(200).send(users);
  });
});

//app.get("/find/:id", function (req, res) {
/* user.find(_id).then((users) => {
    res.status(200).send(users);
  }); */
/* user.findById(ObjectId("5f896ad859b30f25a48ecd86")).then((users) => {
    res.status(200).send(users);
  }); */
//  console.log(id);
//});
app.get("/find/:id", function (req, res) {
  const id = req.params.id;

  user.findById(id).then((users) => {
    res.status(200).send(users);
  });
  console.log(id);
});

app.put("/update/:id", function (req, res) {
  user.findByIdAndUpdate(req.params.id, req.body).then((user) => {
    if (!user) {
      return res.status(404).send({
        message: "no user found",
      });
    }
    console.log(req.body);
    res.status(200).send(user);
  });
});

app.delete("/delete/:id", function (req, res) {
  user.findByIdAndRemove(req.params.id).then((user) => {
    if (!user) {
      return res.status(404).send({
        message: "User not found ",
      });
    }
    res.send({ message: "User deleted successfully!" });
  });
});

app.listen(3000);
console.log("Server Listening on port 3000");
