function CheckLength() {
    var msg_area = document.getElementById("Message");
    msg_area.innerHTML = "";
  if (document.getElementById("description").value.length < 30) {
    msg_area.innerHTML = "<div class='alert alert-danger' role='alert'>YOU DID NOT ENTER ENOUGH INFORMATION</div>";
    return false;
  }else{
     document.getElementById("formSubmit").submit();
   }
}

function textCounter(description, counterID, minLen) {
    cnt = document.getElementById(counterID);
    if (description.value.length < minLen) {
        cnt.innerHTML = minLen - description.value.length;
      }else{
        cnt.innerHTML = "OK";
      }
  }
