<div class="notification close"><div class="notification-element">
        <p>Käyttämällä tätä sivua hyväksyt keksien ja anonyymin datan keruun. Tietosuojakäytäntö sopimuksen näet <a href="https://htory.fi/tursajais-app-privacy-policy/">täältä</a></p>
        <button id="accept-cookies" class="btn btn-primary">Hyväksyn ehdot</button>
    </div>
</div>
<script>

$('.notification').toggleClass('close');
    var now = new Date();
    var time = now.getTime();
    var expireTime = time + 1000*36000*10;
    now.setTime(expireTime);
    $("#accept-cookies").click(function(){
        document.cookie = "iAcceptCookies=yes; expires="+now.toGMTString()+"; secure=true ;samesite=Lax";
        $('.notification').toggleClass('close');
    })
    
</script>