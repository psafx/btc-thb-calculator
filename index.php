<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>BTC-THB-Calculator</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script>
jQuery(function($, undefined) {
  var now = 0
    , last_thb = 0
    , last_usd = 0
    , id = 1
  function get_btc_price() {
    latest = $.now()
    if (now + 900000 > latest) {
      return
    }
    now = latest
    url = '/get-price.php'
    return $.getJSON(url, function(data) {
      if (data.THB !== undefined) {
        last_thb = data.THB
      }
      if (data.USD !== undefined) {
        last_usd = data.USD
      }
    })
  }
  function calculate(e) {
    e.preventDefault()
    var target = $(this).data('target')
      , btc = $('#btc_amount_' + target).val()
    if ((btc == undefined) || (btc == '')) {
      $('#alert_btc').css('visibility', 'visible')
      return
    } else {
      $('#alert_btc').css('visibility', 'hidden')
    }
    $.when(get_btc_price()).then(function() {
      var unit = $('#btc_unit_' + target + ' option:selected').val()
        , decimal = 4
        , output = 0.0
        , output_str = ''
      if (last_thb > 0) {
        output = parseFloat(btc) * parseFloat(last_thb)
        if (unit == '2') {
          output /= 1000.0
          decimal = 6
        } else if (unit == '3') {
          output /= 1000000.0
          decimal = 8
        } else if (unit == '4') {
          output /= 100000000.0
          decimal = 10
        }
        output_str = output.toFixed(decimal)
        $('#thb_output_' + target).val(output_str)
      }
      if (last_usd > 0) {
        output = parseFloat(btc) * parseFloat(last_usd)
        if (unit == '2') {
          output /= 1000.0
          decimal = 6
        } else if (unit == '3') {
          output /= 1000000.0
          decimal = 8
        } else if (unit == '4') {
          output /= 100000000.0
          decimal = 10
        }
        output_str = output.toFixed(decimal)
        $('#usd_output_' + target).val(output_str)
      }
    })
    $(this).attr('disabled', 'disabled')
    $('#btc_amount_' + target).attr('disabled', 'disabled')
    $('#btc_unit_' + target).attr('disabled', 'disabled')
    $('#thb_output_' + target).attr('disabled', 'disabled')
    $('#usd_output_' + target).attr('disabled', 'disabled')
  }
  $(document).on('click', '.btn-calculate', calculate)
  $('.btn-add').on('click', function(e) {
    e.preventDefault()
    id++
    id_str = ('000'+id).slice(-3)
    var content = '              <div class=\"form-row\">' +
'                <div class="form-group col-sm-2">' +
'                  <input type="text" class="form-control" id="btc_amount_' + id_str + '">' +
'                </div>' +
'                <div class="form-group col-sm-2">' +
'                  <select id="btc_unit_' + id_str + '" class="form-control">' +
'                    <option value="1">BTC</option>' +
'                    <option value="2">mBTC</option>' +
'                    <option value="3">&#181;BTC</option>' +
'                    <option value="4">Satoshi</option>' +
'                  </select>' +
'                </div>' +
'                <div class="form-group col-sm-3">' +
'                  <input type="text" readonly class="form-control" id="thb_output_' + id_str + '">' +
'                </div>' +
'                <div class="form-group col-sm-3">' +
'                  <input type="text" readonly class="form-control" id="usd_output_' + id_str + '">' +
'                </div>' +
'                <div class="form-group col-sm-2">' +
'                  <button class="btn btn-primary btn-calculate" data-target="' + id_str + '">Calculate</button>' +
'                </div>' +
'              </div>'
    $('#btc_to_thb_form').append(content)
  })
})
</script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-35429216-3"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-35429216-3');
</script>
  </head>
  <body>
    <div class="container">
      <header class="header clearfix">
        <nav>
          <ul class="nav nav-pills float-right">
            <li class="nav-item">
              <a class="nav-link active" href="/">Home <span class="sr-only">(current)</span></a>
            </li>
          </ul>
        </nav>
        <h3 class="text-muted">BTC-THB-Calculator</h3>
      </header>
      <main role="main">
        <div class="jumbotron">
          <p class="lead text-center">สำหรับแปลงราคา BTC เป็น THB แบ่งออกเป็น BTC mBTC &#181;BTC และ Satoshi</p>
        </div>
        <p>*** ราคา BTC และอัตราแลกเปลี่ยน ใช้ข้อมูลจาก blockchain.info</p>
        <div class="row calculator">
          <div class="col-lg-10 offset-lg-1">
            <form id="btc_to_thb_form">
              <div class="form-row">
                <div class="col-1 offset-11">
                  <img class="btn-add" src="/open-iconic/svg/plus.svg" width="24px" height="24px" alt="plus">
                </div>
              </div>
              <div class="form-row first-row">
                <div class="form-group col-sm-2">
                  <label for="btc_amount_001">BTC</label>
                  <input type="text" class="form-control" id="btc_amount_001">
                </div>
                <div class="form-group col-sm-2">
                  <label for="btc_unit_001">Unit</label>
                  <select id="btc_unit_001" class="form-control">
                    <option value="1">BTC</option>
                    <option value="2">mBTC</option>
                    <option value="3">&#181;BTC</option>
                    <option value="4">Satoshi</option>
                  </select>
                </div>
                <div class="form-group col-sm-3">
                  <label for="thb_output_001">THB</label>
                  <input type="text" readonly class="form-control" id="thb_output_001">
                </div>
                <div class="form-group col-sm-3">
                  <label for="usd_output_001">USD</label>
                  <input type="text" readonly class="form-control" id="usd_output_001">
                </div>
                <div class="form-group col-sm-2">
                  <button class="btn btn-primary btn-calculate" data-target="001">Calculate</button>
                </div>
              </div>
            </form>
            <div id="alert_btc" class="alert alert-danger" role="alert">
              Please input BTC amount!
            </div>
          </div>
        </div>
      </main>
      <footer class="footer">
        <p>&copy; Prasong Aroonruviwat 2018-&#x221e;</p>
      </footer>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4" crossorigin="anonymous"></script>
  </body>
</html>
