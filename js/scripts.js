function SendRequest(){
                $.ajax({
                    type: "POST",
                    url: "getChars.php",
                    data: "sid=<?=session_id()?>&keyID="+$('#keyID').val()+"&vCode="+$('#vCode').val(),
                    success: function(data){
                        $('.results').html(data);
                    }
                });
                document.getElementById("submit").disabled = false;
                };