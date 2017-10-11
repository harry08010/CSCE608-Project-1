    <!-- jQuery first, then Tether, then Bootstrap JS. -->
    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-3.1.1.js"   integrity="sha256-16cdPddA6VdVInumRGo6IbivbERE8p7CQR3HzTBuELA="   crossorigin="anonymous"></script>
	<script type = "text/javascript">
		
		$(".toggleForms").click(function(){
			
			$("#sighUpForm").toggle();
			$("#logInForm").toggle();
			
		});
		
		function showCate(str) {

			if (str == "") {
				document.getElementById("description").innerHTML = "Category description:";
				return;
			} else { 
			if (window.XMLHttpRequest) {
				// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp = new XMLHttpRequest();
			} else {
				// code for IE6, IE5
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					document.getElementById("description").innerHTML = "Category description:\n" + this.responseText;
				}
			};
			xmlhttp.open("GET","getcate.php?q="+str,true);
			xmlhttp.send();
			}
		}
		
		function showCatee(str) {

			if (str == "") {
				document.getElementById("descriptione").innerHTML = "Category description:";
				return;
			} else { 
			if (window.XMLHttpRequest) {
				// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp = new XMLHttpRequest();
			} else {
				// code for IE6, IE5
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					document.getElementById("descriptione").innerHTML = "Category description:\n" + this.responseText;
				}
			};
			xmlhttp.open("GET","getcate.php?q="+str,true);
			xmlhttp.send();
			}
		}
		
		function getID(val){
 
            $.ajax({
                type: "POST",
                url: "getuniversities.php",
                data: "cid="+val,
                success: function(data){
                    $("#university").html(data);
                }
            });
			}
			
		
		
	</script>
	
	
  
  </body>
</html>