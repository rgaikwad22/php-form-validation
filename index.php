<!doctype html>
<!-- If multi-language site, reconsider usage of html lang declaration here. -->
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Form Validation</title>

  <!-- 120 word description for SEO purposes goes here. Note: Usage of lang tag. -->
  <meta name="description" lang="en" content="">

  <!-- Keywords to help with SEO go here. Note: Usage of lang tag.  -->
  <meta name="keywords" lang="en" content="">

  <!-- View-port Basics: http://mzl.la/VYREaP -->
  <!-- This meta tag is used for mobile device to display the page without any zooming,
        so how much the device is able to fit on the screen is what's shown initially. 
        Remove comments from this tag, when you want to apply media queries to the website. -->
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

  <!-- To adhear no-cache for Chrome -->
  <!-- <meta http-equiv="cache-control" content="no-store, no-cache, must-revalidate" />
    <meta http-equiv="Pragma" content="no-store, no-cache" />
    <meta http-equiv="Expires" content="0" /> -->

  <!-- Place favicon.ico in the root directory: mathiasbynens.be/notes/touch-icons -->
  <link rel="shortcut icon" href="favicon.ico" />

  <!--font-awesome link for icons-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <!-- Default style-sheet is for 'media' type screen (color computer display).  -->
  <link rel="stylesheet" media="screen" href="css/style.css">
</head>

<body>
  <?php
  require_once "form.php";
  ?>
  <!--container starts here-->
  <div class="container">
    <!--main starts here-->
    <main>
      <section class="form-section">
        <div class="wrapper">
          <h1 class="section-heading">Form Validation</h1>
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form"
            enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo isset($edit_id) ? $edit_id : ''; ?>">
            <div class="input-grp">
              <label for="first-name">First Name: <span class="requred">*</span></label>
              <input type="text" id="first-name" name="firstname" class="input-write" placeholder="Enter your name"
                value="<?php echo $fname ?>">
              <span class="error">
                <?php echo $fnameErr; ?>
              </span>
            </div>
            <div class="input-grp">
              <label for="last-name">Last Name: <span class="requred">*</span></label>
              <input type="text" id="last-name" name="lastname" class="input-write" placeholder="Enter last name"
                value="<?php echo $lname; ?>">
              <span class="error">
                <?php echo $lnameErr; ?>
              </span>
            </div>
            <div class="input-grp">
              <label for="email">Email: <span class="requred">*</span></label>
              <input type="email" id="email" name="email" class="input-write" placeholder="Enter your email"
                value="<?php echo $email; ?>">
              <span class="error">
                <?php echo $emailErr; ?>
              </span>
            </div>
            <div class="input-grp gender-input">
              <label for="gender">Gender: <span class="required">*</span></label>
              <?php
                $genders = ["male", "female", "other"];
                foreach ($genders as $genderOption) {
                  $isChecked = isset($gender) && $gender == $genderOption;
              ?>
              <div>
                <input type="radio" id="<?php echo $genderOption; ?>" name="gender" <?php echo $isChecked ? "checked" : ""; ?> value="<?php echo $genderOption; ?>">
                <label for="<?php echo $genderOption; ?>">
                  <?php echo ucfirst($genderOption); ?>
                </label>
              </div>
              <?php } ?>
              <span class="error">
                <?php echo $genderErr; ?>
              </span>
            </div>
            <div class="input-grp">
              <label for="city">City: <span class="required">*</span></label>
              <select name="city" id="city" class="input-write">
                <option value="">Select</option>
                <?php
                $cities = ["mumbai", "pune", "bangalore", "delhi"];
                foreach ($cities as $cityOption) {
                  $isSelected = isset($city) && $city == $cityOption;
                  echo "<option value=\"$cityOption\"";
                  echo $isSelected ? " selected" : "";
                  echo ">$cityOption</option>";
                }
                ?>
              </select>
              <span class="error">
                <?php echo $cityErr; ?>
              </span>
            </div>
            <div class="input-grp file-input">
              <label for="file">Select a file: <span class="requred">*</span></label>
              <input type="file" id="file" name="doc" />
              <span class="error" *>
                <?php echo $upload ?>
              </span>
            </div>
            <input type="submit" name="submit" value="<?php echo ($edit_id ? 'Update' : 'Submit'); ?>"
              class="btn submit-btn">
          </form>
          <br>
        </div>
      </section>
    </main>
    <!--main ends here-->
  </div>
  <!--container ends here-->
  <script src="assets/js/script.js"></script>
</body>

</html>