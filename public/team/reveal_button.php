    <img id="reveal-bar-button<?php echo $row['id'];?>" class="hint-reveal-button" src="images/unlock-icon.svg" alt="home">
    <script>
    document.getElementById("reveal-bar-button<?php echo $row['id'];?>").onclick = RevealBar;
    
        function RevealBar() {
            
            var confirmed = confirm("Haluatko varmasti paljastaa vihjeen? Tämä velottaa 20 pistettä!");
            if(confirmed == true){
                    $.post(window.location.pathname+'/tools/revealBar.php', {
                    loginID: <?php echo $loginID;?>,
                    hintNumber: <?php echo $row['id'];?>
                }, function(data,status){
                    location.reload();
                }) 
            }

    }

    </script>