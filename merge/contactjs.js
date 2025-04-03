  // Az üzenet mező tartalmának figyelése
  document.getElementById("message").addEventListener("input", function() {
    
    // Az üzenet mező és a küldés gomb elérése
    let sendButton = document.getElementById("sendButton");

    // Ha a mező tartalma nem üres, engedélyezzük a gombot
    if (this.value.trim() !== "") {
      sendButton.disabled = false; // Engedélyezett
    } else {
      sendButton.disabled = true; // Letiltott
    }
  });