
var currentPage = 0;
var hasMore;

function createCard(quoteData) {
    var quote = $("<div class='quote'>").
        append($("<span class='quotemark'>").html("&ldquo;")).
        append($("<span>").append($("<em>").text(quoteData.quote))).
        append($("<span class='quotemark'>").html("&rdquo;"));

    var card = $("<div class='card'>").attr("id", quoteData.id).
        append(quote).
        append($("<span class='date'>").text(quoteData.timestamp)).
        append($("<span class='author'>").append($("<em>").text(quoteData.attribution ? quoteData.attribution : "Anonymous"))).
        append($("<div class='spacer'>").html("&nbsp;"));

    return card;
}

function submitQuote() {
    var quote = $("#quoteInput").val();
    var attribution = $("#attributionInput").val();
    if (attribution.length == 0)
    {
        attribution = null;
    }

    $.ajax({
        type: "POST",
        url: "api.php",
        data: JSON.stringify({"quote" : quote, "attribution" : attribution}),
        dataType: 'json'
    }).done(function (returnData) {
        getQuotes(0, 10);
        $("#quoteInput").val("");
        $("#attributionInput").val("");
    }).fail(function (jqXHR, textStatus) {
    });
}

function loadMore() {
    if (!more)
        return;

    getQuotes(currentPage + 1, 25);
}

function getQuotes(page, pageSize) {
    $.ajax({
        async: false,
        type: "GET",
        url: "api.php",
        data: {"page" : page, "pageSize" : pageSize},
        dataType: 'json'
    }).done(function (returnData) {

        currentPage = page;
        more = returnData.more;
        $("#loadMoreButton").prop('disabled', !more);

        var quotes = returnData["quotes"];
        if (page == 0)
        {
            for (var i = quotes.length - 1; i >= 0; i--)
            {
                var quote = quotes[i];
                if ($("div.card#" + quote.id).length == 0)
                {
                    $("#quotes").prepend(createCard(quotes[i]));
                }
            }
        }
        else
        {
            for (var i = 0; i < quotes.length; i++)
            {
                var quote = quotes[i];
                if ($("div.card#" + quote.id).length == 0)
                {
                    $("#quotes").append(createCard(quotes[i]));
                }
            }
        }
    }).fail(function (jqXHR, textStatus) {
        
    });
}

function updateLatest() {
    getQuotes(0, 25);
}

$(document).ready(function() {
    getQuotes(0, 25);

    setInterval(updateLatest, 30000);

});
