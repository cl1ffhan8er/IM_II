
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>send email test</title>
</head>
<body>
    <h1>SEND EMAIL</h1>
    <a href = "../user/login/logout.php">Log Out</a> 
    <!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equsiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
   <div class="container">
    <form id="contact" action="mail.php" method="post">
      <h1>Contact Form</h1>
      <fieldset>
        <input placeholder="Your Name" name="name" type="text" tabindex="1" autofocus>
      </fieldset>
      <fieldset>
        <input placeholder="Your Email Address" name="email" type="email" tabindex="2">
      </fieldset>

      <fieldset>
                <label for="template">Reason for Contact:</label><br>
                <select id="template" name="template" tabindex="3" required>
                    <option value="itinerary_change">Itinerary Change</option>
                    <option value="itinerary_approval">Itinerary Approval</option>
                </select>
    </fieldset>

      <fieldset>
        <input placeholder="Type your subject line" type="text" name="subject" tabindex="4">
      </fieldset>
      <fieldset>
        <textarea name="message" placeholder="Type your Message Details Here..." tabindex="5"></textarea>
      </fieldset>
      <fieldset>
        <button type="submit" name="send" id="contact-submit">Submit Now</button>
      </fieldset>
    </form>
  </div> 


</body>
</html> 

