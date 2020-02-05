<button id="reveal-bar-button">Reveal the bar</button>

    <script>
    document.getElementById("reveal-bar-button").onclick = RevealBar;
    
        function RevealBar() {
            
            var confirmed = confirm("Are you really sure to reveal the bar, it will cost you 20 points of your score!");
            if(confirmed == true){
                    $.post('/kisapanel/tools/revealBar.php', {
                    loginID: <?php echo $loginID;?>,
                    hintNumber: <?php echo $hintNumber;?>
                }, function(data,status){
                    location.reload();
                }) 
            }

    }

    </script>