<!DOCTYPE html>
<html>

  <body>

    <input type="submit" class="button" name="insert" value="insert"/>
    <input type="submit" class="button" name="submit" value="submit"/>
    <script src="node_modules/jquery/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
          $(.button).click(function(){
            var clickBtnValue = $(this).val();
            var ajaxurl = "summa2.php", data = {'action': clickBtnValue};
            $.post(ajaxurl, data, function(response){
              alert("action completed successfully");
            });
          });
        });
    </script>
  </body>
</html>
