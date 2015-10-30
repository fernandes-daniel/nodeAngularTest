var http = require('http');


var req = http.request({hostname: 'www.google.com'}, function(res){
    res.on('data', function(dataChunk){
        console.log("***************ANOTHER CHUNK*********************");
        console.log(dataChunk);
    });

    res.on('end', function(){
        console.log("***************NO MORE DATA*********************");
    });

});

req.on('error', function (e) {
    console.log('Problem with request: ' + e.message);
})