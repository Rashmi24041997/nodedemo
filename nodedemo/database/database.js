const mongoose = require('mongoose');

mongoose.connect('mongodb://localhost:27017/learnmongo1',{ useUnifiedTopology: true,useNewUrlParser:true} ,function(err,db)
{
    if (!err) {
        console.log('Success')
        
    }
    else {
        console.log('Failed'+ err)
    }
});
module.exports = mongoose;
