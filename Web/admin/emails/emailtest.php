<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Send Email Test</title>
</head>
<body>
  <h1>SEND EMAIL</h1>
  <a href="../user/login/logout.php">Log Out</a>

  <div class="container">
    <form id="contact" action="mail.php" method="post">
      <h1>Contact Form</h1>

      <fieldset>
        <input placeholder="Your Name" name="name" type="text" tabindex="1" required autofocus>
      </fieldset>

      <fieldset>
        <input placeholder="Your Email Address" name="email" type="email" tabindex="2" required>
      </fieldset>

      <fieldset>
        <label for="template">Reason for Contact:</label><br>
        <select id="template" name="template" tabindex="3" required>
          <option value="itinerary_change">Itinerary Change</option>
          <option value="itinerary_approval">Itinerary Approval</option>
        </select>
      </fieldset>

      <fieldset>
        <input placeholder="Subject" name="subject" type="text" tabindex="4" required>
      </fieldset>

      <fieldset>
        <textarea placeholder="Your Message" name="message" tabindex="5" required></textarea>
      </fieldset>

      <fieldset>
        <button type="submit" name="send" id="contact-submit">Submit Now</button>
      </fieldset>
    </form>
  </div>
</body>
</html>
