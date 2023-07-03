function getUpdates() {
  $.ajax({
    url: "message.txt",
    type: "GET",
    success: function(response) {
      var messages = response.trim().split('\n');
      var formattedMessages = "";

      for (var i = 0; i < messages.length; i++) {
        var values = messages[i].split("|");
        var formattedMessage = "<tr>";

        for (var j = 0; j < values.length; j++) {
          var value = values[j].trim();

          if (value.includes("Player:")) {
            value = '<span class="player-name">' + value + '</span>';
          } else if (value.includes("Total Loot")) {
            var lootValue = parseInt(value.split(":")[1].trim().replace(/,/g, ""));
            var threshold = 2000000;
            var cssClass = (lootValue > threshold) ? "high-value" : "normal-value";
            value = '<span class="' + cssClass + '">' + value + '</span>';
          }

          formattedMessage += "<td>" + value + "</td>";
        }

        formattedMessage += "</tr>";
        formattedMessages += formattedMessage;
      }

      $("#content tbody").html(formattedMessages);
    },
    error: function(xhr, status, error) {
      console.error("Error: " + error);
    }
  });
}

getUpdates();
setInterval(getUpdates, 5000); // Update every 5 seconds
