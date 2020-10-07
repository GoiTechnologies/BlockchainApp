

function create_wallet(){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
      $.ajax({
      type: "POST",
      url: '/request_wallet',
      data: {
            initial: $('#initial_balance').val()
      },
      success: function(respuesta) {
        alert(respuesta.message);
        location.reload();
      },
      error: function(error) {
            alert(error);
        }
      });
}


function wallet_explore(){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
      $.ajax({
      type: "GET",
      url: '/wallet_explore',
      success: function(respuesta) {
        $('#wallet_explore_div').empty();
        $('#results_div').css('display','block');
        // Convierto respuesta y la muestro en el div
        var obj = JSON.parse(respuesta);
        $.each( obj, function( element, body ) {
          $('#wallet_explore_div').append("ID Blockchain User: "+(element+1)+"<br> wallet: " +
           body.wallet + "<hr>");
          // Impresion con balance interno, el valor del arreglo no de blockchain
          // $('#wallet_explore_div').append("ID Blockchain User: "+(element+1)+"<br> wallet: " +
          //  body.wallet + "<br>" + 'balance: ' + body.balance + "<hr>");

        });
        location.href = "#wallet_explore_div";
      },
      error: function(error) {
            alert(error);
        }
      });
}

function request_blockchain_code(){
    if($('#pass_code').val() != ''){
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
          $.ajax({
          type: "POST",
          url: '/request_blockchain_code',
          data: {
                pass: $('#pass_code').val()
          },
          success: function(respuesta) {
            alert(respuesta.message);
            location.reload();
          },
          error: function(error) {
                alert(error);
            }
          });
    }else{
      $('#pass_code').focus();
    }
}



function close_datablock(id){
    $('#datablock_'+id).css('display','none');
    $('#datablock_outputs_'+id).css('display','none');
}
function open_datablock(id){
    $('#datablock_'+id).css('display','block');
    $('#datablock_outputs_'+id).css('display','block');
}




function send_transacction(address){
recover_ide(address, function(my_ide) {
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
      $.ajax({
      type: "POST",
      url: '/transaction',
      data: {
            ide: my_ide,
            recipient: $('#receiber_wallet').val(),
            amount: $('#qty_to_send').val()
      },
      success: function(respuesta) {
        alert(respuesta.message);
        location.reload();
      },
      error: function(error) {
            alert(error);
        }
      });
});
}















function recover_ide(address,callback){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
      $.ajax({
      type: "GET",
      url: '/wallet_explore',
      success: function(respuesta) {
        // Convierto respuesta y obtengo el ide
        var obj = JSON.parse(respuesta);
        $.each( obj, function( element, body ) {
          if(address == body.wallet){ callback(element); }
        });
      },
      error: function(error) {
            alert(error);
        }
      });
}



















function add_balance(){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
      $.ajax({
      type: "POST",
      url: '/add_balance',
      data: {
            ini: $('#initial_balance').val()
      },
      success: function(respuesta) {
        alert(respuesta.message);
        //location.reload();
      },
      error: function(error) {
            alert(error);
        }
      });

}









function transactions_explorer(){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
      $.ajax({
      type: "POST",
      url: '/transactions_explorer',
      data: {},
      success: function(respuesta) {
        $("#respuesta_div").append(respuesta);
      },
      error: function(error) {
        $("#respuesta_div").append("ERROR: <br>" + respuesta);
        }
      });
}


function blockchain_blocks_back(){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
      $.ajax({
      type: "POST",
      url: '/blockchain_blocks_back',
      data: {},
      success: function(respuesta) {
          var obj1 = JSON.parse(respuesta);
          conter = 0;

          $.each(obj1, function( obj ) {
            $("#respuesta_div").append("<span class='badge badge-pill badge-primary' style='font-size:20px;'>Blockchain Block Number: "
             + obj + "</span><br>");
            $("#respuesta_div").append('<br><div class="alert alert-primary" role="alert">'+
            "Timestamp: " + obj1[obj].timestamp + "<br>"+
            "Hash: " + obj1[obj].hash + "<br>"+
            "Nonce: " + obj1[obj].nonce + "<br>"+
            "Difficulty: " + obj1[obj].difficulty + "</div>");

            if(conter > 0){
              $("#respuesta_div").append("<div style='margin-top:15px; margin-bottom:15px;'>"+
              "<button class='btn btn-outline-primary btn-sm' style='margin-right:15px;' onclick='close_datablock("+obj+");'>Cerrar Desgloce</button>"+
                "<span><button class='btn btn-outline-primary btn-sm' onclick='open_datablock("+obj+");'>Abrir Desgloce</button></span></div>");

              html = "<div id='datablock_"+obj+"' style='background-color:#c8e8fa; padding:5px; display:none;'>";
              $.each(obj1[obj].data, function( dat ) {
                      html += "Data ID: " + obj1[obj].data[dat].id + "<br>";
                      html += "<hr><span class='badge badge-pill badge-success'>INPUT DATA: </span><br>";
                      html += "Timestamp: " + obj1[obj].data[dat].input.timestamp + "<br>";
                      html += "Amount: " + obj1[obj].data[dat].input.amount + "<br>";
                      html += "Address: " + obj1[obj].data[dat].input.address + "<br>";
                      html += "Signature:<br>";
                      html += "r: " + obj1[obj].data[dat].input.signature.r + "<br>";
                      html += "s: " + obj1[obj].data[dat].input.signature.s + "<br>";

                        $.each(obj1[obj].data[dat].outputs, function( val ) {
                          html += "<span class='badge badge-pill badge-warning'>OUTPUTS DATA: </span><br>";
                          html +="Amount: "+obj1[obj].data[dat].outputs[val].amount+"<br>";
                          html +="Adress: "+obj1[obj].data[dat].outputs[val].address+"<br>";
                        });
              });
            html += "</div><br>";
            $("#respuesta_div").append(html);

            }
            conter++;
          });


      },
      error: function(error) {
        $("#respuesta_div").append("ERROR: <br>" + respuesta);
        }
      });
}

function blockchain_find_block_back(hash){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
      $.ajax({
      type: "POST",
      url: '/blockchain_blocks_back',
      data: {},
      success: function(respuesta) {
          var obj1 = JSON.parse(respuesta);
          conter = 0;

          $.each(obj1, function( obj ) {


            if(obj1[obj].hash == hash){

            $("#respuesta_div").append("<span class='badge badge-pill badge-primary' style='font-size:20px;'>Blockchain Block Number: " + obj + "</span><br>");
            $("#respuesta_div").append('<br><div class="alert alert-primary" role="alert">'+
            "Timestamp: " + obj1[obj].timestamp + "<br>"+
            "Hash: " + obj1[obj].hash + "<br>"+
            "Nonce: " + obj1[obj].nonce + "<br>"+
            "Difficulty: " + obj1[obj].difficulty + "</div>");


              $("#respuesta_div").append("<div style='margin-top:15px; margin-bottom:15px;'>"+
              "<button class='btn btn-info btn-sm' style='margin-right:15px;' onclick='close_datablock("+obj+");'>Cerrar Desgloce</button>"+
              "<span><button class='btn btn-info btn-sm' onclick='open_datablock("+obj+");'>Abrir Desgloce</button></span></div>");

              html = "<div id='datablock_"+obj+"' style='background-color:#d4d4d4; padding:5px; display:none;'>";
              $.each(obj1[obj].data, function( dat ) {
                      html += "Data ID: " + obj1[obj].data[dat].id + "<br>";
                      html += "<hr><span class='badge badge-pill badge-success'>INPUT DATA: </span><br>";
                      html += "Timestamp: " + obj1[obj].data[dat].input.timestamp + "<br>";
                      html += "Amount: " + obj1[obj].data[dat].input.amount + "<br>";
                      html += "Address: " + obj1[obj].data[dat].input.address + "<br>";
                      html += "Signature:<br>";
                      html += "r: " + obj1[obj].data[dat].input.signature.r + "<br>";
                      html += "s: " + obj1[obj].data[dat].input.signature.s + "<br>";

                        $.each(obj1[obj].data[dat].outputs, function( val ) {
                          html += "<span class='badge badge-pill badge-warning'>OUTPUTS DATA: </span><br>";
                          html +="Amount: "+obj1[obj].data[dat].outputs[val].amount+"<br>";
                          html +="Adress: "+obj1[obj].data[dat].outputs[val].address+"<br>";
                        });
              });
            html += "</div>";
            $("#respuesta_div").append(html);


            conter++;
          }



          });




      },
      error: function(error) {
        $("#respuesta_div").append("ERROR: <br>" + respuesta);
        }
      });
}


function blockchain_minero(){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
      $.ajax({
      type: "POST",
      url: '/blockchain_minero',
      data: {},
      success: function(respuesta) {
        if(respuesta == 0){ alert("No hay transacciones por minar."); }else{
            window.location.replace("/blockchain_blocks");
        }
      },
      error: function(error) {
        alert(error);
        }
      });
}




function miner_data(){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
      $.ajax({
      type: "POST",
      url: '/miner_data',
      data: {},
      success: function(respuesta) {
        var obj = JSON.parse(respuesta);
        $("#miner_data").empty();
        $("#miner_data").append("<h5>Balance Wallet: " + obj.balance+"</h5>");
        mostrar_QR_miner(obj.wallet);
      },
      error: function(error) {
        alert(error);
        }
      });
}



$(document).ready(function() {
  var url = window.location.href;
  if(url.includes("transacciones_blockchain")){
      transactions_explorer();
  }
  if(url.includes("blockchain_blocks")){
      blockchain_blocks_back();
  }
});



function mostrar_QR(wallet){
  $('#btn_qr').css('display','none');
  // Generador de QR
    var typeNumber = 0;
    var errorCorrectionLevel = 'L';
    var qr = qrcode(typeNumber, errorCorrectionLevel);
    qr.addData(wallet);
    qr.make();
    document.getElementById('placeHolder').innerHTML = qr.createImgTag();
    $('img').css('width','100%');
    $('img').css('height','100%');
}
function mostrar_QR_miner(wallet){
  $('#btn_qr').css('display','none');
  // Generador de QR
    var typeNumber = 0;
    var errorCorrectionLevel = 'L';
    var qr = qrcode(typeNumber, errorCorrectionLevel);
    qr.addData(wallet);
    qr.make();
    document.getElementById('placeHolder_miner').innerHTML = qr.createImgTag();
}



function solicitar_pago(wallet){
  qty = $('#qty_solicitar').val();
  if(qty != ''){
  // Generador de QR
    var typeNumber = 0;
    var errorCorrectionLevel = 'L';
    var qr = qrcode(typeNumber, errorCorrectionLevel);
    qr.addData(wallet+"&"+qty);
    qr.make();
    document.getElementById('placeHolder_solicitarpago').innerHTML = qr.createImgTag();
    $('img').css('width','100%');
    $('img').css('height','100%');
    $('#placeHolder_solicitarpago').append('<br><b>Solicitar $'+qty+'</b><br>');
  }else{
    alert('Ingresa monto a solicitar $');
    $('#qty_solicitar').focus();
  }
}

function dividirCadena(cadenaADividir,separador) {
   return cadenaADividir.split(separador);
}
