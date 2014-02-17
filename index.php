<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CFQ - Context Free Quotes</title>

    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <link href="style.css" rel="stylesheet" type="text/css" />
    <script src="script.js"></script>
  </head>

  <body>
  <div class="container">
  
  <div class="quoteInputContainer">
    <div class="form-group">
      <label for="quoteInput">Quote</label>
      <textarea class="form-control" id="quoteInput" rows="3"></textarea>
    </div>
    <div class="form-group">
      <label for="attributionInput">Attribution</label>
      <input class="form-control" id="attributionInput" placeholder="Attribution (optional)">
    </div>
    <button type="submit" class="btn btn-default" onClick="submitQuote()">Submit</button>
  </div>

  <div id="quotes">
  </div>
  <div class="loadMore">
    <button id="loadMoreButton" class="btn btn-default" onClick="loadMore()">Load More</button>
  </div>
</div>
  </body>
</html>