﻿<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudentPoll</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
    <div class="container body-content">
        <div id="buttonframe">
            <h1 data-template="question"></h1>
            <div class="alert alert-info" id="thanks">Danke!</div>
            <div id="buttons">
                <div data-role="button-template" class="hidden">
                    <div class="col-md-10" data-template="text"></div>
                    <div class="col-md-2">
                        <button class="btn btn-primary" data-pollId="" data-answerId="" data-template="votebutton">Auswählen</button>
                    </div>
                </div>
            </div>
        </div>
        <hr />
        <footer>
            <p>Made with ❤. Soon on GitHub.</p>
        </footer>
    </div>

    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="/jquery.signalR-2.2.1.min.js"></script>
    <!--Reference the autogenerated SignalR hub script. -->
    <script src="http://<?=$_SERVER['SERVER_NAME']?>:1337/signalr/hubs"></script>
    <script type="text/javascript">
        $(function () {
            $.connection.hub.url = "http://<?=$_SERVER['SERVER_NAME']?>:1337/signalr";
            $("#buttonframe").hide();
            $("#thanks").hide();

            var $template = $("[data-role='button-template']").clone();
            var cpoll = $.connection.pollHub;

            cpoll.client.activatePoll = function (poll) {
                $("[data-template='question']").text(poll.Title);

                for (var i = 0; i < poll.Answers.length; i++) {
                    $t = $template.clone();
                    $t.find("[data-template='text']").text("Antwort " + i + ": " + poll.Answers[i].Text);
                    $t.find("[data-template='votebutton']").data("answerId", poll.Answers[i].Id);
                    $t.find("[data-template='votebutton']").data("pollId", poll.Answers[i].PollId);
                    $t.removeClass("hidden");
                    $t.find("button").click(function (e) {
                       cpoll.server.vote($(this).data("pollId"), $(this).data("answerId"));
                    });
                    $("#buttons").append($t);
                }
                $("#buttonframe").show();
            };

            cpoll.client.showResult = function (result) {
                $("[data-template='question']").text("");
                $("#thanks").hide();
                $("#buttons").empty();
                $("#buttonframe").hide();
            };

            cpoll.client.voteOk = function () {
                $("#thanks").show();
                $("#buttons").empty();
            };
            cpoll.client.display = function (poll) {

            };
$.connection.hub.logging = true;
            $.connection.hub.start();
        });
    </script>
</body>
</html>