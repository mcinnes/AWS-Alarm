

Parse.Cloud.job("newRecording", function(request, status) {
  // the params passed through the start request
  const params = request.params;
  // Headers from the request that triggered the job
  const headers = request.headers;

  // get the parse-server logger
  const log = request.log;

  // Update the Job status message
  status.message("I just started");

  var xmlHttp = new XMLHttpRequest();
  xmlHttp.onreadystatechange = function() { 
      if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
          callback(xmlHttp.responseText);
  }
  xmlHttp.open("GET", 'http://63.142.240.17/parse.php', true); // true for asynchronous 
  xmlHttp.send(null);

  status.message(xmlHttp.responseText);
  
});