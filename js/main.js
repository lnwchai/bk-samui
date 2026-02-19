/**
 * main.js
 *
 * For all custom js codes.
 */



/* VANILLA JS */
// console.log("Fruit Theme");





/* JQUERY */
/*
jQuery(document).ready(function($) {

    // YOUR CODE
    
});
*/


var PaymentQRCode = document.querySelector("#payment-qrcode");
if (PaymentQRCode !== null) {
  new QRCode(PaymentQRCode, PaymentQRCode.dataset.link);
}

// Copy Link
var CopyLink = document.querySelector('.copy-link');
if (CopyLink !== null) {
    CopyLink.addEventListener('click', function (e) {
    e.preventDefault();

    let url = e.target.dataset.link;
    navigator.clipboard.writeText(url).then(
      function () {
        CopyLink.innerHTML = 'Copied Link';
      },
      function () {
        console.log('Copy error');
      }
    );
  });
}



if (document.body.classList.contains("page-id-943")) {

  function value_clinic(){

    var v_dpm = document.getElementById("input_4_24_1");
    var dpm = v_dpm.options[v_dpm.selectedIndex].text;
  
    var v_dt = document.getElementById("input_4_24_2");
    var dt = v_dt.options[v_dt.selectedIndex].text;

    if(!dpm){ var dpm = 'No options'; }
    if(!dt){ var dt = 'No options'; }

    document.getElementById("input_4_25").value = dpm;
    document.getElementById("input_4_26").value = dt;

  }

  value_clinic();

  var dpm_select = document.querySelector("#input_4_24_1");
  var dt_select = document.querySelector("#input_4_24_2");

  dpm_select.addEventListener('change', function() {
   
    var v_dpm = document.getElementById("input_4_24_1");
    var dpm = v_dpm.options[v_dpm.selectedIndex].text;
  
    var dt = 'No options';

    document.getElementById("input_4_25").value = dpm;
    document.getElementById("input_4_26").value = dt;
    
  });

  dt_select.addEventListener('change', function() {
   
    value_clinic();

  });

}
